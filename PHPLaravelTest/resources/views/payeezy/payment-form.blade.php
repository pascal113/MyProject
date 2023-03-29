<form id="payeezy-form" action="{{ $paymentPageUrl }}" method="POST">
    <p class="page-intro">Redirecting you to payment...</p>
    <div class="loading-indicator loading" style="margin-block-end: 24px;"></div>
    <p>If you are not automatically redirected, please <button type="submit" class="a">click here</button>.</p>

    @if ($isDebug)
        <hr>
        <div style="text-align: left;">
            <p><strong>DEBUG MODE</strong></p>
            <p>The form below is normally hidden and automatically submits, causing a redirect to Payeezy.</p>
            <p>Since you are in debug mode, the form is shown, and you will need to manually submit it to proceed.</p>
        </div>
    @endif

    @if ($isLocal && $isBrowserSync)
        <hr>
        <div style="text-align: left;">
            <p><strong>This message only shows when you are on localhost and using BrowserSync:</strong></p>
            <p>Because of funkiness with the way BrowserSync proxy rewrites URLs, the receipt URL below may or may not be valid.</p>
            <p><strong>If you get an error on the next screen (payeezy),</strong> please check the URL below. If it is invalid, make sure you are running on the BrowserSync proxy port (BROWSER_SYNC_PORT in .env).</p>
        </div>
    @endif

    <input type="{{ $isDebug ? 'text' : 'hidden' }}" name="x_login" value="{{ $paymentPageId }}">
    <input type="{{ $isDebug ? 'text' : 'hidden' }}" name="x_amount" value="{{ $amount }}">
    <input type="{{ $isDebug ? 'text' : 'hidden' }}" name="x_type" value="{{ $transactionType }}">
    <input type="{{ $isDebug ? 'text' : 'hidden' }}" name="x_fp_sequence" value="{{ $fpSequence }}">
    <input type="{{ $isDebug ? 'text' : 'hidden' }}" name="x_fp_timestamp" value="{{ $fpTimestamp }}">
    <input type="{{ $isDebug ? 'text' : 'hidden' }}" name="x_fp_hash" value="{{ $fpHash }}" size="50">
    <input type="{{ $isDebug ? 'text' : 'hidden' }}" name="x_currency_code" value="{{ $currencyCode }}">

    <input type="{{ $isDebug ? 'text' : 'hidden' }}" name="x_receipt_link_method" value="{{ $receiptLinkMethod }}" />
    <input type="{{ $isDebug ? 'text' : 'hidden' }}" name="x_receipt_link_url" value="{{ $receiptLinkUrl }}" />
    <input type="{{ $isDebug ? 'text' : 'hidden' }}" name="x_receipt_link_text" value="{{ $receiptLinkText }}" />

    <input type="{{ $isDebug ? 'text' : 'hidden' }}" name="x_show_form" value="{{ $showForm }}"/>

    <input type="{{ $isDebug ? 'text' : 'hidden' }}" name="x_email_customer" value="{{ $emailCustomer }}"/>
    <input type="{{ $isDebug ? 'text' : 'hidden' }}" name="x_cust_id" value="{{ $customerId }}"/>
    <input type="{{ $isDebug ? 'text' : 'hidden' }}" name="x_invoice_num" value="{{ $invoiceNumber }}"/>
    @foreach ($lineItems as $lineItem)
        <input type="{{ $isDebug ? 'text' : 'hidden' }}" name="x_line_item" value="{{ $lineItem }}"/>
    @endforeach
    <input type="{{ $isDebug ? 'text' : 'hidden' }}" name="x_description" value="{{ $description }}"/>
    <input type="{{ $isDebug ? 'text' : 'hidden' }}" name="x_first_name" value="{{ $customerFirstName }}"/>
    <input type="{{ $isDebug ? 'text' : 'hidden' }}" name="x_last_name" value="{{ $customerLastName }}"/>
    <input type="{{ $isDebug ? 'text' : 'hidden' }}" name="x_zip" value="{{ $zip }}"/>
    <input type="{{ $isDebug ? 'text' : 'hidden' }}" name="x_tax" value="{{ $tax }}"/>
    <input type="{{ $isDebug ? 'text' : 'hidden' }}" name="x_tax_rate" value="{{ $taxRate }}"/>
    <input type="{{ $isDebug ? 'text' : 'hidden' }}" name="x_discount_amount" value="{{ $discountAmount }}"/>
</form>

@if (!$isDebug)
    @push('js')
        <script>
            function submitPaymentForm() {
                document.querySelector('#payeezy-form').submit();
            }
            $(document).ready(function() {
                submitPaymentForm();
            });
        </script>
    @endpush
@endif
