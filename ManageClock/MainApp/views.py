from django.shortcuts import render
from .serializers import *
from rest_framework import generics
import time
from .per_timing import millis

class ClockDeviceStateInfoListCreate(generics.ListCreateAPIView):
    queryset = ClockDeviceStateInfo.objects.all()
    serializer_class = ClockDeviceStateInfoSerializer

# Create your views here.
def MainAppView(request):
    title = '首页'
    classContent = ''

    return render(request, 'index.html', locals())


def SendView(request):
    title = '首页'
    classContent = ''

    print('Process Start:', millis())

    cmd = int(request.GET['cmd'])

    from .tcp_comm import SendPacketBlocking
    SendPacketBlocking(cmd)

    print('Process End:', millis())

    return render(request, 'index.html', locals())
