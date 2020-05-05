<!DOCTYPE html>
<html lang="en" >

  <head>
    <meta charset="UTF-8">
    <title>XVK3 - Results</title>
    <link rel='stylesheet' href='http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css'>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
    <script src="/js/index.js"></script>
    <script>
    function secToHMS(seconds) {
      var hours = Math.floor(seconds / 60 / 60);
      var minutes = Math.floor(seconds / 60) - (hours * 60);
      var rseconds = Math.floor(seconds % 60);
      return hours.toString().padStart(2, '0') + ':' + minutes.toString().padStart(2, '0') + ':' + rseconds.toString().padStart(2, '0');
    }
    </script>
  </head>
  <body onload="parseResults()">
    <div class="vcent noselect">
      <div id="header"> <h1>XVK3<span>.NET</span></h1> </div>
      <div id="nav">
        <ul>
          <li><a href="register.php">Register</a></li>
          <li><a href="countdown.php">Countdown</a></li>
          <li><a href="results.php">Results</a></li>
        </ul>
      </div>
      <div id="results" class="results">
<?php
  // Database conn
  include("ghu_dbconnect.php");
  global $conn;

  // Check connection
  if ($conn) {

    // FP? (Final Period)
    $sql = "SELECT STATE,FP FROM META";
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

    $row = mysqli_fetch_assoc($res);
    if(!$row) {
      echo "results.php:mysqli_fetch_assoc failed\r\n";
      //die();
    } else {
      //echo "results.php:mysqli_fetch_assoc success\r\n";
    }

    if($row['STATE'] != 3) {
      echo "<div class=\"head\"> <h1> Results will be public on </h1> </div>";
      echo "<div class=\"hidden\">";
      echo "</div>\r\n";
      echo "<script>\r\n";
      echo "function tr() {\r\n";
      echo "  var n = new Date;\r\n";
      echo "  var t = new Date(" . $row['FP'] . "*1000);\r\n";
      echo "  if(t > n) d = (t - n);\r\n";
      echo "    document.getElementById(\"fpt\").innerHTML = t\r\n";
      echo "    document.getElementById(\"fpd\").innerHTML = secToHMS(d/1000);\r\n";
      echo "  }\r\n";
      echo "</script>\r\n";
      echo "<p id=\"fpt\">FINAL PERIOD TIME</p>\r\n";
      echo "<p id=\"fpd\">FINAL PERIOD TIME REMAINGING</p>\r\n";
    } else {

      echo "<div class=\"head\"> <h1> The results are in  </h1> </div>";
      echo "<div class=\"hidden\">";

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
        echo "</div>";
      } else {
        echo "results.php:mysqli_fetch_assoc failed\r\n";
        echo "nobody submitted a guess\r\n";
      }
    }
    mysqli_close($conn);
  }
?>
          <div class="container">
            <!--generate elements -->
          </div>
        </div>
      </div>
      <script>
      function parseResults() {
        var resultDataRaw = $("div.hidden").text();
        // If there are no guesses / not in FP
        if(resultDataRaw.length == 0) return tr();

        var resultData = $("div.hidden").text().split(',');

        var rawTokens = [];
        var rawGuesses = [];
        for(var i = 0; i < resultData.length; i++)  {
          rawTokens.push(resultData[i].split(':')[0]);
          rawGuesses.push(resultData[i].split(':')[1]);
        }
        // rawTokens = array of tokens ["T0", "T1", "T2"...
        // rawGuesses = array of guesses ["1", "3", "1"...
        //console.log(rawTokens);
        //console.log(rawGuesses);

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
      
          }
        }
        console.log(finalArray);
        console.log(finalArray[1].count);

        var highestUnique = undefined;
        // Build elements
        for(var i = finalArray.length-1; i >= 0; i--) {
          // var item = "<div class='result' id='result" + i + "' " +
          //            "tabindex='0' tooltip-position='bottom'>test</div>";
          var item = "<div class='result' id='result" + i + "'>" + finalArray[i].value + "</br>" + finalArray[i].count + "</div>";
  
          // Add element 
          $(".results .container").append(item);
          // Style element
          if(finalArray[i].count == 1) {
            if(highestUnique == undefined) {
              $("#result" + i).addClass("winner");
              highestUnique = finalArray[i].tokens[0];
              //console.log(highestUnique);
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
