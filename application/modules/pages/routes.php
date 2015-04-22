<?php

$router->map('GET', '/admin/pages', 'GoCart\Controller\AdminPages#index');
$router->map('GET|POST', '/admin/pages/form/[i:id]?', 'GoCart\Controller\AdminPages#form');
$router->map('GET|POST', '/admin/pages/link_form/[i:id]?', 'GoCart\Controller\AdminPages#link_form');
$router->map('GET|POST', '/admin/pages/delete/[i:id]', 'GoCart\Controller\AdminPages#delete');