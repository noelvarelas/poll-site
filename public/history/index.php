<?php

require __DIR__ . '/../../private/db.php';

$today = date('Y-m-d');

$rows = $pdo->query(
  "SELECT * FROM questions WHERE date < '$today' ORDER BY date DESC LIMIT 16"
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
        <li><a href="/">Home Page</a></li>
        <li><a href="./" class="active">Past Questions</a></li>
        <li><a href="../request/">Request Question</a></li>
        <li><a href="https://github.com/noelvarelas/poll-site">Source Code</a></li>
      </ul>
    </nav><br>

    <div class="main-content">
    
    <h1>Past Questions</h1>

    <?php if (empty($rows)): ?>
      <p>There are no past questions yet!</p>
    <?php else: ?>
      <?php foreach ($rows as $row):
        $total = $row['results_a'] + $row['results_b'];
        $percent_a = $total > 0 ? round(($row['results_a'] / $total) * 100) : 0;
        $percent_b = $total > 0 ? round(($row['results_b'] / $total) * 100) : 0; ?>
        <hr>
        <p><?= txt($row['date']) ?></p>
        <p><?= txt($row['question']) ?></p>
        <p><?= txt($percent_a . "%" . " - " . $row['answer_a']) ?></p>
        <p><?= txt($percent_b . "%" . " - " . $row['answer_b']) ?></p>
      <?php endforeach; ?>
    <?php endif; ?>

    </div>

  </body>
</html>