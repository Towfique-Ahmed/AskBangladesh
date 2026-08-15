<?php
/**
 * AskBangladesh — front controller.
 * Everything is routed through ?p=<page>; unknown pages fall through to 404.
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/services.php';

$routes = [
    'home', 'map', 'districts', 'geography', 'travel', 'transport',
    'time', 'currency', 'gold', 'prayer', 'religion', 'government',
    'about', 'search',
];

$page = bd_current_page();
$file = APP_ROOT . '/pages/' . $page . '.php';

if (!in_array($page, $routes, true) || !is_file($file)) {
    http_response_code(404);
    $page = '404';
    $file = APP_ROOT . '/pages/404.php';
}

require $file;
