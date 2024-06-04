// раскрывающиеся списки
let closeAndOpenListBtns = document.querySelectorAll(".aside-main__list__openList");

closeAndOpenListBtns.forEach((btn) => {
   btn.addEventListener("click", (event) => {
      event.preventDefault();

      let id = btn.dataset.id;
      let arrow = document.querySelector(`.aside-main__list__iconArrow[data-id="${id}"]`);
      let listHidden = document.querySelector(`.aside-main__listHidden[data-id="${id}"]`);
      let listShow = document.querySelector(`.aside-main__listShow[data-id="${id}"]`);

      listHidden.classList.toggle("active");

      if(listHidden.classList.contains("active")) {
         arrow.style.transform = "rotate(180deg)";
         listHidden.style.height = "0px";
      } else {
         arrow.style.transform = "rotate(360deg)";
         listHidden.style.height = `${listShow.offsetHeight + 5}px`;
      }
   })
});