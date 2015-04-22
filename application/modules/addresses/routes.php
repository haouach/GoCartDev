<?php
$router->map('GET|POST', '/addresses', 'GoCart\Controller\Addresses#index');
$router->map('GET|POST', '/addresses/json', 'GoCart\Controller\Addresses#addressJSON');
$router->map('GET|POST', '/addresses/form/[i:id]?', 'GoCart\Controller\Addresses#form');
$router->map('GET|POST', '/addresses/delete/[i:id]', 'GoCart\Controller\Addresses#delete');
$router->map('GET|POST', '/addresses/get-zone-options/[i:id]', 'GoCart\Controller\Addresses#getZoneOptions');