<?php

$db_path = __DIR__ . '/database.sqlite';

// timezone set here since all pages require db.php
date_default_timezone_set('America/Phoenix');

// establish php connection to sqlite
$pdo = new PDO('sqlite:' . $db_path, null, null, [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);