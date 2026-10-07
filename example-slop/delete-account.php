<?php
session_start();

// Require the user to be logged in.
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Only accept POST requests.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: home.php');
    exit;
}

require __DIR__ . '/db.php';

$user_id = $_SESSION['user_id'];
$password = $_POST['password'] ?? '';

// Retrieve the current user's password hash.
$stmt = $pdo->prepare(
    'SELECT password_hash FROM users WHERE id = ?'
);
$stmt->execute([$user_id]);

$user = $stmt->fetch();

// Verify the password before deleting anything.
if (!$user || !password_verify($password, $user['password_hash'])) {
    http_response_code(403);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Account deletion failed</title>
    </head>
    <body>
        <h1>Account not deleted</h1>
        <p>The password was incorrect.</p>
        <p><a href="home.php">Return to your home page</a></p>
    </body>
    </html>
    <?php
    exit;
}

// Delete only the currently logged-in user's account.
$stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
$stmt->execute([$user_id]);

// Clear the session.
$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account deleted</title>
</head>
<body>
    <h1>Account deleted</h1>
    <p>Your account has been permanently deleted.</p>
    <p><a href="index.php">Return to login</a></p>
</body>
</html>

