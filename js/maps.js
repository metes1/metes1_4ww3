// Functions for embedding live maps onto both the results page and the individual object page

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
function loadResultsMap(data) {
  var resultsMap;

  if (data.length == 0) { // no data
    // Initialize map with default values, if data is empty
    resultsMap = initMap("map", 43.25743, -79.87747, 13);
  } else {
    // Initialize map with first store result , as the center point
    resultsMap = initMap("map", data[0]["latitude"], data[0]["longitude"], 12);
    for (i=0; i < data.length; i++) { //add a marker for each location
      let marker1 = L.marker([parseFloat(data[i]["latitude"]), parseFloat(data[i]["longitude"])]).addTo(resultsMap);
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