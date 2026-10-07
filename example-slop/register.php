<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit;
}

require __DIR__ . '/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please fill in all required fields.';
    } elseif (strlen($username) > 50) {
        $error = 'Username must be 50 characters or fewer.';
    } elseif (strlen($password) < 12) {
        $error = 'Password must be at least 12 characters.';
    } elseif ($password !== $confirm_password) {
        $error = 'The passwords do not match.';
    } else {
        try {
            $password_hash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt = $pdo->prepare(
                'INSERT INTO users (username, password_hash)
                 VALUES (?, ?)'
            );

            $stmt->execute([$username, $password_hash]);

            $success = 'Account created! You can now log in.';
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $error = 'That username is already taken.';
            } else {
                error_log($e->getMessage());
                $error = 'Something went wrong. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>
    <h1>Create an account</h1>

    <?php if ($error !== ''): ?>
        <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <?php if ($success !== ''): ?>
        <p><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></p>
        <p><a href="index.php">Go to login</a></p>
    <?php else: ?>
        <form method="post" action="register.php">
            <label>
                Username:
                <input
                    type="text"
                    name="username"
                    maxlength="50"
                    required
                >
            </label>

            <br><br>

            <label>
                Password:
                <input
                    type="password"
                    name="password"
                    minlength="12"
                    required
                >
            </label>

            <br><br>

            <label>
                Confirm password:
                <input
                    type="password"
                    name="confirm_password"
                    minlength="12"
                    required
                >
            </label>

            <br><br>

            <button type="submit">Register</button>
        </form>

        <p>Already have an account? <a href="index.php">Log in</a></p>
    <?php endif; ?>
</body>
</html>

