<?php

$router->map('GET|POST', '/admin/sitemap', 'GoCart\Controller\AdminSitemap#index');
$router->map('GET|POST', '/admin/sitemap/new-sitemap', 'GoCart\Controller\AdminSitemap#newSitemap');
$router->map('GET|POST', '/admin/sitemap/generate-products', 'GoCart\Controller\AdminSitemap#generateProducts');
$router->map('GET|POST', '/admin/sitemap/generate-pages', 'GoCart\Controller\AdminSitemap#generatePages');
$router->map('GET|POST', '/admin/sitemap/generate-categories', 'GoCart\Controller\AdminSitemap#generateCategories');
$router->map('GET|POST', '/admin/sitemap/complete-sitemap', 'GoCart\Controller\AdminSitemap#completeSitemap');