<?php
   require_once "../core.php";

   if(isset($_POST["registration"])) {
      $login = mysqli_real_escape_string($link, $_POST["login"]);
      $password = md5($_POST["password"]);
      $email = mysqli_real_escape_string($link, $_POST["email"]);
      $group = 2;
  
      $stmt = $link->prepare("SELECT * FROM `users` WHERE login = ? OR email = ?");
      $stmt->bind_param("ss", $login, $email);
      $stmt->execute();
      $result = $stmt->get_result();
  
      if ($result->num_rows > 0) {
         $_SESSION["registration_error"] = "Указанные почта и/или логин уже зарегестрированы";
         header("Location: ../../../index.php");
         exit;
      }
  
      $stmt = $link->prepare("INSERT INTO `users` (`login`, `password`, `email`, `role_id`) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("sssi", $login, $password, $email, $group);
      $stmt->execute();
  
      header("Location: ../../../index.php");
   }
?>