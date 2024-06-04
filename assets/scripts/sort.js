// список сортировки
let btnSort = document.querySelector(".main-central__header__btnSort");
let menuSort = document.querySelector(".main-central__header__menuSort");
let arrow = document.querySelector(".main-central__header__iconArrow");

btnSort.addEventListener("click", (event) => {
   event.preventDefault();

   menuSort.classList.toggle("active");
   
   if(menuSort.classList.contains("active")) {
      menuSort.style.display = "flex";
      arrow.style.transform = "rotate(360deg)";
   } else {
      menuSort.style.display = "none";
      arrow.style.transform = "rotate(180deg)";
   }
});