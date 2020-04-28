<?php

  /*
  permit_registration.php

  1. Sets META MAIN = 1
  2. Sets META STATE = 1
 
  */

  include("ghu_dbconnect.php");
  global $conn;
  if($conn) {

    $sql = "UPDATE META SET MAIN=1, STATE=1, WHERE TRUE";
    mysqli_query($conn, $sql);

    mysqli_close($conn);
    die();
  }
?>
