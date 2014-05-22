from django.conf.urls import patterns, url
from django.contrib import admin

from ethzmeetup import views


admin.autodiscover()

urlpatterns = patterns('',
    # url(r'^admin/', include(admin.site.urls)),
    url(r'^$', views.index, name='index'),
    url(r'^login/$', views.login, name='login'),
    url(r'^register/$', views.register, name='register'),
    url(r'^login/complete/$', views.complete_login, name='complete-login'),
    url(r'^register/complete/$', views.complete_register, name='complete-register'),
    url(r'^create/group$', views.create_group, name='create-group'),
    url(r'^group/(?P<group_id>[0-9]+)/$', views.display_group, name='display-group'),
    url(r'^edit/group/(?P<group_id>[0-9]+)/$', views.edit_group, name='edit-group'),
    url(r'^create/group/(?P<group_id>[0-9]+)/activity/$', views.create_activity, name='create-activity'),
    url(r'^activity/(?P<activity_id>[0-9]+)/$', views.display_activity, name='display-activity'),
    url(r'^edit/activity/(?P<activity_id>[0-9]+)/$', views.edit_activity, name='edit-activity'),
    url(r'^edit/activity/(?P<activity_id>[0-9]+)/definitive/$', views.make_activity_definitive, name='definitive-activity'),
    url(r'^edit/activity/(?P<activity_id>[0-9]+)/thumbsup/$', views.thumbsup, name='thumbsup-activity'),
    url(r'^edit/activity/(?P<activity_id>[0-9]+)/thumbsup/remove/$', views.thumbsupremove, name='thumbsupremove-activity'),
    url(r'^edit/activity/(?P<activity_id>[0-9]+)/images/$', views.search_for_images, name='search-images'),
    url(r'^edit/activity/(?P<activity_id>[0-9]+)/attach/$', views.attach_image, name='attach-image'),
    url(r'^flickr/$', views.get_flickr_images, name='flickr-service'),
    url(r'^sign_in_twitter/$', views.auth_flow_twitter, name='twitter-oauth-1'),
    url(r'^twitter_callback/$', views.auth_flow_twitter_2, name='twitter-oauth-2'),
    url(r'^logout/$', views.logout, name='logout')
)
