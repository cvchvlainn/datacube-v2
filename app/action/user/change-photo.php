<?php
   require_once "../core.php";

   if(isset($_FILES['cover']) && $_FILES['cover']['size'] > 0) {
      $get_id = $_POST['get_id'];

      $stmt = $link->prepare("UPDATE `users` SET `cover` = (?) WHERE `id` = '$get_id'");

      if($_FILES['cover']['size'] > 5 * 1024 * 1024) {
         $_SESSION['file_error2'] = "Размер файла превышает 5 МБ";
         header("Location: ../../../profile.php?id=" . $get_id);
         exit;
      }
 
      $allowed_types = array('image/jpeg', 'image/png', 'image/gif');

      if(!in_array($_FILES['cover']['type'], $allowed_types)) {
         $_SESSION['file_error3'] = "Неверный тип файла";
         header("Location: ../../../profile.php?id=" . $get_id);
         exit;
      }

      $upload_dir = '../../../assets/files/users/covers/';

      $file_extension = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION);
      $name_img = uniqid() . '.' . $file_extension;
      $upload_file = $upload_dir . $name_img;

      move_uploaded_file($_FILES['cover']['tmp_name'], $upload_file);

      $stmt->bind_param("s", $name_img);
      $stmt->execute();

      header("Location: ../../../profile.php?id=" . $get_id);
   }

   if(isset($_FILES['avatar']) && $_FILES['avatar']['size'] > 0) {
      $get_id = $_POST['get_id'];

      $stmt = $link->prepare("UPDATE `users` SET `avatar` = (?) WHERE `id` = '$get_id'");

      if($_FILES['avatar']['size'] > 5 * 1024 * 1024) {
         $_SESSION['file_error2'] = "Размер файла превышает 5 МБ";
         header("Location: ../../../profile.php?id=" . $get_id);
         exit;
      }
 
      $allowed_types = array('image/jpeg', 'image/png', 'image/gif');

      if(!in_array($_FILES['avatar']['type'], $allowed_types)) {
         $_SESSION['file_error3'] = "Неверный тип файла";
         header("Location: ../../../profile.php?id=" . $get_id);
         exit;
      }

      $upload_dir = '../../../assets/files/users/avatars/';

      $file_extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
      $name_img = uniqid() . '.' . $file_extension;
      $upload_file = $upload_dir . $name_img;

      move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_file);

      $stmt->bind_param("s", $name_img);
      $stmt->execute();

      header("Location: ../../../profile.php?id=" . $get_id);
   }
?>