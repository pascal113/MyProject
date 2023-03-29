from twisted.internet import protocol, reactor

class Echo(protocol.Protocol):
    def dataReceived(self, data):
        print('data received')
        self.transport.write(data)

class EchoFactory(protocol.Factory):
    def buildProtocol(self, addr):
        return Echo()

def start_com():
    print('Twisted start1')
    reactor.listenTCP(8888, EchoFactory())
    reactor.run()
    print('Twisted start2')