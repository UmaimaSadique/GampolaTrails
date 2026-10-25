<?php
// Returns places as JSON. Optional ?ids=1,2,3 returns only those places.
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

$sql = 'SELECT id, name, category, tags, description, distance_km, latitude, longitude,
               opening_hours, visit_minutes, photo FROM places';
$ids = [];
if (!empty($_GET['ids'])) {
    $ids = array_values(array_filter(array_map('intval', explode(',', $_GET['ids']))));
    if (!$ids) { echo '[]'; exit; }
    $sql .= ' WHERE id IN (' . implode(',', $ids) . ')';   // values are ints, so this is safe
}
$sql .= ' ORDER BY distance_km';

$out = [];
$res = $db->query($sql);
while ($row = $res->fetch_assoc()) {
    $row['id'] = (int)$row['id'];
    $row['latitude'] = (float)$row['latitude'];
    $row['longitude'] = (float)$row['longitude'];
    $row['distance_km'] = (float)$row['distance_km'];
    $row['visit_minutes'] = (int)$row['visit_minutes'];
    $row['photo_url'] = $row['photo'] && is_file(__DIR__ . '/../uploads/' . $row['photo']) ? 'uploads/' . $row['photo'] : '';
    $out[] = $row;
}
echo json_encode($out, JSON_UNESCAPED_UNICODE);
