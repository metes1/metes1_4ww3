// Validates registration form
function validateRegistration(form) {
  let fname = form["fname"];
  let lname = form["lname"];
  let email = form["email"];
  let password = form["password"];
  let passConfirm = form["passConfirm"];
  let terms = form["terms"];

  // Check if first name field is empty
  if (fname.value == "") {
    // Display error message and indicate invalid field, return false
    invalidField(fname);
    document.getElementById("error-fname").innerHTML = "First name cannot be empty";
    return false;
  } else {
    //If field is valid, ensure any previous error messages are gone
    validField(fname);
    document.getElementById("error-fname").innerHTML = "";
  }

  // Check if last name field is empty
  if (lname.value == "") {
    // Display error message and indicate invalid field, return false
    invalidField(lname);
    document.getElementById("error-lname").innerHTML = "Last name cannot be empty";
    return false;
  } else {
    //If field is valid, ensure any previous error messages are gone
    validField(lname);
    document.getElementById("error-lname").innerHTML = "";
  }

  // Check if email field is empty
  if (email.value == "") {
    invalidField(email);
    document.getElementById("error-email").innerHTML = "Email cannot be empty";
    return false;
  } else if (!validateEmail(email.value)){ // if email isn't in the proper form
    invalidField(email);
    document.getElementById("error-email").innerHTML = "Email format is incorrect";
    return false;
  } else {
    validField(email);
    document.getElementById("error-email").innerHTML = "";
  }

  // Check if password field is empty
  if (password.value == "") {
    invalidField(password);
    document.getElementById("error-password").innerHTML = "Must enter a password";
    return false;
  } else if (!validatePassword(password.value)){ // if password doesn't meet requirements
    invalidField(password);
    document.getElementById("error-password").innerHTML = "Password must be at least 6 characters long";
    return false;
  } else {
    validField(password);
    document.getElementById("error-password").innerHTML = "";
  }

  // Check if confirm password field is empty
  if (passConfirm.value == "") {
    invalidField(passConfirm);
    document.getElementById("error-passConfirm").innerHTML = "Please confirm your password";
    return false;
  } else if (passConfirm.value != password.value) { // if passConfirm doesn't match password
    invalidField(passConfirm);
    document.getElementById("error-passConfirm").innerHTML = "Passwords don't match";
    return false;
  } else {
    validField(passConfirm);
    document.getElementById("error-passConfirm").innerHTML = "";
  }

  //Check if terms have been agreed to or not
  if (!terms.checked) { // if terms box has not been checked
    document.getElementById("error-terms").innerHTML = "Must agree to the terms of service in order to create an account";
    return false;
  } else {
    document.getElementById("error-terms").innerHTML = "";
  }

  // If all tests above passed, form is validated and can be submitted
  return true;
}

// Higlight an invalid input field red
function invalidField(field) {
  field.style.background ="rgba(255,0,0,0.2)";
  field.focus();
}

// Revert back to default (remove red highlight)
function validField(field) {
  field.style.background ="white";
}

// Validates email, ensures it is formatted correctly
function validateEmail(emailInput) {
  let regEx = /^([a-zA-z0-9_\.\-])+\@(([a-zA-z0-9\-])+\.)+([a-zA-z0-9]{2,})+$/;
  return regEx.test(emailInput);
}

// Validates password, ensures it meets the requirements
function validatePassword(passInput) {
  // Checks if password meets minimum length requirements
  if (passInput.length < 6) {
    return false;
  } else {
    return true;
  }
}