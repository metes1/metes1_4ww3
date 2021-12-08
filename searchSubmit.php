<?php 
  //searches database, based on search query submitted by search form

  //define search variables and set as empty;
  $star = $search = $lat = $lon = "";
  //variable will hold search result array
  $searchResults = array();
  $resultsJson = "";
  try {
    //db configurations
    require_once "config.php";
    //connect to database using configurations
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //if search value is set, store in variable
    if (isset($_GET["star"])) {
      $star = intval($_GET["star"]); //change to int
    }
    if (isset($_GET["search"])) {
      $search = $_GET["search"];
      //clean up search form data
      $search = trim($search); //trim any excess white space
      $search = stripslashes($search); //remove backslashes
    }
    if (isset($_GET["lat"])) {
      $lat = floatval($_GET["lat"]); //change value type to float
    }
    if (isset($_GET["lon"])) {
      $lon = floatval($_GET["lon"]);
    }

    $selects = "SELECT S.`id`, S.`name`, S.`latitude`, S.`longitude`, S.`rating`, COUNT(R.`id`) AS reviewCount"; 
    $tables = " FROM `store` AS S LEFT JOIN review AS R ON S.`id` = R.`storeId`";
    $searchVals = ""; //keep track of all search conditions for the where clause
    $endQuery = " GROUP BY S.`id`"; //add ons to the end of query
    $valArr = array(); //to hold all input values for prepared statement

    if (!empty($lat) and !empty($lon)) { // only select stores within a certain distance from the user
      //uses haversine formula
      $selects .= ", ( 6371 * acos( cos(radians( ? )) * cos(radians(s.`latitude`)) * cos(radians(s.`longitude`)
       - radians( ? )) + sin(radians( ? )) * sin(radians(s.`latitude`)) ) ) AS distance";
      $endQuery .= " HAVING distance < 30 ORDER BY distance";
      array_push($valArr, $lat, $lon, $lat); //push values in array
    }
    if (empty($star) or $star < 0 or $star > 5) {
      $star = 0; //default star to 0 if value empty or out of bounds
    }
    $searchVals .= " WHERE S.`rating` >= ?";
    array_push($valArr, (int)$star);
    if (!empty($search)) {
      //search for any stores that have the searched name in it
      $searchVals .= " AND S.`name` LIKE CONCAT( '%',?,'%')";
      array_push($valArr, $search);
    }

    //add values to search query and add group by to count # of reviews properly
    $searchQuery = $selects.$tables.$searchVals.$endQuery;
    $stmt = $conn->prepare($searchQuery);
    $stmt->execute($valArr);
    $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC); //fetch results
    //search results in json format, so it can be passed on to js map function
    $resultsJson = json_encode($searchResults); 

  } catch (PDOException $e) { //catches any errors
    //Errors, terminate script
    die("Error!: " . $e->getMessage());
  }
  //close the connection
  $conn = null;
?>