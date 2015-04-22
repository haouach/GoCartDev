<?php
$router->map('GET|POST', '/admin/stripe/form', 'GoCart\Controller\AdminStripe#form');
$router->map('GET|POST', '/admin/stripe/install', 'GoCart\Controller\AdminStripe#install');
$router->map('GET|POST', '/admin/stripe/uninstall', 'GoCart\Controller\AdminStripe#uninstall');

$paymentModules[] = ['name'=>'Stripe', 'key'=>'stripe', 'class'=>'Stripe'];