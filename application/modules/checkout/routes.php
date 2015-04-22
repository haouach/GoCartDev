<?php
$router->map('GET|POST', '/checkout', 'GoCart\Controller\Checkout#index');
$router->map('GET|POST', '/checkout/address-list', 'GoCart\Controller\Checkout#addressList');
$router->map('GET|POST', '/checkout/submit-order', 'GoCart\Controller\Checkout#submitOrder');
$router->map('GET|POST', '/checkout/address', 'GoCart\Controller\Checkout#address');
$router->map('GET|POST', '/checkout/payment-methods', 'GoCart\Controller\Checkout#paymentMethods');
$router->map('GET|POST', '/checkout/shipping-methods', 'GoCart\Controller\Checkout#shippingMethods');
$router->map('GET|POST', '/checkout/set-shipping-method', 'GoCart\Controller\Checkout#setShippingMethod');