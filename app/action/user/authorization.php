<?php
   require_once "../core.php";

   if (isset($_POST["authorization"])) {
      $stmt = $link->prepare("SELECT * FROM `users` WHERE `login` = ? AND `password` = ?");
      $stmt->bind_param("ss", $_POST["login"], md5($_POST["password"]));
      $stmt->execute();
      $result = $stmt->get_result();
    
      if ($result->num_rows == 0) {
         $_SESSION["authorization_error"] = "Неверный логин и/или пароль";
         header("Location: ../../../index.php");
         exit;
      }
    
      $user_data = $result->fetch_assoc();
    
      $_SESSION["user"] = [
         "id" => $user_data["id"],
         "login" => $user_data["login"],
         "role_id" => $user_data["role_id"]
      ];
    
      header("Location: ../../../index.php");
   }
?>