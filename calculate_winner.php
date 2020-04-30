<?php

  /*
  calculate_winner.php

  1. Sets META STATE = 3
  2. Sets META FP = FP + 7 days
  3. Calculates winning token
  4. Sends email to winner

 
  When this script runs on Thursday @ midnight:
  The current date $ct will be greater than $fp
    While the above is true add 7 days to $st
  Until $fp is greater than $ct
  $fp is now the end of the guessing period and the start of the final period
  */

  // Starting dates
  $sFP = "2020/04/10";

  echo "Final Period Starting at " . $sFP . "\r\n";

  $fp = strtotime($sFP);

  // Current Date
  $cd = time();

  // While "Registration Period" < "Current Date"
  while($fp < $cd) {
    $fp = $fp + 604800;
    echo "Moved to " . date('Y/m/d', $fp) . "\r\n";
  }

  echo "New Final Date = " . date('Y/m/d', $fp) . "\r\n";

  include("ghu_dbconnect.php");
  global $conn;
  if($conn) {

    // Update META
    $sql = "UPDATE META SET STATE=3, FP=$fp WHERE TRUE";
    mysqli_query($conn, $sql);

    // TODO
    // Update META with winning token and email
    // Email winner

  }
  mysqli_close($conn);

?>
