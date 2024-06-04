// поле ответа на комментарий
let addCommentBtn = document.querySelectorAll(".main-central__comment__feedback");

addCommentBtn.forEach((btn) => {
   btn.addEventListener("click", (event) => {
      event.preventDefault();
   
      let btnId = btn.dataset.id;
      let addCommentBlock = document.querySelector(`.main-central__addCommentsReply[data-id="${btnId}"]`);

      document.querySelectorAll(".main-central__addCommentsReply").forEach((addCommentBlocks) => {
         addCommentBlocks.style.display = "none";
      });

      addCommentBlock.style.display = "flex";
   });
});