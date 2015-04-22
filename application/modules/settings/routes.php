<?php

$router->map('GET|POST', '/admin/settings', 'GoCart\Controller\AdminSettings#index');
$router->map('GET|POST', '/admin/settings/canned_messages', 'GoCart\Controller\AdminSettings#canned_messages');
$router->map('GET|POST', '/admin/settings/canned_message_form/[i:id]', 'GoCart\Controller\AdminSettings#canned_message_form');
$router->map('GET|POST', '/admin/settings/delete_message/[i:id]', 'GoCart\Controller\AdminSettings#delete_message');