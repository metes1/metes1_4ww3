// Script taken from https://dev.to/devggaurav/let-s-build-a-responsive-navbar-and-hamburger-menu-using-html-css-and-javascript-4gci
// Adds functionality to hamburger menu, can be opened and closed

const hamburger = document.querySelector(".hamburger");
const navMenu = document.querySelector(".nav-menu");

// Listens for when hamburger menu is clicked
hamburger.addEventListener("click", responsiveMenu);

// Opens hamburger menu by toggling active class
function responsiveMenu() {
	hamburger.classList.toggle("active");
	navMenu.classList.toggle("active");
}

const navLink = document.querySelectorAll(".nav-link");

// For each menu item, listen for when the item clicked, then close the menu
// This prevents the menu from staying open when you click a navigation link
navLink.forEach(n => n.addEventListener("click", closeMenu));

// Closes opened hamburger menu when a link is clicked
// When "active" is removed from the class list the menu is no longer visible.
function closeMenu() {
	hamburger.classList.remove("active");
	navMenu.classList.remove("active");
}