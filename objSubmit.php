<?php
  // For file upload to s3: https://www.tutsmake.com/upload-file-to-aws-s3-bucket-in-php/
  session_start();

  require "../s3_guide/vendor/autoload.php";
  use Aws\S3\S3Client;
  use Aws\S3\Exception\S3Exception;

  //if a logged in user submits the submission form
  if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION["loggedIn"]) && isset($_POST["objectSubmit"])) {

    // Boolean for keeping track if the form is error free
    $errors = false;

    //get form values, trim any excess spaces
    $objName = trim($_POST["name"]);
    $objDesc = trim($_POST["desc"]);
    $objLat = $_POST["lat"];
    $objLon = $_POST["lon"];

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

    //initialize vars
    $allowed = "";
    $filename = "";
    $filetype = "";
    $filesize = "";

    //check if files were uploaded properly
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
      $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png");
      $filename = $_FILES["image"]["name"];
      $filetype = $_FILES["image"]["type"];
      $filesize = $_FILES["image"]["size"];
      // Validate file extension
      $ext = pathinfo($filename, PATHINFO_EXTENSION);
      if(!array_key_exists($ext, $allowed)) {
        $_SESSION["uploadErrs"]["image1"] = "Please select a valid file format.";
        $errors = true;
      }
      // Validate file size - 10MB maximum
      $maxsize = 10 * 1024 * 1024;
      if($filesize > $maxsize) {
        $_SESSION["uploadErrs"]["image2"] = " File size is larger than the allowed limit.";
        $errors = true;
      }
    }

    //if there are no errors int form inputs and file uploads
    //upload files to s3 and insert data into db
    if (!$errors) { 
      //db configurations
      require_once "config.php";
      //s3 configurations
      require_once "s3conf.php";
      //keyname wil be null in db if file is not uploaded
      $keyName = null;
      
      //if a file was uploaded and there are no errors, add it to bucket
      //this is not a required field, so we have to check it
      if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        try { //connect to AWS S3
          // Instantiate an Amazon S3 client
          $s3Client = new S3Client(
            array(
              'version' => 'latest',
              'region'  => 'us-east-2',
              'credentials' => array(
                'key'    => $key,
                'secret' => $secret
              )
            )
          );

          //generate a random name by combining a unique ID based on the microtime and 
          // the MD5 hash of the original file name, this is to ensure we don't overwrite an existing file
          $keyName = 'images/'.uniqid().md5(basename($filename)).'.'.$ext;

          //upload the image to s3
          try {
            $s3Client->putObject([
              'Bucket' => $bucketName,
              'Key'    => $keyName,
              'SourceFile'   => $_FILES["image"]["tmp_name"]
            ]);
          } catch(Exception $e) {
            $_SESSION["upload_err"] = "Store failed to upload, connection or server error";
            header("Location: submission.php");
            exit();
          }

        } catch(Exception $e) {
          $_SESSION["upload_err"] = "Store failed to upload, connection or server error";
          header("Location: submission.php");
          exit();
        }
      }

      try {
        //connect to database
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //Insert new store into database
        $stmt = $conn->prepare("INSERT INTO `store` (`name`, `description`, `latitude`, `longitude`, `image`) VALUES (?,?,?,?,?)");
        $stmt->execute([$objName, $objDesc, $objLat, $objLon, $keyName]);
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
        $_SESSION["upload_err"] = "Store failed to upload, connection or server error";
        header("Location: submission.php");
        exit();
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