from django.urls import path

from MyTest.views import MyTestView

urlpatterns = [
    path('', MyTestView, name='MyTest'),
]