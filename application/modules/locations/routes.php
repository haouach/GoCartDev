<?php

$router->map('GET|POST', '/admin/locations/zone_areas/[i:id]', 'GoCart\Controller\AdminLocations#zone_areas');
$router->map('GET|POST', '/admin/locations/delete_zone_area/[i:id]', 'GoCart\Controller\AdminLocations#delete_zone_area');
$router->map('GET|POST', '/admin/locations/zone_area_form/[i:zone_id]/[i:id]?', 'GoCart\Controller\AdminLocations#zone_area_form');
$router->map('GET|POST', '/admin/locations/zones/[i:id]', 'GoCart\Controller\AdminLocations#zones');
$router->map('GET|POST', '/admin/locations/zone_form/[i:id]?', 'GoCart\Controller\AdminLocations#zone_form');
$router->map('GET|POST', '/admin/locations/delete_zone[i:id]', 'GoCart\Controller\AdminLocations#delete_zone');
$router->map('GET|POST', '/admin/locations/get_zone_menu', 'GoCart\Controller\AdminLocations#get_zone_menu');
$router->map('GET|POST', '/admin/locations/country_form[i:id]?', 'GoCart\Controller\AdminLocations#country_form');
$router->map('GET|POST', '/admin/locations/delete_country[i:id]', 'GoCart\Controller\AdminLocations#delete_country');
$router->map('GET|POST', '/admin/locations/organize_countries', 'GoCart\Controller\AdminLocations#organize_countries');
$router->map('GET|POST', '/admin/locations', 'GoCart\Controller\AdminLocations#index');