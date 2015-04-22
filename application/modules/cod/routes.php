<?php

$router->map('GET|POST', '/admin/cod/form', 'GoCart\Controller\AdminCod#form');
$router->map('GET|POST', '/admin/cod/install', 'GoCart\Controller\AdminCod#install');
$router->map('GET|POST', '/admin/cod/uninstall', 'GoCart\Controller\AdminCod#uninstall');

$paymentModules[] = ['name'=>'Charge on Delivery', 'key'=>'cod', 'class'=>'Cod'];