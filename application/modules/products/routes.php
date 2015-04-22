<?php

$router->map('GET|POST', '/admin/products/product_autocomplete', 'GoCart\Controller\AdminProducts#product_autocomplete');
$router->map('GET|POST', '/admin/products/bulk_save', 'GoCart\Controller\AdminProducts#bulk_save');
$router->map('GET|POST', '/admin/products/product_image_form', 'GoCart\Controller\AdminProducts#product_image_form');
$router->map('GET|POST', '/admin/products/product_image_upload', 'GoCart\Controller\AdminProducts#product_image_upload');
$router->map('GET|POST', '/admin/products/form/[i:id]?/[i:copy]?', 'GoCart\Controller\AdminProducts#form');
$router->map('GET|POST', '/admin/products/delete/[i:id]', 'GoCart\Controller\AdminProducts#delete');
$router->map('GET|POST', '/admin/products/[i:rows]?/[:order_by]?/[:sort_order]?/[:code]?/[i:page]?', 'GoCart\Controller\AdminProducts#index');

$router->map('GET|POST', '/product/[:slug]', 'GoCart\Controller\Product#index');