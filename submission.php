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
    <?php include("objSubmit.php"); ?>
    <div class="submission-container">
      <!-- Submission form, layed out in two columns (label to the left of input boxes) -->
      <!-- Informs user that fields marked with a * are required. Hidden from screen readers because they will instead
           read that a field is required using the required attribute in the input tag
      -->
      <p aria-hidden="true"><b>*</b> Required field</p>
      <form method="post" action="submission.php">
        <!-- Client-side form validation added using purely HTML5/CSS -->
        <div class="row">
          <div class="col-25">
            <!-- Prevents screen reader from reading the star in the label -->
            <label for="name">Name of Bookstore <b aria-hidden="true">*</b></label>
          </div>
          <div class="col-75">
            <!-- Input is required, form cannot be submitted without this value filled, validation performed automatically by browser -->
            <!-- Length of name restricted to maximum 100 characters -->
            <input type="text" id="name" name="name" value="<?php echo $objName;?>" placeholder="Enter the name of the bookstore (max 100 characters)" required maxlength="100">
            <div class="error" id="error-name"><?php echo $objnameErr;?></div>
          </div>
        </div>
        <div class="row">
          <div class="col-25">
            <label for="desc">Description</label>
          </div>
          <div class="col-75">
            <!-- Maximum number of characters allowed is 1000 -->
            <textarea id="desc" name="desc" value="<?php echo $objDesc;?>" placeholder="Enter a description for the bookstore (max 1000 characters)" maxlength="1000"></textarea>
            <div class="error" id="error-desc"><?php echo $objdescErr;?></div>
          </div>
        </div>
        <div class="row">
          <div class="col-25">
            <label for="lat">Latitude <b aria-hidden="true">*</b></label>
          </div>
          <div class="col-75">
            <!-- Coordinate input restricted to being numbers only (inlcuding decimal numbers), number range restricted -->
            <input type="number" id="lat" name="lat" value="<?php echo $objLat;?>" placeholder="Ex. 41.40338" required min="-90" max="90" step="any">
            <div class="error" id="error-lat"><?php echo $objlatErr;?></div>
          </div>
        </div>
        <div class="row">
          <div class="col-25">
            <label for="lon">Longitude <b aria-hidden="true">*</b></label>
          </div>
          <div class="col-75">
            <input type="number" id="lon" name="lon" value="<?php echo $objLon;?>" placeholder="Ex. 2.17403" required min="-180" max="180" step="any">
            <div class="error" id="error-lon"><?php echo $objlonErr;?></div>
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
            <input type="file" id="image" name="image" accept=".png, .jpg, .jpeg, .gif, .svg">
            <div class="error" id="error-image"><?php echo $objimageErr;?></div>
          </div>
        </div>
        <div class="row">
          <div class="col-25">
            <label for="video">Upload a Video</label>
          </div>
          <div class="col-75">
            <!-- Specify which types of files can be accepted -->
            <input type="file" id="video" name="video" accept=".mp4, .mov, .avi, .webm, .m4v">
            <div class="error" id="error-video"><?php echo $objvideoErr;?></div>
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