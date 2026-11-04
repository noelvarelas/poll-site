<?php

$filepath = __DIR__ . '/../private/requests.txt';

// only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  exit('Method not allowed');
}

// remove whitespace from ends
$request = trim($_POST['request'] ?? '');

// check that submission isn't empty or too long
if ($request === '' || mb_strlen($request) > 128) {
  http_response_code(400);
  exit('Invalid input');
}

// turns newlines into spaces and makes everything one line
$request = preg_replace('/[\r\n]+/', ' ', $request);

// prepare line to write
$line = sprintf(
  "%s\n",
  $request
);

// write request to file, limit one per day
if (file_put_contents($filepath, $line, FILE_APPEND | LOCK_EX) === false) {
  http_response_code(500);
  exit('Could not save');
} else {
// create cookie that expires at midnight
$tomorrow = new DateTime('tomorrow');
$expires = $tomorrow->getTimestamp();
setcookie("requested", "true", $expires, "/");
}

// sends user to home page (refreshes page)
header("Location: index.php");
exit;