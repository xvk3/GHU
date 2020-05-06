<!DOCTYPE html>
<html lang="en" >

  <head>
    <meta charset="UTF-8">
    <title>XVK3 - Results</title>
    <link rel='stylesheet' href='http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css'>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
    <script src="/js/index.js"></script>
  </head>
  <body onload="parseResults()">
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
      <div id="results" class="results">
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
      echo "<div class=\"head\"> <h1> Results will be public on </h1> </div>";
      echo "<div class=\"hidden\">";
      echo "</div>\r\n";
      echo "<script>\r\n";
      echo "function tr() {\r\n";
      echo "  var n = new Date;\r\n";
      echo "  var t = new Date(" . $row['FP'] . "*1000);\r\n";
      echo "  if(t > n) d = (t - n);\r\n";
      echo "    document.getElementById(\"fpt\").innerHTML = t\r\n";
      echo "    document.getElementById(\"fpd\").innerHTML = secToHMS(d/1000);\r\n";
      echo "  }\r\n";
      echo "</script>\r\n";
      echo "<p id=\"fpt\">FINAL PERIOD TIME</p>\r\n";
      echo "<p id=\"fpd\">FINAL PERIOD TIME REMAINGING</p>\r\n";
    } else {

      echo "<div class=\"head\"> <h1> The results are in  </h1> </div>";
      echo "<div class=\"hidden\">";

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
        echo "</div>";
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
      </div>
  </body>
</html>
