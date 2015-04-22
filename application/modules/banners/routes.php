<?php

$router->map('GET', '/admin/banners', 'GoCart\Controller\AdminBanners#index');
$router->map('GET|POST', '/admin/banners/banner_collection_form/[i:id]?', 'GoCart\Controller\AdminBanners#banner_collection_form');
$router->map('GET|POST', '/admin/banners/delete_banner_collection/[i:id]', 'GoCart\Controller\AdminBanners#delete_banner_collection');
$router->map('GET|POST', '/admin/banners/banner_collection/[i:id]', 'GoCart\Controller\AdminBanners#banner_collection');
$router->map('GET|POST', '/admin/banners/banner_form/[i:banner_collection_id]/[i:id]?', 'GoCart\Controller\AdminBanners#banner_form');
$router->map('GET|POST', '/admin/banners/delete_banner/[i:id]', 'GoCart\Controller\AdminBanners#delete_banner');
$router->map('GET|POST', '/admin/banners/organize', 'GoCart\Controller\AdminBanners#organize');