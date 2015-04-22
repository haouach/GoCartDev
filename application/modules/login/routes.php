<?php

$router->map('GET|POST', '/admin/login', 'GoCart\Controller\AdminLogin#login');
$router->map('GET|POST', '/admin/logout', 'GoCart\Controller\AdminLogin#logout');

$router->map('GET|POST', '/login/[i:id]?', 'GoCart\Controller\Login#login');
$router->map('GET|POST', '/logout', 'GoCart\Controller\Login#logout');
$router->map('GET|POST', '/forgot_password', 'GoCart\Controller\Login#forgot_password');