<!DOCTYPE html>
<html lang="en" >

  <head>
    <meta charset="UTF-8" name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no"/>
    <title>XVK3 - Countdown</title>
  
    <link rel="stylesheet" href="/css/google_font_css.css">
    <link rel="stylesheet" href="/css/style.css">
    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/index.js"></script>
  </head>
  <body onload="tr();">
    <div class="vcent noselect">
      <div id="header">
        <h1>XVK3<span>.NET</span></h1>
      </div>
      <div id="nav">
        <ul>
          <li><a href="register.php">Register</a></li>
          <li><a href="countdown.php">Countdown</a></li>
          <li><a href="results.php">Results</a></li>
        </ul>
      </div>
    <div class="submission">
  
  <?php

  // Database conn
  include("ghu_dbconnect.php");
  global $conn;

  $sql = "SELECT STATE,GP,NOP FROM META";
  $pql = mysqli_prepare($conn, $sql);

  if(!mysqli_stmt_execute($pql)) {
    echo "register.php:mysqli_stmt_execute failed\r\n";
    die();
  } else {
    //echo "register.php:mysqli_stmt_execute sucess\r\n";
  }

  $res = mysqli_stmt_get_result($pql);
  if(!$res) {
    echo "register.php:mysqli_stmt_get_result failed\r\n";
    die();
  } else {
    //echo "register.php:mysqli_stmt_get_result success\r\n";
  }

  $row = mysqli_fetch_assoc($res);
  if(!$row) {
    echo "register.php:mysqli_fetch_assoc failed\r\n";
    die();
  } else {
    //echo "register.php:mysqli_fetch_assoc success\r\n";
  }

  if($row['STATE'] == 2) {
    echo "<div class=\"head\">\r\n";
    echo "  <h1>Submission Active</h1>\r\n";
    echo "  <h1>Guess between 1 and " . $row['NOP'] . "</h1>\r\n";
    echo "</div>\r\n";
    echo "<form action=\"guess.php\" method=\"post\">\r\n";
  } else {
    echo "<div class=\"head\"> <h1>Submission will be active on </h1> </div>\r\n";
    echo "<p id=\"td\">GUESSING PERIOD TIME</p>\r\n";
    echo "<p id=\"tr\">GUESSING PERIOD TIME REMAINING</p>\r\r";
    echo "<p hidden id=\"phpi\">" . $row['GP'] . "</p>\r\n";
    echo "<form>\r\n";
  }
    ?>
      <!--<form action="guess.php" method="post">-->
        <div class="input" >
          <input type="name" name="token" placeholder="token" required="required"/>
          <span><i class="fa fa-key"></i></span>
        </div>
        <div class="input">
          <input type="number" name="guess" placeholder="1 - <?php
          echo $row['NOP'];
          echo "\" min=\"1\" max=\"";
          echo $row['NOP'];
          ?>" required="required" />
          <span><i class="fa fa-question"></i></span>
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-large">Submit</button>
      </form>
    </div>

    <div class="container">
      <div id="countdown">
        <p id="countdown-timer"></p>
      </div>
    </div>

    <div id="context">
      <p>When the Guessing Countdown above completes submit your guess!</p>
      <p>You'll need your token supplied during the Registration Period</p>
    </div>
  </div>

</body>

</html>
