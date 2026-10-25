</main>
<footer class="site-footer">
  <div class="container"><?= SITE_NAME ?> &copy; <?= date('Y') ?> &middot; <?= t('footer') ?></div>
</footer>
<script>
  window.LABELS = <?= json_encode(['add' => t('add_plan'), 'remove' => t('in_plan')], JSON_UNESCAPED_UNICODE) ?>;
</script>
<?= $data_js ?? "" ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="assets/js/app.js"></script>
<?= $extra_js ?? '' ?>
</body>
</html>
