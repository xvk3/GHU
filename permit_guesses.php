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

    $sql = "UPDATE META STATE=2, RP=$rp WHERE TRUE";
    mysqli_query($conn, $sql);

    mysqli_close($conn);
    die();
  }
?>
