<?php

$db_path = __DIR__ . '/database.sqlite';

// mariadb credentials in separate file
//require_once __DIR__ . '/db-credentials.php';

// timezone set here since all pages require db.php
date_default_timezone_set('America/Phoenix');

// establish php connection to mariadb
// uses recommended attributes
//$pdo = new PDO($dsn, $db_user, $db_password, [
//  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//  PDO::ATTR_EMULATE_PREPARES => false,
//]);

// establish php connection to sqlite
$pdo = new PDO('sqlite:' . $db_path, null, null, [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);