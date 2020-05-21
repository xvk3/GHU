<!DOCTYPE html>
<html>
  
  <head>
    <meta charset="UTF-8" name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no"/>
    <title>GHU - Guess Highest Unique Registration</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel='stylesheet' href='http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css'>
    <link rel="stylesheet" href="/css/google_font_css.css">
    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/index.js"></script>
  </head>
  <body onload="tr();">
  <div class="vcent noselect">
    <div id="header"> <h1>XVK3<span>.NET</span></h1> </div>
    <div id="nav">
       <ul>
         <li><a href="register.php">Register</a></li>
         <li><a href="countdown.php">Countdown</a></li>
         <li><a href="results.php">Results</a></li>
      </ul>
    </div>
    <div class="register">
<?php

  // Database conn
  include("ghu_dbconnect.php");
  global $conn;


  $sql = "SELECT STATE,RP,NOP FROM META";
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

  $rp = $row['RP'];
  $state = $row['STATE'];
  $nop = $row['NOP'];

  if($state == 1) {
    echo "<h1>Registration Active</h1>\r\n";
    echo "<p>There are currently " . $nop . " participants!</p>\r\n";
    echo "<form action=\"token.php\" method=\"post\">\r\n";
  } else {
    echo "<div class=\"head\"> <h1> Registration will be active on </h1> </div>\r\n";
    echo "<p id=\"td\">REGISTRATION PERIOD TIME</p>\r\n";
    echo "<p id=\"tr\">REGISTRATION PERIOD TIME REMAINING</p>\r\r";
    echo "<p hidden id=\"phpi\">" . $rp . "</p>\r\n";
    echo "<form>\r\n";
  }

?>
      <!--<form action="token.php" method="post">-->
        <div class="input">
          <input <?php if($state != 1) echo "disabled"; ?> type="name" name="name" placeholder="name" required="required" />
          <span><i class="fa fa-user"></i></span>
        </div>
        <div class="input">
          <input <?php if($state != 1) echo "disabled"; ?> type="email" name="email" placeholder="email" required="required" />
          <span><i class="fa fa-envelope-o"></i></span>
        </div>
        <button  <?php if($state != 1) echo "disabled"; ?> id="submit" type="submit" class="btn btn-primary btn-block btn-large">Register</button>
      </form>
    </div>
    <div id="context">
      <p>Register to participate in the GHU Lottery! When the Guessing Countdown completes each player is prompted to guess a number between 1 and the number of registered participants. The winner, determined when the Final Countdown completes, is defined as..</br><b>The player with the highest unique guess (only submitted by 1 participant)</b></p>
    </div>
  </div>

</body>

</html>
