// модальное окно
let openModalBtns = document.querySelectorAll(".header-menu__openModal");
let darkening = document.querySelector(".darkening");

openModalBtns.forEach((btn) => {
   btn.addEventListener("click", (event) => {
      event.preventDefault();
   
      darkening.style.display = "flex";
      body.style.overflowY = "hidden";
   });
});

darkening.addEventListener("click", (event) => {
   if (event.target === darkening && darkening.style.display === "flex") {
      darkening.style.display = "none";
      body.style.overflowY = "auto";
   }
});

let btnClose = document.querySelector(".modal-window__close");

btnClose.addEventListener("click", () => {
   darkening.style.display = "none";
   body.style.overflowY = "auto";
});

let modalAuth = document.querySelector(".modal-window__auth");
let modalReg = document.querySelector(".modal-window__reg");
let btnAuth = document.querySelector(".modal-window__content__noteAuth");
let btnReg = document.querySelector(".modal-window__content__noteReg");

btnReg.addEventListener("click", (event) => {
   event.preventDefault();

   modalAuth.style.display = "none";
   modalReg.style.display = "block";
});

btnAuth.addEventListener("click", (event) => {
   event.preventDefault();

   modalAuth.style.display = "block";
   modalReg.style.display = "none";
});