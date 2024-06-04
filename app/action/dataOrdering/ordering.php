<?php
   $topic_filter = !empty($_GET['topic']) ? "AND `topics`.`id` = ?" : "";
   $search_filter = !empty($_GET['search']) ? "AND `posts`.`heading` LIKE ?" : "";
   $sort_filter = !empty($_GET['sort']) ? ($_GET['sort'] == 'popular' ? "ORDER BY `posts`.`count_likes` DESC" : ($_GET['sort'] == 'old' ? "ORDER BY `posts`.`created_at` ASC" : ($_GET['sort'] == 'new' ? "ORDER BY `posts`.`created_at` DESC" : header("Location: ./index.php")))) : "";
   
   $query = "SELECT `users`.*, `topics`.*, `posts`.*
   FROM `posts` 
      LEFT JOIN `users` ON `posts`.`user_id` = `users`.`id` 
      LEFT JOIN `topics` ON `posts`.`topic_id` = `topics`.`id`
   WHERE TRUE $topic_filter $search_filter $sort_filter";
   
   $stmt = $link->prepare($query);
   
   if (!empty($_GET['topic']) && !empty($_GET['search'])) {
      $topic_id = (int)$_GET['topic'];
      $searchParam = "%" . $link->real_escape_string($_GET['search']) . "%";
      $stmt->bind_param("is", $topic_id, $searchParam);
   } elseif (!empty($_GET['topic'])) {
      $topic_id = (int)$_GET['topic'];
      $stmt->bind_param("i", $topic_id);
   } elseif (!empty($_GET['search'])) {
      $searchParam = "%" . $link->real_escape_string($_GET['search']) . "%";
      $stmt->bind_param("s", $searchParam);
   }
   
   $stmt->execute();
   $posts = $stmt->get_result();
   
   if (!empty($_GET['topic'])) {
      $selected_topic_query = $link->prepare("SELECT * FROM `topics` WHERE `id` = ?");
      $selected_topic_id = (int)$_GET['topic'];
      $selected_topic_query->bind_param("i", $selected_topic_id);
      $selected_topic_query->execute();
      $selected_topic_result = $selected_topic_query->get_result();
      $selected_topic = $selected_topic_result->fetch_assoc();

      if (!$selected_topic) {
         header("Location: ./index.php");
      }
   }
?>