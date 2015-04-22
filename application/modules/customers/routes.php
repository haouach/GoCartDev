<?php


$router->map('GET|POST', '/admin/customers/export_xml', 'GoCart\Controller\AdminCustomers#export_xml');
$router->map('GET|POST', '/admin/customers/get_subscriber_list', 'GoCart\Controller\AdminCustomers#get_subscriber_list');
$router->map('GET|POST', '/admin/customers/form/[i:id]?', 'GoCart\Controller\AdminCustomers#form');
$router->map('GET|POST', '/admin/customers/addresses/[i:id]', 'GoCart\Controller\AdminCustomers#addresses');
$router->map('GET|POST', '/admin/customers/delete/[i:id]?', 'GoCart\Controller\AdminCustomers#banner_form');
$router->map('GET|POST', '/admin/customers/groups', 'GoCart\Controller\AdminCustomers#groups');
$router->map('GET|POST', '/admin/customers/group_form/[i:id]?', 'GoCart\Controller\AdminCustomers#group_form');
$router->map('GET|POST', '/admin/customers/get_group', 'GoCart\Controller\AdminCustomers#get_group');
$router->map('GET|POST', '/admin/customers/delete_group/[i:id]?', 'GoCart\Controller\AdminCustomers#delete_group');
$router->map('GET|POST', '/admin/customers/address_list/[i:id]?', 'GoCart\Controller\AdminCustomers#address_list');
$router->map('GET|POST', '/admin/customers/address_form/[i:customer_id]/[i:id]?', 'GoCart\Controller\AdminCustomers#address_form');
$router->map('GET|POST', '/admin/customers/delete_address/[i:customer_id]/[i:id]', 'GoCart\Controller\AdminCustomers#delete_address');
$router->map('GET|POST', '/admin/customers/[:order_by]?/[:direction]?/[i:page]?', 'GoCart\Controller\AdminCustomers#index');