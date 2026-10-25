<?php
$page = 'planner';
$title = 'My Trip Plan';
require __DIR__ . '/includes/header.php';

$labels = [];
foreach (['arrive', 'leave', 'travel', 'visit', 'total', 'remove', 'empty_plan', 'warn_late', 'directions', 'from_gampola'] as $k) {
    $labels[$k] = t($k);
}
?>
<div class="container planner">
  <h1><?= t('plan_title') ?></h1>

  <div class="toolbar no-print">
    <label><?= t('start_time') ?>
      <input type="time" id="startTime" value="08:00">
    </label>
    <button class="btn outline" id="printBtn"><?= t('print') ?></button>
    <button class="btn outline danger" id="clearBtn"><?= t('clear') ?></button>
  </div>

  <div id="warn" class="warn" hidden></div>
  <p id="emptyMsg" class="empty" hidden><?= t('empty_plan') ?> <a href="index.php"><?= t('home') ?></a></p>

  <div class="plan-layout" id="planLayout" hidden>
    <ol class="itinerary" id="itinerary"></ol>
    <div class="no-print">
      <h3><?= t('map_plan') ?></h3>
      <div id="map" class="map tall"></div>
    </div>
  </div>

  <p id="summary" class="summary"></p>
  <p class="note"><?= t('plan_note') ?></p>
</div>
<?php
$extra_js = '<script>window.PLAN_LABELS = ' . json_encode($labels, JSON_UNESCAPED_UNICODE)
          . '; window.GAMPOLA = {lat: ' . GAMPOLA_LAT . ', lng: ' . GAMPOLA_LNG . '};</script>'
          . '<script src="assets/js/planner.js"></script>';
require __DIR__ . '/includes/footer.php';
