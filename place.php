<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $db->prepare('SELECT * FROM places WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$p = $stmt->get_result()->fetch_assoc();
if (!$p) { http_response_code(404); }

$page = 'home';
$title = $p['name'] ?? 'Not found';
require __DIR__ . '/includes/header.php';

if (!$p): ?>
  <div class="container"><p class="empty">Place not found. <a href="index.php"><?= t('back') ?></a></p></div>
<?php require __DIR__ . '/includes/footer.php'; exit; endif;

// Nearby = the 3 closest other places, using straight-line distance
$nearby = [];
$res = $db->query('SELECT * FROM places WHERE id <> ' . (int)$p['id']);
while ($row = $res->fetch_assoc()) {
    $row['_d'] = distance_km($p['latitude'], $p['longitude'], $row['latitude'], $row['longitude']);
    $nearby[] = $row;
}
usort($nearby, fn($a, $b) => $a['_d'] <=> $b['_d']);
$nearby = array_slice($nearby, 0, 3);

$dirUrl = 'https://www.google.com/maps/dir/?api=1&origin=' . GAMPOLA_LAT . ',' . GAMPOLA_LNG
        . '&destination=' . $p['latitude'] . ',' . $p['longitude'] . '&travelmode=driving';
?>
<div class="container detail">
  <a class="back" href="index.php">&larr; <?= t('back') ?></a>
  <div class="detail-grid">
    <div>
      <?= photo_html($p, 'photo big') ?>
      <div class="meta">
        <span class="tag cat-<?= e($p['category']) ?>"><?= e(t($p['category'])) ?></span>
        <?php if ($p['tags']): ?><span class="tag light"><?= e($p['tags']) ?></span><?php endif; ?>
      </div>
      <h1><?= e($p['name']) ?></h1>
      <p class="dist">~ <?= e($p['distance_km']) ?> km <?= t('from_gampola') ?></p>
      <p><?= nl2br(e($p['description'])) ?></p>

      <table class="info">
        <tr><th><?= t('opening_hours') ?></th><td><?= e($p['opening_hours'] ?: '-') ?></td></tr>
        <tr><th><?= t('entrance_fee') ?></th><td><?= e($p['entrance_fee'] ?: '-') ?></td></tr>
        <tr><th><?= t('facilities') ?></th><td><?= e($p['facilities'] ?: '-') ?></td></tr>
      </table>

      <div class="actions">
        <button class="btn plan-btn" data-id="<?= (int)$p['id'] ?>"><?= t('add_plan') ?></button>
        <a class="btn outline" target="_blank" rel="noopener" href="<?= e($dirUrl) ?>"><?= t('directions') ?></a>
      </div>
    </div>
    <div>
      <div id="map" class="map tall"></div>
    </div>
  </div>

  <h2 class="section-title"><?= t('nearby') ?></h2>
  <div class="grid">
    <?php foreach ($nearby as $n): ?>
      <article class="card">
        <a href="place.php?id=<?= (int)$n['id'] ?>"><?= photo_html($n) ?></a>
        <div class="card-body">
          <h3><?= e($n['name']) ?></h3>
          <p class="dist">~ <?= number_format($n['_d'], 1) ?> km</p>
          <a class="btn small outline" href="place.php?id=<?= (int)$n['id'] ?>"><?= t('view_details') ?></a>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</div>
<?php
$data_js = '<script>window.MAP_PLACES = ' . json_encode([[
    'id' => (int)$p['id'], 'name' => $p['name'], 'lat' => (float)$p['latitude'],
    'lng' => (float)$p['longitude'], 'category' => $p['category'],
]], JSON_UNESCAPED_UNICODE) . '; window.SINGLE_PLACE = true;</script>';
require __DIR__ . '/includes/footer.php';
