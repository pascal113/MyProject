<div class="block-hide-show">
  <div class="block-hide-show__content">
    {{ $slot }}
  </div>
  <a class="button block-hide-show__trigger" href="#"{!! isset($id) ? ' id="hide-show-toggle-'.$id.'"' : '' !!} data-test-id="hide-show:{{ $id }}">Show More Info</a>
</div>
@push('js')
    <script>
        function queryParams() {
            var a = {}
            var q = location.search.replace(/^\?/,"").split(/\&/);

            for (var i in q) {
                if (q[i]) {
                    var b = q[i].split("=");

                    if (b[0]) {
                        a[b[0]] = decodeURIComponent(b[1]).replace(/\+/g," ");
                    }
                }
            }

            return a;
        }

        function toggleHideShow(event) {
            event.preventDefault();
            $(this).parent().toggleClass('is-active');
            $(this).toggleClass('button');
            $(this).text(function(i, text) {
                return text === "Show More Info" ? "Show Less Info" : "Show More Info";
            });
        }

        $(document).ready(function() {
            @if (isset($id))
            $("{{'#hide-show-toggle-'.$id}}").on('click', toggleHideShow);
                if (queryParams().show === '{{ $id }}') {
                    window.setTimeout(function () {
                        var elem = $('#hide-show-toggle-{{ $id }}');
                        elem.click();
                        elem.get(0).parentNode.scrollIntoView({
                            behavior: "smooth",
                            block: "start"
                        });
                    }, 100);
                }
            @else
            $('.block-hide-show__trigger').on('click', toggleHideShow);
            @endif
        });
    </script>
@endpush
