<?php

require __DIR__ . '/db.php';

// get question id and answer from buttons
$id = $_POST['id'] ?? '';
$answer = $_POST['answer'] ?? '';

// prepare to increment the correct database entry
if ($answer === 'answer_a') {
  $stmt = $pdo->prepare("
    UPDATE questions
    SET results_a = results_a + 1
    WHERE id = ?
  ");
} elseif ($answer === 'answer_b') {
  $stmt = $pdo->prepare("
    UPDATE questions
    SET results_b = results_b + 1
    WHERE id = ?
  ");
} else {
    exit("Invalid answer");
}

// check if user has a voted cookie
if (($_COOKIE['voted'] ?? '') !== 'true') {

  // finally update results for provided question id
  $stmt->execute([$id]);

  // create cookie that expires at midnight
  $tomorrow = new DateTime('tomorrow');
  $expires = $tomorrow->getTimestamp();
  setcookie("voted", "true", $expires, "/");

}

// sends user to home page (refreshes page)
header("Location: index.php");
exit;