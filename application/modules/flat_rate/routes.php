<?php

$router->map('GET|POST', '/admin/flat-rate/form', 'GoCart\Controller\AdminFlatRate#form');
$router->map('GET|POST', '/admin/flat-rate/install', 'GoCart\Controller\AdminFlatRate#install');
$router->map('GET|POST', '/admin/flat-rate/uninstall', 'GoCart\Controller\AdminFlatRate#uninstall');

$shippingModules[] = ['name'=>'Flat Rate', 'key'=>'flat-rate', 'class'=>'FlatRate'];