<?php
/** Exchange rates, expressed as units of each currency per 1 BDT. */

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/services.php';

$data = bd_exchange_rates();

$amount = isset($_GET['amount']) ? (float) $_GET['amount'] : null;
$from   = strtoupper((string) ($_GET['from'] ?? ''));
$to     = strtoupper((string) ($_GET['to'] ?? ''));

$payload = [
    'base'       => 'BDT',
    'live'       => $data['live'],
    'source'     => $data['source'],
    'updated'    => $data['updated'],
    'currencies' => bd_currencies(),
    'rates'      => $data['rates'],
];

if ($amount !== null && $from !== '' && $to !== '') {
    $converted = bd_convert($amount, $from, $to, $data['rates']);
    $payload['conversion'] = $converted === null
        ? ['error' => 'Unknown currency code']
        : ['amount' => $amount, 'from' => $from, 'to' => $to, 'result' => round($converted, 6)];
}

bd_json_response($payload);
