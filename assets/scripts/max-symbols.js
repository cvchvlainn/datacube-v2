// максимальное число символов (функция)
function setupInputValidation(inputElement, maxCharacters, maxTextElement) {
   inputElement.addEventListener('input', () => {
     let text = inputElement.value;
     let strippedText = text.replace(/<[^>]*>/g, ''); // Удаляем HTML-теги из текста
 
     if (strippedText.length > maxCharacters) {
       let diff = strippedText.length - maxCharacters;
       let newText = text.substring(0, text.length - diff);
       inputElement.value = newText;
       strippedText = newText.replace(/<[^>]*>/g, ''); // Обновляем очищенный текст после обрезания
     }
 
     maxTextElement.textContent = `${strippedText.length}/${maxCharacters}`;
   });
 
   inputElement.addEventListener('paste', (e) => {
     e.preventDefault();
 
     let pastedText = e.clipboardData.getData('text');
     let remainingCharacters = maxCharacters - inputElement.value.length;
 
     if (pastedText.length > remainingCharacters) {
       pastedText = pastedText.substring(0, remainingCharacters);
     }
 
     inputElement.value += pastedText;
 
     let newTextLength = inputElement.value.length;
     if (newTextLength > maxCharacters) {
       inputElement.value = inputElement.value.substring(0, maxCharacters);
       newTextLength = maxCharacters;
     }
 
     maxTextElement.textContent = `${newTextLength}/${maxCharacters}`;
   });
 }