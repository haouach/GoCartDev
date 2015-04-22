<?php

$router->map('GET|POST', '/admin/orders/form/[i:id]?', 'GoCart\Controller\AdminOrders#form');
$router->map('GET|POST', '/admin/orders/export', 'GoCart\Controller\AdminOrders#export');
$router->map('GET|POST', '/admin/orders/bulk_delete', 'GoCart\Controller\AdminOrders#bulk_delete');
$router->map('GET|POST', '/admin/orders/order/[i:id]', 'GoCart\Controller\AdminOrders#order');
$router->map('GET|POST', '/admin/orders/sendNotification/[i:id]', 'GoCart\Controller\AdminOrders#sendNotification');
$router->map('GET|POST', '/admin/orders/packing_slip/[i:id]', 'GoCart\Controller\AdminOrders#packing_slip');
$router->map('GET|POST', '/admin/orders/edit_status', 'GoCart\Controller\AdminOrders#edit_status');
$router->map('GET|POST', '/admin/orders/delete/[i:id]', 'GoCart\Controller\AdminOrders#delete');
$router->map('GET|POST', '/admin/orders', 'GoCart\Controller\AdminOrders#index');