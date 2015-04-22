<?php
$router->map('GET|POST', '/my-account', 'GoCart\Controller\MyAccount#index');
$router->map('GET|POST', '/my-account/downloads', 'GoCart\Controller\MyAccount#downloads');