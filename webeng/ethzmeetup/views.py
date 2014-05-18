from datetime import datetime
import json
import re
import urllib
import flickrapi

from django.contrib.auth.hashers import make_password, check_password
from django.core.exceptions import ValidationError, ObjectDoesNotExist
from django.core.urlresolvers import reverse
from django.core.validators import validate_email
from django.http.response import HttpResponseRedirect, HttpResponse
from django.shortcuts import render

from ethzmeetup import auth
from ethzmeetup.lexicon import validate_password, InvalidPasswordError
from ethzmeetup.models import User, MeetupGroup, Activity, Vote


# Create your views here.
def aux_render_user_page(request, context):
    user = auth.get_current_user(request)
    groups_owned = MeetupGroup.objects.filter(owner=user.id)
    # https://docs.djangoproject.com/en/dev/topics/db/examples/many_to_many/
    groups_member = MeetupGroup.objects.filter(members=user.id)
    members = User.objects.exclude(id=user.id)
    context.update({'groups_owned' : groups_owned,
                    'groups_member' : groups_member,
                    'members' : members})
    return render(request, 'user.html', context)
@auth.requires_auth
def index(request):
    return aux_render_user_page(request, {})

@auth.unregistered
def login(request):
    return render(request, 'login.html', {})

@auth.requires_auth
def logout(request):
    auth.logout_user(request)
    return HttpResponseRedirect('%s?redirect_to=%s' % (reverse('login'),
                                                        urllib.quote(request.get_full_path())))

@auth.unregistered
def register(request):
    return render(request, 'register.html', {})

@auth.unregistered
def complete_login(request):
    try:
        user = User.objects.get(username=request.POST['login_id'].lower())
    except ObjectDoesNotExist:
        try:
            # This happens if there login_id does not match an username
            # It may match an email then
            user = User.objects.get(email=request.POST['login_id'].lower())
        except ObjectDoesNotExist:
            return render(request, 'login.html', {'error' : True,
                                                  'error_message' : 'User or e-mail not registered.'})
    if check_password(request.POST['password'], user.password):
        auth.login_user(request, user.id)
        return HttpResponseRedirect(reverse('index'))
    else:
        return render(request, 'login.html', {'error' : True,
                                              'error_message' : 'Username/password combination does not match.'})

@auth.unregistered
def complete_register(request):
    try:
        validate_password(request.POST['password'])
        validate_email(request.POST['email'])
        new_user = User(username=request.POST['username'].lower(),
                        email=request.POST['email'].lower(),
                        password=make_password(request.POST['password']))
        new_user.full_clean()
        new_user.save()
    except KeyError as ke:
        return render(request, 'register.html', {'error' : True,
                                                 'error_message' : 'Required field %s is missing.' % str(ke)})
    except ValidationError as ve:
        if hasattr(ve, 'message_dict') and ve.message_dict['username']:
            return render(request, 'register.html', {'error_message' : 'Username did not pass validation. Reason: %s' % ve.message_dict['username'],
                                                 '    error' : True})
        elif hasattr(ve, 'message_dict') and ve.message_dict['email']:
            return render(request, 'register.html', {'error_message' : 'E-mail did not pass validation. Reason: %s' % ve.message_dict['email'],
                                                 '    error' : True})
        else:
            return render(request, 'register.html', {'error_message' : ve.message,
                                                     'error' : True})
    except InvalidPasswordError as ipe:
        return render(request, 'register.html', {'error_message' : ipe.message,
                                                 'error' : True})
    else:
        auth.login_user(request, new_user.id)
        return HttpResponseRedirect(reverse('index'))

@auth.requires_auth
def create_group(request):
    try:
        member_names = request.POST['members'].strip().splitlines()
        members = []
        for member_name in member_names:
            member = User.objects.get(username=member_name)
            members.append(member)
        new_group = MeetupGroup(name=request.POST['name'],
                                city=request.POST['city'],
                                owner=auth.get_current_user(request))
        new_group.full_clean()
        new_group.save()
        for member in members:
            new_group.members.add(member)  # Add member in members of this group
        new_group.save()
    except KeyError as ke:
        return aux_render_user_page(request, {'error' : True,
                                             'error_message' : 'Required field %s is missing' % str(ke)})
    except ObjectDoesNotExist as odne:
        return aux_render_user_page(request, {'error' : True,
                                             'error_message' : 'Member does not exist, %s.' % str(odne)})
    except ValidationError as ve:
        return aux_render_user_page(request, {'error' : True,
                                             'error_message' : 'Group validation failed because of %s' % ve.message_dict.values()[0][0]})
    else:
        return aux_render_user_page(request,  {'success' : True,
                                               'success_group' : request.POST['name']})

def aux_render_group_page(request, group_id, context):
    current_user = auth.get_current_user(request)
    group = MeetupGroup.objects.get(id=group_id)
    group_members = group.members.all()
    is_owner = True if current_user.id == group.owner.id else False
    members = User.objects.exclude(id=current_user.id)
    activities = Activity.objects.filter(group=group)\
        .filter(start_date__gte=datetime.now())
    activities_with_ranking = []
    for activity in activities:
        votes_for_activity_positive = Vote.objects.filter(activity=activity).filter(thumbs=True).count()
        votes_for_activity_negative = Vote.objects.filter(activity=activity).filter(thumbs=False).count()
        activities_with_ranking.append({'activity' : activity,
                                        'thumbs_up' : votes_for_activity_positive,
                                        'thumbs_down' : votes_for_activity_negative})
    activities_with_ranking.sort(key=lambda x : x['thumbs_up'], reverse=True)
    context.update({'group' : group,
                    'group_members' : group_members,
                    'is_owner' : is_owner,
                    'members' : members,
                    'upcoming_activities' : activities_with_ranking})
    return render(request, 'group.html', context)

@auth.requires_auth
def display_group(request, group_id):
    try:
        return aux_render_group_page(request, group_id, {})
    except ObjectDoesNotExist:
        return aux_render_user_page(request, {'error' : True,
                                              'error_message' : 'Group does not exist'})

@auth.requires_auth
def edit_group(request, group_id):
    try:
        existing_group = MeetupGroup.objects.get(id=group_id)
        current_user = auth.get_current_user(request)
        if existing_group.owner.id != current_user.id:
            return aux_render_group_page(request, group_id, {'error' : True,
                                                             'error_message' : 'You are not authorized to edit this group.'})
        member_names = request.POST['members'].strip().splitlines()
        members = []
        for member_name in member_names:
            member = User.objects.get(username=member_name)
            members.append(member)
        existing_group.name = request.POST['name']
        existing_group.city = request.POST['city']
        existing_group.full_clean()
        existing_group.save()
        existing_group.members.clear()
        for member in members:
            existing_group.members.add(member)  # Add member in members of this group
        existing_group.save()
    except KeyError as ke:
        return aux_render_group_page(request, group_id, {'error' : True,
                                             'error_message' : 'Required field %s is missing' % str(ke)})
    except ObjectDoesNotExist as odne:
        return aux_render_group_page(request, group_id, {'error' : True,
                                             'error_message' : 'Member or group does not exist, %s.' % str(odne)})
    except ValidationError as ve:
        return aux_render_group_page(request, group_id, {'error' : True,
                                             'error_message' : 'Group validation failed because of %s' % ve.message_dict.values()[0][0]})
    else:
        return aux_render_group_page(request, group_id, {'success' : True,
                                                         'success_group' : request.POST['name']})

@auth.requires_auth
def create_activity(request, group_id):
    try:
        existing_group = MeetupGroup.objects.get(id=group_id)
        current_user = auth.get_current_user(request)
        if existing_group.owner.id != current_user.id:
            return aux_render_group_page(request, group_id, {'activity_error' : True,
                                                             'activity_error_message' : 'You are not authorized to create activities in this group.'})
        new_activity = Activity(name = request.POST['activity_name'],
                 city = request.POST['activity_city'],
                 start_date = datetime.strptime(request.POST['activity_date'], '%Y-%m-%dT%H:%M'),
                 duration = float(request.POST['activity_duration']),
                 description = request.POST['activity_description'],
                 group = existing_group)
        if new_activity.start_date < datetime.now():
            raise ValueError('Activity must start in the future.')
        new_activity.full_clean()
        new_activity.save()
    except KeyError as ke:
        return aux_render_group_page(request, group_id, {'activity_error' : True,
                                             'activity_error_message' : 'Required field %s is missing' % str(ke)})
    except ObjectDoesNotExist as odne:
        return aux_render_group_page(request, group_id, {'activity_error' : True,
                                             'activity_error_message' : 'Group does not exist, %s.' % str(odne)})
    except ValidationError as ve:
        return aux_render_group_page(request, group_id, {'activity_error' : True,
                                             'activity_error_message' : 'Activity validation failed because of %s' % ve.message_dict.values()[0][0]})
    except ValueError as ve:
        return aux_render_group_page(request, group_id, {'activity_error' : True,
                                                         'activity_error_message' : 'Validation failed because of %s' % str(ve)})
    else:
        return aux_render_group_page(request, group_id, {'activity_success' : True,
                                                         'activity_success_name' : request.POST['activity_name']})

def aux_render_activity_page(request, activity_id, context):
    activity = Activity.objects.get(id=activity_id)
    activity.start_date_formatted = activity.start_date.strftime('%Y-%m-%dT%H:%M')
    current_user = auth.get_current_user(request)
    is_owner = current_user.id == activity.group.owner.id
    vote_count = Vote.objects.filter(activity=activity).filter(thumbs=True).count()
    last_votes = list(Vote.objects.filter(activity=activity))[:20]
    user_vote = Vote.objects.filter(activity=activity).filter(user=current_user)
    user_voted_already = user_vote.exists()
    context.update( {'activity' : activity,
                     'is_owner' : is_owner,
                     'vote_count' : vote_count,
                     'last_votes' : last_votes,
                     'user_already_voted' : user_voted_already})
    return render(request, 'activity.html', context)
@auth.requires_auth
def display_activity(request, activity_id):
    try:
        return aux_render_activity_page(request, activity_id, {})
    except ObjectDoesNotExist:
        return aux_render_user_page(request, {'error' : True,
                                              'error_message' : 'Not such activity'})

@auth.requires_auth
def edit_activity(request, activity_id):
    try:
        existing_activity = Activity.objects.get(id=activity_id)
        current_user = auth.get_current_user(request)
        if existing_activity.group.owner.id != current_user.id:
            return aux_render_activity_page(request, activity_id, {'activity_error' : True,
                                                                   'activity_error_message' : 'You are not authorized to edit this activity.'})
        if existing_activity.definitive:
            return aux_render_activity_page(request, activity_id, {'activity_error' : True,
                                                                   'activity_error_message' : 'Can not edit a definitive activity.'})
        existing_activity.name = request.POST['activity_name']
        existing_activity.city = request.POST['activity_city']
        existing_activity.start_date = datetime.strptime(request.POST['activity_date'], '%Y-%m-%dT%H:%M')
        existing_activity.duration = float(request.POST['activity_duration'])
        existing_activity.description = request.POST['activity_description']
        if existing_activity.start_date < datetime.now():
            raise ValueError('Activity must start in the future.')
        existing_activity.full_clean()
        existing_activity.save()
    except KeyError as ke:
        return aux_render_activity_page(request, activity_id, {'activity_error' : True,
                                             'activity_error_message' : 'Required field %s is missing' % str(ke)})
    except ObjectDoesNotExist as odne:
        return aux_render_activity_page(request, activity_id, {'activity_error' : True,
                                             'activity_error_message' : 'Activity does not exist, %s.' % str(odne)})
    except ValidationError as ve:
        return aux_render_activity_page(request, activity_id, {'activity_error' : True,
                                             'activity_error_message' : 'Activity validation failed because of %s' % ve.message_dict.values()[0][0]})
    except ValueError as ve:
        return aux_render_activity_page(request, activity_id, {'activity_error' : True,
                                                         'activity_error_message' : 'Validation failed because of %s' % str(ve)})
    else:
        return aux_render_activity_page(request, activity_id, {'activity_success' : True,
                                                         'activity_success_name' : request.POST['activity_name']})

@auth.requires_auth
def make_activity_definitive(request, activity_id):
    try:
        existing_activity = Activity.objects.get(id=activity_id)
        if existing_activity.definitive:
            raise ValueError('Activity is already definitive.')
        existing_activity.definitive = True
        existing_activity.save()
    except ObjectDoesNotExist as odne:
        return aux_render_activity_page(request, activity_id, {'activity_error' : True,
                                             'activity_error_message' : 'Activity does not exist, %s.' % str(odne)})
    except ValueError as ve:
        return aux_render_activity_page(request, activity_id, {'activity_error' : True,
                                                         'activity_error_message' : 'Validation failed because of %s' % str(ve)})
    else:
        return aux_render_activity_page(request, activity_id, {})

@auth.requires_auth
def thumbsup(request, activity_id):
    try:
        existing_activity = Activity.objects.get(id=activity_id)
        current_user = auth.get_current_user(request)
        existing_vote = Vote.objects.filter(user=current_user).filter(activity=existing_activity)
        if existing_vote.exists():
            return aux_render_activity_page(request, activity_id, {'activity_error' : True,
                                             'activity_error_message' : 'You have already voted'})
        new_vote = Vote(activity=existing_activity,
                        user=current_user)
        new_vote.save()
    except ObjectDoesNotExist as odne:
        return aux_render_activity_page(request, activity_id, {'activity_error' : True,
                                             'activity_error_message' : 'Activity does not exist, %s.' % str(odne)})
    except ValueError as ve:
        return aux_render_activity_page(request, activity_id, {'activity_error' : True,
                                                         'activity_error_message' : 'Validation failed because of %s' % str(ve)})
    else:
        return aux_render_activity_page(request, activity_id, {})

@auth.requires_auth
def thumbsupremove(request, activity_id):
    try:
        existing_activity = Activity.objects.get(id=activity_id)
        current_user = auth.get_current_user(request)
        existing_vote = Vote.objects.filter(user=current_user).filter(activity=existing_activity)
        if not existing_vote.exists():
            return aux_render_activity_page(request, activity_id, {'activity_error' : True,
                                             'activity_error_message' : 'You have not voted yet'})
        existing_vote.delete()
    except ObjectDoesNotExist as odne:
        return aux_render_activity_page(request, activity_id, {'activity_error' : True,
                                             'activity_error_message' : 'Activity does not exist, %s.' % str(odne)})
    else:
        return aux_render_activity_page(request, activity_id, {})

@auth.requires_auth
def search_for_images(request, activity_id):
    return render(request, 'select-image.html', {'activity_id' : activity_id})
@auth.requires_auth
def attach_image(request, activity_id):
    try:
        existing_activity = Activity.objects.get(id=activity_id)
        current_user = auth.get_current_user(request)
        if current_user.id != existing_activity.group.owner.id:
            return aux_render_activity_page(request, activity_id, {'activity_error' : True,
                                             'activity_error_message' : 'You can not attach images to this activity.'})
        image_url = request.GET['image_url']
        existing_activity.image = image_url
        existing_activity.save()
    except ObjectDoesNotExist as odne:
        return aux_render_activity_page(request, activity_id, {'activity_error' : True,
                                             'activity_error_message' : 'Activity does not exist, %s.' % str(odne)})
    else:
        return aux_render_activity_page(request, activity_id, {})

@auth.requires_auth
def get_flickr_images(request):
    request_data = json.loads(request.body)
    tags = request_data['tags']
    tags_match = re.match(r'^\w+(,\s?\w+)*$', tags)
    if tags_match is None:
        response_data = {'error' : 'Input is not a comma separated list.'}
    else:
        #http://stuvel.eu/media/flickrapi-docs/documentation/#response-parser-elementtree
        #https://docs.python.org/2/library/xml.etree.elementtree.html
        flickr = flickrapi.FlickrAPI('4a392efaaf14a1ff6b077f87c5844305', secret='c06df7c603f61f05')
        photo_set = flickr.photos_search(tags=tags, per_page="24")
        photo_coll = []
        for photo in photo_set.iter('photo'):
            photo_id = photo.attrib['id']
            info = flickr.photos_getInfo(photo_id=photo_id).find('photo')
            #https://www.flickr.com/services/api/misc.urls.html
            photo_url = 'http://farm%s.staticflickr.com/%s/%s_%s_b.jpg' %(info.attrib['farm'],
                                                                          info.attrib['server'],
                                                                          info.attrib['id'],
                                                                          info.attrib['secret'])
            photo_coll.append(photo_url)
        
        response_data = {'photos' : photo_coll}
    return HttpResponse(json.dumps(response_data), content_type='application/json')