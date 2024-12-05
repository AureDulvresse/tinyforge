<?php

namespace Forge\Support;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PayPalExtension extends AbstractExtension
{
    private $clientId;

    public function __construct(string $clientId)
    {
        $this->clientId = $clientId;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('paypal_button', [$this, 'renderPayPalButton'], ['is_safe' => ['html']])
        ];
    }

    public function renderPayPalButton($amount = '20.00', $currency = 'EUR'): string
    {
        return <<<HTML
            <div id="paypal-button-container"></div>
            <script src="https://www.paypal.com/sdk/js?client-id={$this->clientId}&currency=$currency"></script>
            <script>
                paypal.Buttons({
                    createOrder: function(data, actions) {
                        return actions.order.create({
                            purchase_units: [{
                                amount: {
                                    value: '$amount'
                                }
                            }]
                        });
                    },
                    onApprove: function(data, actions) {
                        return actions.order.capture().then(function(details) {
                            window.location.href = '/paiement/success';
                        });
                    },
                    onError: function(err) {
                        console.log("Erreur de paiement : ", err);
                    }
                }).render('#paypal-button-container');
            </script>
        HTML;
    }
}
