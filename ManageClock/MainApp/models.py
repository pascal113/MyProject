from django.db import models

# Create your models here.

class ClockDeviceStateInfo(models.Model):
    id = models.AutoField(primary_key=True)
    imei = models.CharField('', max_length=32, default="")
    clk_type = models.IntegerField('', default="")
    brightness = models.IntegerField('', default="")
    time_zone = models.IntegerField('', default="")
    time_fmt = models.IntegerField('', default="")
    run_mode = models.IntegerField('', default="")
    ntp_period = models.IntegerField('', default="")
    check_period = models.IntegerField('', default="")
    counter_unixtime = models.IntegerField('', default="")
    counter_init_value = models.IntegerField('', default="")
    counter_alarm_time = models.IntegerField('', default="")
    clk_last_recv_time = models.IntegerField('', default="")
    clk_id = models.IntegerField('', default="")
    clk_ipaddr = models.CharField('', max_length=16, default="")
    clk_socket = models.IntegerField('', default="")
    connect_state = models.IntegerField('', default="")
    position = models.CharField('', max_length=128, default="")
    state_code = models.IntegerField('', default="")
    clk_version = models.IntegerField('', default="")
    clk_note = models.CharField('', max_length=64, default="")
    cmd_state = models.IntegerField('', default="")

    def __str__(self):
        return str(self.id)

    class Meta:
        verbose_name = 'Clock Info'
        verbose_name_plural = 'Clock Info'


class StateNoteInfo(models.Model):
    id = models.AutoField(primary_key=True)
    state_code = models.IntegerField('', default="")
    state_name_cn = models.CharField('', max_length=64, default="")
    state_name_en = models.CharField('', max_length=64, default="")
    state_name_fr = models.CharField('', max_length=64, default="")
    state_note = models.CharField('', max_length=64, default="")

    def __str__(self):
        return str(self.id)

    class Meta:
        verbose_name = 'State Note Info'
        verbose_name_plural = 'State Note Info'

class ClockTempRegInfo(models.Model):
    id = models.AutoField(primary_key=True)
    imei = models.CharField('', max_length=32, default="")
    clk_type = models.IntegerField('', default="")
    brightness = models.IntegerField('', default="")
    time_zone = models.IntegerField('', default="")
    time_fmt = models.IntegerField('', default="")
    run_mode = models.IntegerField('', default="")
    ntp_period = models.IntegerField('', default="")
    check_period = models.IntegerField('', default="")
    counter_unixtime = models.IntegerField('', default="")
    counter_init_value = models.IntegerField('', default="")
    counter_alarm_time = models.IntegerField('', default="")
    clk_last_recv_time = models.IntegerField('', default="")
    clk_id = models.IntegerField('', default="")
    clk_ipaddr = models.CharField('', max_length=16, default="")
    register = models.IntegerField('', default="")

    def __str__(self):
        return str(self.id)

    class Meta:
        verbose_name = 'Clock Temp Reg Info'
        verbose_name_plural = 'Clock Temp Reg Info'

class TimeZoneInfo(models.Model):
    id = models.AutoField(primary_key=True)
    time_zone = models.IntegerField('', default="")
    offset = models.IntegerField('', default="")
    name = models.CharField('', max_length=128, default="")

    def __str__(self):
        return str(self.id)

    class Meta:
        verbose_name = 'Time Zone Info'
        verbose_name_plural = 'Time Zone Info'


class CounterGroupInfo(models.Model):
    group_id = models.AutoField(primary_key=True)
    group_name = models.CharField('', max_length=64, default="")

    def __str__(self):
        return str(self.id)

    class Meta:
        verbose_name = 'Counter Group Info'
        verbose_name_plural = 'Counter Group Info'

class CounterClkInfo(models.Model):
    id = models.AutoField(primary_key=True)
    imei = models.CharField('', max_length=32, default="")
    group_id = models.IntegerField('', default="")
    counter_unixtime = models.IntegerField('', default="")
    counter_init_value = models.IntegerField('', default="")
    counter_alarm_time = models.IntegerField('', default="")

    def __str__(self):
        return str(self.id)

    class Meta:
        verbose_name = 'Counter Clock Info'
        verbose_name_plural = 'Counter Clock Info'


class ClockLogInfo(models.Model):
    id = models.AutoField(primary_key=True)
    imei = models.CharField('', max_length=32, default="")
    msg_code = models.IntegerField('', default="")
    log_unixtime = models.IntegerField('', default="")

    def __str__(self):
        return str(self.id)

    class Meta:
        verbose_name = 'Clock Log Info'
        verbose_name_plural = 'Clock Log Info'

class MsgInfo(models.Model):
    id = models.AutoField(primary_key=True)
    msg_code = models.IntegerField('', default="")
    msg_name_cn = models.CharField('', max_length=64, default="")
    msg_name_en = models.CharField('', max_length=64, default="")
    msg_name_fr = models.CharField('', max_length=64, default="")

    def __str__(self):
        return str(self.id)

    class Meta:
        verbose_name = 'Message Info'
        verbose_name_plural = 'Message Info'