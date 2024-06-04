<?php
   if(isset($_GET['search'])) {
      $field = $_GET['field'];
      $topic = $_GET['topic'];

      if(!empty($topic)) {
         header("Location:../../../index.php?topic=$topic&search=$field");
      } else {
         header("Location:../../../index.php?search=$field");
      }
   }
?>