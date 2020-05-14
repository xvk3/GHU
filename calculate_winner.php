<?php

  /*
  calculate_winner.php

  1. Sets META STATE = 3
  2. Sets META FP = FP + 7 days
  3. Calculates winning token
  4. Sends email to winner

 
  When this script runs on Thursday @ midnight:
  The current date $cd will be greater than $fp
    While the above is true add 7 days to $st
  Until $fp is greater than $cd
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
    // TODO change the above to use prepared statements
    
    $sql = "SELECT ID,GUESS,TOKEN FROM GHU WHERE TRUE";
    $pql = mysqli_prepare($conn, $sql);
    if(!mysqli_stmt_execute($pql)) {
      echo "calculate_winner.php:mysqli_stmt_execute failed\r\n";
      //die();
    } else {
      //echo "calculate_winner.php:mysqli_stmt_execute success\r\n";
    }

    $res = mysqli_stmt_get_result($pql);
    if(!$res) {
      echo "calculate_winner.php:mysqli_stmt_get_result failed\r\n";
      //die();
    } else {
      //echo "calculate_winner.php:mysqli_stmt_get_result success\r\n";
    }
    $nr = $res->num_rows;

    // Generate CSV of results (guess, token)
    $filename = "/home/xvk3/public_html/hghu/" . date('Ymd', time()) . ".csv";
    $file = fopen($filename, 'w');
    $arr = array();
    if($nr > 0) {
      while($row = mysqli_fetch_assoc($res)) {
        fwrite($file, $row['TOKEN'] . "," . $row['GUESS'] . "\r\n");
        $arr[] = $row['GUESS'];
      }
      fclose($file);
    } else {
      echo "calculate_winner.php:num_rows <= 0\r\n";
    }

    //var_dump($arr);
    rsort($arr);
    //var_dump($arr);

    $highestUnique = 0;
    $skipGuess = 0;
    // Loop over the sorted array (highest guesses first)
    for($i = 0; $i < count($arr); $i++) {
      // Make sure we're not at the end of the array, also as soon as highestUnique is set we can exit the loop
      if($i != (count($arr)-1) && $highestUnique == 0) {
        //echo "i = " . $i . ", arr[i] = " . $arr[$i] . ", arr[i+1] = " . $arr[$i+1] . "\r\n";
        // Skip duplicate guesses
        if($arr[$i] == $arr[$i+1]) $skipGuess = $arr[$i];
        if($skipGuess != $arr[$i]) {
          if($arr[$i] > $highestUnique && $arr[$i] != $arr[$i+1]) {
            //echo "setting highestUnique = " . $highestUnique . ", to = " . $arr[$i] . "\r\n";
	    $highestUnique = $arr[$i];
          }
        }
      }
    }

    echo "Highest Unique = " . $highestUnique . "\r\n";

    // Find participant with the highestUnique guess
    $sql = "SELECT NAME,EMAIL,TOKEN FROM GHU WHERE GUESS=?";
    $pql = mysqli_prepare($conn, $sql);
    if(!mysqli_stmt_bind_param($pql, 's', $highestUnique)) {
      echo "calculate_winner.php:mysqli_stmt_bind_param failed\r\n";
      //die();
    } else {
      //echo "calculate_winner.php:mysqli_stmt_bind_param success\r\n";
    }

    if(!mysqli_stmt_execute($pql)) {
      echo "calculate_winner.php:mysqli_stmt_execute failed\r\n";
      //die();
    } else {
      //echo "calculate_winner.php:mysqli_stmt_execute success\r\n";
    }

    $res = mysqli_stmt_get_result($pql);
    if(!$res) {
      echo "calculate_winner.php:mysqli_stmt_get_result failed\r\n";
      //die();
    } else {
      //echo "calculate_winner.php:mysqli_stmt_get_result success\r\n";
    }

    if($res->num_rows == 0) {
      echo "calculate_winner.php:num_rows == 0\r\n";
      //die();
    } else {
      //echo "calculate_winner.php:num_rows > 0\r\n";
    }
    
    while($row = mysqli_fetch_assoc($res)) {
      echo $row['NAME'] . "\r\n";
      echo $row['EMAIL'] . "\r\n";
      echo $row['TOKEN'] . "\r\n";
    }

    // Update Historical GHU Table with winner's token, guess and results CSV file
    $sql = "INSERT INTO HGHU (WTOKEN, WGUESS, CSV) VALUES (?, ?, ?)";
    $pql = mysqli_prepare($conn, $sql);
    
    if(!mysqli_stmt_bind_param($pql, 'sss', $row['TOKEN'], $highestUnique, $filename)) {
      echo "calculate_winner:mysqli_stmt_bind_param failed\r\n";
      //die();
    } else {
      //echo "calculate_winner:mysqli_stmt_bind_param success\r\n";
    }

    if(!mysqli_stmt_execute($pql)) {
      echo "calculate_winner.php:mysqli_stmt_execute failed\r\n";
      //die();
    } else {
      //echo "calculate_winner.php:mysqli_stmt_execute success\r\n";
    }

    $res = mysqli_stmt_get_result($pql);
    if(!$res) {
      echo "calculate_winner.php:mysqli_stmt_get_result failed\r\n";
      //die();
    } else {
      //echo "calculate_winner.php:mysqli_stmt_get_result success\r\n";
    }

    if($res->num_rows == 0) {
      echo "calculate_winner.php:res->num_rows == 0\r\n";
      //die();
    } else {
      //echo "calculate_winner.php:mysqli_stmt_get_result $res success\r\n";
      echo "calculate_winner.php " . $row['TOKEN'] . "\r\n";
    }

    // Email winner
  
  }
  mysqli_close($conn);

?>
