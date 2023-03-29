from django.urls import path

from . import views

urlpatterns = [
    # path('react', views.ClockDeviceStateInfoListCreate.as_view()),
    path('', views.ClockDeviceStateInfoListCreate.as_view(), name='MainApp'),
    path('/send', views.SendView, name='SendView'),
]