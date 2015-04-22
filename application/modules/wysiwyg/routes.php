<?php

$router->map('GET|POST', '/admin/wysiwyg/upload_image', 'GoCart\Controller\AdminWysiwyg#upload_image');
$router->map('GET|POST', '/admin/wysiwyg/upload_file', 'GoCart\Controller\AdminWysiwyg#upload_file');
$router->map('GET|POST', '/admin/wysiwyg/get_images', 'GoCart\Controller\AdminWysiwyg#get_images');