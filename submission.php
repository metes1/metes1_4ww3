<?php
  session_start();
  //only logged in users have access to submission page
  if (!isset($_SESSION["loggedIn"])) {
    //redirect to login page
    header("Location: login.php");
    exit();
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Stylesheets -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <link rel="stylesheet" href="./css/style.css">
  <link rel="stylesheet" href="./css/submission.css">

  <!-- Scripts -->
  <script src="./js/geolocation.js"></script>

  <title>Bookshopper - Submit a Location</title>
</head>
<body>
  <!-- Header/Navigation menu -->
  <?php include "menu.inc" ?>

  <!-- Main content section for submission -->
  <main>
    <h2>Submit a New Bookstore Location</h2>
    <hr>
    <?php
      //display a success message if session indicates a store was successfully submitted
      if (isset($_SESSION["uploadSuccess"])) {
        echo "<div id=uploadSuccess><b>Success:</b>The store has successfully been submitted to Bookshopper!</div>";
        unset($_SESSION["uploadSuccess"]);
      }
      //display error message, if there was a connection or some other server error
      if (isset($_SESSION["upload_err"])) {
        echo "<div id=uploadFailure><b>Error:</b>The store has successfully been submitted to Bookshopper!</div>";
        unset($_SESSION["uploadSuccess"];
      }
      //set inital form values to empty or get prefilled values from session (if they exist)
      $objName = (isset($_SESSION["uploadVals"]["name"]) ? $_SESSION["uploadVals"]["name"] : "");
      $objDesc = (isset($_SESSION["uploadVals"]["desc"]) ? $_SESSION["uploadVals"]["desc"] : "");
      $objLat = (isset($_SESSION["uploadVals"]["lat"]) ? $_SESSION["uploadVals"]["lat"] : "");
      $objLon = (isset($_SESSION["uploadVals"]["lon"]) ? $_SESSION["uploadVals"]["lon"] : "");

      //get errors from session data if they exist, save to variables to display in form
      $objnameErr = (isset($_SESSION["uploadErrs"]["name"]) ? $_SESSION["uploadErrs"]["name"] : "");
      $objdescErr = (isset($_SESSION["uploadErrs"]["desc"]) ? $_SESSION["uploadErrs"]["desc"] : "");
      $objlatErr = (isset($_SESSION["uploadErrs"]["lat"]) ? $_SESSION["uploadErrs"]["lat"] : "");
      $objlonErr = (isset($_SESSION["uploadErrs"]["lon"]) ? $_SESSION["uploadErrs"]["lon"] : "");
      $objImageErr1 = (isset($_SESSION["uploadErrs"]["image1"]) ? $_SESSION["uploadErrs"]["image1"] : "");
      $objImageErr2 = (isset($_SESSION["uploadErrs"]["image2"]) ? $_SESSION["uploadErrs"]["image2"] : "");

      //After session values are saved in variables, unset the session variables
      if (isset($_SESSION["uploadVals"])) unset($_SESSION["uploadVals"]);
      if (isset($_SESSION["uploadErrs"])) unset($_SESSION["uploadErrs"]);
    ?>
    <div class="submission-container">
      <!-- Submission form, layed out in two columns (label to the left of input boxes) -->
      <!-- Informs user that fields marked with a * are required. Hidden from screen readers because they will instead
           read that a field is required using the required attribute in the input tag
      -->
      <p aria-hidden="true"><b>*</b> Required field</p>
      <form method="post" action="objSubmit.php" enctype="multipart/form-data">
        <!-- Client-side form validation added using HTML5/CSS -->
        <!-- PHP validation is used as a fallback if client side doesn't work -->
        <div class="row">
          <div class="col-25">
            <!-- Prevents screen reader from reading the star in the label -->
            <label for="name">Name of Bookstore <b aria-hidden="true">*</b></label>
          </div>
          <div class="col-75">
            <!-- Input is required, form cannot be submitted without this value filled, validation performed automatically by browser -->
            <!-- Length of name restricted to maximum 100 characters -->
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($objName);?>" placeholder="Enter the name of the bookstore (max 100 characters)" required maxlength="100">
            <div class="error" id="error-name"><?php echo htmlspecialchars($objnameErr);?></div>
          </div>
        </div>
        <div class="row">
          <div class="col-25">
            <label for="desc">Description</label>
          </div>
          <div class="col-75">
            <!-- Maximum number of characters allowed is 1000 -->
            <textarea id="desc" name="desc" placeholder="Enter a description for the bookstore (max 1000 characters)" maxlength="1000"><?php echo htmlspecialchars($objDesc);?></textarea>
            <div class="error" id="error-desc"><?php echo htmlspecialchars($objdescErr);?></div>
          </div>
        </div>
        <div class="row">
          <div class="col-25">
            <label for="lat">Latitude <b aria-hidden="true">*</b></label>
          </div>
          <div class="col-75">
            <!-- Coordinate input restricted to being numbers only (inlcuding decimal numbers), number range restricted -->
            <input type="number" id="lat" name="lat" value="<?php echo htmlspecialchars($objLat);?>" placeholder="Ex. 41.40338" required min="-90" max="90" step="any">
            <div class="error" id="error-lat"><?php echo htmlspecialchars($objlatErr);?></div>
          </div>
        </div>
        <div class="row">
          <div class="col-25">
            <label for="lon">Longitude <b aria-hidden="true">*</b></label>
          </div>
          <div class="col-75">
            <input type="number" id="lon" name="lon" value="<?php echo htmlspecialchars($objLon);?>" placeholder="Ex. 2.17403" required min="-180" max="180" step="any">
            <div class="error" id="error-lon"><?php echo htmlspecialchars($objlonErr);?></div>
          </div>
        </div>
        <!-- Row for Use Current Location button -->
        <div class="row">
          <!-- No label needed for this row -->
          <div class="col-25">
          </div>
          <div class="col-75">
            <button type="button" onclick="getLocation()" id="getLocBtn">
              <i class="material-icons md-24">location_on</i>Use Current Location
            </button>
          </div>
        </div>
        <div class="row">
          <div class="col-25">
            <label for="image">Upload an Image</label>
          </div>
          <div class="col-75">
            <!-- Specify which types of files can be accepted -->
            <input type="file" id="image" name="image" accept="image/png, image/jpg, image/jpeg">
            <div class="error" id="error-image"><?php echo htmlspecialchars($objImageErr1.$objImageErr2);?></div>
          </div>
        </div>
        <div class="row">
          <input name="objectSubmit" type="submit" value="Submit">
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