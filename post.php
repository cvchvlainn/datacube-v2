<?php 
   require_once "./app/components/header.php";
   require_once "./app/action/functions/displayComments.php";

   if(empty($_GET['id'])) {
      header("Location: ./index.php");
      exit;
   }

   if(isset($_GET['id'])) {
      $pageExists = $link->query("SELECT * FROM `posts` WHERE `id` = '{$_GET['id']}'");

      if ($pageExists->num_rows == 0) {
         header("Location: ./index.php");
      }
   }

   $stmt = $link->prepare("SELECT `users`.*, `topics`.*, `posts`.*
   FROM `posts`
      LEFT JOIN `users` ON `posts`.`user_id` = `users`.`id`
      LEFT JOIN `topics` ON `posts`.`topic_id` = `topics`.`id`
      WHERE `posts`.`id` = ?");

   $get_id = (int)$_GET['id'];

   $stmt->bind_param("i", $get_id);
   $stmt->execute();
   $post = $stmt->get_result()->fetch_assoc();
?>
   <main>
      <div class="main-container">
         <?php require_once "./app/components/aside.php"; ?>
         <section class="main-central">
            <div class="main-central__feedPosts">
                  <div class="main-central__postView">
                     <div class="main-central__postHeader">
                        <span href="#" class="main-central__postCommunity">
                           <img src="./assets/files/users/avatars/<?php if($post['avatar'] != NULL) { echo $post['avatar']; } else { echo "default-avatar.jpg"; } ?>" alt="<?php echo $post['login']; ?>" class="main-central__postCommunity__icon">
                           <div class="main-central__postCommunity__authorAndCommunity">
                              <a href="./index.php?topic=<?php echo $post['topic_id']; ?>" class="main-central__authorAndCommunity__community"><?php echo $post['topic']; ?></a>
                              <a href="./profile.php?id=<?php echo $post['user_id']; ?>" class="main-central__authorAndCommunity__author"><?php echo $post['login']; ?></a>
                           </div>
                        </span>
                     </div>
                     <div class="main-central__postText">
                        <?php echo $post['heading']; ?>
                     </div>
                     <?php if($post['text'] != NULL) { ?>
                        <div class="main-central__postTextP">
                           <?php echo $post['text']; ?>
                        </div>
                     <?php } ?>
                     <?php if($post['post_file'] != NULL) { ?>
                        <?php $postFileType = pathinfo($post['post_file'], PATHINFO_EXTENSION); ?>
                        <?php if($postFileType == 'jpg' || $postFileType == 'jpeg' || $postFileType == 'png' || $postFileType == 'gif') { ?>
                           <div class="main-central__postImg">
                              <img src="./assets/files/posts/images/<?php echo $post['post_file']; ?>" alt="Фоновое изображение" class="main-central__postImg__backgroundImg">
                              <div class="main-central__postImg__slot">
                                 <img src="./assets/files/posts/images/<?php echo $post['post_file']; ?>" alt="Основное изображение" class="main-central__postImg__mainImg">
                              </div>
                           </div>
                        <?php } elseif($postFileType == 'mp4' || $postFileType == 'webm') { ?>
                           <video class="main-central__postVideo" preload="auto" controls>
                              <source src="./assets/files/posts/videos/<?php echo $post['post_file']; ?>" type="video/<?php echo $postFileType; ?>">
                           </video>
                        <?php } ?>
                     <?php } ?>
                     <form action="./app/action/post/post-btns.php" method="POST" class="main-central__postFooter">
                        <div class="main-central__postFooter__block">
                           <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                           <?php if(isset($_SESSION['user'])) {
                                 $post_id = $post['id'];
                                 $user_id = $_SESSION['user']['id'];
                                 $friend_id = $post['user_id'];
                               
                                 $like = $link->prepare("SELECT * FROM `likes` WHERE `post_id` = ? AND `user_id` = ?");
                                 $save = $link->prepare("SELECT * FROM `saved` WHERE `post_id` = ? AND `user_id` = ?");
                                 $friend = $link->prepare("SELECT * FROM `friends` WHERE `user_id` = ? AND `friend_id` = ?");
                               
                                 $like->bind_param('ii', $post_id, $user_id);
                                 $save->bind_param('ii', $post_id, $user_id);
                                 $friend->bind_param('ii', $user_id, $friend_id);
                               
                                 $like->execute();
                                 $like->store_result();
                                 $save->execute();
                                 $save->store_result();

                                 $friend->execute();
                                 $friendResult = $friend->get_result();
                                 $friend = $friendResult->fetch_assoc();
                              ?>
                           <button name="like" class="main-central__postFooter__btn">
                              <img src="./assets/files/<?php if($like->num_rows == 0) { echo "inactive-like.svg"; } else { echo "like-active.svg"; } ?>" alt="Лайк" class="main-central__postFooter__btnIcon">
                              <span class="like-count"><?php echo $post['count_likes']; ?></span>
                           </button>
                           <?php } else { ?>
                           <button class="main-central__postFooter__btn header-menu__openModal">
                              <img src="./assets/files/inactive-like.svg" alt="Лайк" class="main-central__postFooter__btnIcon">
                              <span class="like-count"><?php echo $post['count_likes']; ?></span>
                           </button>
                           <?php } ?>
                           <div class="main-central__postFooter__btn"><img src="./assets/files/comment.svg" alt="Комментарий" class="main-central__postFooter__btnIcon"><?php echo $post['count_comments']; ?></div>
                           <?php if(isset($_SESSION['user'])) { ?>
                           <button name="save" class="main-central__postFooter__btn">
                              <img src="./assets/files/<?php if($save->num_rows == 0) { echo "inactive-save.svg"; } else { echo "save-active.svg"; } ?>" alt="Сохранить" class="main-central__postFooter__btnIcon">
                              <span class="save"><?php if($save->num_rows == 0) { echo "Сохранить"; } else { echo "Сохранено"; } ?></span>
                           </button>
                           <?php } else { ?>
                           <button class="main-central__postFooter__btn header-menu__openModal">
                              <img src="./assets/files/inactive-save.svg" alt="Сохранить" class="main-central__postFooter__btnIcon">
                              <span class="save">Сохранить</span>
                           </button>
                           <?php } ?>
                           <button class="main-central__postFooter__btn"><img src="./assets/files/share.svg" alt="Поделиться" class="main-central__postFooter__btnIcon">Поделиться</button>
                        </div>
                        <div class="main-central__postFooter__block main-central__postFooter__time">
                           19 часов назад
                        </div>
                     </form>
                  </div>
                  <div class="separator"></div>
                  <?php if(isset($_SESSION['user'])) { ?>
                     <form action="./app/action/post/add-comments.php" method="POST" class="main-central__addComments">
                        <input type="hidden" name="post_id" value="<?php echo $_GET['id']; ?>">
                        <textarea name="comment" class="main-central__addComments__textarea" placeholder="Добавьте комментарий"></textarea>
                        <div class="main-central__addComments__blockButton">
                           <button name="addComment" class="main-central__addComments__button">Добавить</button>
                        </div>
                     </form>
                  <?php } ?>
                  <?php displayComments($comments->fetch_all(MYSQLI_ASSOC)); ?>
         </section>
         <section class="main-right">
            <div class="main-right__block main-right__postCommunity">
               <a href="./profile.php?id=<?php echo $post['user_id']; ?>" class="main-right__postCommunity__community">
                  <img src="./assets/files/users/avatars/<?php if($post['avatar'] != NULL) { echo $post['avatar']; } else { echo "default-avatar.jpg"; } ?>" alt="<?php echo $post['login']; ?>" class="main-right__postCommunity__iconCommunity"><?php echo $post['login']; ?>
               </a>
               <div class="separator main-right__postCommunity__separator"></div>
               <?php if($post['status'] != NULL) { ?>
                  <div class="main-right__postCommunity__description">
                     <?php echo $post['status']; ?>
                  </div>
               <?php } ?>
               <div class="main-right__postCommunity__subscribers">
                  <?php echo $post['count_friends'] . " друзей"; ?>
               </div>
               <?php if(!isset($_SESSION['user'])) { ?>
                  <button name="friend" class="main-right__postCommunity__subscribe main-right__postCommunity__subscribeMargine header-menu__openModal">Добавить в друзья</button>
               <?php } elseif($post['user_id'] != $_SESSION['user']['id']) { ?>
                  <form action="./app/action/user/add-friend.php" class="main-right__postCommunity__subscribeForm">
                     <input type="hidden" name="friend_id" value="<?php echo $post['user_id']; ?>">
                     <button name="friend" class="main-right__postCommunity__subscribe"><?php if(isset($friend) && $friend['status_friend_id'] == 1) { echo "Заявка отправлена"; } elseif(isset($friend) && $friend['status_friend_id'] == 2) { echo "Удалить из друзей"; } else { echo "Добавить в друзья"; } ?></button>
                  </form>
               <?php } ?>
            </div>
         </section>
      </div>
   </main>
   <?php require_once "./app/components/notifications.php"; ?>
   <?php require_once "./app/components/modal-window.php"; ?>
   <script src="./assets/scripts/header.js"></script>
   <script src="./assets/scripts/modal-window.js"></script>
   <script src="./assets/scripts/aside.js"></script>
   <script src="./assets/scripts/post-btns.js"></script>
   <script src="./assets/scripts/post-share.js"></script>
   <script src="./assets/scripts/comments.js"></script>
</body>
</html>