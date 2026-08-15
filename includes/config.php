<?php
/**
 * AskBangladesh — application configuration and bootstrap.
 */

declare(strict_types=1);

define('APP_NAME', 'AskBangladesh');
define('APP_TAGLINE', 'Everything about Bangladesh, in one place');
define('APP_VERSION', '1.0.0');
define('APP_ROOT', dirname(__DIR__));
define('CACHE_DIR', APP_ROOT . '/storage/cache');
define('BD_TIMEZONE', 'Asia/Dhaka');

// Cache lifetime in seconds for the live rate/prayer feeds.
define('CACHE_TTL_RATES', 3600);
define('CACHE_TTL_GOLD', 3600);
define('CACHE_TTL_PRAYER', 21600);

// Seconds to wait on an outbound API before falling back to bundled values.
define('HTTP_TIMEOUT', 6);

date_default_timezone_set(BD_TIMEZONE);

if (!is_dir(CACHE_DIR)) {
    @mkdir(CACHE_DIR, 0775, true);
}

require_once __DIR__ . '/functions.php';
