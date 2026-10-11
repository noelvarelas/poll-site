<?php

// db.php has the connection info php uses to talk to mariadb
require __DIR__ . '/../includes/db.php';

$date = date('Y-m-d');

// $stmt dumps the question info into php using the date
// $row extracts the data we want
$stmt = $pdo->prepare("SELECT * FROM questions WHERE date = ?");
$stmt->execute([$date]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// check if the user has a cookie for having voted already
// this cookie is created in vote.php upon voting
// cookie expires at midnight to allow voting again
$voted = ($_COOKIE['voted'] ?? '' ) === 'true';

// helper function to safely put database text into the html
function txt($string) {
  return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Let's Settle This!</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <center>

    <h1>Let's Settle This!</h1>

    <?php if (!$row): ?>
      <p>There is no question today :(</p>
    <?php elseif (! $voted): ?>
      <p><?= txt($row['question']) ?></p>
      <form method="post" action="./vote.php">
        <input type="hidden" name="id" value="<?= $row['id'] ?>">
        <button type="submit" name="answer" value="answer_a">
          <?= txt($row['answer_a']) ?>
        </button>
        <button type="submit" name="answer" value="answer_b">
          <?= txt($row['answer_b']) ?>
        </button>
      </form>
    <?php else: ?>
      <p>Thank you for participating!</p>
      <p>Current results:</p>
      <p><?= txt($row['question']) ?></p>
      <p><?= txt($row['answer_a']) ?>: <?= txt($row['results_a']) ?></p>
      <p><?= txt($row['answer_b']) ?>: <?= txt($row['results_b']) ?></p>
    <?php endif; ?>
    
    </center>
  </body>
</html>
