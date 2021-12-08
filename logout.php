<?php
  session_start();
  //destroy all data associated with the current session
  session_destroy();

  //redirect to login page after logout
  header("Location: login.php");
?>