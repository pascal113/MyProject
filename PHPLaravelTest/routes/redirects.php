<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

$redirects = [
    '/about/car-wash-questions' => '/support#car-wash-related-questions',
    '/about/contact' => '/support/contact-us',
    '/about/free-car-wash-day' => '/community-commitment/free-car-wash-day',
    '/about/in-the-community' => '/community-commitment/giving-back',
    '/about/job-opportunities' => '/our-company/careers',
    '/about/news' => '/our-company/news',
    '/about/our-history' => '/our-company/our-history',
    '/about/PayRange' => '/about-our-washes/payrange',
    '/about/policies' => '/support/policies',
    '/about/staff_members' => '/our-company/leadership',
    '/about/vehicle-restrictions' => '/about-our-washes/vehicle-restrictions',
    '/charity' => '/community-commitment/car-wash-fundraiser',
    '/clubchange' => '/support/contact-us?clubchange=true&show=email',
    '/dealerships' => '/commercial-programs/car-dealership-program',
    '/fleet' => '/commercial-programs/fleet-wash-program',
    '/intranet_categories' => '/company-files',
    '/news_stories' => function (Request $request) {
        if ($request->get('category') === 'press') {
            $url = '/our-company/press-center';
        } else {
            $url = '/our-company/news';
        }

        return [
            $url,
            301,
        ];
    },
    '/news_stories/{id}' => '/our-company/news',
    '/pages/ClubFAQ' => '/support',
    '/pages/ClubTC' => '/terms/wash-clubs',
    '/pages/unlimited-wash-club-FAQ ' => '/support#unlimited-wash-club-questions',
    '/pages/unlimited-wash-club-terms-conditions' => '/terms/wash-clubs',
    '/payment' => '/my-account/payment-methods',
    '/payrange' => '/about-our-washes/payrange',
    '/programs/car-dealerships' => '/commercial-programs/car-dealership-program',
    '/programs/car-wash-fundraiser' => '/community-commitment/car-wash-fundraiser',
    '/programs/fleet' => '/commercial-programs/fleet-wash-program',
    '/programs/unlimited-car-wash-club' => '/wash-clubs/unlimited-wash-club',
    '/programs/unlimited-car-wash-club/change' => '/support/contact-us?clubchange=true&show=email',
    '/services' => '/about-our-washes',
    '/services/{slug}' => function (Request $request, string $slug) {
        return [
            '/about-our-washes/'.$slug,
            301,
        ];
    },
    '/store' => '/shop',
    '/users/login' => '/sign-in',
    '/users/password/new' => '/password/reset',
    '/why-us/car-wash-questions' => '/support',
    '/why-us/wash-green' => '/community-commitment/wash-green',
];

foreach ($redirects as $from => $to) {
    Route::get($from, function (Request $request, $param1 = null) use ($to) {
        if (is_callable($to)) {
            list($url, $code) = $to($request, $param1);
        } else {
            $url = is_string($to) ? $to : $to['url'];
            $code = $to['code'] ?? 301;
        }

        return Redirect::to($url, $code);
    });
}
