@php
    $isYouTube = preg_match('/^[a-z]*:\/\/[^\/]*youtube\.com\/embed\//', $videoUrl);
@endphp
                <div class="video-wrapper video-wrapper--section-margin {{ $isYouTube ? 'video-wrapper--youtube' : '' }} img-framed">
                    @if ($isYouTube)
                        <iframe width="560" height="315" src="{{ $videoUrl }}" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    @else
                        <video controls="" @if(!empty($posterUrl)) poster="{{ $posterUrl }}" @endif style="width: 100%">
                            <source src="{{ $videoUrl }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @endif
                </div>
