<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Stylesheets -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <link rel="stylesheet" href="./css/style.css">
  <link rel="stylesheet" href="./css/search.css">

  <!-- Scripts -->
  <script src="./js/geolocation.js"></script>

  <title>Bookshopper - Search</title>
</head>
<body>
  <!-- Header/Navigation menu -->
  <?php include "menu.inc" ?>
  
  <!-- Main content section for Search -->
  <main>
    <div class="search-container">
      <h2>Search for bookstores</h2>
      <hr>
      <!-- When submitted, redirected to results page, results_sample.php -->
      <form class="search animate__animated animate__fadeIn" method="get" action="results_sample.php">
        <!-- Star rating search drop down-->
        <select id="star" name="star">
          <option value="0">Any rating</option>
          <option value="1">1+ star</option>
          <option value="2">2+ star</option>
          <option value="3">3+ star</option>
          <option value="4">4+ star</option>
          <option value="5">5 star</option>
        </select>
        <!-- Search bar -->
        <input type="text" placeholder="Search by name..." id="search" name="search">
        <!-- Search form submit button -->
        <button type="submit"><i class="material-icons md-36">search</i></button>
        <div id="searchByLoc">
          <!-- When button is clicked, user's location is retrieved (if allowed)-->
          <button type="button" onclick="getLocation()" id="getLocBtn">
            <i class="material-icons md-24">location_on</i>Use My Location
          </button>
          <!-- Read only input boxes, to solely displays the user's coordinates
               that will be used in the search results.
          -->
          <input type="number" id="lat" name="lat" placeholder="Latitude" readonly>
          <input type="number" id="lon" name="lon" placeholder="Longitude" readonly>
        </div>
      </form>
    </div>
  </main>

  <!-- Footer of webpage -->
  <?php include "footer.inc" ?>
  
  <!-- Script for hamburger menu -->
  <script src="./js/hamburger.js"></script>
</body>
</html>