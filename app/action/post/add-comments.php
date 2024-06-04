<?php
   require_once "../core.php";

   if(isset($_POST['addComment'])) {
      $stmt = $link->prepare("INSERT INTO `comments` (`post_id`, `user_id`, `text`) VALUES (?, ?, ?)");

      $user_id = $_SESSION['user']['id'];
      $post_id = $_POST['post_id'];
      $comment = $_POST['comment'];

      $link->query("UPDATE `posts` SET `count_comments` = `count_comments` + 1 WHERE `id` = '$post_id'");

      $stmt->bind_param("iss", $post_id, $user_id, $comment);
      $stmt->execute();

      header("Location: ../../../post.php?id=$post_id");
   }
?>