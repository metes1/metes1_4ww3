<?php
  try { //first basic server side form validation
    //define form variables and set to empty values
    $firstName = "";
    $lastName = "";
    $email = "";
    $password = "";
    $passConfirm = "";
    $terms = null;

    //define error message variables and set to empty values
    $fnameErr = $lnameErr = $emailErr = $passErr = $passConfirmErr = $termsErr = "";

    // Boolean for keeping track if the form is error free
    $regErrors = false;
    // Keep track if email is in database already
    $emailUsed = false;

    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["regUser"])) { //if user clicks submit form

      // trim extra whitespace at the end of input, remove backslashes
      function clean_input($input) {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
      }

      //get form values
      $firstName = $_POST["fname"];
      $lastName = $_POST["lname"];
      $email = $_POST["email"];
      $password = $_POST["password"];
      $passConfirm = $_POST["passConfirm"];

      //form validation
      if (empty($firstName)) { //check if empty
        $fnameErr = "First name cannot be empty";
        $regErrors = true;
      } else { //if not empty, use test_input to remove any uneeded characters (extra space, ect. )
        $firstName = clean_input($firstName);
      }
      if (empty($lastName)) {
        $lnameErr = "Last name cannot be empty";
        $regErrors = true;
      } else {
        $lastName = clean_input($lastName);
      }
      if (empty($email)) {
        $emailErr = "Email cannot be empty";
        $regErrors = true;
      } else {
        $email = clean_input($email);
        //use php function to validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $emailErr = "Email format is incorrect";
          $regErrors = true;
        }
      }
      if (empty($password)) {
        $passErr = "Must enter a password";
        $regErrors = true;
      } else if (strlen($password) < 6) {
        $passErr = "Password must be at least 6 characters long";
        $regErrors = true;
      }
      if (empty($passConfirm)) {
        $passConfirmErr = "Please confirm your password";
        $regErrors = true;
      } else if (strcmp($password, $passConfirm) !== 0) { //compares strings to see if they are equivalent or, case-sensitive
        $passConfirmErr = "Passwords do not match";
        $regErrors = true;
      }
      if (!isset($_POST["terms"])) {
        $termsErr = "Must agree to the terms of service in order to create an account";
        $regErrors = true;
      } else {
        $terms = $_POST["terms"];
      }

      if (!$regErrors) { //if there are no inital form errors, connect and attempt to input data into db
        //db configurations
        require "config.php";
        try {
          $conn = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
          // set the PDO error mode to exception
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

          // Check if email already exists in database, account emails must be unique
          $stmt = $conn->prepare("SELECT * FROM `user` WHERE `email` = ?"); //prepare sql statement
          $stmt->execute([$email]);
          $result = $stmt->fetch();
          $stmt = null;

          if ($result) { //email exists in database
            $emailUsed = true;
          }
          if (!$emailUsed) { //if email doesn't already exist, we can input the new user
            $salt = bin2hex(random_bytes(20)); //salt password
            $regUserQuery = "INSERT INTO `user` (`email`, `firstName`, `lastName`, `salt`, `passHash`)
              VALUES (?, ?, ?, ?, SHA2(CONCAT(?, `salt`),0))";
            $stmt = $conn->prepare($regUserQuery);
            $stmt->execute([$email, $firstName, $lastName, $salt, $password]);
            $stmt = null;

            //clear form inputs if registration is successful
            $firstName = $lastName = $email = $password = $passConfirm = "";
            unset($terms); //uncheck checkbox
            //clear error messages
            $fnameErr = $lnameErr = $emailErr = $passErr = $passConfirmErr = $termsErr = "";
           //Display registration success message
            echo "<div id=regSuccess><b>Success:</b> Your account was created successfully!</div>";
          } else { // Display error message if email is used
              $emailErr = "Email already in use";
          }
          $conn = null; //close connection
        } catch (PDOException $e) { //catches any errors
          //Possible connection errors, terminate script
          die("Error!: " . $e->getMessage());
        }
      }
    }
  } catch (PDOException $e) { //catches any errors
    //Errors, terminate script
    die("Error!: " . $e->getMessage());
  }
?>