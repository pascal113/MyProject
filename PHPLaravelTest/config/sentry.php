<?php

$content = file_get_contents(base_path().'/composer.json');
$composerJson = json_decode($content, true);
$version = $composerJson ? 'brownbear_com@'.$composerJson['version'] : null;

return [

    'dsn' => env('REPORT_TO_SENTRY') ? env('SENTRY_LARAVEL_DSN') : null,

    'release' => $version,

    'breadcrumbs' => [
        // Capture Laravel logs in breadcrumbs
        'logs' => true,

        // Capture SQL queries in breadcrumbs
        'sql_queries' => true,

        // Capture bindings on SQL queries logged in breadcrumbs
        'sql_bindings' => true,

        // Capture queue job information in breadcrumbs
        'queue_info' => true,

        // Capture command information in breadcrumbs
        'command_info' => true,
    ],

    // @see: https://docs.sentry.io/error-reporting/configuration/?platform=php#send-default-pii
    'send_default_pii' => false,

];
