<?php 
   require_once "./app/components/header.php";

   $possibleFriends = $link->query("SELECT * FROM `users` ORDER BY RAND() LIMIT 5");
?>
   <main>
      <div class="main-container">
         <?php require_once "./app/components/aside.php"; ?>
         <section class="main-central">
            <div class="main-central__header">
               <a href="#" class="main-central__header__btnSort">
                  Сортировать по<img src="./assets/files/arrow.svg" alt="Стрелочка" class="main-central__header__iconArrow">
               </a>
               <div class="main-central__header__menuSort">
                  <a href="./index.php?<?php if(!empty($_GET['topic'])) { echo "topic={$_GET['topic']}&"; } if(!empty($_GET['search'])) { echo "search={$_GET['search']}&"; } ?>sort=popular" class="main-central__header__menuSortBtn">
                     Популярное
                  </a>
                  <a href="./index.php?<?php if(!empty($_GET['topic'])) { echo "topic={$_GET['topic']}&"; } if(!empty($_GET['search'])) { echo "search={$_GET['search']}&"; } ?>sort=old" class="main-central__header__menuSortBtn">
                     Старое
                  </a>
                  <a href="./index.php?<?php if(!empty($_GET['topic'])) { echo "topic={$_GET['topic']}&"; } if(!empty($_GET['search'])) { echo "search={$_GET['search']}&"; } ?>sort=new" class="main-central__header__menuSortBtn">
                     Новое
                  </a>
               </div>
            </div>
            <div class="separator"></div>
            <div class="main-central__feedPosts" id="feed">
               <?php if($posts->num_rows > 0) { ?>
                  <?php while($post = $posts->fetch_assoc()) { ?>
                     <a href="./post.php?id=<?php echo $post['id']; ?>" class="main-central__post">
                        <div class="main-central__postHeader">
                           <span href="#" class="main-central__postCommunity">
                              <img src="./assets/files/users/avatars/<?php if($post['avatar'] != NULL) { echo $post['avatar']; } else { echo "default-avatar.jpg"; } ?>" alt="<?php echo $post['login']; ?>" class="main-central__postCommunity__icon"><?php echo $post['login']; ?>
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
                               
                                 $like = $link->prepare("SELECT * FROM `likes` WHERE `post_id` = ? AND `user_id` = ?");
                                 $save = $link->prepare("SELECT * FROM `saved` WHERE `post_id` = ? AND `user_id` = ?");
                               
                                 $like->bind_param('ii', $post_id, $user_id);
                                 $save->bind_param('ii', $post_id, $user_id);
                               
                                 $like->execute();
                                 $like->store_result();
                                 $save->execute();
                                 $save->store_result();
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
                     </a>
                     <div class="separator"></div>
                  <?php } ?>
                  <div id="loading">Загрузка...</div>
               <?php } else { ?>
                  <div class="main-central__feedPosts__noPosts">
                     По вашему запросу нет постов
                  </div>
               <?php } ?>
            </div>
         </section>
         <section class="main-right">
            <div class="main-right__block main-right__communities">
               <div class="main-right__communities__title">
                  СЛУЧАЙНЫЕ ПОЛЬЗОВАТЕЛИ
               </div>

               <?php while($possibleFriend = $possibleFriends->fetch_assoc()) { ?>
               <a href="./profile.php?id=<?php echo $possibleFriend['id']; ?>" class="main-right__community">
                  <img src="./assets/files/users/avatars/<?php if($possibleFriend['avatar'] != NULL) { echo $possibleFriend['avatar']; } else { echo "default-avatar.jpg"; } ?>" alt="<?php echo $possibleFriend['login']; ?>" class="main-right__community__icon">
                  <div class="main-right__community__text">
                     <span class="main-right__community__name"><?php echo $possibleFriend['login']; ?></span>
                     <span class="main-right__community__subscribers"><?php echo $possibleFriend['count_friends']; ?> друзей</span>
                  </div>
               </a>
               <?php } ?>

               <!-- <a href="#" class="main-right__community__btnMore">
                  Показать больше
               </a> -->
            </div>
         </section>
      </div>
   </main>
   <?php require_once "./app/components/notifications.php"; ?>
   <?php require_once "./app/components/modal-window.php"; ?>
   <script src="./assets/scripts/header.js"></script>
   <script src="./assets/scripts/modal-window.js"></script>
   <script src="./assets/scripts/aside.js"></script>
   <script src="./assets/scripts/sort.js"></script>
   <script src="./assets/scripts/post-btns.js"></script>
   <script src="./assets/scripts/feedPosts-share.js"></script>
</body>
</html>