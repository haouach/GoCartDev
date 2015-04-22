<?php

$router->map('GET', '/admin/categories', 'GoCart\Controller\AdminCategories#index');
$router->map('GET|POST', '/admin/categories/form/[i:id]?', 'GoCart\Controller\AdminCategories#form');
$router->map('GET|POST', '/admin/categories/delete/[i:id]', 'GoCart\Controller\AdminCategories#delete');

$router->map('GET|POST', '/category/[:slug]/[:sort]?/[:dir]?/[:page]?', 'GoCart\Controller\Category#index');