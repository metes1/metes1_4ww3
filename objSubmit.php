<?php
  try { //server side validation, a fallback if client side validation doesn't work
    //define form variables and set to empty values
    $objName = "";
    $objDesc = "";
    $objLat = "";
    $objLon = "";
    $objImage = "";
    $objVideo = "";

    //define error message variables and set to empty values
    $objnameErr = $objdescErr = $objlatErr = $objlonErr = $objimageErr = $objvideoErr = "";

    // Boolean for keeping track if the form is error free
    $objErrors = false;

    if (isset($_POST["objectSubmit"])) { //if user clicks submit form
      //get form values
      $objName = $_POST["name"];
      $objDesc = $_POST["desc"];
      $objLat = $_POST["lat"];
      $objLon = $_POST["lon"];
      //DO image and video later

      //form validation
      // if (empty($objName)) { //check if empty
      //   $objnameErr = "Name cannot be empty";
      //   $errors = true;
      // } else if (strlen($objName) > 100) { //name can't be greater than 100 characters
      //   $objnameErr = "Name is too long, maximum length is 100 characters";
      //   $errors = true;
      // } else { //if not empty, use test_input to remove any uneeded characters (extra space, ect. )
      //   //$objName = test_input($objName);
      // }
      // if (strlen($objDesc) > 1000) {
      //   $objdescErr = "Description too long, maximum length is 1000 characters";
      //   $errors = true;
      // } else {
      //   //$objDesc = test_input($objDesc);
      // }
      // if (empty($objLat)) {
      //   $objlatErr = "Latitude cannot be empty";
      // } 
    }
  } catch (PDOException $e) { //catches any errors
    //Errors, terminate script
    die("Error!: " . $e->getMessage());
  }
?>