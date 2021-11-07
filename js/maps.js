// Scripts for embedding maps onto both the results page and the individual object page
// Data is hardcoded in for now

// Initialize the map
// Code slightly modified from https://leafletjs.com/examples/quick-start/
function initMap(mapName, lat, lon, zoom) {
  //Create map and set the view to specific coordinates and zoom level
  var myMap = L.map(mapName).setView([lat, lon], zoom);

  // Add tile layer
  L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1Ijoia2VsZWJlayIsImEiOiJja3ZvYzVoNzUwdGpjMm9tbjk1bXA1bXBuIn0.Wgugm8oLgeHuOT1u4KllVw', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1,
    accessToken: 'pk.eyJ1Ijoia2VsZWJlayIsImEiOiJja3ZvYzVoNzUwdGpjMm9tbjk1bXA1bXBuIn0.Wgugm8oLgeHuOT1u4KllVw'
  }).addTo(myMap);

  return myMap;
}

// Create a map for results page
// Information hardcoded for now
function loadResultsMap() {
  // Initialize map with custom values
  var resultsMap = initMap("map", 43.25743, -79.87747, 13);

  // Add hardcoded example result markers on the map
  var marker1 = L.marker([43.257207363845644, -79.86905868465867]).addTo(resultsMap);
  var marker2 = L.marker([43.25289070754244, -79.87094064482412]).addTo(resultsMap);
  var marker3 = L.marker([43.25369183554057, -79.8600087443657]).addTo(resultsMap);
  var marker4 = L.marker([43.26137224471584, -79.90746691419702]).addTo(resultsMap);

  //Add popup for each marker, with name and link to object page (all link to individual_sample for now)
  marker1.bindPopup("<a href=\"./individual_sample.html\"><b>Coles - Jackson Square</b></a>");
  marker2.bindPopup("<a href=\"./individual_sample.html\"><b>James Street Bookseller & Gallery</b></a>");
  marker3.bindPopup("<a href=\"./individual_sample.html\"><b>J.H. Gordon Books</b></a>");
  marker4.bindPopup("<a href=\"./individual_sample.html\"><b>King W. Books</b></a>");
}

// Create a map for the individual object page
// Information hardcoded for now
function loadObjectMap() {
  var objectMap = initMap("map", 43.26137224471584, -79.90746691419702, 14);

  // Add hard coded marker
  var marker = L.marker([43.26137224471584, -79.90746691419702]).addTo(objectMap);
}