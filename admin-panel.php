<?php 
   require_once "./app/components/header.php";

   if(!isset($_SESSION['user']) || $_SESSION['user']['role_id'] != 1) {
      header("Location: ./index.php");
   }

   $blockedUsers = $link->query("SELECT * FROM `users` WHERE `status_user_id` = 2");
   $deleteTopics = $link->query("SELECT * FROM `topics`");
?>
   <main>
      <div class="main-container">
         <?php require_once "./app/components/aside.php"; ?>
         <section class="main-central">
            <div class="main-central__header">
               <div class="main-central__header__title">
                  Список заблокированных пользователей
               </div>
            </div>
            <div class="separator"></div>
            <div class="main-central__feedPosts" id="feed">
               <div class="main-central__blockedUsers">
               <?php if($blockedUsers->num_rows > 0) { ?>
                  <?php while($blockedUser = $blockedUsers->fetch_assoc()) { ?>
                     <form action="#" method="POST" class="main-central__blockedUsers__user">
                        <a href="#" class="main-central__blockedUsers__userBlock">
                           <img src="./assets/files/users/avatars/<?php if($blockedUser['avatar'] != NULL) { echo $blockedUser['avatar']; } else { echo "default-avatar.jpg"; } ?>" alt="<?php echo $blockedUser['login']; ?>" class="main-central__blockedUsers__userBlockIcon">
                           <?php echo $blockedUser['login']; ?>
                        </a>
                        <div class="main-central__blockedUsers__userBlock">
                           <button name="unlock" class="main-central__blockedUsers__userBlockButton">Разблокировать</button>
                        </div>
                     </form>
                  <?php } ?>
                  <?php } else { ?>
               <div class="main-central__feedPosts__noPosts">
                  Нет заблокированных пользователей
               </div>
            <?php } ?>
               </div>
            </div>
         </section>
         <section class="main-right">
            <div class="main-right__block">
               <div class="main-right__topic__title">
                  ДОБАВЛЕНИЕ ТЕМЫ
               </div>
               <form action="./app/action/admin/add-topic.php" method="POST" enctype="multipart/form-data" class="main-right__addTopic">
                  <input type="text" name="topic" class="main-central__mainCreatePost__title main-central__mainCreatePost__topic" placeholder="Укажите тему">
                  <div class="main-central__mainCreatePost__titleMax main-central__mainCreatePost__topicMax">0/50</div>
                  <label for="file" class="main-central__mainCreatePost__label">
                     <div class="main-central__mainCreatePost__fileDivPlaceholder">Выберите файл (SVG)</div>
                     <input type="file" name="file" id="file" class="main-central__mainCreatePost__file" hidden>
                     <div class="main-central__mainCreatePost__fileDiv">Выбрать</div>
                  </label>
                  <div class="main-central__mainCreatePost__uploadFileView">
                     <span>Загрузите файл для предпросмотра</span>
                  </div>
                  <button name="addTopic" class="main-right__addTopic__button">Добавить</button>
               </form>
            </div>
            <div class="main-right__block">
               <div class="main-right__topic__title">
                  УДАЛЕНИЕ ТЕМЫ
               </div>
               <div class="main-right__topic__deleteBlock">
                  <?php while($topic = $deleteTopics->fetch_assoc()) { ?>
                     <form action="./app/action/admin/delete-topic.php" class="main-right__topic__deleteForm">
                        <input type="hidden" name="topic_id" value="<?php echo $topic['id']; ?>">
                        <div class="main-right__topic__deleteName">
                           <img src="./assets/files/others/topics/<?php echo $topic['topic_image']; ?>" alt="<?php echo $topic['topic']; ?>" class="main-right__topic__deleteNameTopic">
                           <?php echo $topic['topic']; ?>
                        </div>
                        <button name="deleteTopic" class="main-right__deleteTopic__button">Удалить</button>
                     </form>
                  <?php } ?>
               </div>
            </div>
         </section>
      </div>
   </main>
   <?php require_once "./app/components/notifications.php"; ?>
   <script src="./assets/scripts/header.js"></script>
   <script src="./assets/scripts/aside.js"></script>
   <script src="./assets/scripts/max-symbols.js"></script>
   <script src="./assets/scripts/admin-panel.js"></script>
</body>
</html>