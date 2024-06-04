<?php
   require_once "../core.php";

   if(isset($_POST['addMessage'])) {
      $user_id = $_SESSION['user']['id'];
      $receiver_id = $_POST['receiver_id'];
      $message = $_POST['message'];

      $existing_chat1 = $link->query("SELECT * FROM `chats` WHERE `sender_id` = '$user_id' AND `receiver_id` = '$receiver_id' LIMIT 1");
      $existing_chat2 = $link->query("SELECT * FROM `chats_participants` WHERE `user_id` = '$receiver_id' AND `contact_id` = '$user_id' LIMIT 1");

      if($existing_chat1->num_rows == 0 && $existing_chat2->num_rows == 0) {
         $link->query("INSERT INTO `chats_participants` (`user_id`, `contact_id`) VALUES ('$user_id', '$receiver_id')");
         $link->query("INSERT INTO `chats_participants` (`user_id`, `contact_id`) VALUES ('$receiver_id', '$user_id')");
      }

      $link->query("INSERT INTO `chats` (`sender_id`, `receiver_id`, `message_text`) VALUES ('$user_id', '$receiver_id', '$message')");
   }
?>