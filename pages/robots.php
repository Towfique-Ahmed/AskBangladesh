<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** robots.txt, pointing crawlers at the sitemap. */

header('Content-Type: text/plain; charset=utf-8');
?>
User-agent: *
Allow: /

# JSON endpoints carry no indexable content.
Disallow: /api/
# Search result pages are infinite and thin — keep them out of the index.
Disallow: /search?
Disallow: /search$

Sitemap: <?= bd_abs_url('sitemap.xml') . "\n" ?>
