<!DOCTYPE html>
<html lang="en" >

  <head>
    <meta charset="UTF-8" name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no"/>
    <title>XVK3 - Results</title>
    <link rel='stylesheet' href='http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css'>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
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
<?php
  // Database conn
  include("ghu_dbconnect.php");
  global $conn;

  // Check connection
  if ($conn) {

    // FP? (Final Period)
    $sql = "SELECT STATE,FP FROM META";
    $pql = mysqli_prepare($conn, $sql);
    if(!mysqli_stmt_execute($pql)) {
      echo "results.php:mysqli_stmt_execute failed\r\n";
      //die();
    } else {
      //echo "results.php:mysqli_stmt_execute success\r\n";
    }

    $res = mysqli_stmt_get_result($pql);
    if(!$res) {
      echo "results.php:mysqli_stmt_get_result failed\r\n";
      //die();
    } else {
      //echo "results.php:mysqli_stmt_get_result success\r\n";
    }

    $row = mysqli_fetch_assoc($res);
    if(!$row) {
      echo "results.php:mysqli_fetch_assoc failed\r\n";
      //die();
    } else {
      //echo "results.php:mysqli_fetch_assoc success\r\n";
    }

    if($row['STATE'] != 3) {
      echo "<div class=\"register\">\r\n";
      echo "  <div class=\"head\"> <h1> Results will be public on </h1> </div>\r\n";
      echo "  <div class=\"hidden\">\r\n";
      echo "  </div>\r\n";
      echo "  <p id=\"td\">FINAL PERIOD TIME</p>\r\n";
      echo "  <p id=\"tr\">FINAL PERIOD TIME REMAINING</p>\r\n";
      echo "  <p hidden id=\"phpi\">" . $row['FP'] . "</p>\r\n";
    } else {
      echo "      <div id=\"results\" class=\"results\">\r\n";
      echo "       <div class=\"head\"> <h1> The results are in  </h1> </div>\r\n";
      echo "       <div class=\"hidden\" >";

      // Find all entries in GHU with valid TOKEN & GUESS
      $sql = "SELECT GUESS,TOKEN FROM GHU WHERE TOKEN IS NOT NULL AND GUESS IS NOT NULL";
      $pql = mysqli_prepare($conn, $sql);

      if(!mysqli_stmt_execute($pql)) {
        echo "results.php:mysqli_stmt_execute failed\r\n";
        //die();
      } else {
        //echo "results.php:mysqli_stmt_execute success\r\n";
      }

      $res = mysqli_stmt_get_result($pql);
      if(!$res) {
        echo "results.php:mysqli_stmt_get_result failed\r\n";
        //die();
      } else {
        //echo "results.php:mysqli_stmt_get_result success\r\n";
      }
      $nr = $res->num_rows;

      //Build array for JS functon
      if($row = mysqli_fetch_assoc($res)) {
        echo $row['TOKEN'] . ":" . $row['GUESS'];
        while($row = mysqli_fetch_assoc($res)) {
          echo "," . $row['TOKEN'] . ":" . $row['GUESS']; 
        }
        echo "\r\n       </div>";
      } else {
        echo "results.php:mysqli_fetch_assoc failed\r\n";
        echo "nobody submitted a guess\r\n";
      }
    }
    mysqli_close($conn);
  }
?>
        <div class="container">
          <!--generate elements -->
        </div>
        <div id="info-footer">
          <div id="token-placement">Tokens:
            <p></p>
          </div>
        </div>
      </div>
      <div id="context">
        <p>When the Final Countdown above completes the results will be displayed!</p>
        <p>Hover over a number to see how many guesses it got</p>
      </div>
    </div>
  </body>
</html>
