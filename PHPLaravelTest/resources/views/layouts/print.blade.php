@include('layouts.print.header', ['meta' => $meta ?? null])
    <body>
        <img class="print-header" src="/images/print-header.png" alt="Brown Bear Car Wash" />

        <main>
            @yield('content')
        </main>

        <script>
            window.print();
            window.onafterprint = window.close;
        </script>
    </body>
</html>
