<?php 
  session_start();
  //logged in users don't have access to registration page
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
  <link rel="stylesheet" href="./css/registration.css">
  <script src="./js/registration.js"></script>
  <title>Bookshopper - Sign Up</title>
</head>
<body>
  <!-- Header/Navigation menu -->
  <?php include("menu.inc") ?>

  <!-- Registration form -->
  <main>
    <div class="signup-container">
      <h2>Sign up for Bookshopper</h2>
      <hr>
      <!-- All inputs are required, user cannot proceed without filling out all
        input boxes and checking the checkbox -->
      <!-- Disable HTML5/CSS automatic validation, will use javascript and php instead -->
      <!-- If javascript is turned off php will validate as a fall back -->
      <!-- Send submitted form data to the same page -->
      <?php include("regSubmit.php"); ?>
      <form name="regForm" onsubmit="return validateRegistration(this)" method="post" action="registration.php" novalidate>
        <!-- Form displays errors and prefilled form, uses htmlspecialchars converts predefined characters to HTML entities -->
        <!-- htmlspecialchars prevents users from entering values that change the html on the page -->
        <div class="formItem">
          <label for="fname">First Name</label>
          <input type="text" placeholder="Enter your first name" id="fname" name="fname" value="<?php echo htmlspecialchars($firstName);?>">
          <div class="error" id="error-fname"><?php echo htmlspecialchars($fnameErr);?></div>
        </div>
        <div class="formItem">
          <label for="lname">Last Name</label>
          <input type="text" placeholder="Enter your last name" id="lname" name="lname" value="<?php echo htmlspecialchars($lastName);?>">
          <div class="error" id="error-lname"><?php echo htmlspecialchars($lnameErr);?></div>
        </div>
        <div class="formItem">
          <label for="email">Email</label>
          <input type="email" placeholder="Enter your email address" id="email" name="email" value="<?php echo htmlspecialchars($email);?>">
          <div class="error" id="error-email"><?php echo htmlspecialchars($emailErr);?></div>
        </div>
        <div class="formItem">
          <label for="password">Password</label>
          <input type="password" placeholder="Enter your password (must be at least 6 characters)" name="password" id="password" placeholder="Password" value="<?php echo htmlspecialchars($password);?>">
          <div class="error" id="error-password"><?php echo htmlspecialchars($passErr);?></div>
        </div>
        <div class="formItem">
          <label for="passConfirm">Confirm Password</label>
          <input type="password" placeholder="Re-enter your password" name="passConfirm" id="passConfirm" placeholder="Confirm Password" value="<?php echo htmlspecialchars($passConfirm);?>">
          <div class="error" id="error-passConfirm"><?php echo htmlspecialchars($passConfirmErr);?></div>
        </div>
        <div id="checkTerms">
          <!-- If checkbox was checked, stay checked (if errors in php valivation, values entered remain) -->
          <input type="checkbox" name="terms" id="terms" value="terms" <?php if (isset($terms)) echo "checked";?> >
          <label for="terms">I agree to the terms of service</label>
          <div class="error" id="error-terms"><?php echo htmlspecialchars($termsErr);?></div>
        </div>
        <input type="submit" value="Sign Up" name="regUser">
      </form>
      <!-- Links to login page -->
      <p>Already have an account? <a href="login.php">Log In</a></p>
    </div>
  </main>

  <!-- Footer of webpage -->
  <?php include "footer.inc" ?>
  
  <!-- Script for hamburger menu -->
  <script src="./js/hamburger.js"></script>
</body>
</html>