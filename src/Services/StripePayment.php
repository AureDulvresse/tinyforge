<?php

namespace Forge\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Forge\Setup\PaymentConfig;

class StripePayment
{
    public function __construct()
    {
        Stripe::setApiKey(PaymentConfig::getStripeSecretKey());
    }

    public function createCheckoutSession(array $product, int $quantity = 1): StripeSession
    {
        return StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => (string) $_ENV['APP_CURRENCY'],
                    'product_data' => [
                        'name' => $product['name'],
                    ],
                    'unit_amount' => ($product['price'] * $quantity) * 100, // en cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => (string) $_ENV['APP_URL'] . 'payment/success',
            'cancel_url' => (string) $_ENV['APP_URL'] . 'payment/cancel',
        ]);
    }
}
