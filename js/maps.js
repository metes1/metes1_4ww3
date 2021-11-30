// Functions for embedding live maps onto both the results page and the individual object page
// Data is hardcoded in for now

// Initialize the map
// Code slightly modified from https://leafletjs.com/examples/quick-start/

// Initializes a map with the given inputs
function initMap(mapName, lat, lon, zoom) {
  //Create map and set the view to specific coordinates and zoom level
  var myMap = new L.map(mapName).setView([lat, lon], zoom);

  // Add tile layer
  L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1Ijoia2VsZWJlayIsImEiOiJja3ZvYzVoNzUwdGpjMm9tbjk1bXA1bXBuIn0.Wgugm8oLgeHuOT1u4KllVw', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 20,
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1,
    accessToken: 'pk.eyJ1Ijoia2VsZWJlayIsImEiOiJja3ZvYzVoNzUwdGpjMm9tbjk1bXA1bXBuIn0.Wgugm8oLgeHuOT1u4KllVw'
  }).addTo(myMap);

  return myMap;
}

// Create a map for results page
// Information hardcoded for now
function loadResultsMap(data) {
  var resultsMap;
  console.log(data);
  if (data.length == 0) { // no data
    // Initialize map with default values, if data is empty
    resultsMap = initMap("map", 43.25743, -79.87747, 10);
  } else {
    // Initialize map with first result value, this will be the center of map
    resultsMap = initMap("map", data[0]["latitude"], data[0]["longitude"], 13);
    for (i=0; i < data.length; i++) {
      let marker1 = L.marker([data[i]["latitude"], data[i]["longitude"]]).addTo(resultsMap);
      let link = "<a href=\"./individual_sample.php?storeid=" + data[i]["id"] + "\"><b>" + data[i]["name"] + "</b></a>";
      marker1.bindPopup(link);
    }
  }
}

// Create a map for the individual object page
function loadObjectMap(lati, long) {
  var objectMap = initMap("map", lati, long, 15);

  // Add  marker
  var marker = L.marker([lati, long]).addTo(objectMap);
}