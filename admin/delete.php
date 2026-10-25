<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int)($_POST['id'] ?? 0);

    // remove the uploaded photo file too
    $stmt = $db->prepare('SELECT photo FROM places WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if ($row && $row['photo'] && is_file(__DIR__ . '/../uploads/' . $row['photo'])) {
        unlink(__DIR__ . '/../uploads/' . $row['photo']);
    }

    $stmt = $db->prepare('DELETE FROM places WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
}
header('Location: index.php?msg=' . urlencode('Place deleted.'));
