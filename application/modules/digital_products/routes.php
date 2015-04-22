<?php

$router->map('GET|POST', '/admin/digital_products/form/[i:id]?', 'GoCart\Controller\AdminDigitalProducts#form');
$router->map('GET|POST', '/admin/digital_products/delete/[i:id]', 'GoCart\Controller\AdminDigitalProducts#delete');
$router->map('GET|POST', '/admin/digital_products', 'GoCart\Controller\AdminDigitalProducts#index');