<?php

// check if the user has a cookie for having requested
// this cookie is created in request.php
// cookie expires at midnight to allow requesing again
$requested = ($_COOKIE['requested'] ?? '' ) === 'true';

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
        <li><a href="../history/">View Questions</a></li>
        <li><a href="./" class="active">Request Question</a></li>
        <li><a href="https://github.com/noelvarelas/poll-site">Source Code</a></li>
      </ul>
    </nav><br>

    <div class="main-content">

    <?php if (! $requested):?>
      <h3>Which question would you like to see settled?</h3>
      <form method="post" action="request.php"
        <label for="request"></label>Your request:</label>
        <input type="text" id="request" name="request" maxlength="128" required>
        <button type="submit" style="padding:0.25rem;">Send</button>
      </form>
    <?php else: ?>
      <h3>Thank you for your request!</h3>
      <p>If you have more, come back tomorrow.</p>
      <p>Requests are limited to one per person per day.<p>
    <?php endif; ?>

    <div class="main-content">

  </body>
</html>