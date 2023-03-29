import os
import subprocess
import time
from typing import Text
import ntplib
from apscheduler.schedulers.asyncio import AsyncIOScheduler
from apscheduler.schedulers.background import BackgroundScheduler
from apscheduler.schedulers.blocking import BlockingScheduler
from django_apscheduler.jobstores import DjangoJobStore, register_job
from apscheduler.triggers.combining import OrTrigger
from apscheduler.triggers.cron import CronTrigger
from django.utils import timezone
from django_apscheduler.models import DjangoJobExecution
import sys
import socket
from ctypes import *
from twisted.internet import protocol, reactor
from threading import Thread,current_thread

import win32api
import win32security

#global udp_socket
#udp_socket = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)

class pyNTPPacket(Structure):
    _fields_ = [("nControlWord", c_int),
        ("nRootDelay", c_int),
        ("nRootDispersion", c_int),
        ("nReferenceIdentifier", c_int64),
        ("n64ReferenceTimestamp", c_int64),
        ("n64OriginateTimestamp", c_int64),
        ("nTransmitTimestampSeconds", c_int),
        ("nTransmitTimestampSeconds", c_int),
        ("nTransmitTimestampFractions", c_int)]

# class pyTCPCommPacket(Structure):
#     _fields_ = [("btUpdown", c_byte),
#         ("cArrIMEI", c_int(16)),
#         ("btCMD", c_int),
#         ("btDataArray", c_int(20))]

class pyTCPCommPacket(Structure):
    _fields_ = [("btUpdown", c_byte),
                ("cArrIMEI", c_byte),
                ("btCMD", c_byte),
                ("btDataArray", c_byte)]

WINPE_SYSTEM32 = os.path.join('C:', os.sep, 'Windows', 'System32')
RETRY_DELAY = 10

class NtpException(Exception):
  pass

def SyncClockToNtp(retries: int = 3, server: Text = 'ntp.ntsc.ac.cn'):
  """Syncs the hardware clock to an NTP server."""
  print('Reading time from NTP server %s.', server)

  attempts = 0
  client = ntplib.NTPClient()
  response = None

  while True:
    try:
      response = client.request(server, version=3)
    except (ntplib.NTPException, socket.gaierror) as e:
      print('NTP client request error: %s', str(e))
    if response or attempts >= retries:
      break
    print(
        'Unable to contact NTP server %s to sync machine clock.  This '
        'machine may not have an IP address yet; waiting %d seconds and '
        'trying again. Repeated failure may indicate network or driver '
        'problems.', server, RETRY_DELAY)
    time.sleep(RETRY_DELAY)
    attempts += 1

  if not response:
    print('No response from NTP server.')
  else:
    #local_time = time.localtime(response.ref_time)
    local_time = time.localtime(response.tx_time)

    current_date = time.strftime('%m-%d-%Y', local_time)
    current_time = time.strftime('%H:%M:%S', local_time)
    print('Current date/time is %s %s', current_date, current_time)

    date_set = r'%s\cmd.exe /c date %s' % (WINPE_SYSTEM32, current_date)
    result = subprocess.call(date_set, shell=True)
    print('Setting date returned result %s', result)
    time_set = r'%s\cmd.exe /c time %s' % (WINPE_SYSTEM32, current_time)
    result = subprocess.call(time_set, shell=True)
    print('Setting time returned result %s', result)

class ThreadTCPComm(Thread):
    def run(self):
        try:
            print('You are in run method, ThreadName - ', current_thread().name)

            print('ThreadTCPComm Start Comm:', timezone.now())
            stTCPCommPacket = pyTCPCommPacket()
            tcp_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM, socket.IPPROTO_TCP)

            if tcp_socket:
                tcp_socket.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)

                server_address = ('localhost', 9191)
                print('Connect Staart')
                ret_conn = tcp_socket.connect(server_address)

                print('Connect State - ', ret_conn)
                recv_ok = True
                stTCPCommPacket.btUpdown = 0x88
                tcp_socket.send(stTCPCommPacket)

                while recv_ok:
                    data = tcp_socket.recv(512)
                    date_len = len(data)
                    if date_len > 0:
                        print('Received Data - ', date_len)
                        recv_ok = False

                print('ThreadTCPComm End Comm:', timezone.now())
                tcp_socket.close()
            else:
                print('Create Socket Error')
        except Exception as ex:
            print('ThreadTCPComm Exception', ex)

class ThreadUtil(Thread):
    def run(self):
        print('You are in run method, ThreadName - ', current_thread().name)
        #c = ntplib.NTPClient()
        #response = c.request('ntp.ntsc.ac.cn')
        SyncClockToNtp()
        #result = ntpfixtime.fix_time('ntp.ntsc.ac.cn')
        #print('Receive Time', ctime(response.tx_time))



        # priv_flags = win32security.TOKEN_ADJUST_PRIVILEGES | win32security.TOKEN_QUERY
        # hToken = win32security.OpenProcessToken(win32api.GetCurrentProcess(), priv_flags)
        # privilege_id = win32security.LookupPrivilegeValue(None, "SE_SYSTEMTIME_NAME")
        # win32security.AdjustTokenPrivileges(hToken, 0, [(privilege_id, win32security.SE_PRIVILEGE_ENABLED)])

        #win32api.SetSystemTime(2015, 11, 20, 20, 5, 30, 0, 0)


        # stNtpRecvPacket = pyNTPPacket()
        #
        # while True:
        #     data, addr = udp_socket.recvfrom(sizeof(pyNTPPacket))
        #     memmove(pointer(stNtpRecvPacket), data, sizeof(pyNTPPacket))
        #     print('Timestamp - ', stNtpRecvPacket.nTransmitTimestampSeconds)
        #     ttime = socket.ntohl(stNtpRecvPacket.nTransmitTimestampSeconds)

# class Echo(protocol.Protocol):
#     def dataReceived(self, data: bytes):
#         print('----Received------')
#         self.transport.write(data)
#
# class EchoFactory(protocol.Factory):
#     def buildProtocol(self, addr):
#         return Echo()




# This is the function you want to schedule - add as many as you want and then register them in the start() function below
def test_scheduler():
    dt_now = timezone.now()
    print('test_scheduler dt_now:', dt_now)
    stNtpPacket = pyNTPPacket()
    stNtpPacket.nControlWord = 0x1B
    ntp_server_ip = socket.gethostbyname('ntp.ntsc.ac.cn')
    SyncClockToNtp()
    # udp_send = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    # udp_send.sendto(stNtpPacket, (ntp_server_ip, 123))
    # udp_send.close()
    # udp_socket.sendto(stNtpPacket, (ntp_server_ip, 123))

def test_scheduler1():
    dt_now = timezone.now()
    print('test_scheduler1 dt_now:', dt_now)
    t = ThreadTCPComm()
    t.start()
    t.join()
    print('test_scheduler1 Thread Start')
    # udp_send = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    # udp_send.sendto(stNtpPacket, (ntp_server_ip, 123))
    # udp_send.close()
    # udp_socket.sendto(stNtpPacket, (ntp_server_ip, 123))

def start_sched():
    try:
       # udp_socket = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
        #udp_socket.bind(("127.0.0.1", 123))

        job_id = 'job_1' #+timezone.now().strftime('%Y%m%d_%H%M')
        # trigger = OrTrigger([
        #     CronTrigger(second='*/5'),
        # ])
        sched = BackgroundScheduler()
        # sched.add_jobstore(DjangoJobStore(), "default")
        # run this job every 24 hours
        #sched.add_job(test_scheduler, 'interval', seconds=10, name='test', jobstore='default')
        #sched.add_job(test_scheduler, 'cron', second=10, name='test', jobstore='default')
        sched.start()
        #sched.add_job(test_scheduler, 'cron', second='*/5', id=job_id, jobstore='default', replace_existing=True) #replace_existing=True :django_apscheduler_djangojob 중복insert 방지
        sched.add_job(test_scheduler, 'interval', seconds=20, name='test', jobstore='default')
        print("My Scheduler background started...", file=sys.stdout)

        # reactor.listenUDP(123, EchoFactory())
        # reactor.run()
        #
        # print("Twisted started...", file=sys.stdout)

        # test_scheduler()

        #t = ThreadUtil()
        #t.start()
        #t.join()

    except Exception as ex:
        print(ex)
        sched.shutdown()


def start_sched1():
    try:
       # udp_socket = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
        #udp_socket.bind(("127.0.0.1", 123))

        job_id = 'job_2' #+timezone.now().strftime('%Y%m%d_%H%M')
        # trigger = OrTrigger([
        #     CronTrigger(second='*/5'),
        # ])
        #sched = AsyncIOScheduler()
        sched = BackgroundScheduler()
        # sched.add_jobstore(DjangoJobStore(), "default")
        # run this job every 24 hours
        #sched.add_job(test_scheduler, 'interval', seconds=10, name='test', jobstore='default')
        #sched.add_job(test_scheduler, 'cron', second=10, name='test', jobstore='default')
        sched.start()
        #sched.add_job(test_scheduler, 'cron', second='*/5', id=job_id, jobstore='default', replace_existing=True) #replace_existing=True :django_apscheduler_djangojob 중복insert 방지
        sched.add_job(test_scheduler1, 'interval', seconds=10, name='test', jobstore='default')
        print("start_sched1 started...", file=sys.stdout)

    except Exception as ex:
        print(ex)
        sched.shutdown()


def start_block_sched():
    try:
       # udp_socket = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
        #udp_socket.bind(("127.0.0.1", 123))

        job_id = 'job_1' #+timezone.now().strftime('%Y%m%d_%H%M')
        # trigger = OrTrigger([
        #     CronTrigger(second='*/5'),
        # ])
        sched = BlockingScheduler()

        print("My Scheduler before blocking started...", file=sys.stdout)

        # sched.add_jobstore(DjangoJobStore(), "default")
        # run this job every 24 hours
        #sched.add_job(test_scheduler, 'interval', seconds=10, name='test', jobstore='default')
        #sched.add_job(test_scheduler, 'cron', second=10, name='test', jobstore='default')
        sched.start()
        #sched.add_job(test_scheduler, 'cron', second='*/5', id=job_id, jobstore='default', replace_existing=True) #replace_existing=True :django_apscheduler_djangojob 중복insert 방지
        sched.add_job(test_scheduler, 'interval', seconds=20, name='test', jobstore='default')
        print("My Scheduler blocking started...", file=sys.stdout)

        # reactor.listenUDP(123, EchoFactory())
        # reactor.run()
        #
        # print("Twisted started...", file=sys.stdout)

        # test_scheduler()

        #t = ThreadUtil()
        #t.start()
        #t.join()

    except Exception as ex:
        print(ex)
        sched.shutdown()

