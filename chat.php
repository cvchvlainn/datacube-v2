<?php 
   require_once "./app/components/header.php";

   if(!isset($_SESSION['user'])) {
      header("Location: ./index.php");
   } elseif(isset($_GET['id']) && $_SESSION['user']['id'] == $_GET['id']) {
      header("Location: ./chat.php");
   }

   if(isset($_GET['id'])) {
      $pageExists = $link->query("SELECT * FROM `users` WHERE `id` = '{$_GET['id']}'");

      if ($pageExists->num_rows == 0) {
         header("Location: ./chat.php");
      }

      $mainUser = $link->query("SELECT * FROM `users` WHERE `id` = '{$_GET['id']}'");
      $mainUser = $mainUser->fetch_assoc();
      
      $chatMessages = $link->query("SELECT `chats`.*,
      `sender`.`id` AS `sender_id`,
      `sender`.`login` AS `sender_login`,
      `sender`.`avatar` AS `sender_avatar`,
      `receiver`.`id` AS `receiver_id`,
      `receiver`.`login` AS `receiver_login`,
      `receiver`.`avatar` AS `receiver_avatar`
      FROM `chats` 
         LEFT JOIN `users` AS `sender` ON `chats`.`sender_id` = `sender`.`id`
         LEFT JOIN `users` AS `receiver` ON `chats`.`receiver_id` = `receiver`.`id`
         WHERE `chats`.`sender_id` = '{$_SESSION['user']['id']}' AND `chats`.`receiver_id` = '{$_GET['id']}' OR `chats`.`sender_id` = '{$_GET['id']}' AND `chats`.`receiver_id` = '{$_SESSION['user']['id']}'
         ORDER BY `chats`.`id` ASC");
   }

   $chats = $link->query("SELECT `users`.*, `chats_participants`.*
   FROM `chats_participants` 
      LEFT JOIN `users` ON `chats_participants`.`user_id` = `users`.`id`
      WHERE `chats_participants`.`contact_id` = '{$_SESSION['user']['id']}'");
?>
   <main class="main__chatHeight">
      <div class="main-container">
         <?php require_once "./app/components/aside.php"; ?>
         <section class="main-central">
            <div class="main-central__chat">
            <?php if(!empty($_GET['id'])) { ?>
               <div class="main-central__chat__header">
                  <a href="./profile.php?id=<?php echo $_GET['id']; ?>" class="main-central__header__chatBlock">
                     <img src="./assets/files/users/avatars/<?php if($mainUser['avatar'] != NULL) { echo $mainUser['avatar']; } else { echo "default-avatar.jpg"; } ?>" alt="" class="main-central__header__chatBlockIcon">
                     <?php echo $mainUser['login']; ?>
                  </a>
               </div>
               <div class="main-central__chat__content">
   
         <?php if($chatMessages->num_rows > 0) { ?>
            <?php $lastSenderId = null; ?>
            <?php while($chatMessage = $chatMessages->fetch_assoc()) { ?>
                  <?php if($chatMessage['sender_id'] != $lastSenderId) { ?>
                     <div class="main-central__chat__contentMessageHeader">
                     <a href="./profile.php?id=<?php echo $chatMessage['sender_id']; ?>" class="main-central__chat__contentMessageHeaderA">
                        <img src="./assets/files/users/avatars/<?php if($chatMessage['sender_avatar'] != NULL) { echo $chatMessage['sender_avatar']; } else { echo "default-avatar.jpg"; } ?>" alt="" class="main-central__chat__contentMessageHeaderIcon">
                        <?php echo $chatMessage['sender_login']; ?>
                     </a>
                  </div>
                  <?php $lastSenderId = $chatMessage['sender_id']; ?>
                  <?php } ?>
                  <div class="main-central__chat__contentMessageBlock main-central__chat__contentMessageLeft">
                     <div class="main-central__chat__contentMessage">
                        <span class="main-central__chat__contentMessageText"><?php echo $chatMessage['message_text']; ?></span>
                        <div class="main-central__chat__contentMessageDate"><?php echo $chatMessage['created_at']; ?></div>
                     </div>
                  </div>
            <?php } ?>
         <?php } else { ?>
                  <div class="main-central__feedPosts__noPosts" style="margin: 0;">
                     Начните общение первым
                  </div>
         <?php } ?>

               </div>
               <div class="main-central__chat__footer">
                  <form action="./app/action/chat/add-message.php" method="POST" class="main-central__footer__chatBlock">
                     <input type="hidden" name="receiver_id" value="<?php echo $_GET['id']; ?>">
                     <textarea name="message" class="main-central__footer__chatBlockTextarea" name="" placeholder="Напишите сообщение" required></textarea>
                     <div class="main-central__mainCreatePost__titleMax main-central__mainCreatePost__titleMaxText">0/1000</div>
                     <div class="main-central__footer__chatBlockSubblock">
                        <button name="addMessage" class="main-central__footer__chatBlockButton">Отправить</button>
                     </div>
                  </form>
               </div>
            <?php } else { ?>
               <div class="main-central__feedPosts__noPosts main-central__feedPosts__noPostsMarginChat">
                  Выберите чат или создайте новый
               </div>
            <?php } ?>
            </div>
         </section>
         <section class="main-right">
            <div class="main-right__block main-right__communities">
               <div class="main-right__communities__title">
                  ЧАТЫ
               </div>
                  
            <?php if($chats->num_rows > 0) { ?>
               <?php while($chat = $chats->fetch_assoc()) { ?>
               <a href="./chat.php?id=<?php echo $chat['user_id']; ?>" class="main-right__community">
                  <img src="./assets/files/users/avatars/<?php if($chat['avatar'] != NULL) { echo $chat['avatar']; } else { echo "default-avatar.jpg"; } ?>" alt="<?php echo $possibleFriend['login']; ?>" class="main-right__community__icon">
                  <div class="main-right__community__text">
                     <span class="main-right__community__name"><?php echo $chat['login']; ?></span>
                     <span class="main-right__community__subscribers"><?php echo $chat['count_friends']; ?> друзей</span>
                  </div>
               </a>
               <?php } ?>
            <?php } else { ?>
               <div class="main-central__feedPosts__noPosts main-central__feedPosts__noPostsMargine">
                  У вас нет чатов
               </div>
            <?php } ?>
            </div>
         </section>
      </div>
   </main>
   <?php require_once "./app/components/notifications.php"; ?>
   <script src="./assets/scripts/header.js"></script>
   <script src="./assets/scripts/aside.js"></script>
   <script src="./assets/scripts/max-symbols.js"></script>
   <script src="./assets/scripts/chat.js"></script>
</body>
</html>