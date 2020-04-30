<!DOCTYPE html>
<html lang="en" >

  <head>
    <meta charset="UTF-8">
    <title>XVK3 - Results</title>
    <link rel='stylesheet' href='http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css'>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
  </head>
  <body onload="parseResults()">
    <div class="hidden"><?php

  // Database conn
  include("ghu_dbconnect.php");
  global $conn;

  // Check connection
  if ($conn) {
        
    // Find all entries in GHU with valid TOKEN & GUESS
    $sql = "SELECT GUESS,TOKEN FROM GHU WHERE TOKEN IS NOT NULL AND GUESS IS NOT NULL";
    $pql = mysqli_prepare($conn, $sql);

    if(!mysqli_stmt_execute($pql)) {
      echo "results.php:mysqli_stmt_execute failed\r\n";
      //die();
    } else {
      //echo "results.php:mysqli_stmt_execute success\r\n";
    }

    $res = mysqli_stmt_get_result($pql);
    if(!$res) {
      echo "results.php:mysqli_stmt_get_result failed\r\n";
      //die();
    } else {
      //echo "results.php:mysqli_stmt_get_result success\r\n";
    }
    $nr = $res->num_rows;

    //Build array for JS functon
    if($row = mysqli_fetch_assoc($res)) {
      echo $row['TOKEN'] . ":" . $row['GUESS'];
      while($row = mysqli_fetch_assoc($res)) {
        echo "," . $row['TOKEN'] . ":" . $row['GUESS']; 
      }
    } else {
      echo "results.php:mysqli_fetch_assoc failed\r\n";
      echo "nobody submitted a guess\r\n";
    }
  }
    mysqli_close($conn);
?></div>
    <div class="vcent noselect">
      <div id="header">
        <h1>XVK3<span>.NET</span></h1>
      </div>
      <div id="results" class="results">      
        <div class="head"> <h1> The Results Are In </h1> </div>
          <div class="container">
            <!--generate elements -->
          </div>
        </div>
      </div>
      <script>
      function parseResults() {
        var resultData = $("div.hidden").text().split(',');

        var rawTokens = [];
        var rawGuesses = [];
        for(var i = 0; i < resultData.length; i++)  {
          rawTokens.push(resultData[i].split(':')[0]);
          rawGuesses.push(resultData[i].split(':')[1]);
        }
        // rawTokens = array of tokens ["T0", "T1", "T2"...
        // rawGuesses = array of guesses ["1", "3", "1"...
        console.log(rawTokens);
        console.log(rawGuesses);

        // Duplicate both arrays
        var sortedTokens = rawTokens.slice(0);
        var sortedGuesses = rawGuesses.slice(0);

        sortedGuesses.sort(function(a, b) {
          return rawGuesses[rawGuesses.indexOf(a)] - rawGuesses[rawGuesses.indexOf(b)];
        });

        sortedTokens.sort(function(a, b) {
          return rawGuesses[sortedTokens.indexOf(a)] - rawGuesses[sortedTokens.indexOf(b)];
        });

        
        console.log(sortedGuesses);
        console.log(sortedTokens);

        var finalArray = [];

        // Build array of unique guesses
        for(var i = 0; i < sortedGuesses.length; i++) {

          if(i == 0 || sortedGuesses[i-1] < sortedGuesses[i])  {
            var count = 1;
            var tokens = [];

// Is the "for-if" better than the "while" implementation?
/*
      for(var w = i + 1; w < sortedGuesses.length; w++) {
        if(sortedGuesses[i] == sortedGuesses[w]) {
          count++;
          tokens.push(sortedTokens[w]);
        }
*/
            var w = i + 1;
            tokens.push(sortedTokens[i]);
            while(sortedGuesses[i] == sortedGuesses[w]) {
              count++;
              tokens.push(sortedTokens[w]);
              w++;
            }
            var a = new Object();
            a.value = sortedGuesses[i];
            a.count = count;
            a.tokens = tokens;
            finalArray.push(a);
      
          } else {
            // Skip a duplicate guess
          }

        }
        console.log(finalArray);
        console.log(finalArray[1].count);

        var highestUnique = undefined;
        // Build elements
        for(var i = finalArray.length-1; i >= 0; i--) {
          // var item = "<div class='result' id='result" + i + "' " +
          //            "tabindex='0' tooltip-position='bottom'>test</div>";
          var item = "<div class='result' id='result" + i + "'>" + finalArray[i].value + " guessed " + finalArray[i].count + " times</div>";
  
          // Add element 
          $(".results .container").append(item);
          // Style element
          if(finalArray[i].count == 1) {
            if(highestUnique == undefined) {
              $("#result" + i).addClass("winner");
              highestUnique = finalArray[i].tokens[0];
              console.log(highestUnique);
            } else {
              $("#result" + i).addClass("unique");
            }
          }
          if(finalArray[i].count > 1 && finalArray[i].count <= 2) {
              $(".result:last").addClass("rare");
          }
          if(finalArray[i].count > 2) {
            $("#result" + i).addClass("basic");
          }
        }
      }
    </script>  
  </body>
</html>
