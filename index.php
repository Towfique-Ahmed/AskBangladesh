<?php
/**
 * AskBangladesh — front controller.
 *
 * Clean URLs are resolved by bd_route(); the web server rewrites anything
 * that is not a real file onto this script.
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/services.php';
require_once __DIR__ . '/includes/seo.php';

$route = bd_route();
$page  = $route['page'];
$slug  = $route['slug'];

// Page name => file. Detail pages take a $slug.
$files = [
    'home'          => 'home',
    'map'           => 'map',
    'districts'     => 'districts',
    'district'      => 'district',
    'division'      => 'division',
    'geography'     => 'geography',
    'mountains'     => 'mountains',
    'rivers'        => 'rivers',
    'travel'        => 'travel',
    'travel-detail' => 'travel-detail',
    'transport'     => 'transport',
    'time'          => 'time',
    'currency'      => 'currency',
    'gold'          => 'gold',
    'sun'           => 'sun',
    'religion'      => 'religion',
    'government'    => 'government',
    'presidents'    => 'presidents',
    'prime-ministers' => 'prime-ministers',
    'about'         => 'about',
    'search'        => 'search',
    'calendar'      => 'calendar',
    'thana'         => 'thana',
    'privacy'       => 'privacy',
    'terms'         => 'terms',
    'contact'       => 'contact',
    'sitemap'       => 'sitemap',
    'robots'        => 'robots',
];

$file = isset($files[$page]) ? APP_ROOT . '/pages/' . $files[$page] . '.php' : null;

if ($file === null || !is_file($file)) {
    http_response_code(404);
    require APP_ROOT . '/pages/404.php';
    exit;
}

require $file;
