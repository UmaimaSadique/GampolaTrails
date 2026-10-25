<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
$p = ['name' => '', 'category' => 'historical', 'tags' => '', 'description' => '', 'distance_km' => '',
      'latitude' => '', 'longitude' => '', 'opening_hours' => '', 'entrance_fee' => '', 'facilities' => '',
      'visit_minutes' => 60, 'photo' => '', 'source_url' => ''];

if ($id) {
    $stmt = $db->prepare('SELECT * FROM places WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $found = $stmt->get_result()->fetch_assoc();
    if (!$found) { die('Place not found.'); }
    $p = $found;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    foreach (['name', 'category', 'tags', 'description', 'opening_hours', 'entrance_fee', 'facilities', 'source_url'] as $f) {
        $p[$f] = trim($_POST[$f] ?? '');
    }
    $p['distance_km'] = $_POST['distance_km'] ?? '';
    $p['latitude'] = $_POST['latitude'] ?? '';
    $p['longitude'] = $_POST['longitude'] ?? '';
    $p['visit_minutes'] = (int)($_POST['visit_minutes'] ?? 60);

    if ($p['name'] === '') $errors[] = 'Name is required.';
    if (!in_array($p['category'], categories(), true)) $errors[] = 'Choose a valid category.';
    if ($p['description'] === '') $errors[] = 'Description is required.';
    if (!is_numeric($p['distance_km']) || $p['distance_km'] <= 0 || $p['distance_km'] > 25) $errors[] = 'Distance must be between 0 and 25 km.';
    if (!is_numeric($p['latitude']) || abs($p['latitude']) > 90) $errors[] = 'Latitude is not valid.';
    if (!is_numeric($p['longitude']) || abs($p['longitude']) > 180) $errors[] = 'Longitude is not valid.';
    if ($p['visit_minutes'] < 10 || $p['visit_minutes'] > 480) $errors[] = 'Visit time must be 10-480 minutes.';

    // photo upload (jpg / png / webp, max 2 MB)
    if (!$errors && !empty($_FILES['photo']['name'])) {
        $f = $_FILES['photo'];
        $info = $f['error'] === UPLOAD_ERR_OK ? @getimagesize($f['tmp_name']) : false;
        $exts = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_WEBP => 'webp'];
        if (!$info || !isset($exts[$info[2]])) {
            $errors[] = 'Photo must be a JPG, PNG or WEBP image.';
        } elseif ($f['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Photo must be smaller than 2 MB.';
        } else {
            $newName = bin2hex(random_bytes(8)) . '.' . $exts[$info[2]];
            if (move_uploaded_file($f['tmp_name'], __DIR__ . '/../uploads/' . $newName)) {
                if ($p['photo'] && is_file(__DIR__ . '/../uploads/' . $p['photo'])) unlink(__DIR__ . '/../uploads/' . $p['photo']);
                $p['photo'] = $newName;
            } else {
                $errors[] = 'Could not save the photo (is the uploads folder writable?).';
            }
        }
    }

    if (!$errors) {
        if ($id) {
            $stmt = $db->prepare('UPDATE places SET name=?, category=?, tags=?, description=?, distance_km=?, latitude=?, longitude=?,
                opening_hours=?, entrance_fee=?, facilities=?, visit_minutes=?, photo=?, source_url=? WHERE id=?');
            $stmt->bind_param('ssssdddsssissi', $p['name'], $p['category'], $p['tags'], $p['description'], $p['distance_km'],
                $p['latitude'], $p['longitude'], $p['opening_hours'], $p['entrance_fee'], $p['facilities'],
                $p['visit_minutes'], $p['photo'], $p['source_url'], $id);
        } else {
            $stmt = $db->prepare('INSERT INTO places (name, category, tags, description, distance_km, latitude, longitude,
                opening_hours, entrance_fee, facilities, visit_minutes, photo, source_url) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt->bind_param('ssssdddsssiss', $p['name'], $p['category'], $p['tags'], $p['description'], $p['distance_km'],
                $p['latitude'], $p['longitude'], $p['opening_hours'], $p['entrance_fee'], $p['facilities'],
                $p['visit_minutes'], $p['photo'], $p['source_url']);
        }
        if ($stmt->execute()) {
            header('Location: index.php?msg=' . urlencode($id ? 'Place updated.' : 'Place added.'));
            exit;
        }
        $errors[] = 'Database error: ' . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= $id ? 'Edit' : 'Add' ?> place | Gampola Trails</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<div class="admin-wrap">
  <h1><?= $id ? 'Edit place' : 'Add place' ?></h1>
  <?php foreach ($errors as $err): ?><div class="msg err"><?= e($err) ?></div><?php endforeach; ?>

  <form class="box" method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <label>Name <input type="text" name="name" value="<?= e($p['name']) ?>" required></label>
    <div class="row2">
      <label>Category
        <select name="category">
          <?php foreach (categories() as $c): ?>
            <option value="<?= $c ?>" <?= $p['category'] === $c ? 'selected' : '' ?>><?= ucfirst($c) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label>Extra tags (comma separated, e.g. Religious, Waterfall)
        <input type="text" name="tags" value="<?= e($p['tags']) ?>"></label>
    </div>
    <label>Description <textarea name="description" rows="5" required><?= e($p['description']) ?></textarea></label>
    <div class="row2">
      <label>Distance from Gampola (km, max 25) <input type="number" step="0.1" name="distance_km" value="<?= e($p['distance_km']) ?>" required></label>
      <label>Suggested visit time (minutes) <input type="number" name="visit_minutes" value="<?= e($p['visit_minutes']) ?>" required></label>
    </div>
    <div class="row2">
      <label>Latitude <input type="number" step="any" name="latitude" value="<?= e($p['latitude']) ?>" required></label>
      <label>Longitude <input type="number" step="any" name="longitude" value="<?= e($p['longitude']) ?>" required></label>
    </div>
    <label>Opening hours <input type="text" name="opening_hours" value="<?= e($p['opening_hours']) ?>"></label>
    <label>Entrance fee <input type="text" name="entrance_fee" value="<?= e($p['entrance_fee']) ?>"></label>
    <label>Facilities <input type="text" name="facilities" value="<?= e($p['facilities']) ?>"></label>
    <label>Source URL <input type="text" name="source_url" value="<?= e($p['source_url']) ?>"></label>
    <label>Photo (JPG/PNG/WEBP, max 2 MB) <input type="file" name="photo" accept="image/jpeg,image/png,image/webp"></label>
    <?php if ($p['photo']): ?><p>Current photo: <?= e($p['photo']) ?></p><?php endif; ?>
    <p>
      <button class="btn" type="submit">Save</button>
      <a class="btn outline" href="index.php">Cancel</a>
    </p>
  </form>
</div>
</body></html>
