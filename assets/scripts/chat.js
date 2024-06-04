// максимальное число символов у описания текстового поста
const titleInput = document.querySelector('.main-central__footer__chatBlockTextarea');
const titleMaxText = document.querySelector('.main-central__mainCreatePost__titleMaxText');
setupInputValidation(titleInput, 1000, titleMaxText);

$(document).ready(function() {
   $('.main-central__footer__chatBlockButton').click(function(e) {
     e.preventDefault();
 
      let receiver_id = $('input[name="receiver_id"]').val();
      let message = $('.main-central__footer__chatBlockTextarea').val();

      if (message.trim() === '') {
         alert('Сообщение не может быть пустым');
         return;
       }
 
      $.post('./app/action/chat/add-message.php', { receiver_id: receiver_id, message: message, addMessage: true}, function() {
         $('.main-central__footer__chatBlockTextarea').val('');
     });
   });
 });