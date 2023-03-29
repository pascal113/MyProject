from rest_framework import serializers
from .models import *

class ClockDeviceStateInfoSerializer(serializers.ModelSerializer):
    class Meta:
        model = ClockDeviceStateInfo
        fields = '__all__'
