<?php

return [

    /*
    |--------------------------------------------------------------------------
    | New Order Notification Recipients
    |--------------------------------------------------------------------------
    |
    | Comma-separated list in ORDER_NOTIFICATION_EMAILS, or the defaults below.
    |
    */

    'order_notification_emails' => array_values(array_filter(array_map(
        trim(...),
        explode(',', (string) env(
            'ORDER_NOTIFICATION_EMAILS',
            'nanta1811@gmail.com,hillnestofficial@gmail.com',
        )),
    ))),

    /*
    |--------------------------------------------------------------------------
    | Contact Form Recipients
    |--------------------------------------------------------------------------
    |
    | Messages from the Contact page go to CONTACT_FORM_TO, copied to the
    | comma-separated list in CONTACT_FORM_CC.
    |
    */

    'contact_form' => [
        'to' => env('CONTACT_FORM_TO', 'hillnestofficial@gmail.com'),
        'cc' => array_values(array_filter(array_map(
            trim(...),
            explode(',', (string) env('CONTACT_FORM_CC', 'nanta1811@gmail.com')),
        ))),
    ],

    /*
    |--------------------------------------------------------------------------
    | Store Details
    |--------------------------------------------------------------------------
    |
    | Shown on the Contact and Shipping Policy pages, in the footer, and in the
    | product declarations. Phone, WhatsApp and FSSAI stay hidden until set.
    | WhatsApp is the full number with country code, e.g. 919812345678.
    |
    */

    'store' => [
        'name' => 'HillNest',
        'email' => env('STORE_EMAIL', 'hillnestofficial@gmail.com'),
        'phone' => env('STORE_PHONE'),
        'whatsapp' => env('STORE_WHATSAPP'),
        'fssai_license' => env('STORE_FSSAI_LICENSE'),
        'address' => [
            'Chhajpur, Village Dharmana, P.O. Anti',
            'Tehsil Jubbal, Distt. Shimla',
            'Himachal Pradesh, India',
        ],
        'country_of_origin' => 'India',
        'best_before' => '9 months from the date of packing',
        'dispatch_days' => '1–2',
        'delivery_days' => '3–7',
        'instagram_url' => 'https://www.instagram.com/hillnest_official?igsi=MW5xZjYydjh4NGc1ZA==',
        'facebook_url' => 'https://www.facebook.com/share/1Jy8Z3zAU2/?mibextid=wwXIfr',
    ],

];
