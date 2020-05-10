$(document).ready(function () {
  //globals
  var finalArray = [];

  $(".input").focusin(function() {
    $(this).find("span").animate({
      opacity: "0"
    }, 200);
  });
  $(".input").focusout(function() {
    $(this).find("span").animate({
      opacity: "1"
    }, 300);
  });

  function secToHMS(seconds) {
    var hours = Math.floor(seconds / 60 / 60);
    var minutes = Math.floor(seconds / 60) - hours * 60;
    var rseconds = Math.floor(seconds % 60);
    return (
      hours.toString().padStart(2, "0") +
      ":" +
      minutes.toString().padStart(2, "0") +
      ":" +
      rseconds.toString().padStart(2, "0")
    );
  }

  function onloadCSS() {
    $("#header span").css("font-size", "20px");
    $("#header span b").css("transform", "rotate(180deg)");
    $("#header h1, #header b").css("font-szie", "100px");
  }

  function tr() {
    var success = true;
    try {
      i = document.getElementById("phpi").innerHTML;
    } catch {
      success = false;
      parseResults();
    }
    if(success) {
      var n = new Date();
      var t = new Date(i * 1000);
      if (t > n) d = t - n;
      document.getElementById("td").innerHTML = t;
      document.getElementById("tr").innerHTML = secToHMS(d / 1000);
    }
  }

  function parseResults() {
    var resultDataRaw = $("div.hidden").text();
    // If there are no guesses / not in FP
    if (resultDataRaw.length == 0) return tr();
    var resultData = $("div.hidden").text().split(",");
    var rawTokens = [];
    var rawGuesses = [];
    for (var i = 0; i < resultData.length; i++) {
      rawTokens.push(resultData[i].split(":")[0]);
      rawGuesses.push(resultData[i].split(":")[1]);
    }

    var sortedTokens = rawTokens.slice(0);
    var sortedGuesses = rawGuesses.slice(0);
    sortedGuesses.sort(function (a, b) {
      return (
        rawGuesses[rawGuesses.indexOf(a)] - rawGuesses[rawGuesses.indexOf(b)]
      );
    });
    sortedTokens.sort(function (a, b) {
      return (
        rawGuesses[sortedTokens.indexOf(a)] -
        rawGuesses[sortedTokens.indexOf(b)]
      );
    });
    console.log(sortedGuesses);
    console.log(sortedTokens);

    var w = 1;
    var s = 0;
    // Build array of unique guesses
    for (var i = 0; i < sortedGuesses.length; i++) {
      //console.log("if(" + sortedGuesses[i] + " > " + s + ")");
      if (Number(sortedGuesses[i]) > Number(s)) {
        //console.log("entered do-while");
        var count = 0;
        var tokens = [];
        s = sortedGuesses[i];
        w = i;
        do {
          count++;
          //console.log(
          //  "updating count for " + sortedGuesses[i] + ", count = " + count
          //);
          tokens.push(sortedTokens[w]);
          w++;
        } while (sortedGuesses[i] == sortedGuesses[w]);
        var a = new Object();
        a.value = sortedGuesses[i];
        a.count = count;
        a.tokens = tokens;
        finalArray.push(a);
      } else {
        // Skip a duplicate guess
      }
    }
    //console.log(finalArray);
    //console.log(finalArray[1].count);
    var highestUnique = undefined;
    // Build elements
    for (var i = finalArray.length - 1; i >= 0; i--) {
      var item =
        "<div class='result' id='result" +
        i +
        "'>" +
        "<h2><span>" +
        finalArray[i].value +
        "</span></h2>" +
        "<p><span>" +
        finalArray[i].count +
        "</span></p>" +
        "</div>";
      // Add element
      $(".results .container").append(item);
      // Style element
      if (finalArray[i].count == 1) {
        if (highestUnique == undefined) {
          $("#result" + i).addClass("winner");
          highestUnique = finalArray[i].tokens[0];
          //console.log(highestUnique);
        } else {
          $("#result" + i).addClass("unique");
        }
      }
      if (finalArray[i].count > 1 && finalArray[i].count <= 2) {
        $(".result:last").addClass("rare");
      }
      if (finalArray[i].count > 2) {
        $("#result" + i).addClass("basic");
      }
    }
    postInject();
  }

  function postInject() {
    $(".result span").each(function () {
      var el = $(this);
      var textLength = el.html().length;

      if (textLength >= 3) {
        el.css("font-size", "1.25em");
      } else {
        el.css("font-size", "1.75em");
      }
    });

    var specifiedElement = document.getElementById("results");
    //toggle info-bar
    document.addEventListener("click", function (event) {
      var isClickInside = specifiedElement.contains(event.target);
      if (isClickInside) {
        $("#info-footer").addClass("expanded");
      } else {
        $("#info-footer").removeClass("expanded");
      }
    });
  }



  $("#results").on("click", ".result", function () {
    var number = $(this).find("span").html().toString();
    var results = [];
    var reduced = [];
    //filter function returns all objects which match the corresponding property (.value)
    results = finalArray.filter(function (a) {
      return a.value === number;
    });
    reduced = results.map(a => a.tokens);
    
    if (number > -1) {
      //.value is unique thus no iteration required
      $("#token-placement p").text(reduced.toString().replace(/,/g, '<br>'));
    } else {
      $("#token-placement p").text("error finding tokens..");
    }
  });

  onloadCSS();
  //parseResults();
  //postInject();
  tr();
}); //document ready
