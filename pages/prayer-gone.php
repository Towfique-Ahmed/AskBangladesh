<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');

/*
 * The prayer-times feature was replaced by sunrise and sunset times.
 * Those URLs were indexable, so they redirect permanently to the
 * equivalent sun page rather than 404ing.
 */
$slug   = $route['slug'] ?? '';
$target = bd_url('sunrise-sunset');

if ($slug !== '') {
    $district = bd_find_district($slug);
    if ($district !== null) {
        $target = bd_sun_url($district);
    }
}

header('Location: ' . $target, true, 301);
exit;
