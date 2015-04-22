<?php

$router->map('GET|POST', '/admin/gift-cards/form', 'GoCart\Controller\AdminGiftCards#form');
$router->map('GET|POST', '/admin/gift-cards/delete/[i:id]', 'GoCart\Controller\AdminGiftCards#delete');
$router->map('GET|POST', '/admin/gift-cards/enable', 'GoCart\Controller\AdminGiftCards#enable');
$router->map('GET|POST', '/admin/gift-cards/disable', 'GoCart\Controller\AdminGiftCards#disable');
$router->map('GET|POST', '/admin/gift-cards/settings', 'GoCart\Controller\AdminGiftCards#settings');
$router->map('GET|POST', '/admin/gift-cards', 'GoCart\Controller\AdminGiftCards#index');