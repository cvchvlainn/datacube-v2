// копирование в буфер
$(".main-central__postFooter__btn").filter((i, btn) => {
   return $(btn).find("img[alt='Поделиться']").length > 0;
}).click((event) => {
   event.preventDefault();

   const absoluteUrl = window.location.href;

   navigator.clipboard.writeText(absoluteUrl);

   alert("Ссылка скопирована в буфер обмена");
});