@foreach($items as $overview)
    <div class="wrapper {{ $overview->wrapperColorClass ?? '' }}">
        <section class="section">
            <div class="
                section-row
                section-row--padded
                section-row--center
                section-row--sm
            ">
                @if(!empty($overview->icon))
                    <i class="icon icon--{{ $overview->icon }}"></i>
                @endif
                <h2 class='section-title section-title--margin-top'>{{ $overview->heading }}</h2>
                @if(isset($overview->image))
                    <img class="img-framed img-framed--has-margin" src="{{ asset($overview->image) }}" alt="Self-Serve">
                @endif
                @include('components.base.paragraphs', ['paragraphs' => $overview->paragraphs])
                @if ($overview->button ?? null)
                    @include('components.base.button', array_merge((array) $overview->button, ['wrapperClass' => 'button-row--no-margin']))
                @endif
            </div>
        </section>
    </div>
@endforeach
