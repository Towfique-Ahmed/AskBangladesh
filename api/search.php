<?php
/** Global search endpoint used by the header's live dropdown. */

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/services.php';

$query = trim((string) ($_GET['q'] ?? ''));
$limit = max(1, min(40, (int) ($_GET['limit'] ?? 10)));

if ($query === '') {
    bd_json_response(['query' => '', 'count' => 0, 'results' => []]);
}

$results = array_map(
    static fn (array $r): array => [
        'title'    => $r['title'],
        'subtitle' => $r['subtitle'],
        'category' => $r['category'],
        'icon'     => $r['icon'],
        'url'      => $r['url'],
    ],
    bd_search($query, $limit)
);

bd_json_response([
    'query'   => $query,
    'count'   => count($results),
    'results' => $results,
]);
