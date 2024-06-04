// максимальное число символов у темы
const topic = document.querySelector('.main-central__mainCreatePost__topic');
const topicMax = document.querySelector('.main-central__mainCreatePost__topicMax');
setupInputValidation(topic, 50, topicMax);

// текст загружаемого файла
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
      }

      const fileName = file.name;

      $(".main-central__mainCreatePost__fileDivPlaceholder").text(fileName).css("color", "white");
      previewContainer.css("padding", "15px");
    }
  });
});