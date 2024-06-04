<?php
   require_once "../core.php";

   if(isset($_POST['addTopic'])) {
      $stmt = $link->prepare("INSERT INTO `topics` (`topic`, `topic_image`) VALUES (?, ?)");

      $topic = $_POST['topic'];

      if(empty($topic)) {
         $_SESSION['file_error1'] = "Укажите тему";
         header("Location: ../../../admin-panel.php");
         exit;
      } else {
         $topic = strip_tags($topic, []);
      }

      if($_FILES['file']['size'] > 5 * 1024 * 1024) {
         $_SESSION['file_error2'] = "Размер файла превышает 5 МБ";
         header("Location: ../../../admin-panel.php");
         exit;
      }
 
      $allowed_types = array('image/svg', 'image/svg+xml');

      if(!in_array($_FILES['file']['type'], $allowed_types)) {
         $_SESSION['file_error3'] = "Неверный тип файла";
         header("Location: ../../../admin-panel.php");
         exit;
      }

      $upload_dir = '../../../assets/files/others/topics/';

      $file_extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
      $name_img = uniqid() . '.' . $file_extension;
      $upload_file = $upload_dir . $name_img;

      move_uploaded_file($_FILES['file']['tmp_name'], $upload_file);

      $stmt->bind_param("ss", $topic, $name_img);
      $stmt->execute();

      header("Location: ../../../admin-panel.php");
   }
?>