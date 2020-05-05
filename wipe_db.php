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

  // Starting dates
  $sRP = "2020/04/06";
  $sGP = "2020/04/08";
  $sFP = "2020/04/10";

  echo "Registration Period Starting at " . $sRP . "\r\n";
  echo "Guessing     Period Starting at " . $sGP . "\r\n";
  echo "Final        Period Starting at " . $sFP . "\r\n";

  $rp = strtotime($sRP);
  $gp = strtotime($sGP);
  $fp = strtotime($sFP);

  // Current Date
  $cd = time();
  
  // While "Registration Period" < "Current Date"
  while($rp < $cd) {
    $rp = $rp + 604800;
    echo "RP moved to " . date('Y/m/d', $rp) . "\r\n";
  }

  // while "Guessing Period" < "Current Date"
  while($gp < $cd) {
    $gp = $gp + 604800;
    echo "GP moved to " . date('Y/m/d', $gp) . "\r\n";
  }

  // While "Final Period" < "Current Date"
  while($fp < $cd) {
    $fp = $fp + 604800;
    echo "FP moved to " . date('Y/m/d', $fp) . "\r\n";
 }

  echo "New Registration Date = " . date('Y/m/d', $rp) . "\r\n";
  echo "New Guessing     Date = " . date('Y/m/d', $gp) . "\r\n";
  echo "New Results      Date = " . date('Y/m/d', $fp) . "\r\n";

  include("ghu_dbconnect.php");
  global $conn;
  if($conn) {

    $sql = "DELETE FROM GHU WHERE 1";
    mysqli_query($conn, $sql);

    $sql = "UPDATE META SET MAIN=0, STATE=0, RP=$rp, GP=$gp, FP=$fp  WHERE TRUE";
    mysqli_query($conn, $sql);

    mysqli_close($conn);
    die();
  }
?>
