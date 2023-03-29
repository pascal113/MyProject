<section class="section">
    <div class="
        section-row
        section-row--padded-top
        section-row--center
        section-row--sm
    ">
        <h3 class="section-title">Terms &amp; Conditions</h3>
        <hr/>
        <p class="width-small page-intro">Please review the Unlimited Wash Club Terms and Conditions.</p>
    </div>
    <div class="
        section-row
        section-row--padded-bottom
    ">
        @if ($content)
            @foreach ($content as $row)
                <div class="terms-group">
                    @include('components.blocks.terms.content-row')
                </div>
            @endforeach
        @endif
    </div>
</section>


