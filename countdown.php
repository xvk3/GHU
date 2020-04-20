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
  //Count down
  var countDownDate = <?php
  include("ghu_dbconnect.php");
  global $conn;
	
	//check connection
	if ($conn) {
		
		$sql = "SELECT GUE FROM META WHERE 1";
		$result = mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($result);
		if($row["GUE"] == 1)    {
		    //guessing is allowed
		}   else {
		    //echo
		    
		    
		}
	
			
		//close the database
		mysqli_close($conn);
			
	}
			
			
    ?>;
    var x = setInterval(function() {
    var now = new Date().getTime();
    var distance = countDownDate - now;
    var days = Math.floor(distance / (1000 * 60 * 60 * 24)).toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false});
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false});
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)).toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false});
    var seconds = Math.floor((distance % (1000 * 60)) / 1000).toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping:false});

    document.getElementById("countdown-timer").innerHTML =
        days + ":" + hours + ":" + minutes + ":" + seconds + "";

    if (distance < 0) {
        clearInterval(x);
        document.getElementById("countdown-timer").innerHTML = "ACTIVE";
    }
    }, 1000);
  </script>
  
  
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

    <div class="submission">
      <form action="guess.php" method="post">
        <div class="input" >
          <input type="name" name="token" placeholder="<?php
          
          if($_SERVER['REQUEST_METHOD'] == 'POST')  {
              
            if(isset($_POST["token"]))  {
                
                include("ghu_dbconnect.php");
                global $conn;

                //posted variables
                $post_token = $_POST["token"];
                
                //check connection
                if ($conn) {
      
                    //check to see if email already exists
                    $sql = "SELECT TOKEN FROM GHU WHERE TOKEN = '$post_token'";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_array($result);
                    echo $row["TOKEN"];
                }
                
            }
          } else {
              echo "token";
          }
          
?>" required="required" />
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
      <p>Guess submission active in</p> <!-- dynamic status box -->
    </div>
  </div>

</body>

</html>
