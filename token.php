
<!DOCTYPE html>
<html lang="en" >

<head>
    <meta charset="UTF-8">
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
         <li><a href="register.html">Register</a></li>
         <li><a href="countdown.php">Countdown</a></li>
         <li><a href="results.php">Results</a></li>
      </ul>
    </div>
    <div class="token select">
      <input id="token" type="text" name="input" value="<?php   

  if($_SERVER['REQUEST_METHOD'] == 'POST')  {
    
    //posted variables
    $post_name = $_POST["name"];
    $post_email = $_POST["email"];

    include("ghu_dbconnect.php");
    global $conn;
    
    //check connection
    if ($conn) {
      
      //check to see if email already exists
      $sql = "SELECT * FROM GHU WHERE EMAIL = '$post_email'";
      $result = mysqli_query($conn, $sql);
      if (mysqli_num_rows($result) == 0) {
      
        //insert name and email into database
        $sql = "INSERT INTO GHU (NAME, EMAIL)
        VALUES ('{$post_name}', '{$post_email}')";
        $result = mysqli_query($conn, $sql);
        if($result === TRUE)  {
        
          regenerate:
          //generate 10 bytes of random data - for token
          $length = 10;
          $crypto_secure = 1;
          $bytes = openssl_random_pseudo_bytes($length, $crypto_secure);
          $hex   = bin2hex($bytes);
          
          //check for duplicate TOKEN
          
          //update database with TOKEN
          $sql = "UPDATE GHU SET TOKEN='$hex' WHERE EMAIL='$post_email'";
          $result = mysqli_query($conn, $sql);
          if($result === TRUE)  {
            
            echo $hex;
                                
          } else  { echo "Error: unable to update record with token"; }
        } else {  echo "Error: unable to insert NAME and EMAIL into database";  }
      } else {  echo "Error: that email address is already registered"; }
      
      //close the database
      mysqli_close($conn);
            
    } else {  echo "Error: unable to connect to database";  }
  } else "Error: Not POSTED to token.html";
  ?>" readonly>
      <button id="copy" class="btn btn-primary btn-block btn-large" tooltip="Copy to Clipboard" tooltip-position="right"><i class="fa fa-copy"></i></button>
        
    </div>
    <div id="context">
      <p>Guess Highest Unique Number Registration Form</p>
    </div>

  </div>


</body>

</html>
