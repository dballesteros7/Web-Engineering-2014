from django.db import models


# Create your models here.
class User(models.Model):
    username = models.CharField(max_length=50, unique=True, db_index=True)
    email = models.EmailField(unique=True, db_index=True)
    password = models.CharField(max_length=200)
    created_on = models.DateField(auto_now_add=True)
    access_token_twitter = models.CharField(max_length=200, blank=True)
    access_token_secret_twitter = models.CharField(max_length=200, blank=True)

class MeetupGroup(models.Model):
    name = models.CharField(max_length=100, db_index=True)
    city = models.CharField(max_length=100, db_index=True)
    created_on = models.DateField(auto_now_add=True)
    owner = models.ForeignKey('User', db_index=True, related_name='owner')
    members = models.ManyToManyField('User', db_index=True)

    class Meta(object):
        unique_together = ('name', 'city')

class Activity(models.Model):
    name = models.CharField(max_length=100, db_index=True)
    city = models.CharField(max_length=100, db_index=True)
    start_date = models.DateTimeField(db_index=True)
    duration = models.FloatField()
    description = models.TextField()
    definitive = models.BooleanField(default=False)
    image = models.URLField(blank=True)
    group = models.ForeignKey('MeetupGroup', db_index=True)

    class Meta(object):
        unique_together = ('name', 'group')

class Vote(models.Model):
    user = models.ForeignKey('User', db_index=True)
    activity = models.ForeignKey('Activity', db_index=True)
    thumbs = models.BooleanField(default=True)

    class Meta(object):
        unique_together = ('user', 'activity')
