<?php 
  session_start();
  //logged in users don't have access to login page, since they're already logged in
  if (isset($_SESSION["loggedIn"])) {
    //redirect to main page
    header("Location: index.php");
    exit();
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <link rel="stylesheet" href="./css/style.css">
  <link rel="stylesheet" href="./css/login.css">
  <title>Bookshopper - Login</title>
</head>
<body>
  <!-- Header/Navigation menu -->
  <?php include "menu.inc" ?>

  <!-- login form -->
  <main>
    <div class="login-container  animate__animated animate__fadeIn">
      <h2>Log in to Bookshopper</h2>
      <hr>
      <?php
        //declare error variables and set initial values
        $logEmail = "";
        $logEmailErr = "";
        $logPassErr = "";
        $logErrors = false;

        //checks if login button was clicked
        if (isset($_POST["login"])) {
          if (isset($_POST["email"])) {
            $logEmail = trim($_POST["email"]);
          }
          //server side form validation, a fallback to client side validation
          if (empty($logEmail)) {
            $logEmailErr = "Please enter your email";
            $logErrors = true;
          } else if (!filter_var($logEmail, FILTER_VALIDATE_EMAIL)){ //use php function to validate email
            $logEmailErr = "Email format is incorrect";
            $logErrors = true;
          }
          if (!isset($_POST["password"]) || empty($_POST["password"])) {
            $logPassErr = "Please enter your password";
            $logErrors = true;
          }

          if (!$logErrors) { //if there are no errors, connect to database
            //db configurations
            require "config.php";
            try {
              //connect to database
              $conn = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
              // set the PDO error mode to exception
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

              //check email and password in database
              $stmt = $conn->prepare("SELECT `id`, `email`, `firstName`, `lastName` FROM `user` WHERE `email` = ? AND `passHash` = SHA2(CONCAT(?, `salt`),0)");
              $stmt->execute([$logEmail, $_POST["password"]]);
              $result = $stmt->fetch(PDO::FETCH_ASSOC);

              if ($result) { //user matching that email exists and password is correct
                //Successful login saves user's email, id, and name in session
                $_SESSION["userId"] = $result["id"];
                $_SESSION["username"] = $result["email"];
                $_SESSION["firstName"] = $result["firstName"];
                $_SESSION["firstName"] = $result["firstName"];
                $_SESSION["loggedIn"] = True;
                // directs logged in user to search page
                header("Location: search.php");
                exit(); //terminate rest of script
              } else { //login failed, error message
                echo "<div id=loginFail>The email address or password you entered is incorrect.</div>";
              }
              $conn = null; //close connection
            } catch (PDOException $e) { //catches any errors
              //Connection failed or other errors, terminate script
              die("Error!: " . $e->getMessage());
            }
          }
        }
      ?>
      <!-- Log in form  -->
      <form name="logForm" method="post" action="login.php">
        <div class="formItem">
        <div class="formItem">
          <label for="email">Email</label>
          <input type="email" placeholder="Enter your email address" id="email" name="email" value="<?php echo htmlspecialchars($logEmail);?>" required>
          <div class="error" id="error-email"><?php echo $logEmailErr;?></div>
        </div>
        <div class="formItem">
          <label for="password">Password</label>
          <input type="password" placeholder="Enter your password" name="password" id="password" placeholder="Password" required>
          <div class="error" id="error-password"><?php echo $logPassErr;?></div>
        </div>
        <input name="login" type="submit" value="Log In">
      </form>
      <!-- Links to sign up page -->
      <p>New to Bookshopper? <a href="registration.php">Sign up</a></p>
    </div>
  </main>

  <!-- Footer of webpage -->
  <?php include "footer.inc" ?>

  <!-- Script for hamburger menu -->
  <script src="./js/hamburger.js"></script>
</body>
</html>