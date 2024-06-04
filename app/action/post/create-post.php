<?php
   require_once "../core.php";

   // пост с текстом
   if(isset($_POST['create-postText'])) {
      $stmt1 = $link->prepare("INSERT INTO `posts` (`heading`, `text`, `user_id`, `topic_id`) VALUES (?, ?, ?, ?)");
      $stmt2 = $link->prepare("SELECT LAST_INSERT_ID()");

      $user_id = $_SESSION['user']['id'];
      $topic_id = $_POST['topic_id'];
      $postTitle = $_POST['postTitle1'];
      $postDescription = $_POST['postDescription'];

      if(empty($topic_id)) {
         $_SESSION['topic_empty'] = "Укажите тему создаваемого поста";
         header("Location: ../../../create-post.php");
         exit;
      }

      if(!empty($postTitle)) {
         $postTitle = strip_tags($postTitle, []);
      } else {
         $_SESSION['title_empty1'] = "Укажите заголовок создаваемого поста";
         header("Location: ../../../create-post.php");
         exit;
      }

      if(!empty($postDescription)) {
         $allowed_tags = array('<strong>', '</strong>', '<em>', '</em>', '<u>', '</u>', '<s>', '</s>');
         $postDescription = strip_tags($postDescription, $allowed_tags);
         $s = ("|\r\n\r\n|");
         $d = ("</p>\n<p>");
         $postDescription = "<p>". preg_replace($s, $d, $postDescription) ."</p>";
         $postDescription = str_replace('<p></p>', '', $postDescription);
      } else {
         $postDescription = NULL;
      }

      $stmt1->bind_param("ssii", $postTitle, $postDescription, $user_id, $topic_id);
      $stmt1->execute();

      $stmt2->execute();
      $result = $stmt2->get_result();
      $post_id = $result->fetch_assoc()['LAST_INSERT_ID()'];

      header("Location: ../../../post.php?id=$post_id");
   }

   // пост с файлом
   if(isset($_POST['create-postImageOrVideo'])) {
      $stmt1 = $link->prepare("INSERT INTO `posts` (`heading`, `post_file`, `user_id`, `topic_id`) VALUES (?, ?, ?, ?)");
      $stmt2 = $link->prepare("SELECT LAST_INSERT_ID()");

      $user_id = $_SESSION['user']['id'];
      $topic_id = $_POST['topic_id'];
      $postTitle = $_POST['postTitle2'];
      $postFile = $_FILES['file'];

      if(empty($topic_id)) {
         $_SESSION['topic_empty'] = "Укажите тему создаваемого поста";
         header("Location: ../../../create-post.php");
         exit;
      }

      if(empty($postTitle)) {
         $_SESSION['title_empty2'] = "Укажите заголовок создаваемого поста";
         header("Location: ../../../create-post.php");
         exit;
      } else {
         $postTitle = strip_tags($postTitle, []);
      }

      if($_FILES['file']['name'] == NULL) {
         $_SESSION['file_error1'] = "Выберите загружаемый файл";
         header("Location: ../../../create-post.php");
         exit;
      }

      if($_FILES['file']['size'] > 50 * 1024 * 1024) {
         $_SESSION['file_error2'] = "Размер файла превышает 50 МБ";
         header("Location: ../../../create-post.php");
         exit;
      }
 
      $allowed_types = array('image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'video/webm');

      if(!in_array($_FILES['file']['type'], $allowed_types)) {
         $_SESSION['file_error3'] = "Неверный тип файла";
         header("Location: ../../../create-post.php");
         exit;
      }

      if (strpos($_FILES['file']['type'], 'image') !== false) {
         $upload_dir = '../../../assets/files/posts/images/';
      } elseif (strpos($_FILES['file']['type'], 'video') !== false) {
         $upload_dir = '../../../assets/files/posts/videos/';
      }

      $file_extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
      $name_img = uniqid() . '.' . $file_extension;
      $upload_file = $upload_dir . $name_img;

      move_uploaded_file($_FILES['file']['tmp_name'], $upload_file);

      $stmt1->bind_param("ssii", $postTitle, $name_img, $user_id, $topic_id);
      $stmt1->execute();

      $stmt2->execute();
      $result = $stmt2->get_result();
      $post_id = $result->fetch_assoc()['LAST_INSERT_ID()'];

      header("Location: ../../../post.php?id=$post_id");
   }
?>