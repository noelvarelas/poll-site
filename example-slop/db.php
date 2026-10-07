<?php
$dsn = 'mysql:host=localhost;dbname=web_game;charset=utf8mb4';
$db_user = 'noel';
$db_password = 'password';

$pdo = new PDO($dsn, $db_user, $db_password, [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false,
]);

