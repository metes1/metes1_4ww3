// Script taken from https://dev.to/devggaurav/let-s-build-a-responsive-navbar-and-hamburger-menu-using-html-css-and-javascript-4gci
// Adds functionality to hamburger menu

const hamburger = document.querySelector(".hamburger");
const navMenu = document.querySelector(".nav-menu");

// Listens for when hamburger menu is clicked
hamburger.addEventListener("click", responsiveMenu);

// Opens hamburger menu
function responsiveMenu() {
	hamburger.classList.toggle("active");
	navMenu.classList.toggle("active");
}

const navLink = document.querySelectorAll(".nav-link");
// Listens for when a navlink is clicked
navLink.forEach(n => n.addEventListener("click", closeMenu));

// Closes opened hamburger menu when a link is clicked
function closeMenu() {
	hamburger.classList.remove("active");
	navMenu.classList.remove("active");
}