<?php 
  session_start();
  //Get results based on the search query, dynamically generate the results page-->
  include("searchSubmit.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <link rel="stylesheet" href="./css/style.css">
  <link rel="stylesheet" href="./css/results_sample.css">

  <!-- Leaflet CSS file -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>

  <!-- Leaflet JavaScript file -->
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>

   <!-- Map script-->
   <script src="./js/maps.js"></script>

  <title>Bookshopper - Search results</title>
</head>
<body onload='loadResultsMap(<?php echo $resultsJson;?>)'>
  <!-- Header/Navigation menu -->
  <?php include "menu.inc" ?>

  <!-- Search form -->
  <main>
    <div class="results-container animate__animated animate__fadeIn">
      <div class="wrapper">
        <h2>Results</h2>
        <hr>
        <!-- Container for list of results -->
        <div class="tabular-results">
          <?php
            $arrLen = count($searchResults); //length of results array
            if ($arrLen == 0) { //if there are no results, display a message
              echo '<p id="noResults">No results found</p>';
            }
            //for every result, create a card for that result
            for ($i = 0; $i < $arrLen; $i++) {
              echo '<a href="individual_sample.php?storeid='.$searchResults[$i]["id"].'">';
              echo '<div class="result-card">';
              echo '<div class= "resultinfo">';
              echo '<h3>'.($i + 1).'. '.$searchResults[$i]["name"].'</h3>';
              echo '<span>'.$searchResults[$i]["rating"].'  </span>';
              //add star icons based on avg rating
              //round avg rating to nearest .5 or .0
              $roundRating = round($searchResults[$i]["rating"]/0.5) * 0.5;
              //loop 5 times for displaying 5 stars
              for ($j = 0; $j < 5; $j++) {
                if ($roundRating == 0.0) { //add empty star
                  echo '<span class="fa fa-star-o checked"></span>';
                  echo "\r\n"; //puts a bit of space between the stars
                } else if ($roundRating >= 1.0) { //add full star
                  echo '<span class="fa fa-star checked"></span>';
                  echo "\r\n";
                  $roundRating -= 1.0;
                } else { //add half star
                  echo '<span class="fa fa-star-half-o checked"></span>';
                  echo "\r\n";
                  $roundRating -= 0.5;
                }
              }
              //Display the total number of reviews
              if ($searchResults[$i]["reviewCount"] == 1) {
                echo "<span>{$searchResults[$i]['reviewCount']} Review</span>";
              } else {
                echo "<span>{$searchResults[$i]['reviewCount']} Reviews</span>";
              }
              echo '</div>';
              echo '</div>';
              echo '</a>';
            }
          ?>
        </div>
        <div class="map-results">
          <!-- Embedded live map -->
          <div id="map"></div>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer of webpage -->
  <?php include "footer.inc" ?>
  
  <!-- Script for hamburger menu -->
  <script src="./js/hamburger.js"></script>
</body>
</html>