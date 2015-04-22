<?php
$router->map('GET|POST', '/cart/summary', 'GoCart\Controller\Cart#summary');
$router->map('GET|POST', '/cart/add-to-cart', 'GoCart\Controller\Cart#addToCart');
$router->map('GET|POST', '/cart/update-cart', 'GoCart\Controller\Cart#updateCart');
$router->map('GET|POST', '/cart/submit-coupon', 'GoCart\Controller\Cart#submitCoupon');
$router->map('GET|POST', '/cart/submit-gift-card', 'GoCart\Controller\Cart#submitGiftCard');