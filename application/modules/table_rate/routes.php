<?php

$router->map('GET|POST', '/admin/table-rate/form', 'GoCart\Controller\AdminTableRate#form');
$router->map('GET|POST', '/admin/table-rate/install', 'GoCart\Controller\AdminTableRate#install');
$router->map('GET|POST', '/admin/table-rate/uninstall', 'GoCart\Controller\AdminTableRate#uninstall');

$shippingModules[] = ['name'=>'Table Rate', 'key'=>'table-rate', 'class'=>'TableRate'];