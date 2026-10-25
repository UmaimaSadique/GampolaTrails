<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/lang.php';
require_once __DIR__ . '/functions.php';
$page = $page ?? '';
?>
<!DOCTYPE html>
<html lang="<?= e($LANG) ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? SITE_NAME) ?> | <?= SITE_NAME ?></title>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="site-header">
  <div class="container nav">
    <a class="brand" href="index.php">Gampola <span>Trails</span></a>
    <nav>
      <a href="index.php" class="<?= $page === 'home' ? 'active' : '' ?>"><?= t('home') ?></a>
      <a href="planner.php" class="<?= $page === 'planner' ? 'active' : '' ?>"><?= t('planner') ?> <span id="planCount" class="badge">0</span></a>
      <a href="admin/login.php"><?= t('admin') ?></a>
      <span class="lang">
        <a href="?<?= e(http_build_query(array_merge($_GET, ['lang' => 'en']))) ?>">EN</a>
        <a href="?<?= e(http_build_query(array_merge($_GET, ['lang' => 'si']))) ?>">සිං</a>
        <a href="?<?= e(http_build_query(array_merge($_GET, ['lang' => 'ta']))) ?>">தமி</a>
      </span>
    </nav>
  </div>
</header>
<main>
