// копирование в буфер
$(".main-central__postFooter__btn").filter((i, btn) => {
   return $(btn).find("img[alt='Поделиться']").length > 0;
}).click((event) => {
   event.preventDefault();

   const postUrl = $(event.target).closest("a").attr("href");
   const absoluteUrl = window.location.origin + postUrl.replace(/^\./, "");

   navigator.clipboard.writeText(absoluteUrl);

   alert("Ссылка скопирована в буфер обмена");
});