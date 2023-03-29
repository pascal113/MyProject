@extends('layouts.web')

@section('content')
    @component('components.blocks.page-hero', [
        'class' => 'block-page-hero--title-bar',
        'heading' =>  Auth::user() ? 'Hi, '.Auth::user()->first_name.'!' : '',
        'image' => asset('images/page-hero-bgrs/find-a-wash.jpg'),
    ])@endcomponent
    <main class="doc-content">
        <div class="wrapper">
            <section class="section">
                <div class="
                    section-row
                    section-row--padded
                    section-row--center
                    section-row--sm
                ">
                    <h2 class='section-title section-title--no-margin'>Brown Bear Files</h2>
                </div>
            </section>
        </div>
        <div class="wrapper">
            <section class="section">
                <div class="
                    section-row
                    section-row--padded-bottom
                    section-row--center
                    section-row--sm
                ">
                    <div class="block-expandable-section block-expandable-section--files">
                        @forelse ($categories as $category)
                            <div>
                                <h3 class="block-expandable-section__section section-title section-trigger" name="{{ $category->slug }}">{{ $category->name }}</h3>
                                <div class="section-content">
                                    <ul>
                                        @forelse ($category->files as $file)
                                            @if (preg_match('/^video/', $file->mime_type))
                                                <li class="clean-list">
                                                    <strong>{{ $file->name }}</strong>
                                                    <video
                                                        controls="controls"
                                                        poster="{{ $file->thumbnail ? $file->temporaryThumbnailUrl :  asset('images/videoplaceholder.jpg') }}"
                                                        style="width: 100%;"
                                                    >
                                                        <source src="{{ $file->temporaryUrl }}" type="{{ $file->mime_type }}">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                </li>
                                            @elseif ($file->mime_type === 'external_url')
                                                <li><a href="{{ $file->path }}" target="_blank">{{ $file->name }}</a></li>
                                            @else
                                                <li><a href="{{ $file->temporaryUrl }}" target="_blank">{{ $file->name }}</a></li>
                                            @endif
                                        @empty
                                            <p class='text-center'>This category is empty.</p>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        @empty
                            <p class='text-center'>There are no files to display.</p>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Hide/Show blocks
            $('.section-trigger').click(function(e) {
                e.preventDefault();
                $(this).toggleClass('is-active');
                $(this).next('div.section-content').toggleClass('is-active');
            });
        });
    </script>
@endpush
