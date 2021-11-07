// Scripts for individual sample page

// Initialize the map with hardcoded data for now
// Code taken from https://leafletjs.com/examples/quick-start/
function initMap() {
  //Create map and set the view to specific coordinates and zoom level
  var myMap = L.map("map").setView([43.26143, -79.90747], 8);

  // Add tile layer
  L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1,
    accessToken: 'your.mapbox.access.token'
  }).addTo(myMap);
}
