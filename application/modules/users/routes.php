<?php

$router->map('GET|POST', '/admin/users', 'GoCart\Controller\AdminUsers#index');
$router->map('GET|POST', '/admin/users/form/[i:id]?', 'GoCart\Controller\AdminUsers#form');
$router->map('GET|POST', '/admin/users/delete/[i:id]', 'GoCart\Controller\AdminUsers#delete');