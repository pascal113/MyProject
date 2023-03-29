<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @if (config('app.env') === 'production')
            <!-- Google Tag Manager -->
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-KH9XCR2');</script>
            <!-- End Google Tag Manager -->

            <!-- Facebook Pixel Code -->
            <script>
                !function(f,b,e,v,n,t,s)
                {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t,s)}(window, document,'script',
                'https://connect.facebook.net/en_US/fbevents.js');
                fbq('init', '{{ config('services.facebook_pixel.id') }}');
                fbq('track', 'PageView');
            </script>
            <noscript>
                <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ config('services.facebook_pixel.id') }}&ev=PageView&noscript=1"/>
            </noscript>
            <!-- End Facebook Pixel Code -->
            @stack('fbq')
        @else
            <script>
                fbq = function() {};
            </script>
        @endif

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <title>{{ $meta->title ?? '' }}</title>

        <link rel="canonical" href="{{ $meta->canonical_url ?? request()->url() }}"/>

        <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/manifest.json" crossorigin="use-credentials">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <link rel="manifest" href="/site.webmanifest">
        <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#0e76bd">
        <meta name="msapplication-TileColor" content="#0e76bd">
        <meta name="theme-color" content="#ffffff">

        <meta name="keywords" content="{{ $meta->keywords ?? '' }}">
        <meta name="description" content="{{ $meta->description ?? '' }}">

        <meta property="og:image:height" content="1257">
        <meta property="og:image:width" content="2400">
        <meta property="og:title" content="{{ $meta->title ?? '' }}">
        <meta property="og:description" content="{{ $meta->description ?? '' }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:image" content="{{ url('/img/logo-og.jpg') }}">

        @if (!App::isProductiony())
            <meta name="robots" content="noindex" />
        @endif

        @if (!empty($meta->schemaOrg))
            <script type="application/ld+json">
{!! json_encode($meta->schemaOrg, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}
            </script>
        @endif

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,400i,500,700&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{ mix('/css/app.css') }}" media="all" />
        @stack('css')

        <!--IXF: JavaScript begin-->
	    <script>
            /*
            --LICENSE--
            Access to and use of BrightEdge Link Equity Manager is governed by the
            Infrastructure Product Terms located at: www.brightedge.com/infrastructure-product-terms.
            Customer acknowledges and agrees it has read, understands and agrees to be bound by the
            Infrastructure Product Terms.
            */

            function startBESDK() {
                BEJSSDK.construct({
                    'api.endpoint': 'https://ixf2-api.bc0a.com',
                    'sdk.account': 'f00000000069397',
                    'requestparameters.caseinsensitive': true,
                    'whitelist.parameter.list': 'ixf|'
                });
            }

            (function() {
                var sdkjs = document.createElement('script');
                sdkjs.type = 'text/javascript';
                sdkjs.async = true;
                sdkjs.src = document.location.protocol + '//cdn.bc0a.com/be_ixf_js_sdk.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(sdkjs, s);
                if (document.addEventListener) {
                    sdkjs.addEventListener('load', startBESDK);
                } else {
                    sdkjs.onreadystatechange = startBESDK;
                }
            })();
        </script>
        <!--IXF: JavaScript end-->

        {!! ReCaptcha::htmlScriptTagJsApi(['contactForm', 'feedbackForm']) !!}

        @stack('header')
    </head>
    <body style="visibility: hidden;" class="fixed-header{{ Auth::user() ? ' is-logged-in' : '' }}">
        @if (config('app.env') === 'production')
            <!-- Google Tag Manager (noscript) -->
            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KH9XCR2" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
            <!-- End Google Tag Manager (noscript) -->
        @endif
{{-- Document Begin --}}
	    <header id="doc-header" class="doc-header">
            <div class="doc-header__top">
                <div class="column">
                    <div class="header-left">
                        <button class="hamburger hamburger--slider js-hamburger" type="button" dusk="main-nav-toggle" data-test-id="header:main-nav-toggle">
                            <span class="hamburger-text"></span>
                            <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
                <div class="column">
                <a id="site-logo-link" href="{{ cms_route('/') }}"><img src="{{ asset('images/logo-bear.svg') }}" alt="Logo" class="logo-bbc"></a>
                </div>
                <div class="column">
                    <div class="header-right">
                        @include('components.blocks.sign-in')
                        <span class="cart-wrap"><a class="cart num-cart-items" href="{{ route('cart.index') }}" data-test-id="masthead:cart">{{Cart::count()}}</a></span>
                    </div>
                </div>
            </div>
            {{-- Nav --}}
            @include('layouts.web.main-nav')
            {{-- End Nav --}}
		</header>

        @push('js')
            <script>
                $(document).ready(function() {
                    // Scrolling header
                    var prevScrollTop = 0;
                    var header = $('.doc-header');
                    var headerHeight = header.outerHeight();

                    $(window).on('scroll', function() {
                        var scrollTop = $(window).scrollTop() || 0;

                        var isHeaderHidden = scrollTop > prevScrollTop && scrollTop > headerHeight;
                        if (isHeaderHidden) {
                            hideHeader();
                        } else {
                            showHeader();
                        }
                        prevScrollTop = scrollTop;

                        if ($(window).scrollTop() <= headerHeight) {
                            header.removeClass('scrolling');
                        } else {
                            header.addClass('scrolling');
                        }
                    });
                    window.showHeader = function() {
                        header.removeClass('hidden');
                    }
                    window.hideHeader = function() {
                        header.addClass('hidden');
                    }

                    window.updateNumCartItems = function(num) {
                        $('.num-cart-items').each(function(i, el) {
                            $(el)
                                .text(num);
                        });
                    }

                    var highlightNumCartItemsActiveCount = 0;
                    window.highlightNumCartItems = function() {
                        setTimeout(function () {
                            highlightNumCartItemsActiveCount++;

                            $('.num-cart-items').each(function(i, el) {
                                $(el).addClass('num-cart-items--highlighted');
                            });

                            setTimeout(function () {
                                highlightNumCartItemsActiveCount--;

                                if (!highlightNumCartItemsActiveCount) {
                                    $('.num-cart-items').each(function(i, el) {
                                        $(el).removeClass('num-cart-items--highlighted');
                                    });
                                }
                            }, 2500);
                        }, 10);
                    }

                    // Toggle nav
                    $('.js-hamburger').click(function() {
                        $(this).toggleClass('nav-open');
                        $('body').toggleClass('nav-open');

                        if ($(this).hasClass('nav-open')) {
                            $('.js-home-slider').slick('slickPause');
                        }
                        else{
                            $('.js-home-slider').slick('slickPlay');
                        }
                    });
                });
            </script>
        @endpush
