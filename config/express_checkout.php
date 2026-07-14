<?php

/*
|--------------------------------------------------------------------------
| Express Checkout wallets (Stripe)
|--------------------------------------------------------------------------
|
| Single source of truth for the Stripe "express" wallet payment methods
| (Amazon Pay, Link, Apple Pay). Everything downstream derives from this map:
|   - the PaymentIntent `payment_method_types` created for each wallet
|   - the customer-facing / admin label
|   - whether the 3.5% processing surcharge applies
|   - the refund allow-list (RefundService)
|   - the webhook finalisation branch (StripeController)
|   - which buttons the checkout page offers
|
| To add or remove a wallet, edit this file only. Set `enabled => false` to
| hide a wallet from checkout without deleting its configuration. A wallet
| must also be enabled in the Stripe Dashboard (and, for Apple Pay/Amazon Pay,
| the domain registered) before its button will actually render.
|
| The array key is the value stored in `orders.payment_method`.
|
*/

return [

    'methods' => [

        'link' => [
            'label' => 'Link',
            'payment_method_types' => ['card', 'link'],
            'surcharge' => true,
            'enabled' => true,
        ],

        'amazon_pay' => [
            'label' => 'Amazon Pay',
            'payment_method_types' => ['amazon_pay'],
            'surcharge' => true,
            'enabled' => true,
        ],

        'apple_pay' => [
            'label' => 'Apple Pay',
            // Apple Pay is card-backed; Stripe returns a card PaymentMethod.
            'payment_method_types' => ['card'],
            'surcharge' => true,
            'enabled' => true,
        ],

    ],

];
