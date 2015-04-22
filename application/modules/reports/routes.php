<?php

$router->map('GET|POST', '/admin/reports', 'GoCart\Controller\AdminReports#index');
$router->map('GET|POST', '/admin/reports/best_sellers', 'GoCart\Controller\AdminReports#best_sellers');
$router->map('GET|POST', '/admin/reports/sales', 'GoCart\Controller\AdminReports#sales');