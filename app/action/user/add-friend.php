<?php
   require_once "../core.php";

   if(isset($_POST['friend'])) {
      $user_id = $_SESSION['user']['id'];
      $friend_id = $_POST['friend_id']; 

      $existing_friend = $link->query("SELECT * FROM `friends` WHERE `user_id` = '$user_id' AND `friend_id` = '$friend_id'");

      if($existing_friend->num_rows == 0) {
         $link->query("INSERT INTO `friends` (`user_id`, `friend_id`) VALUES ('$user_id', '$friend_id')");
         $addFriend = true;
      } else {
         $link->query("DELETE FROM `friends` WHERE `user_id` = '$user_id' AND `friend_id` = '$friend_id'");
         $link->query("DELETE FROM `friends` WHERE `user_id` = '$friend_id' AND `friend_id` = '$user_id'");
         $addFriend = false;
      }
      
      echo json_encode(array('addFriend' => $addFriend));
   }

   if(isset($_POST['accept'])) {
      $user_id = $_SESSION['user']['id'];
      $friend_id = $_POST['friend_id']; 
      $notification_id = $_POST['notification_id'];

      $link->query("UPDATE `friends` SET `status_friend_id` = 2 WHERE `id` = '$notification_id'");
      $link->query("INSERT INTO `friends` (`user_id`, `friend_id`, `status_friend_id`) VALUES ('$user_id', '$friend_id', 2)");
      $link->query("UPDATE `users` SET `count_friends` = `count_friends` + 1 WHERE `id` = '$user_id'");
      $link->query("UPDATE `users` SET `count_friends` = `count_friends` + 1 WHERE `id` = '$friend_id'");

      header("Location: ../../../index.php");
   }

   if(isset($_POST['reject'])) {
      $user_id = $_SESSION['user']['id'];
      $notification_id = $_POST['notification_id'];

      $link->query("DELETE FROM `friends` WHERE `id` = '$notification_id'");

      header("Location: ../../../index.php");
   }
?>