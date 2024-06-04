<?php
   $stmt = $link->prepare("SELECT `users`.*, `topics`.*, `posts`.*
   FROM `posts` 
   LEFT JOIN `users` ON `posts`.`user_id` = `users`.`id` 
   LEFT JOIN `topics` ON `posts`.`topic_id` = `topics`.`id`
   WHERE `posts`.`user_id` = ?");
   
   $stmt2 = $link->prepare("SELECT `users`.*, `topics`.*, `saved`.*, `posts`.*
   FROM `posts` 
   LEFT JOIN `users` ON `posts`.`user_id` = `users`.`id` 
   LEFT JOIN `topics` ON `posts`.`topic_id` = `topics`.`id`
   LEFT JOIN `saved` ON `saved`.`post_id` = `posts`.`id`
   WHERE `saved`.`user_id` = ?");
   
   $stmt3 = $link->prepare("SELECT `users`.*, `topics`.*, `likes`.*, `posts`.*
   FROM `posts` 
   LEFT JOIN `users` ON `posts`.`user_id` = `users`.`id` 
   LEFT JOIN `topics` ON `posts`.`topic_id` = `topics`.`id` 
   LEFT JOIN `likes` ON `likes`.`post_id` = `posts`.`id`
   WHERE `likes`.`user_id` = ?");

   if (empty($_GET['posts']) || $_GET['posts'] == "my" || $_GET['posts'] == "liked" || $_GET['posts'] == "saved") {
      if (empty($_GET['posts']) || $_GET['posts'] == "my") {
         $stmt->bind_param('i', $_GET['id']);
         $stmt->execute();
         $profilePosts = $stmt->get_result();
      } elseif ($_GET['posts'] == "saved") {
         $stmt2->bind_param('i', $_GET['id']);
         $stmt2->execute();
         $profilePosts = $stmt2->get_result();
      } elseif ($_GET['posts'] == "liked") {
         $stmt3->bind_param('i', $_GET['id']);
         $stmt3->execute();
         $profilePosts = $stmt3->get_result();
      }
   } else {
      header("Location: profile.php?id=" . $_GET['id']);
   }
?>