<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'The :attribute must be accepted.',
    'active_url'           => 'The :attribute is not a valid URL.',
    'after'                => 'The :attribute must be a date after :date.',
    'after_or_equal'       => 'The :attribute must be a date after or equal to :date.',
    'alpha'                => 'The :attribute may only contain letters.',
    'alpha_dash'           => 'The :attribute may only contain letters, numbers, and dashes.',
    'alpha_num'            => 'The :attribute may only contain letters and numbers.',
    'array'                => 'The :attribute must be an array.',
    'before'               => 'The :attribute must be a date before :date.',
    'before_or_equal'      => 'The :attribute must be a date before or equal to :date.',
    'between'              => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file'    => 'The :attribute must be between :min and :max kilobytes.',
        'string'  => 'The :attribute must be between :min and :max characters.',
        'array'   => 'The :attribute must have between :min and :max items.',
    ],
    'boolean'              => 'The :attribute field must be true or false.',
    'confirmed'            => 'The :attribute confirmation does not match.',
    'date'                 => 'The :attribute is not a valid date.',
    'date_format'          => 'The :attribute does not match the format :format.',
    'different'            => 'The :attribute and :other must be different.',
    'digits'               => 'The :attribute must be :digits digits.',
    'digits_between'       => 'The :attribute must be between :min and :max digits.',
    'dimensions'           => 'The :attribute has invalid image dimensions.',
    'distinct'             => 'The :attribute field has a duplicate value.',
    'email'                => 'The :attribute must be a valid email address.',
    'exists'               => 'The selected :attribute is invalid.',
    'file'                 => 'The :attribute must be a file.',
    'filled'               => 'The :attribute field must have a value.',
    'image'                => 'The :attribute must be an image.',
    'in'                   => 'The selected :attribute is invalid.',
    'in_array'             => 'The :attribute field does not exist in :other.',
    'integer'              => 'The :attribute must be an integer.',
    'ip'                   => 'The :attribute must be a valid IP address.',
    'ipv4'                 => 'The :attribute must be a valid IPv4 address.',
    'ipv6'                 => 'The :attribute must be a valid IPv6 address.',
    'json'                 => 'The :attribute must be a valid JSON string.',
    'max'                  => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file'    => 'The :attribute may not be greater than :max kilobytes.',
        'string'  => 'The :attribute may not be greater than :max characters.',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'mimes'                => 'The :attribute must be a file of type: :values.',
    'mimetypes'            => 'The :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => 'The :attribute must be at least :min.',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => 'The :attribute must be at least :min characters.',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'numeric'              => 'The :attribute must be a number.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'The :attribute format is invalid.',
    'required'             => 'The :attribute field is required.',
    'required_if'          => 'The :attribute field is required when :other is :value.',
    'required_unless'      => 'The :attribute field is required unless :other is in :values.',
    'required_with'        => 'The :attribute field is required when :values is present.',
    'required_with_all'    => 'The :attribute field is required when :values is present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same'                 => 'The :attribute and :other must match.',
    'size'                 => [
        'numeric' => 'The :attribute must be :size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'string'               => 'The :attribute must be a string.',
    'timezone'             => 'The :attribute must be a valid zone.',
    'unique'               => 'The :attribute has already been taken.',
    'uploaded'             => 'The :attribute failed to upload.',
    'url'                  => 'The :attribute format is invalid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'current_password'              => "current password",
        'new_password'                  => "new password",
        'email'                         => "email",
        'avatar'                        => "avatar",
        'phone'                         => "phone number",
        'country'                       => "country",
        'state'                         => "state",
        'city'                          => "city",
        'username'                      => "username",
        'ad_deal_sidebar'               => "sidebar ad",
        'ad_deal_top_related'           => "top ad",
        'ad_deal_bottom_related'        => "bottom ad",
        'title'                         => "title",
        'content'                       => "content",
        'cover'                         => "cover",
        'slug'                          => "slug",
        'isChild'                       => "is child",
        'parent'                        => "parent",
        'icon'                          => "icon",
        'comment'                       => "comment",
        'subject'                       => "subject",
        'message'                       => "message",
        'code'                          => "code",
        'locale'                        => "locale",
        'name'                          => "name",
        'description'                   => "description",
        'category'                      => "category",
        'price'                         => "price",
        'currency'                      => "currency",
        'video'                         => "video",
        'type'                          => "type",
        'days'                          => "days",
        'discount'                      => "discount",
        'required'                      => "required",
        'searchable'                    => "searchable",
        'options'                       => "options",
        'capital'                       => "capital",
        'continent'                     => "continent",
        'flag'                          => "flag",
        'has_states'                    => "has states",
        'callingcode'                   => "calling code",
        'has_cities'                    => "has cities",
        'enable'                        => "enable",
        'token'                         => "token",
        'is_link'                       => "is link",
        'link'                          => "link",
        'column'                        => "column",
        'subtitle'                      => "subtitle",
        'frequency'                     => "frequency",
        'recommended'                   => "recommended",
        'maximum_stores'                => "maximum shops",
        'maximum_classifieds'           => "maximum deals",
        'maximum_classifieds_images'    => "maximum deals images",
        'classifieds_expiration_period' => "deals expiration period",
        'enable_stores'                 => "enable shops",
        'enable_autoshare'              => "enable autoshare",
        'key'                           => "key",
        'secret'                        => "secret",
        'from'                          => "from",
        'enable_registration'           => "enable registration",
        'activation_type'               => "activation type",
        'login_by'                      => "login by",
        'default_sms_service'           => "default sms service",
        'activation_code_life'          => "activation code life",
        'max_attempts'                  => "max attempts",
        'retry_after'                   => "retry after",
        'default_role'                  => "default role",
        'default_plan'                  => "default plan",
        'copyrights'                    => "copyrights",
        'terms'                         => "terms",
        'privacy'                       => "privacy",
        'firstColumn'                   => "first column",
        'secondColumn'                  => "second column",
        'thirdColumn'                   => "third column",
        'fourthColumn'                  => "fourth column",
        'mailchimp_key'                 => "mailchimp key",
        'mailchimp_id'                  => "mailchimp id",
        'facebook'                      => "facebook",
        'twitter'                       => "twitter",
        'google'                        => "google",
        'instagram'                     => "instagram",
        'linkedin'                      => "linkedin",
        'vk'                            => "vk",
        'tumblr'                        => "tumblr",
        'youtube'                       => "youtube",
        'tagline'                       => "tagline",
        'timezone'                      => "timezone",
        'direction'                     => "direction",
        'transparent'                   => "transparent logo",
        'white'                         => "white logo",
        'favicon'                       => "favicon",
        'multiple_countries'            => "multiple countries",
        'enable_states'                 => "enable states",
        'enable_cities'                 => "enable cities",
        'default_country'               => "default country",
        'default_state'                 => "default state",
        'default_city'                  => "default city",
        'default_currency'              => "default currency",
        'wallpaper'                     => "wallpaper",
        'text'                          => "text",
        'stripe'                        => "stripe",
        'deals_per_day'                 => "deals per day",
        'deals_expires_after'           => "deals expires after",
        'deals_max_images'              => "deals max images",
        'blacklist_usernames'           => "blacklist usernames",
        'blacklist_emails'              => "blacklist emails",
        'blacklist_words'               => "blacklist words",
        'auto_approve_classifieds'      => "auto approve deals",
        'auto_approve_comments'         => "auto approve comments",
        'auto_approve_stores'           => "auto approve shops",
        'enable_recaptcha'              => "enable recaptcha",
        'recaptcha_key'                 => "recaptcha key",
        'recaptcha_secret'              => "recaptcha secret",
        'spam'                          => "spam",
        'keywords'                      => "keywords",
        'separator'                     => "separator",
        'dnsPrefetch'                   => "dns prefetch",
        'header_code'                   => "header code",
        'footer_code'                   => "footer code",
        'enable_cdn'                    => "enable cdn",
        'cdn_domain'                    => "cdn domain",
        'google_analytics'              => "google analytics",
        'sitemap'                       => "sitemap",
        'driver'                        => "driver",
        'host'                          => "host",
        'port'                          => "port",
        'encryption'                    => "encryption",
        'password'                      => "password",
        'max_image_size'                => "max image size",
        'max_total_images_size'         => "max total images size",
        'compression'                   => "compression",
        'storage'                       => "storage",
        'watermark'                     => "watermark",
        'position'                      => "position",
        'enabled'                       => "enabled",
        'store'                         => "store",
        'address1'                      => "address 1",
        'address2'                      => "address 2",
        'zip'                           => "zip",
        'customer_email'                => "customer email",
        'primary_locale'                => "primary locale",
        'logo'                          => "logo",
        'user'                          => "user",
        'priority'                      => "priority",
        'example'                       => "example",
        'budget'                        => "budget",
        'details'                       => "details",
        'unique'                        => "unique",
        'theme'                         => "theme",
        'role'                          => "role",
        'plan'                          => "plan",
        'verifyCode'                    => "verify code",
        'password_confirmation'         => "password confirmation",
        'credentials'                   => "credentials",
        'captchaToken'                  => "captcha token",
        'isSatisfied'                   => "is satisfied",
        'feedback'                      => "feedback",
        'sub_cat'                       => "sub category",
        'package'                       => "package",
        'shop'                          => "shop"
    ],

];
