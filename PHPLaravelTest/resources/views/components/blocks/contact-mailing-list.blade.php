<div class='block-contact-email'>
    @include('components.base.paragraphs', ['paragraphs' => $paragraphs])
    <form id="mailingListForm" action="{{ route('api.contact.mailing-list') }}" method="POST" onsubmit="submitToMailingList(event)">
        <div class="field-wrapper">
            <label for="mailing-list-name">Your Full Name</label>
            <input id="mailing-list-name" name="mailing-list-name" type="text" placeholder="First and Last Name" required>
            <span class="error-text hidden"></span>
        </div>
        <div class="field-wrapper">
            <label for="mailing-list-email">Your Email Address</label>
            <input id="mailing-list-email" name="mailing-list-email" type="email" placeholder="name@email.com" required>
            <span class="error-text hidden"></span>
        </div>
        <div class="button-row text-center">
            <button name="optinform$btnSubscribe" type="submit" class="button" >Sign Up</button>
        </div>
    </form>
</div>

@include('components.popups.mailing-list-thank-you')

@push('js')
    <script>
        function submitToMailingList(event) {
            event.preventDefault();

            var data = Object.fromEntries(new FormData(event.target).entries());

            window.axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
            var response = window.axios.post(event.target.action, data)
                .then(function () {
                    $.featherlight($('#mailing-list-thank-you'), {
                        beforeOpen: function () {
                            $.featherlight.close();
                        }
                    });
                })
                .catch(function () {
                    Vue.toasted.error('Oops! There was a problem saving you information.');
                });

            return false;
        }
    </script>
@endpush
