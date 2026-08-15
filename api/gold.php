<?php
/** Gold and silver price board for Bangladesh, per bhori / gram / ana. */

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/services.php';

$prices = bd_gold_prices();

bd_json_response([
    'currency' => 'BDT',
    'units'    => array_map(
        static fn (array $u): float => $u['grams'],
        bd_weight_units()
    ),
    'gold'     => $prices['gold'],
    'silver'   => $prices['silver'],
    'live'     => $prices['live'],
    'source'   => $prices['source'],
    'updated'  => $prices['updated'],
    'note'     => 'Indicative retail figures. Confirm with BAJUS or your jeweller before a transaction.',
]);
