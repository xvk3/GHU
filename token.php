<?php

// Generate TOKEN
function generateToken($conn, $post_email, $row_id) {
  //generate 10 bytes of random data - for token
  $length = 10;
  $crypto_secure = 1;
  $bytes = openssl_random_pseudo_bytes($length, $crypto_secure);
  $hex   = bin2hex($bytes);
         
  //check for duplicate TOKEN
  $sql = "SELECT ID FROM GHU WHERE TOKEN=?";
  $pql = mysqli_prepare($conn, $sql);
  if(!mysqli_stmt_bind_param($pql, 's', $hex)) {
    echo "token.php:mysqli_stmt_bind_param failed\r\n";
    //die();
  } else {
    //echo "token.php:mysqli_stmt_bind_param success\r\n";
  }

  if(!mysqli_stmt_execute($pql)) {
    echo "token.php:mysqli_stmt_execute failed\r\n";
    //die();
  } else {
    //echo "token.php:mysqli_stmt_execute failed\r\n";
  }

  $res = mysqli_stmt_get_result($pql);
  if(!$res) {
    echo "token.php:mysqli_stmt_get_result failed\r\n";
    //die();
  } else {
    //echo "token.php:mysqli_stmt_get_result success\r\n";
  }

  if($res->num_rows != 0) {
    generateToken($conn, $post_email);
  } else {
  
    //update database with TOKEN
    $sql = "UPDATE GHU SET TOKEN=? WHERE EMAIL=?";
    $pql = mysqli_prepare($conn, $sql);
    if(!mysqli_stmt_bind_param($pql, 'ss', $hex, $post_email)) {
      echo "token.php:mysqli_stmt_bind_param failed\r\n";
      //die();
    } else {
      //echo "token.php:mysqli_stmt_bind_param success\r\n";
    }

    if(!mysqli_stmt_execute($pql)) {
      echo "token.php:mysqli_stmt_execute failed\r\n";
      //die();
    } else {
      //echo "token.php:mysqli_stmt_execute success\r\n";
    }

    $row_id2 = mysqli_insert_id($conn);
    if($row_id == $row_id2) {
      //echo "match\r\n";
      echo $hex;
    }
                                
  }
}
?>

<!DOCTYPE html>
<html lang="en" >

<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no"/>
    <title>XVK3 - Post</title>
  
    <link rel="stylesheet" href="/css/google_font_css.css">
    <link rel='stylesheet' href='http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css'>
    <link rel="stylesheet" href="/css/style.css">
    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/index.js"></script>
  
</head>

<body>

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
    <div class="token select">
      <input id="token" type="text" name="input" value="<?php   

  include("ghu_dbconnect.php");
  global $conn;
  
  $sql = "SELECT STATE,RP FROM META";
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

  if(($row['STATE'] == 1) && ($_SERVER['REQUEST_METHOD'] == 'POST')) {

    //posted variables
    $post_name = $_POST["name"];
    $post_email = $_POST["email"];

    //$post_name = "hello";
    //$post_email = "email@gg.com" . rand(1, 10000);

    //echo $post_name . "\r\n";
    //echo $post_email . "\r\n";

    //check connection
    if ($conn) {
      //check to see if email already exists
      $sql = "SELECT * FROM GHU WHERE EMAIL = ?";
      $pql = mysqli_prepare($conn, $sql);
      if(!mysqli_stmt_bind_param($pql, 's', $post_email)) {
        echo "token.php:mysqli_stmt_bind_param failed\r\n";
        //die();
      } else {
        //echo "token.php:mysqli_stmt_bind_param success\r\n";
      }
      
      if(!mysqli_stmt_execute($pql)) {
        echo "token.php:mysqli_stmt_execute failed\r\n";
        //die();
      } else {
        //echo "token.php:mysqli_stmt_execute success\r\n";
      }

      $res = mysqli_stmt_get_result($pql);
      if(!$res) {
        echo "token.php:mysqli_stmt_get_result failed\r\n";
        //die();
      } else {
        //echo "token.php:mysqli_stmt_get_result success\r\n";
      }
      if($res->num_rows != 0) {
        echo "token.php:Email already registered\r\n";
        //die();
      } else {
        //echo "token.php:Email not already registered\r\n";
      }
      
      // Insert name and email into database
      $sql = "INSERT INTO GHU (NAME, EMAIL) VALUES (?,?)";
      $pql = mysqli_prepare($conn, $sql);
      if(!mysqli_stmt_bind_param($pql, 'ss', $post_name, $post_email)) {
        echo "token.php:mysqli_stmt_bind_param failed\r\n";
        //die();
      } else {
        //echo "token.php:mysqli_stmt_bind_param success\r\n";
      }

      if(!mysqli_stmt_execute($pql)) {
        echo "token.php:mysqli_stmt_execute failed\r\n";
        //die();
      } else {
        //echo "token.php:mysqli_stmt_execute success\r\n";
      }
      // Get the ID of the INSERTED row
      $row_id = mysqli_insert_id($conn);
      //echo $row_id;
      generateToken($conn, $post_email, $row_id); 
      
    } 
    //close the database
    mysqli_close($conn);
  } else { header('Location: http://www.xvk3.net/register.php');
            
  ?>" readonly>
      <button id="copy" class="btn btn-primary btn-block btn-large" tooltip="Copy to Clipboard" tooltip-position="right"><i class="fa fa-copy"></i></button>
        
    </div>
    <div id="context">
      <p>Guess Highest Unique Number Token Generator</p>
    </div>

  </div>


</body>

</html>
