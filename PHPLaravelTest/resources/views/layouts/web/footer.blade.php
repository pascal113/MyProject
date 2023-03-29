        @php
            $route =  request()->route() ? request()->route()->getName() : null;
            if ($route === 'page' && isset($page)) {
                $route = \FPCS\FlexiblePageCms\Services\CmsRoute::getPathByPage($page);
            }

            $composerContents = file_get_contents(base_path().'/composer.json');
            $composerJson = json_decode($composerContents, true);
            $version = $composerJson['version'] ?? null;
        @endphp

        <footer class="doc-footer">
            @if (substr($route, 0, 9) !== 'checkout.' && !in_array($route, ['support/contact-us']))
                <!--IXF: begin -->
                <div class="be-ix-link-block"><!--Link Equity Target Div--></div>
                <!--IXF: end -->
            @endif
            <div class="doc-footer__bottom">
                <div class="doc-footer__column-wrap">
                    <div class="doc-footer__column doc-footer__column--first">
                        <span><a class="icon-link icon-link--pin" href="{{ cms_route('locations') }}">Find a Wash</a></span>
                        <a href="https://apps.apple.com/us/app/brown-bear-car-wash/id1216372042" target="_blank"><img src="{{ asset('images/badge-appStore.svg') }}" alt="Logo" class="badge-apple"></a>
                        <a href="https://play.google.com/store/apps/details?id=com.thanx.brownbearcarwash&hl=en_US" target="_blank"><img src="{{ asset('images/badge-playStore.svg') }}" alt="Google Play" class="badge-google"></a>
                    </div>
                    <div class="doc-footer__column doc-footer__column--center">
                        <span>Copyright <sup>&copy;</sup> {{ date('Y') }} Car Wash Enterprises, Inc.</span>
                    </div>
                    <div class="doc-footer__column">
                        @include('components.blocks.social-icons')
                    </div>
                </div>
            </div>
            <div id="version">v{{ $version }}</div>
        </footer>
        @if (!App::isDownForMaintenance())
            @php
                $splashesAlreadyShown = json_decode(Cookie::get('viewed_splashes'), true) ?? [];

                $splash = \App\Models\Splash::where('is_enabled', true)
                    ->where('starts_at', '<=', \Carbon\Carbon::now()->toDateTimeString())
                    ->where('ends_at', '>', \Carbon\Carbon::now()->toDateTimeString())
                    ->whereNotIn('id', $splashesAlreadyShown)
                    ->first();
            @endphp
            @if ($splash)
                @include('components.popups.splash', $splash)

                @php
                    $viewedSplashes = array_merge($splashesAlreadyShown, [ $splash->id ]);
                    Cookie::queue('viewed_splashes', json_encode($viewedSplashes) , 60 * 60 * 24 * 30);
                @endphp
            @endif
        @endif

        @php
            $composerContents = file_get_contents(base_path().'/composer.json');
            $composerJson = json_decode($composerContents, true);
            $version = $composerJson['version'] ?? null;
        @endphp
        <script src="{{ preg_replace('/^http[s]?:\/\//', '//', asset(mix('/js/app.js').'?v='.$version)) }}"></script>

        @stack('js')

        <script>
            window.app = new Vue({
                el: window.vueElement || '#app',
            });
        </script>

        @stack('flexible-page-cms')
    </body>
</html>
