<?php
$router->map('GET', '/admin/coupons', 'GoCart\Controller\AdminCoupons#index');
$router->map('GET|POST', '/admin/coupons/form/[i:id]?', 'GoCart\Controller\AdminCoupons#form');
$router->map('GET|POST', '/admin/coupons/delete/[i:id]', 'GoCart\Controller\AdminCoupons#delete');
