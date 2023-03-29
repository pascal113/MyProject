@include('layouts.web.header', ['meta' => $meta ?? null])

<div id="app">
    @yield('content')
</div>

@include('layouts.web.footer')
