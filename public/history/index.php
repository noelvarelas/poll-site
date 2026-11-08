<?php

require __DIR__ . '/../../private/db.php';

$rows = $pdo->query(
  "SELECT * FROM questions ORDER BY date DESC LIMIT 100"
  )->fetchAll((PDO::FETCH_ASSOC));

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
    <link rel="stylesheet" href="../style.css">
  </head>
  <body>

    <nav class="navbar">
      <ul>
        <li><a href="/">Home</a><li>
        <li><a href="index.php" class="active">View Past Questions</a></li>
        <li><a href="../request/index.php">Request a Question</a></li>
        <li><a href="https://github.com/noelvarelas/poll-site">Source Code</a></li>
      </ul>
    </nav>

    <center>

    <h1>Past Questions</h1>
    
    <?php foreach ($rows as $row):
      $db_date = $row['date'];
      $date = new DateTime($db_date); ?>
      <hr>
      <p><?= $date->format('F j, Y') ?></p>
      <p><?= txt($row['question']) ?></p>
      <p><?= txt($row['answer_a']) . " - " . txt($row['results_a']) ?></p>
      <p><?= txt($row['answer_b']) . " - " . txt($row['results_b']) ?></p>
    <?php endforeach; ?>

    </center>

  </body>
</html>