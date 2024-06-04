<?php
$comments = $link->query("SELECT `posts`.*, `users`.*, `comments`.*, 
`recipient_users`.`id` AS `recipient_id`, 
`recipient_users`.`login` AS `recipient_login`,
`users`.`id` AS `user_id`
FROM `comments` 
   LEFT JOIN `posts` ON `comments`.`post_id` = `posts`.`id`
   LEFT JOIN `users` ON `comments`.`user_id` = `users`.`id`
   LEFT JOIN `users` AS `recipient_users` ON `comments`.`recipient_id` = `recipient_users`.`id`
   WHERE `posts`.`id` = '{$_GET['id']}' ORDER BY `comments`.`id` ASC");

   function displayComments($comments, $parent_id = null, $level = 0)
   {
      foreach ($comments as $comment) {
         if ($comment['parent_id'] == $parent_id) {
?>
            <div class="main-central__comments__comment <?php if($level == 1) { echo " main-central__comments__commentReply"; }?>">
               <div class="main-central__comments__mainBlock">
                  <div class="main-central__comment__block">
                     <a href="./profile.php?id=<?php echo $comment['user_id']; ?>" class="main-central__comment__user"><img src="./assets/files/users/avatars/<?php if($comment['avatar'] != NULL) { echo $comment['avatar']; } else { echo "default-avatar.jpg"; } ?>" alt="<?php echo $comment['login']; ?>" class="main-central__comment__iconUser"></a>
                  </div>
                  <div class="main-central__comment__block main-central__comment__blockText">
                     <div class="main-central__comment__userBlock">
                        <a href="./profile.php?id=<?php echo $comment['user_id']; ?>" class="main-central__comment__user"><?php echo $comment['login']; ?></a>
                        <?php if($parent_id != NULL) { ?>
                           <span class="central__comment__userSpan">></span><a href="./profile.php?id=<?php echo $comment['recipient_id']; ?>" class="main-central__comment__userParent"><?php echo $comment['recipient_login']; ?></a>
                        <?php } ?>
                     </div>
                     <div class="main-central__comment__text">
                        <?php echo $comment['text']; ?>
                     </div>
                     <div class="main-central__comment__feedbackAndDate">
                        <?php if(isset($_SESSION['user'])) { ?>
                           <div class="main-central__comment__feedback" data-id="<?php echo $comment['id']; ?>">
                              ОТВЕТИТЬ
                           </div>
                        <?php } ?>
                        <div class="main-central__comment__date">
                        <?php echo $comment['created_at']; ?>
                        </div>
                     </div>
                     <?php if(isset($_SESSION['user'])) { ?>
                     <form action="./app/action/post/add-commentReply.php" method="POST" class="main-central__addComments main-central__addCommentsReply" data-id="<?php echo $comment['id']; ?>">
                        <input type="hidden" name="post_id" value="<?php echo $_GET['id']; ?>">
                        <input type="hidden" name="parent_id" value="<?php echo $comment['id']; ?>">
                        <input type="hidden" name="recipient_id" value="<?php echo $comment['user_id']; ?>">
                        <textarea name="comment" class="main-central__addComments__textarea" placeholder="Добавьте комментарий"></textarea>
                        <div class="main-central__addComments__blockButton">
                           <button name="addCommentReply" class="main-central__addComments__button">Добавить</button>
                        </div>
                     </form>
                     <?php } ?>
                  </div>
               </div>
               <?php
                  displayComments($comments, $comment['id'], $level + 1);
               ?>
         </div>
<?php
         }
      }
   }
?>