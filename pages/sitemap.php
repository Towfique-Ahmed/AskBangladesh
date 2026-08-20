<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** XML sitemap covering every indexable URL on the site. */

// Same content timestamp the pages report as their modified date.
$lastmod = bd_content_modified();

header('Content-Type: application/xml; charset=utf-8');
header('X-Robots-Tag: noindex');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach (bd_sitemap_urls() as $url): ?>
  <url>
    <loc><?= e($url['loc']) ?></loc>
    <lastmod><?= e($lastmod) ?></lastmod>
    <changefreq><?= e($url['changefreq']) ?></changefreq>
    <priority><?= e($url['priority']) ?></priority>
  </url>
<?php endforeach; ?>
</urlset>
