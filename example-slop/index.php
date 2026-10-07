<?php

if (isset($_SESSION['user_id'])) {
  header('Location: home.php');
  exit;
}

require __DIR__ . '/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';

  $stmt = $pdo->prepare(
    'SELECT id, password_hash FROM users WHERE username = ?'
  );

  $stmt->execute([$username]);

  $user = $stmt->fetch();

  if ($user && password_verify($password, $user['password_hash'])) {
    session_regenerate_id(true);

    $_SESSION['user_id'] = $user['id'];

    header('Location: home.php');
    exit;
  }

  $error = 'Invalid username or password.';
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Login</title>
  </head>
  <body>
    <h1>Login</h1>

    <?php if ($error !== ''): ?>
        <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="post" action="index.php">
      <label>
        Username:
        <input type="text" name="username" required>
      </label>

      <br><br>

      <label>
        Password:
        <input type="password" name="password" required>
      </label>

      <br><br>

      <button type="submit">Log in</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
  </body>
</html>
