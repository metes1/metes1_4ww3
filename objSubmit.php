<?php
  session_start();

  //if a logged in user submits the submission form
  if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION["loggedIn"]) && isset($_POST["objectSubmit"])) {
    // Boolean for keeping track if the form is error free
    $errors = false;

    //get form values, trim any excess spaces
    $objName = trim($_POST["name"]);
    $objDesc = trim($_POST["desc"]);
    $objLat = $_POST["lat"];
    $objLon = $_POST["lon"];
    //DO image and video later

    //Save error messages in this session array
    $_SESSION["uploadErrs"] = array();

    //form validation
    if (empty($objName)) { //check if empty
      $_SESSION["uploadErrs"]["name"] = "Name cannot be empty";
      $errors = true;
    } else if (strlen($objName) > 100) { //name can't be greater than 100 characters
       $_SESSION["uploadErrs"]["name"] = "Name is too long, maximum length is 100 characters";
       $errors = true;
    }
    if (strlen($objDesc) > 1000) { //description can't be more than 1000 characters'
      $_SESSION["uploadErrs"]["desc"] = "Description too long, maximum length is 1000 characters";
      $errors = true;
    }
    if (empty($objLat)) {
      $_SESSION["uploadErrs"]["lat"] = "Latitude cannot be empty";
      $errors = true;
    } else if (!preg_match("/^[-]?(([0-8]?[0-9])\.([0-9]+)|90\.0+)$/", $objLat)) {
      //checks if latitude is in proper format and within the proper range
      $_SESSION["uploadErrs"]["lat"] = "Invalid format";
      $errors = true;
    }
    if (empty($objLon)) {
      $_SESSION["uploadErrs"]["lon"] = "Longitude cannot be empty";
      $errors = true;
    } else if (!preg_match("/^[-]?(([1-9]?[0-9]\.[0-9]+)|(1[0-7][0-9]\.[0-9]+)|(180\.0+))$/", $objLon)) {
       //checks if longitude is in proper format and within the proper range
      $_SESSION["uploadErrs"]["lon"] = "Invalid format";
      $errors = true;
    }

    if (!$errors) { //if error free, insert into db
      //db configurations
      require_once "config.php";
      try {
        //connect to database
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //Insert new store into database
        $stmt = $conn->prepare("INSERT INTO `store` (`name`, `description`, `latitude`, `longitude`) VALUES (?,?,?,?)");
        $stmt->execute([$objName, $objDesc, $objLat, $objLon]);
        $stmt = null;
        $conn = null; //close connection

        //there were no errors so we can unset the session variable for it
        unset($_SESSION["uploadErrs"]);
        $_SESSION["uploadSuccess"] = True; //to display success message on page
        //redirects to submission page, with success message
        header("Location: submission.php");
         exit();
      } catch (PDOException $e) { //catches any errors
        //Possible connection errors, terminate script
        die("Error!: " . $e->getMessage());
      }
    
    } else { //errors, submission failed
      //Save form values in session to prefill form
      $_SESSION["uploadVals"] = array();
      $_SESSION["uploadVals"]["name"] = $objName;
      $_SESSION["uploadVals"]["desc"] = $objDesc;
      $_SESSION["uploadVals"]["lat"] = $objLat;
      $_SESSION["uploadVals"]["lon"] = $objLon;

      //redirect back to submission page
      header("Location: submission.php");
      exit();
    }
  } else { //invalid access to script
    header("Location: submission.php");
    exit();
  }
?>