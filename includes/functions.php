<?php
function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function categories() { return ['historical', 'religious', 'natural', 'recreational']; }

// A place may be "historical" with the tag "Religious" - tags also count for filtering
function place_categories($p) {
    $list = [strtolower($p['category'])];
    foreach (explode(',', (string)$p['tags']) as $tag) {
        $tag = strtolower(trim($tag));
        if ($tag !== '') $list[] = $tag;
    }
    return array_values(array_unique($list));
}

// Photo tag: uploaded image, or a coloured placeholder when none is uploaded
function photo_html($p, $class = 'photo', $prefix = '') {
    if (!empty($p['photo']) && is_file(__DIR__ . '/../uploads/' . $p['photo'])) {
        return '<img class="' . $class . '" src="' . $prefix . 'uploads/' . e($p['photo']) . '" alt="' . e($p['name']) . '" loading="lazy">';
    }
    return '<div class="' . $class . ' placeholder cat-' . e($p['category']) . '"><span>' . e($p['name']) . '</span></div>';
}

function is_admin() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    return !empty($_SESSION['admin_id']);
}

function require_admin() {
    if (!is_admin()) { header('Location: login.php'); exit; }
}

// Distance between two coordinates in km (haversine formula)
function distance_km($lat1, $lng1, $lat2, $lng2) {
    $r = 6371;
    $dLat = deg2rad($lat2 - $lat1);
    $dLng = deg2rad($lng2 - $lng1);
    $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
    return $r * 2 * atan2(sqrt($a), sqrt(1 - $a));
}

function csrf_token() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
    return $_SESSION['csrf'];
}

function csrf_check() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(400);
        die('Invalid form token.');
    }
}
