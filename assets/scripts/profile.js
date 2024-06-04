// отправка формы без кнопки
const form = document.querySelector('#cover-and-avatar');
const cover = document.querySelector('#cover');
const avatar = document.querySelector('#avatar');

if (form && cover && avatar) {
  document.addEventListener("DOMContentLoaded", () => {
    [cover, avatar].forEach(input => {
      input.addEventListener('change', () => {
        if (cover.files.length > 0 || avatar.files.length > 0) {
          form.submit();
        }
      });
    });
  });
}