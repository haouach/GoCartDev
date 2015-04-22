<?php

$router->map('GET', '/admin/dashboard', 'GoCart\Controller\AdminDashboard#index');
$router->map('GET', '/admin', 'GoCart\Controller\AdminDashboard#index');