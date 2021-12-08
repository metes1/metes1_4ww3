<?php 
  session_start();

  require "../s3_guide/vendor/autoload.php";
  use Aws\S3\S3Client;
  use Aws\S3\Exception\S3Exception;

  //inital data variables
  $storeData = "";
  $reviewData = "";
  $storeId = "";
  $presignedUrl = "";
  try {
    if (isset($_GET["storeid"])) { //only connect to database if store id and name variables are set
      $storeId = intval($_GET["storeid"]); //ensure it is int val for database search
      //db configurations
      require_once "config.php";
      //connect to database
      $conn = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      //get store data from db
      $stmt = $conn->prepare("SELECT * FROM `store` WHERE `id` = ?"); //prepare sql statement
      $stmt->execute([$storeId]);
      $storeData = $stmt->fetch(PDO::FETCH_ASSOC); //fetch results
      $stmt = null;

      //if storeid doesn't exist in database (no data was returned), redirect to home page as this is an invalid page
      if (!$storeData) { //if data array is empty
        header("Location: index.php");
        $conn = null;
        exit();
      }

      //get store reviews and associated user info for reviews, ordered by newest to oldest
      $reviewQuery = "SELECT R.`rating`, R.`reviewText`, U.`firstName`, U.`lastName`
        FROM `review` AS R LEFT JOIN `user` AS U ON R.`userId` = U.`id`
        WHERE R.`storeId` = ? ORDER BY R.`dateCreated` DESC";
      $stmt = $conn->prepare($reviewQuery);
      $stmt->execute([$storeId]);
      $reviewData = $stmt->fetchAll(PDO::FETCH_ASSOC); //fetch reviews

      //close the connection
      $conn = null;
    } else { //redirect to main homepage, storeid and is not set
      header("Location: index.php");
      exit();
    }
  } catch (PDOException $e) { //catches any errors
    //Errors terminate script
    die("Error!: " . $e->getMessage());
  }

  //only attempt to get image, if the store has an image on record
  if ($storeData["image"] != null or $storeData != "") {
    try {
      //db configurations
      require_once "s3conf.php";
      //get image name from database result
      $keyPath = $storeData["image"];

      // Instantiate an Amazon S3 client
      $s3Client = new S3Client(
        array(
          'version' => 'latest',
          'region'  => 'us-east-2',
          'credentials' => array(
          'key'    => $key,
          'secret' => $secret
          ),
        )
      );

      //get image
      $getImg = $s3Client->getCommand('GetObject', [
        'Bucket' => $bucketName,
        'Key'    => $keyPath
      ]);

      $request = $s3Client->createPresignedRequest($getImg, '+20 minutes');
      $presignedUrl = (string)$request->getUri();
    } catch (Exception $e){
      $_SESSION["store_msg"] = "Couldn't get store image.";
    }
  }
?>
<!DOCTYPE html>
<!-- The language code is English -->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Stylesheets for icons and star rating images -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

  <!-- CSS files used for this page -->
  <link rel="stylesheet" href="./css/style.css">
  <link rel="stylesheet" href="./css/individual_sample.css">

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

  <!-- The title that will show on the tab on the browser -->
  <title>Bookshopper | <?php echo $storeData["name"];?></title>

  <!-- Facebook’s Open Graph Protocol -->
  <meta property="og:title" content="<?php echo $storeData['name'];?>">
  <meta property="og:type" content="Website">
  <meta property="og:site_name" content="Bookshopper">
  <meta property="og:url" content="https://4ww3seda.live/bookshopper/individual_sample.php/?storeid=<?php echo $storeData['id'];?>">
  <?php if (!empty($presignedUrl)) {
      echo '<meta property="og:image" content="'.$presignedUrl.'">';
    }
  ?>
  <meta property="og:description" content="<?php echo $storeData['description'];?>">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary">
  <meta property="twitter:title" content="<?php echo $storeData['name'];?>">
  <meta property="twitter:description" content="<?php echo $storeData['description'];?>">
  <?php if (!empty($presignedUrl)) {
      echo '<meta property="twitter:image" content="'.$presignedUrl.'">';
    }
  ?>

  <!-- Configures website for iOS home screen -->
  <link rel="apple-touch-icon-precomposed" href="images/icon.png"/>
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="black" />
  <meta name="viewport" content="width = device-width, initial-scale = 1, minimum-scale = 1, maximum-scale = 5" />

  <!-- Configures website for Android home screen -->
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="theme-color" content="black">
</head>
<body onload="loadObjectMap(<?php echo $storeData['latitude'] . ', ' . $storeData['longitude'];?>)">
  <!-- Header/Navigation menu -->
  <?php include "menu.inc" ?>

  <!-- Main Content of individual object page -->
  <main>
    <div class="obj-container" itemscope itemtype="https://schema.org/BookStore">
      <h1 class="heading" itemprop="name"><?php echo $storeData["name"];?></h1>
      <hr>
      <!-- Images of the bookstore -->
      <div class="image-container">
        <?php if (!empty($presignedUrl)) {
            echo '<img class="mainimage" src="'.$presignedUrl.'" alt="'.$storeData['name'].'" itemprop="photo">';
          }
        ?>
      </div>

      <!-- Contains the description and overall star rating of bookstore -->
      <div class="obj-info">
        <div class="starrating" itemscope itemtype="https://schema.org/AggregateRating">
          <?php
            if ($storeData["rating"] != null) { //only display if an average rating exists
              echo $storeData["rating"]." ";
              echo '<meta itemprop="ratingCount" content=$storeData["rating"]';
            }
            //add star icons based on avg rating
            //round avg rating to nearest .5 or .0
            $roundRating = round($storeData["rating"]/0.5) * 0.5;
            //loop 5 times for displaying 5 stars
            for ($i = 0; $i < 5; $i++) {
              if ($roundRating == 0.0) { //add empty star
                echo '<span class="fa fa-star-o checked"></span>';
                echo "\r\n"; //puts a bit of space between the stars
              } else if ($roundRating >= 1.0) { //add full star
                echo "<span class='fa fa-star checked'></span>";
                echo "\r\n";
                $roundRating -= 1.0;
              } else { //add half star
                echo "<span class='fa fa-star-half-o checked'></span>";
                echo "\r\n";
                $roundRating -= 0.5;
              }
            }
            // display total number of reviews
            $reviewCount = count($reviewData);
            if (count($reviewData) == 1) {
              $reviewCount .= " Review";
            } else {
              $reviewCount .= " Reviews";
            }
            //links to review section on same page
            echo "<a href='#reviewSection' id='reviewsLink'>$reviewCount</a>";
            echo "<meta itemprop='reviewCount' content=$reviewCount>";
          ?>
        </div>
        <div itemprop="description">
          <p><strong>Decription: </strong><?php echo $storeData["description"]?></p>
        </div>
        <button id="reviewbtn">Write a Review</button>
        <!-- Write a Review Modal -->
        <div id="reviewModal">
          <div class="modal-content">
            <div class="modal-header">
              <span id="modal-close">&times;</span>
              <h3>Write a Review</h3>
            </div>
            <div class="modal-body">
              <?php
                // only logged in users can submit a review
                if (isset($_SESSION["loggedIn"])) {
                  //submit form to reviewSubmit script, for validation and database insertion
                  echo '<form method="post" action="reviewSubmit.php?storeid='.$storeData["id"].'">';
                  echo '<label for="modal-rating">Star rating: </label>';
                  echo '<select id="modal-rating" name="modal-rating" required>';
                  echo '<option hidden disabled selected value=""></option>';
                  echo '<option value="1">1</option>';
                  echo '<option value="2">2</option>';
                  echo '<option value="3">3</option>';
                  echo '<option value="4">4</option>';
                  echo '<option value="5">5</option>';
                  echo '</select>';
                  echo '<div class="error" id="error-rating"></div>';
                  echo '<br>';
                  echo '<label for="modal-review">Review: </label><br>';
                  echo '<textarea name="modal-review" placeholder="Write your review here (max 1000 characters)" maxlength="1000"></textarea>';
                  echo '<br>';
                  echo '<input type="submit" id="modal-submit" name="modal-submit" value="Post Review">';
                  echo '</form>';
                } else {
                  // message telling user to log in in order to submit review, link to log in page
                  echo '<p>You must log in to submit a review. <a href="login.php">Log in</a></p>';
                }
              ?>          
            </div>
          </div>
        </div>
        <hr>
      </div>

      <div class="maplocation">
        <h2>Location</h2>
        <!-- Section containing the map and address -->
        <div class="obj-map" itemscope itemtype="https://schema.org/Place">
          <!-- Leaflet Map -->
          <div id="map"></div>
        </div>
      </div>

      <!-- Review section -->
      <div id="reviewSection">
        <hr>
        <h2>Reviews</h2>
        <?php
          $arrLen = count($reviewData); //length of reviews array
          if ($arrLen == 0) { //if there are no reviews, display a message
            echo '<p id="noReviews">There are no reviews for this shop yet.</p>';
          }
          for ($i = 0; $i < $arrLen; $i++) {
            echo '<div class="review" itemscope itemtype="https://schema.org/Review">';
            echo '<p itemprop="author"><b>'.$reviewData[$i]["firstName"].' '.$reviewData[$i]["lastName"].'</b></p>';
            $starCount = $reviewData[$i]["rating"]; //keeps track of how many filled stars need to be added still
            for ($j = 0; $j < 5; $j++) {
              if ($starCount == 0) { //add empty star
                echo '<span class="fa fa-star"></span>';
                echo "\r\n"; //puts a bit of space between the stars
              } else { //add filled star
                echo '<span class="fa fa-star checked"></span>';
                echo "\r\n";
                $starCount -= 1;
              }
            }
            echo '<meta itemprop="reviewRating" content='.$reviewData[$i]["rating"].'>';
            echo '<p itemprop="reviewBody">'.$reviewData[$i]["reviewText"].'</p>';
            echo '</div>';
          }
        ?>
      </div>
    </div>
  </main>

  <!-- Footer of webpage -->
  <?php include "footer.inc" ?>

  <script>
    //open and close review modal
    var modal = document.getElementById("reviewModal");
    var btn = document.getElementById("reviewbtn");
    var span = document.getElementById("modal-close");

    //display modal when write a review button is clicked
    btn.onclick = function() {
      modal.style.display = "block";
    }
    
    //close modal when the x in the top corner is clicked
    span.onclick = function() {
      modal.style.display = "none";
    }

    //close modal when user clicks outside of the modal
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
  </script>
  <!-- Script for hamburger menu -->
  <script src="./js/hamburger.js"></script>
</body>
</html>