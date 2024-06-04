<?php
   require_once "../core.php";

   if(isset($_POST['addCommentReply'])) {
      $stmt = $link->prepare("INSERT INTO `comments` (`post_id`, `user_id`, `recipient_id`, `parent_id`, `text`) VALUES (?, ?, ?, ?, ?)");

      $user_id = $_SESSION['user']['id'];
      $parent_id = $_POST['parent_id'];
      $recipient_id = $_POST['recipient_id'];
      $post_id = $_POST['post_id'];
      $comment = $_POST['comment'];

      $link->query("UPDATE `posts` SET `count_comments` = `count_comments` + 1 WHERE `id` = '$post_id'");

      $stmt->bind_param("issss", $post_id, $user_id, $recipient_id, $parent_id, $comment);
      $stmt->execute();

      header("Location: ../../../post.php?id=$post_id");
   }
?>