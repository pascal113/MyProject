import win32con
from django.shortcuts import render
from ctypes import *
import win32service

# Create your views here.
def MyTestView(request):
    title = '首页'
    classContent = ''

    accessSCM = win32con.GENERIC_READ
    accessSrv = win32service.SC_MANAGER_ALL_ACCESS

    #Open Service Control Manager
    hscm = win32service.OpenSCManager(None, None, accessSCM)
    if hscm:
        service_handle = win32service.OpenService(hscm, 'My Sample Service', win32service.SERVICE_USER_DEFINED_CONTROL | win32service.SERVICE_QUERY_STATUS)
#        if service_handle:
#            response = win32service.ControlService(141)
    return render(request, 'index.html', locals())