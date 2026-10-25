<?php
$page = 'home';
$title = 'Home';
require __DIR__ . '/includes/header.php';

$places = [];
$res = $db->query('SELECT * FROM places ORDER BY distance_km');
while ($row = $res->fetch_assoc()) $places[] = $row;

$mapData = array_map(fn($p) => [
    'id' => (int)$p['id'], 'name' => $p['name'], 'lat' => (float)$p['latitude'],
    'lng' => (float)$p['longitude'], 'category' => $p['category'],
], $places);
?>
<section class="hero">
  <div class="container">
    <h1><?= t('hero_title') ?></h1>
    <p><?= t('hero_text') ?></p>
    <div class="search-row">
      <input type="search" id="search" placeholder="<?= e(t('search')) ?>" aria-label="<?= e(t('search')) ?>">
    </div>
    <div class="chips" id="chips">
      <button class="chip active" data-cat="all"><?= t('all') ?></button>
      <?php foreach (categories() as $c): ?>
        <button class="chip" data-cat="<?= $c ?>"><?= t($c) ?></button>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<div class="container">
  <div class="grid" id="placeGrid">
    <?php foreach ($places as $p): ?>
      <article class="card" data-name="<?= e(strtolower($p['name'] . ' ' . $p['description'])) ?>"
               data-cats="<?= e(implode(' ', place_categories($p))) ?>">
        <a href="place.php?id=<?= (int)$p['id'] ?>"><?= photo_html($p) ?></a>
        <div class="card-body">
          <div class="meta">
            <span class="tag cat-<?= e($p['category']) ?>"><?= e(t($p['category'])) ?></span>
            <?php if ($p['tags']): ?><span class="tag light"><?= e($p['tags']) ?></span><?php endif; ?>
          </div>
          <h3><?= e($p['name']) ?></h3>
          <p class="dist">~ <?= e($p['distance_km']) ?> km <?= t('from_gampola') ?></p>
          <p><?= e(mb_strimwidth($p['description'], 0, 110, '...')) ?></p>
          <div class="actions">
            <a class="btn small outline" href="place.php?id=<?= (int)$p['id'] ?>"><?= t('view_details') ?></a>
            <button class="btn small plan-btn" data-id="<?= (int)$p['id'] ?>"><?= t('add_plan') ?></button>
          </div>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
  <p id="noResults" class="empty" hidden><?= t('no_results') ?></p>

  <h2 class="section-title"><?= t('map_title') ?></h2>
  <div id="map" class="map"></div>
</div>

<?php
$data_js = '<script>window.MAP_PLACES = ' . json_encode($mapData, JSON_UNESCAPED_UNICODE) . ';</script>';
require __DIR__ . '/includes/footer.php';
