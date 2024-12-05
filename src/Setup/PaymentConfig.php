<?php

namespace Forge\Setup;

class PaymentConfig
{
    public static function getStripeSecretKey(): string
    {
        return $_ENV['STRIPE_SECRET_KEY'];
    }

    public static function getPaypalClientId(): string
    {
        return $_ENV['PAYPAL_CLIENT_ID'];
    }

    public static function getPaypalSecret(): string
    {
        return $_ENV['PAYPAL_SECRET'];
    }

    public static function getPaypalMode(): string
    {
        return $_ENV['PAYPAL_MODE']; // 'sandbox' ou 'live'
    }
}
