<?php
/**
 * AskBangladesh — application configuration and bootstrap.
 */

declare(strict_types=1);

define('APP_NAME', 'AskBangladesh');
define('APP_TAGLINE', 'Everything about Bangladesh, in one place');
define('APP_VERSION', '2.0.0');
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

/*
 * Canonical origin. Search engines need one absolute, stable origin for
 * canonical tags, Open Graph and the sitemap. Set SITE_URL in the
 * environment to pin it (strongly recommended in production); otherwise it
 * is derived from the current request.
 */
if (!defined('SITE_URL')) {
    $envUrl = getenv('SITE_URL');

    if (is_string($envUrl) && $envUrl !== '') {
        define('SITE_URL', rtrim($envUrl, '/'));
    } else {
        $https = (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off')
            || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
            || (($_SERVER['SERVER_PORT'] ?? '') === '443');

        $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';
        // HTTP_HOST is attacker-controlled, so allow only host/port characters.
        $host = preg_replace('/[^A-Za-z0-9.\-:\[\]]/', '', (string) $host) ?: 'localhost';

        define('SITE_URL', ($https ? 'https://' : 'http://') . $host);
    }
}

/*
 * Sub-directory the app is served from, '' when it sits at the domain root.
 * Lets the same code run at https://example.com/ and https://example.com/bd/.
 */
if (!defined('BASE_PATH')) {
    $scriptDir = str_replace('\\', '/', dirname((string) ($_SERVER['SCRIPT_NAME'] ?? '')));

    /*
     * The running script is not always the front controller — api/*.php are
     * entry points too, and their SCRIPT_NAME would otherwise make BASE_PATH
     * "/api", so every generated link would be prefixed with it. Climb back
     * out by however many directories the script sits below the app root.
     */
    $scriptFile = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_FILENAME'] ?? ''));
    $appRoot    = str_replace('\\', '/', APP_ROOT);

    if ($scriptFile !== '' && str_starts_with($scriptFile, $appRoot . '/')) {
        $relative = trim(substr(dirname($scriptFile), strlen($appRoot)), '/');
        if ($relative !== '') {
            foreach (explode('/', $relative) as $ignored) {
                $scriptDir = dirname($scriptDir);
            }
        }
    }

    define('BASE_PATH', ($scriptDir === '/' || $scriptDir === '.' || $scriptDir === '\\')
        ? '' : rtrim(str_replace('\\', '/', $scriptDir), '/'));
}

if (!is_dir(CACHE_DIR)) {
    @mkdir(CACHE_DIR, 0775, true);
}

require_once __DIR__ . '/functions.php';
