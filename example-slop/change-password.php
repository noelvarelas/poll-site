<?php
session_start();

// The user must be logged in.
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Only accept form submissions.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: home.php');
    exit;
}

require __DIR__ . '/db.php';

$user_id = $_SESSION['user_id'];

$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

$error = '';

// Check that the new password meets the requirements.
if (strlen($new_password) < 12) {
    $error = 'The new password must be at least 12 characters.';
} elseif ($new_password !== $confirm_password) {
    $error = 'The new passwords do not match.';
} elseif ($current_password === $new_password) {
    $error = 'Your new password must differ from your current password.';
}

if ($error === '') {
    // Retrieve the current password hash.
    $stmt = $pdo->prepare(
        'SELECT password_hash FROM users WHERE id = ?'
    );
    $stmt->execute([$user_id]);

    $user = $stmt->fetch();

    // Verify the current password.
    if (!$user || !password_verify(
        $current_password,
        $user['password_hash']
    )) {
        $error = 'Your current password is incorrect.';
    }
}

if ($error === '') {
    // Hash the new password.
    $new_password_hash = password_hash(
        $new_password,
        PASSWORD_DEFAULT
    );

    // Save the new password hash.
    $stmt = $pdo->prepare(
        'UPDATE users SET password_hash = ? WHERE id = ?'
    );
    $stmt->execute([$new_password_hash, $user_id]);

    // Regenerate the session ID.
    session_regenerate_id(true);

    $message = 'Your password has been changed successfully.';
} else {
    http_response_code(400);
    $message = $error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change password</title>
</head>
<body>
    <h1>Change password</h1>

    <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>

    <p><a href="home.php">Return to your home page</a></p>
</body>
</html>

