<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Rules\ValidateRecaptcha;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class ContactController extends ApiController
{
    /**
     * post Feedback
     */
    public function postFeedback(Request $request): JsonResponse
    {
        $customerName = $request->get('name', null);
        $customerPhone = $request->get('phone', null);
        $customerEmail = $request->get('email', null);
        $regarding = $request->get('regarding', null);
        $host = $request->server('HTTP_HOST', null);
        $program = $request->get('program', null);
        $washClubInquiryType = $request->get('wash-club-inquiry-type', null);
        $washClubChangeAction = $request->get('wash-club-change-action', null);
        $washClubChangeLicensePlate = $request->get('wash-club-change-license-plate', null);
        $washClubChangeTagNumber = $request->get('wash-club-change-tag-number', null);

        $rules = ['comments' => 'required'];
        $messages = ['comments.required' => 'You must enter a comment.'];
        if ($regarding === 'Unlimited Wash Club') {
            $rules['wash-club-inquiry-type'] = 'required';
            $messages['wash-club-inquiry-type.required'] = 'Inquiry type is required.';

            if ($washClubInquiryType === 'Club Change') {
                $rules['wash-club-change-action'] = 'required';
                $messages['wash-club-change-action.required'] = 'What would you like to change is required.';

                if ($washClubChangeAction === 'Change Membership') {
                    $rules['wash-club-change-license-plate'] = 'required';
                    $rules['wash-club-change-tag-number'] = 'required';

                    $messages['wash-club-change-license-plate.required'] = 'License plate number is required.';
                    $messages['wash-club-change-tag-number.required'] = 'Tag number is required.';
                    $messages['comments.required'] = 'Reason for change is required.';
                }
            }
        }

        $validator = self::validatePostFeedback($request, $rules, $messages);

        if ($validator->fails()) {
            return parent::handleFailedValidation($validator, $request);
        }

        $emailTo = $request->get('mailto', null) ?? config('mail.'.Str::snake($program).'.address') ?? ($regarding === 'Unlimited Wash Club' ? config('mail.unlimited_wash_club.address') : config('mail.contact.address'));
        $carbonCopy = null;
        if ($request->has('subject')) {
            $subject = $request->get('subject');
        } elseif ($regarding === 'Corporate Inquiry') {
            $subject = 'Website Corporate Inquiry';
        } elseif ($regarding === 'Car Wash Location Inquiry') {
            $subject = "Website Location Inquiry".($request->has('location') ? ': '.$request->get('location') : '');
        } elseif ($regarding === "Programs Inquiry") {
            switch ($program) {
                case "Charity Car Wash Program":
                    $selectedProgram = "Charity";
                    break;
                case "Car Dealership Program":
                    $carbonCopy = config('mail.car_dealership_program.address');
                    $selectedProgram = "Dealership";
                    break;
                case "Fleet Wash Program":
                    $carbonCopy = config('mail.fleet_wash_program.address');
                    $selectedProgram = "Fleet";
                    break;
            }

            $subject = 'Website Programs Inquiry'.(isset($selectedProgram) ? ': '.$selectedProgram : '');
        } elseif ($regarding === "Unlimited Wash Club") {
            $carbonCopy = config('mail.unlimited_wash_club.address');

            switch ($washClubInquiryType) {
                case "General Question":
                    $inquiryType = "General";
                    break;
                case "Club Change":
                    $inquiryType = "Change";
                    break;
            }

            $subject = 'Wash Club Inquiry'.(isset($inquiryType) ? ': '.$inquiryType : '');
        } else {
            $subject = 'Website Inquiry';
        }

        Mail::send(
            [
                'html' => 'emails.send-feedback-html',
                'text' => 'emails.send-feedback-text',
            ],
            [
                'name' => $customerName,
                'phone' => $customerPhone,
                'email' => $customerEmail,
                'host' => $host,
                'regarding' => $regarding,
                'location' => $request->get('location'),
                'service' => $request->get('service'),
                'program' => $program,
                'comments' => $request->get('comments', null),
                'washClubInquiryType' => $washClubInquiryType,
                'washClubChangeAction' => $washClubChangeAction,
                'washClubChangeLicensePlate' => $washClubChangeLicensePlate,
                'washClubChangeTagNumber' => $washClubChangeTagNumber,
            ],
            function ($message) use ($emailTo, $subject, $customerEmail, $customerName, $carbonCopy) {
                $message->to($emailTo, config('mail.contact.name'))
                    ->from(config('mail.do_not_reply.address'), config('mail.do_not_reply.name'))
                    ->subject($subject);

                if ($customerEmail) {
                    $message->replyTo($customerEmail, $customerName);
                }

                if ($carbonCopy) {
                    $message->cc($carbonCopy);
                }
            }
        );

        return Response::json(['success' => true])->setStatusCode(200);
    }

    /**
     * Add user to mailing list
     */
    public function postMailingList(Request $request)
    {
        $jangoConfig = config('services.jango');
        $fullName = $request->get('mailing-list-name');
        $email = $request->get('mailing-list-email');
        $client = new Client();

        try {
            $client->post($jangoConfig['api_url'].'/AddGroupMember', [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'Username' => '',
                    'Password' => $jangoConfig['api_key'],
                    'GroupName' => $jangoConfig['mailing_list_name'],
                    'EmailAddress' => $email,
                    'FieldNames' => 'Name',
                    'FieldValues' => $fullName,
                ],
            ]);
            return Response::json()->setStatusCode(200);
        } catch (Exception $e) {
            return Response::json($e->getMessage())->setStatusCode(400);
        }
    }

    /**
     * Validate request for postFeedback
     */
    private static function validatePostFeedback(Request $request, array $rules = [], array $messages = []): \Illuminate\Validation\Validator
    {
        $rules = array_merge([
            'mailto' => 'nullable|string',
            'subject' => 'nullable|string',
            'name' => 'required',
            'email' => 'required|email:filter',
            'program' => 'required_if:regarding,Programs Inquiry',
            'recaptcha' => [new ValidateRecaptcha()],
        ], $rules);

        $messages = array_merge([
            'name.required' => 'Your full name is required.',
            'email.required' => 'Your email address is required.',
            'email.email' => 'Your email must be a valid email address.',
        ], $messages);

        return self::validateRequest($request, $rules, $messages);
    }
}
