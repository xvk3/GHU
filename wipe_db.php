<?php

  /*
  1. Wipes the GHU database
  2. Sets META MAIN = 0
  3. Sets META STATE = 0
  4. Sets RP to the next Monday
 
  When this script runs on Sunday ~ 10pm:
  The current date $ct will be greater than $st
    While the above is true add 7 days to $st
  Until $st is greater than $ct
  $st is now the start of the registration period
  */
  $st = "2020/04/06";
  echo "Starting at " . $st . "\r\n";
  $st =  strtotime($st);
  echo $st . "\r\n";

  $ct = time();
  echo $ct . "\r\n";
  //echo date('r', $ct) . "\r\n";
  echo "enter while\r\n";
  while($ct > $st) {
    $st = $st + 604800;
    echo "Moved to " . date('Y/m/d', $st) . "\r\n";
  }
  echo $st . "\r\n";
  echo $ct . "\r\n";

  include("ghu_dbconnect.php");
  global $conn;
  if($conn) {

    $sql = "DELETE FROM GHU WHERE 1";
    mysqli_query($conn, $sql);

    $sql = "UPDATE META SET MAIN=0, STATE=0, RP=$st WHERE TRUE";
    mysqli_query($conn, $sql);

    mysqli_close($conn);
    die();
  }
?>
