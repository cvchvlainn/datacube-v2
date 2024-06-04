<?php
   require_once "../core.php";

   if(isset($_POST['deleteTopic'])) {
      $topic_id = $_POST['topic_id'];

      $link->query("DELETE FROM `topic` WHERE `id` = '$topic_id'");

      header("Location: ../../../admin-panel.php");
   }
?>