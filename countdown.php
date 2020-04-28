<!DOCTYPE html>
<html lang="en" >

  <head>
    <meta charset="UTF-8">
    <title>XVK3 - Countdown</title>
  
    <link rel="stylesheet" href="/css/google_font_css.css">
    <link rel="stylesheet" href="/css/style.css">
    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="js/index.js"></script>
    <script>
    function secToHMS(seconds) {
      var hours = Math.floor(seconds / 60 / 60);
      var minutes = Math.floor(seconds / 60) - (hours * 60);
      var rseconds = Math.floor(seconds % 60);
      return hours.toString().padStart(2, '0') + ':' + minutes.toString().padStart(2, '0') + ':' + rseconds.toString().padStart(2, '0');
    }
    </script>
  </head>
  <body onload="tr()">
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


  $sql = "SELECT STATE,GP FROM META";
  $pql = mysqli_prepare($conn, $sql);

  if(!mysqli_stmt_execute($pql)) {
    echo "register.php:mysqli_stmt_execute failed\n";
    die();
  } else {
    //echo "register.php:mysqli_stmt_execute sucess\n";
  }

  $res = mysqli_stmt_get_result($pql);
  if(!$res) {
    echo "register.php:mysqli_stmt_get_result failed\n";
    die();
  } else {
    //echo "register.php:mysqli_stmt_get_result success\n";
  }

  $row = mysqli_fetch_assoc($res);
  if(!$row) {
    echo "register.php:mysqli_fetch_assoc failed\n";
    die();
  } else {
    //echo "register.php:mysqli_fetch_assoc success\n";
  }

  if($row['STATE'] == 1) {
    echo "<p>Submission Active</p>\r\n";
    echo "<form action=\"guess.php\" method=\"post\">\r\n";
  } else {
    echo "<script>\r\n";
    echo "function tr() {\r\n";
    echo "  var n = new Date;\r\n";
    echo "  var t = new Date(";
    echo $row['GP'];
    echo "*1000);\r\n";
    echo "  if (t > n) d = (t - n);\r\n"; 
    echo "  document.getElementById(\"gpt\").innerHTML = t;\r\n";
    echo "  document.getElementById(\"gpd\").innerHTML = secToHMS(d/1000);\r\n";
    echo "}\r\n";
    echo "</script>\r\n";
    echo "<p id=\"gpt\">GUESSING PERIOD TIME</p>\r\n";
    echo "<p id=\"gpd\">GUESSING PERIOD TIME REMAINING</p>\r\r";
    echo "<form>\r\n";
  }
    ?>
      <!--<form action="guess.php" method="post">-->
        <div class="input" >
          <input type="name" name="token" required="required"/>
          <span><i class="fa fa-key"></i></span>
        </div>
        <div class="input">
          <input type="number" name="guess" placeholder="guess" required="required" />
          <!-- Chris to add some fancy input validation preventing participants from entering an invalid number -->
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
      <p>GHU - Number Submission</p>
    </div>
  </div>

</body>

</html>
