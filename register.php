<!DOCTYPE html>
<html>
  
<head>
    <meta charset="UTF-8">
    <title>GHU - Guess Highest Unique Registration</title>
    
    <link rel="stylesheet" href="/css/style.css">
    <!-- have to link to this stylesheet for now -->
    <link rel='stylesheet' href='http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css'>
    <link rel="stylesheet" href="/css/google_font_css.css">
  
    <!-- this is the correct order for js files -->
    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/index.js"></script>
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


  $sql = "SELECT STATE,RP FROM META";
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

  if($row['STATE'] == 1) {
    echo "<p>Registration Active</p>\r\n";
    echo "<form action=\"token.php\" method=\"post\">\r\n";
  } else {
    echo "<script>\r\n";
    echo "function tr() {\r\n";
    echo "  var n = new Date;\r\n";
    echo "  var t = new Date(";
    echo $row['RP'];
    echo "*1000);\r\n";
    echo "  if(t > n) d = (t - n);\r\n"; 
    echo "  document.getElementById(\"rpt\").innerHTML = t;\r\n";
    echo "  document.getElementById(\"rpd\").innerHTML = secToHMS(d/1000);\r\n";
    echo "}\r\n";
    echo "</script>\r\n";
    echo "<p id=\"rpt\">REGISTRATION PERIOD TIME</p>\r\n";
    echo "<p id=\"rpd\">REGISTRATION PERIOD TIME REMAINING</p>\r\r";
    echo "<form>\r\n";
  }

?>
      <!--<form action="token.php" method="post">-->
        <div class="input">
          <input type="name" name="name" placeholder="name" required="required" />
          <span><i class="fa fa-user"></i></span>
        </div>
        <div class="input">
          <input type="email" name="email" placeholder="email" required="required" />
          <span><i class="fa fa-envelope-o"></i></span>
        </div>
        <button id="submit" type="submit" class="btn btn-primary btn-block btn-large">Register</button>
      </form>
    </div>
    <div id="context">
      <p>Guess Highest Unique Lottery</p>
    </div>
  </div>

</body>

</html>
