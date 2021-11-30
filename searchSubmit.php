<?php 
  //db configurations
  require "config.php";
  //define search variables and set as empty;
  $star = $search = $lat = $lon = "";
  //variable will hold search result array
  $searchResults = array();
  $resultsJson = "";
  try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //if search value is set, store in variable
    if (isset($_GET["star"])) {
      $star = $_GET["star"];
    }
    if (isset($_GET["search"])) {
      $search = $_GET["search"];
    }
    if (isset($_GET["lat"])) {
      $lat = $_GET["lat"];
    }
    if (isset($_GET["lon"])) {
      $lon = $_GET["lon"];
    }

    $searchQuery = "SELECT S.id, S.name, S.latitude, S.longitude, S.rating, COUNT(R.id) AS reviewCount FROM store AS S LEFT JOIN review AS R ON S.id = R.storeId WHERE ";
    $searchVals = ""; //keep track of all search elements
    if (empty($star)) {
      $star = 0; //default star to 0 if value empty
    }
    $valArr = array((int)$star); //values for prepared stmt, ensures value is of type int
    $searchVals .= "S.rating >= ?";
    if (!empty($search)) {
      if (!empty($searchVals)) { //add comma if searchVals is not empty
        $searchVals .= " AND ";
      }
      $searchVals .= "S.name LIKE CONCAT( '%',?,'%')"; //search for any store that has the searched name in it
      array_push($valArr, $search);
    }

    //add values to search query and add group by to count # of reviews properly
    $searchQuery .= $searchVals."GROUP BY S.id;";
    $stmt = $conn->prepare($searchQuery);
    $stmt->execute($valArr);
    $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC); //fetch results
    //search results in json format, so it can be passed on to js map function
    $resultsJson = json_encode($searchResults); 
    //still need do search my location
   // if (!empty($lat)) {
    //   if (!empty($searchVals)) { $searchVals .= ", "}
    //   $searchVals .= "lat=?";
    // }
    // if (!empty($lon)) {
    //   if (!empty($searchVals)) { $searchVals .= ", AND "}
    //   $searchVals .= "lon=?";
    // }
  } catch (PDOException $e) { //catches any errors
    //Errors terminate script
    die("Error!: " . $e->getMessage());
  }
  //close the connection
  $conn = null;
?>