<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $stmt = $db->prepare('SELECT id, password_hash FROM admins WHERE username = ?');
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();
    if ($admin && password_verify($_POST['password'] ?? '', $admin['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $admin['id'];
        header('Location: index.php');
        exit;
    }
    $error = 'Wrong username or password.';
}
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin login | Gampola Trails</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<div class="login-box">
  <form class="box" method="post">
    <h2>Admin login</h2>
    <?php if ($error): ?><div class="msg err"><?= e($error) ?></div><?php endif; ?>
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <label>Username <input type="text" name="username" required autofocus></label>
    <label>Password <input type="password" name="password" required></label>
    <p><button class="btn" type="submit">Log in</button> <a href="../index.php">Back to site</a></p>
  </form>
</div>
</body></html>
