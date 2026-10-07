<?php

// timezone set here since all pages require db.php
date_default_timezone_set('America/Phoenix');

// mariadb credentials
$dsn = 'mysql:host=localhost;dbname=website;charset=utf8mb4';
$db_user = 'noel';
$db_password = 'almalinuxapachemariadbphp';

// establish php connection to mariadb
// uses recommended attributes
$pdo = new PDO($dsn, $db_user, $db_password, [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false,
]);
