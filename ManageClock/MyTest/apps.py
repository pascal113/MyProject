from django.apps import AppConfig

class MytestConfig(AppConfig):
    default_auto_field = 'django.db.models.BigAutoField'
    name = 'MyTest'
    def ready(self):
        #from .scheduler import start_sched, start_sched1
        #start_sched()
        #start_sched1()
        #start_block_sched()
        print('My Test App Ready')
        #from .net_com import start_com
        #start_com()