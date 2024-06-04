// общие переменные
let body = document.querySelector("body");

// гамбургер меню
let btnHamburger = document.querySelector(".header-menu__btnHamburgerMenu");
let menuHamburger = document.querySelector(".header-menu__hamburgerMenu");

btnHamburger.addEventListener("click", (event) => {
   event.preventDefault();

   menuHamburger.classList.toggle("active");
   
   if(menuHamburger.classList.contains("active")) {
      menuHamburger.style.display = "flex";
   } else {
      menuHamburger.style.display = "none";
   }
});

// уведомления
let btnNotification = document.querySelector(".header-menu__userBtnNotification");
let menuNotification = document.querySelector(".header-menu__notificationBlock");

if (btnNotification && menuNotification) {
btnNotification.addEventListener("click", (event) => {
   event.preventDefault();

   menuNotification.classList.toggle("active");
   
   if(menuNotification.classList.contains("active")) {
      menuNotification.style.display = "block";
   } else {
      menuNotification.style.display = "none";
   }
});
}