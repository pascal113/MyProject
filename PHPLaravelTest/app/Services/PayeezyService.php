<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PayeezyService
{
    public const FORM_DEFAULT_CURRENCY_CODE = 'USD';
    public const FORM_DEFAULT_EMAIL_CUSTOMER = 'FALSE';
    public const FORM_DEFAULT_FORM = 'PAYMENT_FORM';
    public const FORM_DEFAULT_RECEIPT_LINK_METHOD = 'AUTO-POST';
    public const FORM_DEFAULT_RECEIPT_LINK_TEXT = 'Return to Brown Bear Car Wash';
    public const FORM_DEFAULT_TRANSACTION_TYPE = 'AUTH_CAPTURE';

    // https://support.payeezy.com/hc/en-us/articles/203992129-Hosted-Checkout-Payment-Pages-Integration-Manual#9.1
    public const FORM_RESPONSE_CODE_APPROVED = 1;
    public const FORM_RESPONSE_CODE_DECLINED = 2;
    public const FORM_RESPONSE_CODE_ERROR = 3;

    // https://support.payeezy.com/hc/en-us/articles/115003604894-Transaction-Types-Available
    public const API_TRANSACTION_TYPE_AUTH_CAPTURE = '00';
    public const API_TRANSACTION_TYPE_AUTH_ONLY = '01';
    public const API_TRANSACTION_TYPE_FORCED_POST = '03';
    public const API_TRANSACTION_TYPE_REFUND = '13';

    // https://support.payeezy.com/hc/en-us/articles/206601408-First-Data-Payeezy-Gateway-Web-Service-API-Reference-Guide#3.13
    public const API_INDICATOR_FIRST_TIME_TRANSACTION = '1';
    public const API_INDICATOR_SUBSEQUENT_TRANSACTION = 'S';
    public const API_INITIATION_CUSTOMER_INITIATED = 'C';
    public const API_INITIATION_MERCHANT_INITIATED = 'M';
    public const API_SCHEDULE_SCHEDULED = 'S';
    public const API_SCHEDULE_UNSCHEDULED = 'U';

    /**
     * Create a payment form (redirects to Payeezy's form)
     */
    public static function paymentForm(array $options): View
    {
        $options = (object)$options;

        // Parse passed options
        $amount = number_format($options->amount, 2);
        $currencyCode = $options->currentCode ?? static::FORM_DEFAULT_CURRENCY_CODE; // Needs to agree with the currency of the payment page
        $customerFirstName = $options->customerFirstName ?? null;
        $customerId = $options->customerId ?? null;
        $customerLastName = $options->customerLastName ?? null;
        $isDebug = $options->debug ?? false;
        $isLocal = app()->isLocal();
        $isBrowserSync = !!config('app.browser_sync_port');
        $description = $options->description ?? null;
        $emailCustomer = $options->emailCustomer ?? static::FORM_DEFAULT_EMAIL_CUSTOMER;
        $discountAmount = $options->discountAmount ?? null;
        $invoiceNumber = $options->invoiceNumber ?? null;
        $lineItems = $options->lineItems ?? [];
        $receiptLinkMethod = $options->responseMethod ?? static::FORM_DEFAULT_RECEIPT_LINK_METHOD;
        $receiptLinkText = $options->returnLinkText ?? static::FORM_DEFAULT_RECEIPT_LINK_TEXT;
        $receiptLinkUrl = ($isLocal && $isBrowserSync ? 'http:' : '').$options->responseUrl; // Funky URL stuff for the browserSync proxy. The browsersync proxy will convert "http://locahost..." to "//localhost", causing all sorts of problems. It appears to do this ons a proxy layer that we can't directly access via PHP... :( More instructions are available for the dev user in the blade view
        $showForm = $options->form ?? static::FORM_DEFAULT_FORM;
        $tax = $options->tax ?? null;
        $taxRate = $options->taxRate ?? null;
        $transactionType = $options->transactionType ?? static::FORM_DEFAULT_TRANSACTION_TYPE;
        $zip = $options->zip ?? null;

        // Generate signing hashes
        $paymentPageUrl = config('services.payeezy.payment_page_url');
        $paymentPageId = config('services.payeezy.payment_page_id');  // Payeezy web interface -> Payment Pages -> "Payment Page ID" column
        $transactionKey = config('services.payeezy.payment_page_key'); // Payeezy web interface -> Payment Pages -> {select one} -> Security -> "Transaction Key"
        $time = Carbon::now('UTC')->timestamp;
        srand($time); // initialize random generator for x_fp_sequence
        $fpSequence = rand(1000, 100000) + 6160319;
        $fpTimestamp = $time; // needs to be in UTC. Make sure webserver produces UTC
        $hmac_data = $paymentPageId.'^'.$fpSequence.'^'.$fpTimestamp.'^'.$amount.'^'.$currencyCode;
        $fpHash = hash_hmac('SHA1', $hmac_data, $transactionKey);

        return view('payeezy.payment-form')->with(compact(
            'amount',
            'currencyCode',
            'customerFirstName',
            'customerId',
            'customerLastName',
            'description',
            'discountAmount',
            'emailCustomer',
            'fpHash',
            'fpSequence',
            'fpTimestamp',
            'invoiceNumber',
            'isBrowserSync',
            'isDebug',
            'isLocal',
            'lineItems',
            'paymentPageId',
            'paymentPageUrl',
            'receiptLinkMethod',
            'receiptLinkText',
            'receiptLinkUrl',
            'showForm',
            'tax',
            'taxRate',
            'transactionType',
            'zip'
        ));
    }

    /**
     * Parse response from Payeezy form and return useful object
     */
    public static function parsePaymentFormResponse(Request $request): object
    {
        $return = (object)[
            'amount' => (float)$request->get('x_amount'),
            'bank_message' => $request->get('Bank_Message'),
            'declined' => (int)$request->get('x_response_code') === static::FORM_RESPONSE_CODE_DECLINED,
            'message' => $request->get('x_response_reason_text'),
            'response' => $request->all(),
            'success' => (int)$request->get('x_response_code') === static::FORM_RESPONSE_CODE_APPROVED,
        ];

        if (!self::validatePaymentFormResponseHash($request)) {
            $return->success = false;
            $return->message = 'Invalid payment signature.';
        }

        return $return;
    }

    /**
     * Validate hash received in response.
     *
     * Hash is expected to be:
     *   sha1( $relayResponseKey . x_login . x_trans_id . $amount(ALWAYS WITH 2 DEC POINTS) )
     */
    private static function validatePaymentFormResponseHash(Request $request): bool
    {
        $hashBase = config('services.payeezy.payment_page_response_key').config('services.payeezy.payment_page_id').$request->get('x_trans_id').$request->get('x_amount');
        $localHash = sha1($hashBase);

        $remoteHash = $request->get('x_SHA1_Hash');

        return $localHash === $remoteHash;
    }

    /**
     * Return paymentMethod extracted from Payment form response
     */
    public static function getPaymentMethodFromPaymentFormResponse(Request $request): object
    {
        return (object)[
            'auth_code' => $request->get('x_auth_code'),
            'token' => $request->get('Card_Number'),
            'brand' => $request->get('TransactionCardType'),
            'cardholder' => $request->get('CardHoldersName'),
            'expiration_date' => $request->get('Expiry_Date'),
            'is_payeezy' => true,
        ];
    }

    /**
     * Return transactionId extracted from Payment form response
     */
    public static function getTransactionIdFromPaymentFormResponse(Request $request): ?string
    {
        return $request->get('x_trans_id');
    }

    /**
     * Return zip code extracted from Payment form response
     */
    public static function getZipCodeFromPaymentFormResponse(Request $request): ?string
    {
        return $request->get('x_zip');
    }

    /**
     * Perform a transaction on a payment method via the API
     */
    public static function chargePaymentMethod(array $paymentMethod, array $options): object
    {
        $paymentMethod = (object)$paymentMethod;
        $options = (object)$options;

        $request = (object)[
            'transarmor_token' => $paymentMethod->token,
            'credit_card_type' => $paymentMethod->brand,
            'cardholder_name' => $paymentMethod->cardholder,
            'cc_expiry' => $paymentMethod->expiration_date,
            'amount' => $options->amount,
            'transaction_type' => $options->transactionType ?? static::API_TRANSACTION_TYPE_AUTH_CAPTURE,
            'reference_no' => $options->orderId ?? null,
            'gateway_id' => config('services.payeezy.api_gateway_id'),
            'password' => config('services.payeezy.api_password'),
            'stored_credentials' => (object)[
                'indicator' => $options->subsequentTransaction ? static::API_INDICATOR_SUBSEQUENT_TRANSACTION : static::API_INDICATOR_FIRST_TIME_TRANSACTION,
                'initiation' => $options->merchantInitiated ? static::API_INITIATION_MERCHANT_INITIATED : static::API_INITIATION_CUSTOMER_INITIATED,
                'schedule' => $options->scheduled ? static::API_SCHEDULE_SCHEDULED : static::API_SCHEDULE_UNSCHEDULED,
                'transaction_id' => $options->transactionId ?? null,
            ],
        ];

        $date = Carbon::now('UTC')->format('Y-m-d\TH:i:s\Z');

        $contentHash = sha1(json_encode($request));

        $hmac_data = 'POST'."\n".'application/json'."\n".$contentHash."\n".$date."\n".config('services.payeezy.api_endpoint');
        $hmac = base64_encode(hash_hmac('sha1', $hmac_data, config('services.payeezy.api_hmac_key'), true));
        $authHeader = 'GGE4_API '.config('services.payeezy.api_key_id').':'.$hmac;

        $headers = [
            'x-gge4-date' => $date,
            'x-gge4-content-sha1' => $contentHash,
            'Authorization' => $authHeader,
        ];
        $client = new Client(['verify' => config('services.payeezy.pem_filename')]);

        $return = (object)[
            'bank_message' => null,
            'message' => null,
            'response' => null,
            'success' => null,
        ];
        try {
            $response = $client->post(
                config('services.payeezy.api_base_url').config('services.payeezy.api_endpoint'),
                [
                    'headers' => $headers,
                    'json' => $request,
                ]
            );

            $response = json_decode((string)$response->getBody());

            $return->bank_message = $response->bank_message;
            $return->message = $response->exact_message;
            $return->response = $response;
            $return->success = !!$response->transaction_approved;
            $return->transactionId = $response->transaction_tag;
        } catch (GuzzleException $e) {
            Log::error('PayeezyService::chargePaymentMethod() failed:');
            Log::error('Headers: '.json_encode($headers));
            Log::error('Request: '.json_encode($request));
            try {
                $response = ($e->getResponse() && $e->getResponse()->getBody()) ? $e->getResponse()->getBody()->getContents() : null;
            } catch (Exception $e) {
            }
            $response = $response ?? $e->getMessage() ?? 'Unable to parse response.';
            Log::error('Response: '.$response);

            if (stristr($response, '(63) - Invalid Duplicate')) {
                $return->isDuplicate = true;
            } else {
                $return->isDuplicate = false;
            }

            $return->success = false;
            $return->message = 'Unable to charge your card. Please try again.';
        }

        return $return;
    }
}
