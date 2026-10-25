<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$msg = $_GET['msg'] ?? '';
$res = $db->query('SELECT id, name, category, distance_km, photo FROM places ORDER BY name');
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Manage places | Gampola Trails</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<div class="admin-wrap">
  <div class="admin-bar">
    <h1>Manage places</h1>
    <div>
      <a class="btn" href="edit.php">+ Add place</a>
      <a class="btn outline" href="../index.php">View site</a>
      <a class="btn outline" href="logout.php">Log out</a>
    </div>
  </div>
  <?php if ($msg): ?><div class="msg ok"><?= e($msg) ?></div><?php endif; ?>
  <table class="data">
    <tr><th>Name</th><th>Category</th><th>Distance</th><th>Photo</th><th>Actions</th></tr>
    <?php while ($p = $res->fetch_assoc()): ?>
      <tr>
        <td><?= e($p['name']) ?></td>
        <td><?= e($p['category']) ?></td>
        <td><?= e($p['distance_km']) ?> km</td>
        <td><?= $p['photo'] ? 'Yes' : 'No' ?></td>
        <td>
          <a href="edit.php?id=<?= (int)$p['id'] ?>">Edit</a> |
          <form method="post" action="delete.php" style="display:inline" onsubmit="return confirm('Delete this place?');">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
            <button class="mini danger" type="submit">Delete</button>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>
</body></html>
