<?php
  //review validation and insertion into database

  session_start();

  //if review form was submitted by a logged in user
  if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION["loggedIn"]) && isset($_POST["modal-submit"])) {
    //declare variables and set initial values
    $reviewRating = "";
    $reviewText = "";
    $ratingErr = "";
    $modalErrors = false;

    //get form values
    if (isset($_POST["modal-rating"])) {
      $reviewRating = intval($_POST["modal-rating"]);
    }
    if (isset($_POST["modal-review"])) { //clean up input
      $reviewText = trim($_POST["modal-review"]); //trim any excess space
      $reviewText = stripslashes($reviewText);
      $reviewText = htmlspecialchars($reviewText);
    }

    //validate form entries
    if (empty($reviewRating)) { //a rating is a required input
      $ratingErr = "Please select a rating";
      $modalErrors = true;
    } else if (!is_int($reviewRating) or $reviewRating < 1 or $reviewRating > 5){
      //input must be an int between 1-5
      $ratingErr = "Rating must be a number between 1 and 5";
      $modalErrors = true;
    }
    if (strlen($reviewText) > 1000) { //exceeds character limit
      $modalErrors = true;
    } else if (empty($reviewText)) {
      $reviewText = null; //empty review text is null in database
    }
    if (!$modalErrors) { //if no errors proceed to connect and input into database
      //db configurations
      require_once "config.php";
      try {
        //connect to database
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //Insert review into database
        $stmt = $conn->prepare("INSERT INTO `review` (`userId`, `storeId`, `rating`, `reviewText`) VALUES (?,?,?,?)");
        $stmt->execute([$_SESSION["userId"], $_GET["storeid"], $reviewRating, $reviewText]);
        $stmt = null;

        //update the average rating for the store
        $updateQuery = "UPDATE `store` s INNER JOIN 
        (SELECT `storeid`, ROUND(AVG(`rating`),1) AS avgRating FROM `review` WHERE `storeid` = ?) r ON s.`id` = r.`storeid`
        SET s.`rating` = r.avgRating WHERE s.id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->execute([$_GET["storeid"], $_GET["storeid"]]);
        $stmt = null;

        //clear form variables
        $reviewRating = $reviewText = $ratingErr = "";

        $conn = null; //close connection

        //redirects to store page review section, so user can see successful submission
        header("Location: individual_sample.php?storeid=".$_GET['storeid']."#reviewSection");
         exit();
      } catch (PDOException $e) { //catches any errors
        //Possible connection errors, terminate script
        die("Error!: " . $e->getMessage());
      }
    } else { //there are errors with form input
      header("Location: individual_sample.php?storeid=".$_GET['storeid']); //redirect to store page
      exit();
    }
  } else { //invalid access to this script
    header("Location: index.php"); //redirect to home
    exit();
  }
?>