<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>XVK3 - Token</title>
  
  <link rel="stylesheet" href="/css/google_font_css.css">
  <link rel="stylesheet" href="/css/style.css">
      <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="js/index.js"></script>

  
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
    <div class="token display">
        <input id="token" type="text" name="input" value="<?php
        //this is going to be a fancy PHP script which fills in the token field on countdown.php
        //if it was accessed from token.php
    if($_SERVER['REQUEST_METHOD'] == 'POST')  {

        if(isset($_POST["token"]) && isset($_POST["guess"]))   {

            include("ghu_dbconnect.php");
            global $conn;
        
            //posted variables
            $post_token = $_POST["token"];
            $post_guess = $_POST["guess"];
    
            //check connection
            if ($conn) {

                //echo "   token:";
                //echo $post_token;

                //find user by TOKEN
                $sql = "SELECT ID, TOKEN, GUESS FROM GHU WHERE TOKEN = '$post_token'";
                
				$result = mysqli_query($conn, $sql);
				//echo mysqli_error($conn);
				//echo "    rows: ";
				//echo mysqli_num_rows($result);
				if(mysqli_num_rows($result) == 1)	{
				    
					//check if guess has been made already
				    if($row = mysqli_fetch_array($result)) {
				        //echo "   guess:";
				        //echo $row[2];
				        if($row[2] == NULL) {
				            
				            //$id = $row[0];
		    	    	    //echo "  id:";
		         		    //echo $id;
			        	    $sql = "UPDATE GHU SET GUESS='$post_guess' WHERE TOKEN='$post_token'";
				            $result = mysqli_query($conn, $sql);
				            if($result === TRUE)  {
			        	        echo "Successful Submission";
				            } else {
				                echo "Error: failed to update DB";
				            }
				        }   else {
				            echo "Error: already guessed a number";
				        }
				    }	else	{
					    echo "Error: invalid TOKEN";
				    }
				
			    //close the database
                mysqli_close($conn);	

                }   else {
                    echo "Error: ";
                }
            }   else {
                echo "Error: invalid post parameters";
            }
        }   else {
            echo "Error: not posted to token.php";
        }
    }

?>" readonly>
    </div>
</div>
</div>


</body>

</html>
