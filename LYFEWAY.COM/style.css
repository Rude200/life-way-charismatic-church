<?php
session_start();
require_once __DIR__ . '/../config.php';

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = isset($_POST['user']) ? $_POST['user'] : '';
    $pass = isset($_POST['pass']) ? $_POST['pass'] : '';

    if ($user === ADMIN_USER && $pass === ADMIN_PASSWORD) {
        $_SESSION['logged_in'] = true;
        header('Location: ../index.php?msg=' . urlencode('Logged in.'));
        exit;
    } else {
        $err = 'Invalid credentials.';
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Login</title>
  <link rel="stylesheet" href="../assets/styles.css">
  <style> .login-box{max-width:380px;margin:40px auto;padding:18px;background:#07111a;border-radius:8px} </style>
</head>
<body>
  <div class="container">
    <div class="login-box">
      <h3>Admin Login</h3>
      <?php if ($err): ?><div class="note" style="background:#2b0510;color:#ffb6c1;padding:8px;border-radius:6px"><?php echo htmlspecialchars($err); ?></div><?php endif; ?>
      <form method="post" action="">
        <label for="user">User</label>
        <input id="user" name="user" value="admin" />
        <label for="pass">Password</label>
        <input id="pass" name="pass" type="password" />
        <button type="submit">Login</button>
      </form>
      <p class="note">Default password is "changeme" — change config.php immediately.</p>
    </div>
  </div>
</body>
</html>