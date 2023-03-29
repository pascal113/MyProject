(function($) {
    'use strict' // Start of use strict

    $(document).ready(function() {
        $(window).load(function() {
            /**
             * Prevent FOUC, in combination with `display: none` directly on <body> tag
             * https://bitbucket.org/flowerpress/brownbear.com/issues/560/firefox-fouc-upon-first-visit-to-any-page
             */
            $('body').css('visibility', 'visible')
        })

        // Make certain characters always superscript
        var superscriptRegexp = new RegExp(/([®©℗])/g)
        $('.make-trademarks-superscript').each(function() {
            if (
                $(this)
                    .html()
                    .match(superscriptRegexp)
            ) {
                $(this).html(
                    $(this)
                        .html()
                        .replace(superscriptRegexp, '<sup>$1</sup>'),
                )
            }
        })

        // Smooth-scroll all anchor links
        $('.scroll-down-icon').click(function(e) {
            e.preventDefault()
            var dest = $(this).attr('href')
            $('html,body').animate({ scrollTop: $(dest).offset().top }, 'slow')
        })

        // Start fading .scroll-fade-top halfway up the page
        $(window).scroll(function() {
            var startPos = 0.5
            var $w = $(window)
            $('.scroll-fade-top').each(function() {
                var pos = $(this).offset().top - $w.scrollTop()
                var vh = $w.height()
                if (pos < vh * startPos) {
                    $(this).css('opacity', (pos / (vh * startPos)) * 1)
                }
                else {
                    $(this).css('opacity', 1)
                }
            })
        })

        // Fix tabindex in featherlight modals
        $.featherlight._callbackChain.afterContent = function() {
            $('.featherlight-content')
                .find(
                    'a, input[type!="hidden"], select, textarea, iframe, button:not(.featherlight-close), iframe, [contentEditable=true]',
                )
                .each(function(index) {
                    if (index === 0) {
                        $(this).prop('autofocus', true)
                    }

                    $(this).prop('tabindex', 0)
                })
        }

        // IE support for Array.from
        if (!Array.from) {
            Array.from = function(input) {
                var newArray = []

                for (var i = 0; i < input.length; i++) {
                    newArray.push(input[i])
                }

                return newArray
            }
        }

        // Custom output for HTML5 form validation errors
        function replaceValidationUI(form) {
            form.addEventListener(
                'invalid',
                function(event) {
                    event.preventDefault()
                },
                true,
            )

            form.addEventListener('submit', function(event) {
                if (!this.checkValidity()) {
                    event.preventDefault()
                }
            })

            if (form.querySelector('button:not([type=button]), input[type=submit]')) {
                form.querySelector(
                    'button:not([type=button]), input[type=submit]',
                ).addEventListener('click', function() {
                    const errors = form.querySelectorAll('.error-text')
                    const invalids = form.querySelectorAll(':invalid')

                    // Clear existing errors
                    Array.from(errors).map(error => {
                        const input = error.parentNode.querySelector('.has-error')
                        if (input) {
                            input.className = input.className.replace(/has-error/, '')
                        }

                        error.className += ' hidden'
                    })

                    // Insert errors into DOM
                    Array.from(invalids).map(invalid => {
                        invalid.className += ' has-error'

                        invalid.parentNode.insertAdjacentHTML(
                            'beforeend',
                            `<span role="alert" class="error-text invalid-feedback"><strong>
                                ${invalid.validationMessage}
                            </strong></span>`,
                        )
                    })

                    // Auto-focus on first erred field
                    if (invalids.length) {
                        invalids[0].focus()
                    }
                })
            }
        }
        Array.from(document.querySelectorAll('form')).map(form => {
            replaceValidationUI(form)
        })
    })
})(window.jQuery) // End of use strict

window.formatCurrency = function(number, options) {
    return `$${number.toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        ...options,
    })}`
}
