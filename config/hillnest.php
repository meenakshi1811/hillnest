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

];
