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
         <li><a href="register.php">Register</a></li>
         <li><a href="countdown.php">Countdown</a></li>
         <li><a href="results.php">Results</a></li>
      </ul>
    </div>
    <div class="token display">
  <?php 
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

        // Check if guess is in the valid range
        $sql = "SELECT STATE,GP,NOP FROM META";
        $pql = mysqli_prepare($conn, $sql);
        if(!mysqli_stmt_execute($pql)) {
          echo "guess.php:mysqli_stmt_execute failed\r\n";
          //die();
        } else {
          //echo "guess.php:mysqli_stmt_execute success\r\n";
        }

        $res = mysqli_stmt_get_result($pql);
        if(!$res) {
          echo "guess.php:mysqli_stmt_get_result failed\r\n";
          //die();
        } else {
          //echo "guess.php:mysqli_stmt_get_result success\r\n";
        }

        $row = mysqli_fetch_assoc($res);
        if(!$row) {
          echo "guess.php:mysqli_fetch_assoc failed\r\n";
          //die();
        } else {
          //echo "guess.php:mysqli_fetch_assoc success\r\n";
        }

        // Check to see if in GP and that the guess is valid
        if($row['STATE'] == 2 && $post_guess >= 1 && $post_guess <= $row['NOP']) {

          //Find participant by TOKEN
          $sql = "SELECT ID, TOKEN, GUESS FROM GHU WHERE TOKEN=?";
          $pql = mysqli_prepare($conn, $sql);
          if(!mysqli_stmt_bind_param($pql, 's', $post_token)) {
            echo "guess.php:mysqli_stmt_bind_param failed\r\n";
            //die();
          } else {
            //echo "guess.php:mysqli_stmt_bind_param success\r\n";
          }

          if(!mysqli_stmt_execute($pql)) {
            echo "guess.php:mysqli_stmt_execute failed\r\n";
            die();
          } else {
            if(!mysqli_stmt_bind_result($pql, $id, $token, $guess)) {
              echo "guess.php:mysqli_stmt_bind_result failed\r\n";
              //die();
            } else {
              $cycle = 0;
              while(mysqli_stmt_fetch($pql)) {
                echo "guess.php:mysqli_stmt_fetch success call " . $cycle . "\r\n";
                $cycle = $cycle + 1;
              }
              echo $token . "\r\n";
              echo $guess . "\r\n";
              if($guess) {
                echo "already guessed\r\n";
                die();
              } else {
                echo "not already guessed\r\n";

                $sql = "UPDATE GHU SET GUESS=? WHERE ID=?";
                $pql = mysqli_prepare($conn, $sql);
                if(!mysqli_stmt_bind_param($pql, 'ss', $post_guess, $id)) {
                  echo "guess.php:mysqli_stmt_bind_param failed\r\n";
                  //die();
                } else {
                  //echo "guess.php:mysqli_stmt_bind_param success\r\n";
                }

                if(!mysqli_stmt_execute($pql)) {
                  echo "guess.php:mysqli_stmt_execute failed\r\n";
                  //die();
                } else {
                  //echo "guess.php:mysqli_stmt_execute success\r\n";
                }

                if(!mysqli_affected_rows($conn)) {
                  echo "guess.php:mysqli_affected_rows failed/returned 0\r\n";
                  //die();
                } else {
                  echo "updated " . mysqli_affected_rows($conn) . " rows\r\n";
                }
              }
            }
          }
        }
      }
    
      //close the database
      mysqli_close($conn);
    }
  }

?>
    </div>
</div>
</div>


</body>

</html>
