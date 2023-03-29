<div class="main-nav-wrap">
    <nav id="menu" class="main-nav">
        <div class="main-nav__top">
            @include('components.blocks.sign-in')
        </div>
        <ul class="main-nav__content nav-list">
            <li>
                <a href="{{ cms_has_route('shop') ? cms_route('shop') : '#' }}" class="js-nav-trigger" data-test-id="main-nav:shop">Shop <span class="nav-trigger"></span></a>
                <ul class="nav-list__sub">
                    @if(cms_has_route('shop'))
                        <li><a href="{{ cms_route('shop') }}" data-test-id="main-nav:shop:overview">Overview</a></li>
                    @endif
                    @if(cms_has_route('shop.all-products'))
                        <li><a href="{{ cms_route('shop.all-products') }}" data-test-id="main-nav:shop:all">All Products</a></li>
                    @endif
                    @if(cms_has_route('shop.wash-club-memberships'))
                        <li><a href="{{ cms_route('shop.wash-club-memberships') }}" data-test-id="main-nav:shop:wash-clubs">Unlimited Wash Club Memberships</a></li>
                    @endif
                    @if(cms_has_route('shop.wash-cards-ticket-books'))
                        <li><a href="{{ cms_route('shop.wash-cards-ticket-books') }}" data-test-id="main-nav:shop:wash-cards">Wash Cards & Ticket Books</a></li>
                    @endif
                    @if(cms_has_route('shop.branded-merchandise'))
                        <li><a href="{{ cms_route('shop.branded-merchandise') }}" data-test-id="main-nav:shop:merch">Branded Merchandise</a></li>
                    @endif
                </ul>
            </li>
            <li>
                <a href="{{ cms_has_route('about-our-washes') ? cms_route('about-our-washes') : '#' }}" class="js-nav-trigger">About Our Washes <span class="nav-trigger"></span></a>
                <ul class="nav-list__sub">
                    @if(cms_has_route('about-our-washes'))
                        <li><a href="{{ cms_route('about-our-washes') }}">Overview</a></li>
                    @endif
                    @if(cms_has_route('about-our-washes.tunnel-car-wash'))
                        <li><a href="{{ cms_route('about-our-washes.tunnel-car-wash') }}"><i class="icon icon--tunnel"></i> Tunnel Car Wash</a></li>
                    @endif
                    @if(cms_has_route('about-our-washes.self-serve-car-wash'))
                        <li><a href="{{ cms_route('about-our-washes.self-serve-car-wash') }}"><i class="icon icon--self"></i> Self-Serve Car Wash</a></li>
                    @endif
                    @if(cms_has_route('about-our-washes.touchless-car-wash'))
                        <li><a href="{{ cms_route('about-our-washes.touchless-car-wash') }}"><i class="icon icon--touchless"></i> Touchless Car Wash</a><hr></li>
                    @endif
                    @if(cms_has_route('about-our-washes.top-tier-gas'))
                        <li><a href="{{ cms_route('about-our-washes.top-tier-gas') }}"><i class="icon icon--gas"></i> Top-Tier Gas</a></li>
                    @endif
                    @if(cms_has_route('about-our-washes.hungry-bear-market'))
                        <li><a href="{{ cms_route('about-our-washes.hungry-bear-market') }}"><i class="icon icon--market"></i> Hungry Bear Market</a></li>
                    @endif
                </ul>
            </li>
            <li>
                <a href="{{ cms_has_route('wash-clubs') ? cms_route('wash-clubs') : '#' }}" class="js-nav-trigger">Wash Clubs <span class="nav-trigger"></span></a>
                <ul class="nav-list__sub">
                    @if(cms_has_route('wash-clubs'))
                        <li><a href="{{ cms_route('wash-clubs') }}">Overview</a></li>
                    @endif
                    @if(cms_has_route('wash-clubs.unlimited-wash-club'))
                        <li><a href="{{ cms_route('wash-clubs.unlimited-wash-club') }}">Unlimited Wash Club</a></li>
                    @endif
                    @if(cms_has_route('wash-clubs.for-hire-unlimited-wash-club'))
                        <li><a href="{{ cms_route('wash-clubs.for-hire-unlimited-wash-club') }}">For Hire Unlimited Wash Club</a></li>
                    @endif
                </ul>
            </li>
            <li>
                <a href="{{ cms_has_route('commercial-programs') ? cms_route('commercial-programs') : '#' }}" class="js-nav-trigger">Commercial Programs <span class="nav-trigger"></span></a>
                <ul class="nav-list__sub">
                    @if(cms_has_route('commercial-programs'))
                        <li><a href="{{ cms_route('commercial-programs') }}">Overview</a></li>
                    @endif
                    @if(cms_has_route('commercial-programs.for-hire-unlimited-wash-club'))
                        <li><a href="{{ cms_route('wash-clubs.for-hire-unlimited-wash-club') }}">For Hire Unlimited Wash Club</a></li>
                    @endif
                    @if(cms_has_route('commercial-programs.car-dealership-program'))
                        <li><a href="{{ cms_route('commercial-programs.car-dealership-program') }}">Car Dealership Program</a></li>
                    @endif
                    @if(cms_has_route('commercial-programs.fleet-wash-program'))
                        <li><a href="{{ cms_route('commercial-programs.fleet-wash-program') }}">Fleet Wash Program</a></li>
                    @endif
                </ul>
            </li>
            <li>
                <a href="{{ cms_has_route('community-commitment') ? cms_route('community-commitment') : '#' }}" class="js-nav-trigger">Community Commitment <span class="nav-trigger"></span></a>
                <ul class="nav-list__sub">
                    @if(cms_has_route('community-commitment'))
                        <li><a href="{{ cms_route('community-commitment') }}">Overview</a></li>
                    @endif
                    @if(cms_has_route('community-commitment.car-wash-fundraiser'))
                        <li><a href="{{ cms_route('community-commitment.car-wash-fundraiser') }}">Car Wash Fundraiser</a></li>
                    @endif
                    @if(cms_has_route('community-commitment.giving-back'))
                        <li><a href="{{ cms_route('community-commitment.giving-back') }}">Giving Back</a></li>
                    @endif
                    @if(cms_has_route('community-commitment.wash-green'))
                        <li><a href="{{ cms_route('community-commitment.wash-green') }}">Wash Green</a></li>
                    @endif
                    @if(cms_has_route('community-commitment.guard-reserves'))
                        <li><a href="{{ cms_route('community-commitment.guard-reserves') }}">Guard & Reserve Support</a></li>
                    @endif
                    @if(cms_has_route('community-commitment.diversity-inclusion'))
                        <li><a href="{{ cms_route('community-commitment.diversity-inclusion') }}">Diversity & Inclusion</a></li>
                    @endif
                </ul>
            </li>
            <li>
                <a href="{{ cms_has_route('our-company') ? cms_route('our-company') : '#' }}" class="js-nav-trigger">Our Company <span class="nav-trigger"></span></a>
                <ul class="nav-list__sub">
                    @if(cms_has_route('our-company'))
                        <li><a href="{{ cms_route('our-company') }}">Overview</a></li>
                    @endif
                    @if(cms_has_route('our-company/our-history'))
                        <li><a href="{{ cms_route('our-company/our-history') }}">Our History</a></li>
                    @endif
                    @if(cms_has_route('our-company/leadership'))
                        <li><a href="{{ cms_route('our-company/leadership') }}">Leadership</a></li>
                    @endif
                    @if(cms_has_route('our-company/careers'))
                        <li><a href="{{ cms_route('our-company/careers') }}">Careers</a></li>
                    @endif
                    @if(cms_has_route('our-company/news'))
                        <li><a href="{{ cms_route('our-company/news') }}">News</a></li>
                    @endif
                    @if(cms_has_route('our-company/press-center'))
                        <li><a href="{{ cms_route('our-company/press-center') }}">Press Center</a></li>
                    @endif
                </ul>
            </li>
            <li>
                <a href="{{ cms_has_route('support') ? cms_route('support') : '#' }}" class="js-nav-trigger" data-test-id="main-nav:support">Support <span class="nav-trigger"></span></a>
                <ul class="nav-list__sub">
                    @if(cms_has_route('support'))
                        <li><a href="{{ cms_route('support') }}" data-test-id="main-nav:support:faq">FAQs</a></li>
                    @endif
                    @if(cms_has_route('support/contact-us'))
                        <li><a href="{{ cms_route('support/contact-us') }}" data-test-id="main-nav:support:contact-us">Contact Us</a></li>
                    @endif
                </ul>
            </li>

            @if (Auth::user())
                <li>
                    <a href="{{ route('my-account.index') }}">My Account</a>
                </li>
                @if (Auth::user()->hasPermission('frontend_company_files'))
                    <li>
                        <a href="{{ route('company-files.index') }}">Company Files</a>
                    </li>
                @endif
                @if (Auth::user()->hasPermission('browse_admin') || Auth::user()->canViewGatewayAdmin)
                    <li>
                        <a href="{{ route('admin.landing') }}">Admin</a>
                    </li>
                @endif
            @endif
        </ul>
    </nav>
    <div class="main-nav__bottom">
        <div>
            <a href="https://apps.apple.com/us/app/brown-bear-car-wash/id1216372042" target="_blank"><img src="{{ asset('images/badge-appStore.svg') }}" alt="Logo" class="badge-apple"></a>
            <a href="https://play.google.com/store/apps/details?id=com.thanx.brownbearcarwash&hl=en_US" target="_blank"><img src="{{ asset('images/badge-playStore.svg') }}" alt="Google Play" class="badge-google"></a>
        </div>
        <div>
            @include('components.blocks.social-icons')
        </div>
        <span>Copyright &copy; {{ date('Y') }} Car Wash Enterprises, Inc.</span>
    </div>
</div>
@push('js')
    <script>
        $(document).ready(function() {
            $('.js-nav-trigger').click(function(e) {
                e.preventDefault();
                $(this).toggleClass('is-active');
                $(this)
                    .next('.nav-list__sub')
                    .toggleClass(' is-active');
            });
        });
    </script>
@endpush
