<?php
namespace wf3\Payments;

use wf3\Payments\Container;
use wf3\Payments\ServiceProviderInterface;

class PayPalServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['paypal'] = function () use ($app) {
            return new PayPal($app['paypal.settings']);
        };
    }

    public function boot(Container $app)
    {
    }
}