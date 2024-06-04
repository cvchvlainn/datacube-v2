<?php 
   require_once "./app/components/header.php";

   if(!isset($_SESSION['user'])) {
      header("Location: ./index.php");
   } elseif(empty($_GET['id'])) {
      header("Location: ./profile.php?id=" . $_SESSION['user']['id']);
   }

   if(isset($_GET['id'])) {
      $pageExists = $link->query("SELECT * FROM `users` WHERE `id` = '{$_GET['id']}'");

      if ($pageExists->num_rows == 0) {
         header("Location: ./profile.php");
      }
   }

   require_once "./app/action/dataOrdering/profilePosts.php";

   $stmt = $link->prepare("SELECT * FROM `users` WHERE `id` = ?");
   $stmt->bind_param('i', $_GET['id']);
   $stmt->execute();
   $result = $stmt->get_result();
   $profileUser = $result->fetch_assoc();

   $profileFriends = $link->query("SELECT `friends`.*, `users`.*, `statuses_friends`.*
   FROM `friends` 
      LEFT JOIN `users` ON `friends`.`friend_id` = `users`.`id` 
      LEFT JOIN `statuses_friends` ON `friends`.`status_friend_id` = `statuses_friends`.`id`
   WHERE `friends`.`user_id` = '{$_GET['id']}' AND `friends`.`status_friend_id` = 2");
?>
   <main>
      <div class="main-container">
         <?php require_once "./app/components/aside.php"; ?>
         <section class="main-central">
            <div class="main-central__header main-central__headerFlex">
               <a href="./profile.php?id=<?php echo $_GET['id']; ?>&posts=my" class="main-central__header__btn <?php if(empty($_GET['posts']) || $_GET['posts'] == "my") { echo "main-central__header__btnActive"; } ?>">
                  <img src="./assets/files/my-posts.svg" alt="Мои посты" class="main-central__header__btnIcon"><?php if($_SESSION['user']['id'] == $_GET['id']) { echo "Мои посты"; } else { echo "Посты пользователя"; } ?>
               </a>
               <?php if($_SESSION['user']['id'] == $_GET['id']) { ?>
                  <a href="./profile.php?id=<?php echo $_GET['id']; ?>&posts=saved" class="main-central__header__btn <?php if(isset($_GET['posts']) && $_GET['posts'] == "saved") { echo "main-central__header__btnActive"; } ?>">
                     <img src="./assets/files/save-active.svg" alt="Мои посты" class="main-central__header__btnIcon">Сохранённое
                  </a>
               <?php } elseif(isset($_GET['posts']) && $_GET['posts'] == "saved") { echo "<script>window.location.href = './profile.php?id=" . $_GET['id'] . "';</script>"; } ?>
               <a href="./profile.php?id=<?php echo $_GET['id']; ?>&posts=liked" class="main-central__header__btn <?php if(isset($_GET['posts']) && $_GET['posts'] == "liked") { echo "main-central__header__btnActive"; } ?>">
                  <img src="./assets/files/like-active.svg" alt="Мои посты" class="main-central__header__btnIcon">Понравившееся
               </a>
            </div>
            <div class="separator"></div>
            <div class="main-central__feedPostsProfile">
            <?php if($profilePosts->num_rows > 0) { ?>
               <?php while($post = $profilePosts->fetch_assoc()) { ?>
               <div class="main-central__postProfile">
                  <div class="main-central__postProfileBlock main-central__postProfileBlockUp">
                     <a href="./post.php?id=<?php echo $post['id']; ?>" class="main-central__postProfileBlock__postBlockImg">
                        <?php if($post['post_file'] != NULL) { ?>
                           <?php $postFileType = pathinfo($post['post_file'], PATHINFO_EXTENSION); ?>
                           <?php if($postFileType == 'jpg' || $postFileType == 'jpeg' || $postFileType == 'png' || $postFileType == 'gif') { ?>
                              <img src="./assets/files/posts/images/<?php echo $post['post_file'] ?>" alt="Изображение поста" class="main-central__postBlockImg__postImg">
                           <?php } elseif($postFileType == 'mp4' || $postFileType == 'webm') { ?> 
                              <video class="main-central__postBlockImg__postImg" preload="auto" controls>
                                 <source src="./assets/files/posts/videos/<?php echo $post['post_file']; ?>" type="video/<?php echo $postFileType; ?>">
                              </video>
                           <?php } ?>
                        <?php } ?>
                     </a>
                     <div class="main-central__postProfileBlock__postMain">
                        <div class="main-central__postMain__block">
                           <a href="#" class="main-central__postMain__blockHeader">
                              <div class="main-central__blockHeader__author">
                                 <img src="./assets/files/users/avatars/<?php if($post['avatar'] != NULL) { echo $post['avatar']; } else { echo "default-avatar.jpg"; } ?>" alt="<?php echo $post['login']; ?>" class="main-central__blockHeader__authorImg">опубликовал <?php echo $post['login']; ?>
                              </div>
                           </a>
                           <a href="./post.php?id=<?php echo $post['id']; ?>" class="main-central__postMain__blockContent">
                              <?php echo $post['heading']; ?>
                           </a>
                        </div>
                        <div class="main-central__postMain__block main-central__postMain__time">
                           23 часа назад
                        </div>
                     </div>
                  </div>
                  <div class="separator"></div>
                  <div class="main-central__postProfileBlock">
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
                           <a href="./post.php?id=<?php echo $post['id']; ?>" class="main-central__postFooter__btn"><img src="./assets/files/comment.svg" alt="Комментарий" class="main-central__postFooter__btnIcon"><?php echo $post['count_comments']; ?></a>
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
                           <a href="./post.php?id=<?php echo $post['id']; ?>" class="main-central__postFooter__btn"><img src="./assets/files/share.svg" alt="Поделиться" class="main-central__postFooter__btnIcon">Поделиться</a>
                        </div>
                        <?php if($post['user_id'] == $_SESSION['user']['id']) { ?>
                           <div class="main-central__postFooter__block main-central__postFooter__time">
                              <button name="redact" class="main-central__postProfileBlock__redactAndDeleteBtn"><img src="./assets/files/edit.svg" alt="Редактировать" class="main-central__postProfileBlock__redactAndDeleteBtnIcon"></button>
                              <button name="delete" class="main-central__postProfileBlock__redactAndDeleteBtn"><img src="./assets/files/trash.svg" alt="Удалить" class="main-central__postProfileBlock__redactAndDeleteBtnIcon"></button>
                           </div>
                        <?php } ?>   
                     </form>
                  </div>
               </div>
               <?php } ?>
            <?php } else { ?>
               <div class="main-central__feedPosts__noPosts">
                  Нет записей
               </div>
            <?php } ?>
            </div>
         </section>
         <section class="main-right">
            <div class="main-right__block main-right__block__profile">
               <form id="cover-and-avatar" action="./app/action/user/change-photo.php" method="POST" enctype="multipart/form-data" class="main-right__profile__cover">
                  <input type="hidden" name="get_id" value="<?php echo $_GET['id']; ?>">
                  <?php if($_SESSION['user']['id'] == $_GET['id']) { ?>
                     <label for="cover" class="main-right__profile__coverButton">
                        <input type="file" name="cover" id="cover" hidden>
                        <img src="./assets/files/photo-change.svg" alt="Сменить фото" class="main-right__profile__coverButtonIcon">
                     </label>
                  <?php } ?>
                  <img src="./assets/files/users/covers/<?php if($profileUser['cover'] != NULL) { echo $profileUser['cover']; } else { echo "default-cover.jpg"; } ?>" alt="Обложка" class="main-right__profile__coverImg">
                  <div class="main-right__profile__avatar">
                     <div class="main-right__profile__avatarBlock <?php if($_SESSION['user']['id'] == $_GET['id']) { echo "main-right__profile__avatarBlockOpacity"; } ?>">
                        <?php if($_SESSION['user']['id'] == $_GET['id']) { ?>
                           <label for="avatar" class="main-right__profile__avatarButton">
                              <input type="file" name="avatar" id="avatar" hidden>
                              <img src="./assets/files/photo-change.svg" alt="Сменить фото" class="main-right__profile__coverButtonIcon">
                           </label>
                        <?php } ?>
                        <img src="./assets/files/users/avatars/<?php if($profileUser['avatar'] != NULL) { echo $profileUser['avatar']; } else { echo "default-avatar.jpg"; } ?>" alt="Аватар" class="main-right__profile__avatarImg">
                     </div>
                  </div>
                  <input type="submit" style="display: none">
               </form>
               <div class="main-right__profile__main">
                  <div class="main-right__profile__mainNickname">
                     <?php echo $profileUser['login']; ?>
                  </div>
                  <div class="main-right__profile__mainDateOfBirthday">
                     <img src="./assets/files/birthday-cake.svg" alt="День рождения аккаунта" class="main-right__profile__mainDateOfBirthdayImg">
                     <?php
                        $date = date('j F Y', strtotime($profileUser['created_at']));
                        echo $date;
                     ?>
                  </div>
                  <?php if($_SESSION['user']['id'] == $_GET['id']) { ?>
                     <a href="./create-post.php" class="main-right__profile__mainCreatePost">Создать пост</a>
                  <?php } else { ?>
                     <form action="./app/action/user/add-friend.php" class="main-right__postCommunity__subscribeForm main-right__postCommunity__subscribeMargine">
                        <input type="hidden" name="friend_id" value="<?php echo $_GET['id']; ?>">
                        <button name="friend" class="main-right__postCommunity__subscribe"><?php if(isset($friend) && $friend['status_friend_id'] == 1) { echo "Заявка отправлена"; } elseif(isset($friend) && $friend['status_friend_id'] == 2) { echo "Удалить из друзей"; } else { echo "Добавить в друзья"; } ?></button>
                     </form>
                     <a href="./chat.php?id=<?php echo $_GET['id']; ?>" class="main-right__profile__mainCreateCommunity">Написать сообщение</a>
                  <?php } ?>
               </div>
            </div>
            <div class="main-right__block main-right__block__myFriends">
               <div class="main-right__myFriends__header">Друзья:</div>
               <?php if($profileFriends->num_rows > 0) { ?>
                  <div class="main-right__myFriends__mainBlock">
                  <?php while($profileFriend = $profileFriends->fetch_assoc()) { ?>
                     <a href="./profile.php?id=<?php echo $profileFriend['friend_id']; ?>" class="main-right__myFriends__content">
                        <img src="./assets/files/users/avatars/<?php if($profileFriend['avatar'] != NULL) { echo $profileFriend['avatar']; } else { echo "default-avatar.jpg"; } ?>" alt="Логотип сообщества" class="main-right__myFriends__friendImg">
                        <div class="main-right__myFriends__friendName"><?php echo $profileFriend['login']; ?></div>
                     </a>
                     <?php } ?>
                  </div>
               <?php } else { ?>
                  <div class="main-central__feedPosts__noPosts">
                     Нет друзей
                  </div>
               <?php }  ?>
            </div>
         </section>
      </div>
   </main>
   <?php require_once "./app/components/notifications.php"; ?>
   <script src="./assets/scripts/header.js"></script>
   <script src="./assets/scripts/aside.js"></script>
   <script src="./assets/scripts/post-btns.js"></script>
   <script src="./assets/scripts/profilePosts-share.js"></script>
   <script src="./assets/scripts/profile.js"></script>
</body>
</html>