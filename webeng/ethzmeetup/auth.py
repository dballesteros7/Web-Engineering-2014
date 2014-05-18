import urllib

from django.core.urlresolvers import reverse
from django.http.response import HttpResponseRedirect

from ethzmeetup.models import User


USER_ID_KEY = 'user_id'

def requires_auth(func):
    def wrapper(request, **kwargs):
        if request.session.get(USER_ID_KEY, None) is not None:
            return func(request, **kwargs)
        else:
            return HttpResponseRedirect('%s?redirect_to=%s' % (reverse('login'),
                                                               urllib.quote(request.get_full_path())))
    return wrapper

def unregistered(func):
    def wrapper(request):
        if request.session.get(USER_ID_KEY, None) is None:
            return func(request)
        else:
            # If there is an user logged in, go to index
            return HttpResponseRedirect(reverse('index'))
    return wrapper

def login_user(request, user_id):
    request.session[USER_ID_KEY] = user_id

def get_current_user(request):
    return User.objects.get(id = request.session[USER_ID_KEY])

def logout_user(request):
    del request.session[USER_ID_KEY]