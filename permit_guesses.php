<?php

  /*
  permit_guesses.php

  1. Sets META STATE = 2
  2. Sets META RP = RP + 7 days
  3. Calculates NUMBER_OF_PARTICIPANTS
    a. Stores result in META
 
  When this script runs on Wednesday:
  The current date $ct will be greater than $st
    While the above is true add 7 days to $st
  Until $st is greater than $ct
  $st is now the start of the registration period
  */

  // Starting dates
  $sRP = "2020/04/06";

  echo "Registration Period Starting at " . $sRP . "\r\n";

  $rp = strtotime($sRP);

  // Current Date
  $cd = time();

  // While "Registration Period" < "Current Date"
  while($rp < $cd) {
    $rp = $rp + 604800;
    echo "Moved to " . date('Y/m/d', $rp) . "\r\n";
  }

  echo "New Registration Date = " . date('Y/m/d', $rp) . "\r\n";

  include("ghu_dbconnect.php");
  global $conn;
  if($conn) {

    // Update META
    $sql = "UPDATE META SET STATE=2, RP=$rp WHERE TRUE";
    mysqli_query($conn, $sql);


    // Determine number of participants
    $sql = "SELECT TOKEN FROM GHU WHERE TRUE";
    $pql = mysqli_prepare($conn, $sql);

    if(!mysqli_stmt_execute($pql)) {
      echo "permit_guesses.php:mysqli_stmt_execute failed\r\n";
      //die();
    } else {
      //echo "permit_guesses.php:mysqli_stmt_execute success\r\n";
    }

    $res = mysqli_stmt_get_result($pql);
    if(!$res) {
      echo "permit_guesses.php:mysqli_stmt_get_result failed\r\n";
      //die();
    } else {
      //echo "permit_guesses.php:mysqli_stmt_get_result success\r\n";
    }

    if($res->num_rows == 0) {
      echo "permit_guesses.php:mysqli_stmt_get_result $res failed\r\n";
      //die();
    } else {
      //echo "permit_guesses.php:mysqli_stmt_get_result $res success\r\n";
      $nr = $res->num_rows;
      $sql = "UPDATE META SET NOP=? WHERE TRUE";
      $pql = mysqli_prepare($conn, $sql);
      if(!mysqli_stmt_bind_param($pql, 's', $nr)) {
        echo "permit_guesses.php:mysqli_stmt_bind_param failed\r\n";
        //die();
      } else {
        //echo "permit_guesses.php:mysqli_stmt_bind_param success\r\n";
      }

      if(!mysqli_stmt_execute($pql)) {
        echo "permit_guesses.php:mysqli_stmt_execute failed\r\n";
        //die();
      } else {
        //echo "permit_guesses.php:mysqli_stmt_execute success\r\n";
      }
    }
  mysqli_close($conn);
  }
?>
