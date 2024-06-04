<?php
   require_once "../core.php";

   if(isset($_POST['like'])) {
      $post_id = $_POST['post_id'];
      $user_id = $_SESSION['user']['id'];

      $existing_like = $link->query("SELECT * FROM `likes` WHERE `post_id` = '$post_id' AND `user_id` = '$user_id'");

      if($existing_like->num_rows == 0) {
         $link->query("INSERT INTO `likes` (`post_id`, `user_id`) VALUES ('$post_id', '$user_id')");
         $link->query("UPDATE `posts` SET `count_likes` = `count_likes` + 1 WHERE `id` = '$post_id'");
         $liked = true;
      } else {
         $link->query("DELETE FROM `likes` WHERE `post_id` = '$post_id' AND `user_id` = '$user_id'");
         $link->query("UPDATE `posts` SET `count_likes` = `count_likes` - 1 WHERE `id` = '$post_id'");
         $liked = false;
      }

      $count_likes = $link->query("SELECT `count_likes` FROM `posts` WHERE `id` = '$post_id'")->fetch_assoc()['count_likes'];

      echo json_encode(array('liked' => $liked, 'count_likes' => $count_likes));
   }

   if(isset($_POST['save'])) {
      $post_id = $_POST['post_id'];
      $user_id = $_SESSION['user']['id'];

      $existing_save = $link->query("SELECT * FROM `saved` WHERE `post_id` = '$post_id' AND `user_id` = '$user_id'");

      if($existing_save->num_rows == 0) {
         $link->query("INSERT INTO `saved` (`post_id`, `user_id`) VALUES ('$post_id', '$user_id')");
         $saved = true;
      } else {
         $link->query("DELETE FROM `saved` WHERE `post_id` = '$post_id' AND `user_id` = '$user_id'");
         $saved = false;
      }
      
      echo json_encode(array('saved' => $saved));
   }

   if(isset($_POST['delete'])) {
      $post_id = $_POST['post_id'];

      $link->query("DELETE FROM `posts` WHERE `id` = '$post_id'");
      header("Location: ../../../profile.php");
   }
?>