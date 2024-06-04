<?php
   require_once "../core.php";

   $user_id = $_POST['user_id'];

   // Запрос сообщений из базы данных
   $messages = $link->query("SELECT `chats`.*,
   `sender`.`id` AS `sender_id`,
   `sender`.`login` AS `sender_login`,
   `sender`.`avatar` AS `sender_avatar`,
   `receiver`.`id` AS `receiver_id`,
   `receiver`.`login` AS `receiver_login`,
   `receiver`.`avatar` AS `receiver_avatar`
   FROM `chats` 
      LEFT JOIN `users` AS `sender` ON `chats`.`sender_id` = `sender`.`id`
      LEFT JOIN `users` AS `receiver` ON `chats`.`receiver_id` = `receiver`.`id`
      WHERE `chats`.`sender_id` = '{$_SESSION['user']['id']}' AND `chats`.`receiver_id` = '{$_GET['id']}' OR `chats`.`sender_id` = '{$_GET['id']}' AND `chats`.`receiver_id` = '{$_SESSION['user']['id']}'
      ORDER BY `chats`.`id` ASC");
   
   // Преобразуем результат запроса в массив
   $messages_array = array();
   while($message = $messages->fetch_assoc()) {
       $messages_array[] = $message;
   }
   
   // Отправляем массив сообщений в качестве ответа
   echo json_encode($messages_array);
?>