<?php

return [

    'trusted_clients' => json_decode(env('API_TRUSTED_CLIENTS', '')) ?? [],

];
