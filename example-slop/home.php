<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Home</title>
  </head>
  <body>
    <h1>Welcome to your home page!</h1>

    <form method="post" action="logout.php">
      <button type="submit">Log out</button>
    </form>

    <br><hr>

    <h2>Change password</h2>

    <form method="post" action="change-password.php">
      <label>
        Current password:
        <input type="password" name="current_password" required>
      </label>

    <br><br>

    <label>
      New password:
      <input
          type="password"
          name="new_password"
          minlength="12"
          required
      >
    </label>

    <br><br>

    <label>
      Confirm new password:
      <input
        type="password"
        name="confirm_password"
        minlength="12"
        required
      >
    </label>

    <br><br>

    <button type="submit">Change password</button>
    </form>

    <br><hr>

    <h2>Delete account</h2>

    <p>Warning: This action cannot be undone.</p>

    <form method="post" action="delete-account.php">
      <label>
        Enter your password to confirm:
        <input
          type="password"
          name="password"
          required
        >
      </label>

    <br><br>

    <button type="submit">Delete my account</button>
    </form>
  </body>
</html>

