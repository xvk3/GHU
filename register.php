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

  
</head>

<body>

  <div class="vcent noselect">
    <div id="header"> <h1>XVK3<span>.NET</span></h1> </div>
    <div id="nav">
       <ul>
         <li><a href="register.html">Register</a></li>
         <li><a href="countdown.php">Countdown</a></li>
         <li><a href="results.php">Results</a></li>
      </ul>
    </div>
    <div class="register">

<?php

  // Database conn
  include("ghu_dbconnect.php");
  global $conn;


  $sql = "SELECT STATE FROM META";
  $pql = mysqli_prepare($conn, $sql);

  if(!mysqli_stmt_execute($pql)) {
    echo "register.php:mysqli_stmt_execute failed";
    die();
  } else {
    echo "register.php:mysqli_stmt_execute sucess";
  }

  $res = mysqli_stmt_get_result($pql);
  if(!$res) {
    echo "register.php:mysqli_stmt_get_result failed";
    die();
  } else {
    echo "register.php:mysqli_stmt_get_result success";
  }

  $row = mysqli_fetch_assoc($res);
  if(!$row) {
    echo "register.php:mysqli_fetch_assoc failed";
    die();
  } else {
    echo "register.php:mysqli_fetch_assoc success";
  }

  echo $row['STATE'];

?>
      <form action="token.php" method="post">
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
      <p>Context for your game here btw</p>
    </div>
  </div>

</body>

</html>
