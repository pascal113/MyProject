    Thanks!
    The Brown Bear Car Wash Team
    CALL: 206.789.3700
    (Monday – Friday 8am - 4pm)
    VISIT: Visit Our Website ({{ url('/') }})
    EMAIL: {{ config('mail.contact.address') }}

    https://www.facebook.com/BrownBearCarWash

    https://www.instagram.com/brownbearcarwash/

    https://twitter.com/BrownBear

    https://www.youtube.com/channel/UCUMOKfYWM8XUbnr-lnruhgQ

    {{ !empty($user) ? 'This email was sent to '.$user->email.' because you opted in on BrownBear.com. To adjust your communication preferences log in to your account and manage your notification settings ('.cms_route('my-account.notification-preferences.edit').').' : '' }}
