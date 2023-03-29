<h2 class='section-title'>Questions?</h2>
<hr class="section-below">
<p><strong>{{ (isset($content) && $content) ? $content : 'Contact support to ask a question.' }}</strong></p>
<div class="button-row">
<a href="{{ cms_route('support/contact-us').($contactSupportQueryString ?? '') }}" class="button">Contact Support</a>
</div>
