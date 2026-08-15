<?php
defined('APP_ROOT') || exit('Direct access is not permitted.');
/** Interactive vector map of Bangladesh with pan, zoom and layered pins. */

$districts = bd_districts();
$divisions = bd_divisions();
$mountains = bd_places('mountains');
$travel    = bd_places('travel');
$airports  = bd_places('airports');
$ports     = bd_places('ports');
$geography = bd_places('geography');

$pageTitle       = 'Interactive map of Bangladesh';
$pageDescription = 'Pan, zoom and explore all 64 districts, the highest peaks, top travel places, airports and ports of Bangladesh on one interactive map.';

require APP_ROOT . '/includes/layout/header.php';

/** Emit one pin. */
$pin = function (array $attrs, float $lat, float $lon, string $color, string $layer, bool $ripple = false) {
    [$x, $y] = bd_project($lon, $lat);
    $data = '';
    foreach ($attrs as $key => $value) {
        if ($value === '' || $value === null) { continue; }
        $data .= ' data-' . $key . '="' . e((string) $value) . '"';
    }
    $cls = 'pin' . ($ripple ? ' pin--ripple' : '');
    echo '<g class="' . $cls . '" data-layer="' . e($layer) . '"' . $data
       . ' data-lat="' . $lat . '" data-lon="' . $lon . '"'
       . ' style="color:' . e($color) . '" tabindex="0" role="button"'
       . ' aria-label="' . e((string) ($attrs['name'] ?? 'Location')) . '">'
       . '<circle class="pin__halo" cx="' . $x . '" cy="' . $y . '" r="10"/>'
       . '<circle class="pin__dot" cx="' . $x . '" cy="' . $y . '" r="4.2" fill="' . e($color) . '" stroke="rgba(0,0,0,.35)" stroke-width="1"/>'
       . '<text class="pin__label" x="' . ($x + 9) . '" y="' . ($y + 3.5) . '">' . e((string) ($attrs['name'] ?? '')) . '</text>'
       . '<circle class="pin__hit" cx="' . $x . '" cy="' . $y . '" r="7"/>'
       . '</g>';
};
?>

<div class="pagehead">
  <span class="pagehead__eyebrow">🗺️ Interactive map</span>
  <h1>Bangladesh, pin by pin</h1>
  <p>
    Drag to pan, scroll or pinch to zoom, and tap any pin for details. Toggle the layers below the
    map to show only districts, peaks, destinations, airports or ports.
  </p>
</div>

<div class="mapshell">
  <div class="mapstage" data-reveal>
    <div class="maptools">
      <button type="button" id="map-zoom-in"  aria-label="Zoom in">＋</button>
      <button type="button" id="map-zoom-out" aria-label="Zoom out">−</button>
      <button type="button" id="map-reset"    aria-label="Reset the map">⟳</button>
    </div>

    <svg id="bd-map" viewBox="0 0 <?= BD_VIEW['w'] ?> <?= BD_VIEW['h'] ?>" role="img"
         aria-label="Map of Bangladesh with district, mountain, travel, airport and port markers">
      <defs>
        <linearGradient id="bdFill" x1="0" y1="0" x2="0.4" y2="1">
          <stop offset="0%"   stop-color="#00a651" stop-opacity="0.30"/>
          <stop offset="55%"  stop-color="#12b5a5" stop-opacity="0.20"/>
          <stop offset="100%" stop-color="#f42a41" stop-opacity="0.16"/>
        </linearGradient>
        <filter id="softGlow" x="-30%" y="-30%" width="160%" height="160%">
          <feGaussianBlur stdDeviation="7" result="b"/>
          <feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge>
        </filter>
      </defs>

      <g id="map-viewport">
        <path class="bd-outline" d="<?= e(bd_outline_path()) ?>"/>
        <path class="bd-outline--trace" pathLength="1000" d="<?= e(bd_outline_path()) ?>"/>

        <?php
        // Districts, coloured by their division.
        foreach ($districts as $district) {
            $color = $divisions[$district['division']]['color'] ?? '#00a651';
            $pin([
                'kind'       => 'District',
                'name'       => $district['name'],
                'bn'         => $district['bn'],
                'division'   => $district['division'],
                'area'       => $district['area'],
                'population' => $district['population'],
                'note'       => $district['famous'],
                'link'       => bd_url('districts', ['q' => $district['name']]),
            ], (float) $district['lat'], (float) $district['lon'], $color, 'district');
        }

        // Mountains.
        foreach ($mountains as $mountain) {
            $pin([
                'kind'     => 'Mountain',
                'name'     => $mountain['name'],
                'district' => $mountain['district'],
                'height'   => $mountain['height'],
                'note'     => $mountain['note'],
                'link'     => bd_url('geography', ['q' => $mountain['name']]),
            ], (float) $mountain['lat'], (float) $mountain['lon'], '#c0392b', 'mountain');
        }

        // Travel destinations.
        foreach ($travel as $place) {
            $pin([
                'kind'     => $place['type'],
                'name'     => $place['name'],
                'district' => $place['district'],
                'best'     => $place['best'],
                'note'     => $place['desc'],
                'link'     => bd_url('travel', ['q' => $place['name']]),
            ], (float) $place['lat'], (float) $place['lon'], '#f5b301', 'travel', true);
        }

        // Airports.
        foreach ($airports as $airport) {
            $pin([
                'kind' => 'Airport (' . $airport['code'] . ')',
                'name' => $airport['name'],
                'note' => $airport['type'] . ' airport serving ' . $airport['city'] . '.',
                'link' => bd_url('transport', ['q' => $airport['code']]),
            ], (float) $airport['lat'], (float) $airport['lon'], '#2980b9', 'airport');
        }

        // Sea and land ports.
        foreach ($ports as $port) {
            $pin([
                'kind' => $port['type'],
                'name' => $port['name'],
                'note' => $port['note'],
                'link' => bd_url('transport', ['q' => $port['name']]),
            ], (float) $port['lat'], (float) $port['lon'], '#8e44ad', 'port');
        }

        // The four extreme points of the country.
        foreach ($geography['extremes'] as $direction => $point) {
            $pin([
                'kind' => 'Extreme point — ' . $direction,
                'name' => $point['name'],
                'note' => 'The ' . strtolower($direction) . 'ernmost point of Bangladesh.',
                'link' => bd_url('geography', ['q' => 'extreme']),
            ], (float) $point['lat'], (float) $point['lon'], '#ffffff', 'extreme');
        }
        ?>
      </g>
    </svg>

    <div class="maplegend">
      <button type="button" class="legendchip" data-layer="district" style="color:var(--green-500)"><i></i> Districts</button>
      <button type="button" class="legendchip" data-layer="travel"   style="color:var(--gold-500)"><i></i> Travel</button>
      <button type="button" class="legendchip" data-layer="mountain" style="color:#c0392b"><i></i> Peaks</button>
      <button type="button" class="legendchip" data-layer="airport"  style="color:#2980b9"><i></i> Airports</button>
      <button type="button" class="legendchip" data-layer="port"     style="color:#8e44ad"><i></i> Ports</button>
      <button type="button" class="legendchip" data-layer="extreme"  style="color:#ffffff"><i></i> Extremes</button>
    </div>
  </div>

  <aside class="mapside">
    <div class="card mapinfo" id="map-info" data-reveal="80"></div>

    <div class="card" data-reveal="140">
      <h3>Country at a glance</h3>
      <div class="mapinfo__rows">
        <div class="mapinfo__row"><span>Continent</span><span><?= e($geography['continent']) ?></span></div>
        <div class="mapinfo__row"><span>Region</span><span><?= e($geography['subregion']) ?></span></div>
        <div class="mapinfo__row"><span>Total area</span><span><?= bd_num($geography['area_km2']) ?> km²</span></div>
        <div class="mapinfo__row"><span>Coastline</span><span><?= bd_num($geography['coastline_km']) ?> km</span></div>
        <div class="mapinfo__row"><span>Land border</span><span><?= bd_num($geography['land_border_km']) ?> km</span></div>
        <div class="mapinfo__row"><span>Highest point</span><span><?= e($geography['highest_point']['name']) ?>, <?= $geography['highest_point']['height_m'] ?> m</span></div>
        <div class="mapinfo__row"><span>Time zone</span><span>UTC+06:00</span></div>
      </div>
      <a class="btn btn--ghost" href="<?= e(bd_url('geography')) ?>">Geography in full →</a>
    </div>
  </aside>
</div>

<!-- ------------------------------------------------------------ divisions -->
<section class="section">
  <div class="section__head">
    <h2>The eight divisions</h2>
    <p>Each division is colour-coded on the map above.</p>
  </div>

  <div class="grid grid--4">
    <?php $i = 0; foreach ($divisions as $name => $division): ?>
      <a class="card" href="<?= e(bd_url('districts', ['division' => $name])) ?>" data-reveal="<?= $i * 45 ?>">
        <div style="display:flex;align-items:center;gap:.55rem;margin-bottom:.4rem">
          <span style="width:12px;height:12px;border-radius:50%;background:<?= e($division['color']) ?>"></span>
          <h3 style="margin:0"><?= e($name) ?></h3>
        </div>
        <div class="mapinfo__rows">
          <div class="mapinfo__row"><span>Established</span><span><?= $division['established'] ?></span></div>
          <div class="mapinfo__row"><span>Area</span><span><?= bd_num($division['area']) ?> km²</span></div>
          <div class="mapinfo__row"><span>Population</span><span><?= bd_compact($division['population']) ?></span></div>
        </div>
      </a>
    <?php $i++; endforeach; ?>
  </div>
</section>

<div class="notice" data-reveal>
  <span>ℹ️</span>
  <p style="margin:0">
    The outline is a stylised projection built from boundary coordinates, drawn for exploration
    rather than navigation or any question of borders. Pin positions are district headquarters and
    landmark coordinates.
  </p>
</div>

<?php require APP_ROOT . '/includes/layout/footer.php'; ?>
