import socket
from ctypes import *
from .per_timing import millis

class pyTCPCommPacket(Structure):
    _fields_ = [("btUpdown", c_byte),
                ("btCMD", c_byte),
                ("btReserved1", c_byte),
                ("btReserved2", c_byte)]


def SendPacketBlocking(cmd):
    try:
        print('SendPacket Start:', millis())
        stTCPCommPacket = pyTCPCommPacket()
        tcp_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM, socket.IPPROTO_TCP)

        if tcp_socket:
            tcp_socket.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)

            #server_address = ('localhost', 9090)
            server_address = ('8.130.13.181', 9090)
            # server_address = ('82.156.19.32', 9090)
            print('SendPacket Connect Staart')
            ret_conn = tcp_socket.connect(server_address)

            print('SendPacket Connect State - ', ret_conn)
            recv_ok = True
            stTCPCommPacket.btUpdown = 0x88
            stTCPCommPacket.btCMD = cmd
            tcp_socket.send(stTCPCommPacket)
            # tcp_socket.setblocking(0)

            start_time = millis()

            while recv_ok:
                data = tcp_socket.recv(512)
                date_len = len(data)
                if date_len > 0:
                    print('Received Data - ', date_len)
                    recv_ok = False
                elif millis() - start_time > 1000:
                    print('Received Faield! ')
                    recv_ok = False

            print('ThreadTCPComm End Comm:', millis())
            tcp_socket.close()
        else:
            print('Create Socket Error')
    except Exception as ex:
        print('ThreadTCPComm Exception', ex)
