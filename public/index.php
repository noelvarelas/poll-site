<?php

// db.php has the connection info php uses to talk to mariadb
require __DIR__ . '/../private/db.php';

$today = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('-1 day'));

// $stmt dumps the question info into php using the date
// $rows extracts the data we want
$stmt = $pdo->prepare("SELECT * FROM questions WHERE date IN (?, ?)");
$stmt->execute([$today, $yesterday]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// separate today's question from yesterday's question
$todayRow = null;
$yesterdayRow = null;
foreach ($rows as $row) {
  if ($row['date'] === $today) {
    $todayRow = $row;
  } elseif ($row['date'] === $yesterday) {
    $yesterdayRow = $row;
  }
}

// check if the user has a cookie for having voted
// this cookie is created in vote.php
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

    <nav class="navbar">
      <ul>
        <li><a href="/" class="active">Home</a><li>
        <li><a href="./history/index.php">View Past Questions</a></li>
        <li><a href="./request/index.php">Request a Question</a></li>
        <li><a href="https://github.com/noelvarelas/poll-site">Source Code</a></li>
      </ul>
    </nav>

    <center>

    <h1>Let's Settle This!</h1>

    <?php if (!$todayRow): ?>
      <p>There is no question today :(</p>
    <?php elseif (! $voted):?>
      <p><?= txt($todayRow['question']) ?></p>
      <form method="post" action="./vote.php">
        <input type="hidden" name="id" value="<?= $todayRow['id'] ?>">
        <button type="submit" name="answer" value="answer_a">
          <?= txt($todayRow['answer_a']) ?>
        </button>
        <button type="submit" name="answer" value="answer_b">
          <?= txt($todayRow['answer_b']) ?>
        </button>
      </form>
    <?php else: ?>
      <p>Thank you for participating!</p>
      <p>Come back tomorrow for the results!</p>
    <?php endif; ?>

    <?php if ($yesterdayRow):
      $total = $yesterdayRow['results_a'] + $yesterdayRow['results_b'];
      $percent_a = $total > 0 ? round(($yesterdayRow['results_a'] / $total) * 100) : 0;
      $percent_b = $total > 0 ? round(($yesterdayRow['results_b'] / $total) * 100) : 0; ?>
      <hr><h3>Yesterday's Results</h3>
      <p><?= txt($yesterdayRow['question']) ?></p>
      <p><?= txt($yesterdayRow['answer_a']) . " - " . $percent_a . "%" ?></p>
      <p><?= txt($yesterdayRow['answer_b']) . " - " . $percent_b . "%" ?></p>
    <?php endif; ?>

    </center>

  </body>
</html>
