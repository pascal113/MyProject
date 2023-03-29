<?php

namespace App\Rules;

use App\App;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ValidUrlOnEachLine implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $urls = App::convertMultiLineStringToArray($value);

        $validator = Validator::make($urls, [ '*' => 'url|active_url' ]);

        return $validator->passes();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute must have a valid URL on each line.';
    }
}
