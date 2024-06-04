// список тем
let btnListTopics = document.querySelector(".main-central__selectTopic");
let arrowListTopics = document.querySelector(".main-central__selectTopic__iconArrow");
let listTopics = document.querySelector(".main-central__selectTopic__listTopic");

btnListTopics.addEventListener("click", (e) => {
  e.preventDefault();

  listTopics.classList.toggle("active");

  if(listTopics.classList.contains("active")) {
    arrowListTopics.style.transform = "rotate(0deg)";
    listTopics.style.display = "block";
    btnListTopics.style.borderBottom = "1px solid transparent";
    btnListTopics.style.borderRadius = "5px 5px 0 0";
    btnListTopics.style.backgroundColor = "#17221F";
  } else {
    arrowListTopics.style.transform = "rotate(180deg)";
    listTopics.style.display = "none";
    btnListTopics.style.borderBottom = "1px solid var(--pressed-and-border-color)";
    btnListTopics.style.borderRadius = "5px";
    btnListTopics.style.removeProperty("background-color");
  }
});

// выбор темы
let topicItems = document.querySelectorAll(".main-central__listTopic__Topic");
let placeholder = document.querySelector(".main-central__selectTopic__placeholder");
let inputTopicId = document.querySelector("input[name='topic_id']");

topicItems.forEach(item => {
  item.addEventListener("click", (e) => {
    e.preventDefault();
    e.stopPropagation();

    const id = e.target.dataset.id;
    placeholder.textContent = item.textContent;
    placeholder.style.color = "white";
    inputTopicId.value = id;

    listTopics.classList.remove("active");
    arrowListTopics.style.transform = "rotate(180deg)";
    listTopics.style.display = "none";
    btnListTopics.style.borderBottom = "1px solid var(--pressed-and-border-color)";
    btnListTopics.style.borderRadius = "5px";
    btnListTopics.style.removeProperty("background-color");
  });
});

// смена типа поста
let btns = document.querySelectorAll(".main-central__headerCreatePost__btn");
let formText = document.querySelector(".main-central__createPost__mainCreatePostText");
let formImageOrVideo = document.querySelector(".main-central__createPost__mainCreatePostImageOrVideo");

btns.forEach((btn) => {
  btn.addEventListener("click", (event) => {
    event.preventDefault();
 
    btns.forEach((otherBtn) => {
      otherBtn.classList.remove("main-central__headerCreatePost__btnActive");
    });
 
    btn.classList.add("main-central__headerCreatePost__btnActive");
 
    if (btn.dataset.id === "1") {
      formText.style.display = "flex";
      formImageOrVideo.style.display = "none";
    } else {
      formText.style.display = "none";
      formImageOrVideo.style.display = "flex";
    }
  });
});

// редактирование текста
const buttons = document.querySelectorAll('.main-central__mainCreatePost__actionButton');
const textarea = document.querySelector('.main-central__mainCreatePost__description');

buttons.forEach(button => {
  button.addEventListener('click', function() {
    const action = this.getAttribute('data-action');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);

    let newText;

    if (action === 'bold') {
      newText = textarea.value.substring(0, start) + '<strong>' + selectedText + '</strong>' + textarea.value.substring(end);
    } else if (action === 'italic') {
      newText = textarea.value.substring(0, start) + '<em>' + selectedText + '</em>' + textarea.value.substring(end);
    } else if (action === 'underline') {
      newText = textarea.value.substring(0, start) + '<u>' + selectedText + '</u>' + textarea.value.substring(end);
    } else if (action === 'line-through') {
      newText = textarea.value.substring(0, start) + '<s>' + selectedText + '</s>' + textarea.value.substring(end);
    }

    textarea.value = newText;
    });
});

// настройки textarea
const descriptionTextarea = document.querySelector("textarea[name='postDescription']");

descriptionTextarea.addEventListener("keydown", (event) => {
  if (event.key === 'Enter') {
    event.preventDefault();

    let textarea = event.target;
    let cursorPosition = textarea.selectionStart;

    let textBeforeCursor = textarea.value.substring(0, cursorPosition);
    let textAfterCursor = textarea.value.substring(cursorPosition);

    let newText = textBeforeCursor + '\n\n' + textAfterCursor;

    textarea.value = newText;

    let newCursorPosition = cursorPosition + 2;
    textarea.setSelectionRange(newCursorPosition, newCursorPosition);
  }
});

// текст загружаемого файла и его предпоказ
$(function() {
  const fileInput = $("#file");

  fileInput.on("change", function() {
    const files = this.files;

    if (files.length > 0) {
      $(".main-central__mainCreatePost__uploadFileView span").show();
    } else {
      $(".main-central__mainCreatePost__uploadFileView span").hide();
    }

    const previewContainer = $(".main-central__mainCreatePost__uploadFileView");

    previewContainer.empty();

    for (let i = 0; i < files.length; i++) {
      const file = files[i];

      if (file.type.includes("image")) {
        const previewImage = $("<img style='max-width: 100%; object-fit: contain'>").addClass("main-central__mainCreatePost__uploadFileView__image");

        previewImage.attr("src", URL.createObjectURL(file));

        previewContainer.append(previewImage);
      } else if (file.type.includes("video")) {
        const previewVideo = $("<video style='max-width: 100%; object-fit: contain' controls>").addClass("main-central__mainCreatePost__uploadFileView__video");

        previewVideo.attr("src", URL.createObjectURL(file));

        previewContainer.append(previewVideo);
      }

      const fileName = file.name;

      $(".main-central__mainCreatePost__fileDivPlaceholder").text(fileName).css("color", "white");
      previewContainer.css("padding", "15px");
    }
  });
});

// максимальное число символов у заголовка текстового поста
const titleInput1 = document.querySelector('.main-central__mainCreatePost__title1');
const titleMaxText1 = document.querySelector('.main-central__mainCreatePost__titleMaxText');
setupInputValidation(titleInput1, 200, titleMaxText1);
 
// максимальное число символов у заголовка поста с изображением или видео
const titleInput2 = document.querySelector('.main-central__mainCreatePost__title2');
const titleMaxText2 = document.querySelector('.main-central__mainCreatePost__titleMaxImageOrVideo');
setupInputValidation(titleInput2, 200, titleMaxText2);
 
// максимальное число символов у описания текстового поста
const titleInput3 = document.querySelector('.main-central__mainCreatePost__description');
const titleMaxText3 = document.querySelector('.main-central__mainCreatePost__titleMaxDescription');
setupInputValidation(titleInput3, 3000, titleMaxText3);