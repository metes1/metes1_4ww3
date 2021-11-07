// HTML5 Geolocation scripts

// Gets the user's current location, if action is supported by browser
// Uses HTML5's Geolocation API
// The two functions below are taken from: https://www.w3schools.com/html/html5_geolocation.asp
function getLocation() {
  // Check if geolocation is supported
  if (navigator.geolocation) {
    // Get current location
    navigator.geolocation.getCurrentPosition(showPosition);
  } else {
      // Alert window pops up if geolocation doesn't work
      alert("Geolocation is not supported by this browser.");
  }
}

// Displays the coordinates in the appropriate input boxes
function showPosition(position) {
  document.getElementById("lat").value = position.coords.latitude;
  document.getElementById("lon").value = position.coords.longitude;
}