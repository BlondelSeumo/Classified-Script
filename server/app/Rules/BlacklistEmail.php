<?php

namespace App\Rules;

use App\Model\SettingsSecurity;
use Illuminate\Contracts\Validation\Rule;

class BlacklistEmail implements Rule
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
        // Get settings
        $settings  = SettingsSecurity::find(1);

        // Get blacklisted emails
        $blacklist = explode(",", str_replace(' ', '', $settings->blockedEmails));

        // Check
        return !in_array($value, $blacklist);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Our system detected a blacklisted e-mail address';
    }
}
