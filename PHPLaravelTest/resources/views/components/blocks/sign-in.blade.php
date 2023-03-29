<div class="block-sign-in">
    <div class="desktop-only">
        @if (Auth::user())
            <div class="block-sign-in__status">
                <a class="icon-link icon-link--key" href="{{ route('my-account.index') }}" data-test-id="masthead:my-account">Hi {{ Auth::user()->first_name }}, </a>
                <a class="icon-link icon-link--hi" href="{{ route('logout') }}" data-test-id="masthead:sign-out">Sign Out</a>
            </div>
        @else
            <span><a class="icon-link icon-link--key icon-link--border-right" href="{{ route('login') }}" data-test-id="masthead:sign-in">Sign In</a></span>
        @endif
        <span><a class="icon-link icon-link--pin" href="{{ cms_route('locations') }}" data-test-id="masthead:find-a-wash">Find a Wash</a></span>
    </div>
</div>
