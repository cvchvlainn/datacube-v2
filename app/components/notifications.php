            <?php if(isset($_SESSION['user'])) { ?>
               <?php
                  $notificationUsers = $link->query("SELECT `users`.*, `statuses_friends`.*, `friends`.*
                  FROM `friends`
                     LEFT JOIN `users` ON `friends`.`user_id` = `users`.`id`
                     LEFT JOIN `statuses_friends` ON `friends`.`status_friend_id` = `statuses_friends`.`id`
                  WHERE `friends`.`friend_id` = '{$_SESSION['user']['id']}' AND `friends`.`status_friend_id` = 1");
               ?>
               <div class="header-menu__notification">
                  <a href="#" class="header-menu__userBtn header-menu__userBtnNotification">
                     <img src="./assets/files/notification.svg" alt="Заявки в друзья" class="header-menu__userBtnIcon">
                     <div class="count-notifications">
                        <?php echo $notificationUsers->num_rows; ?>
                     </div>
                  </a>
               </div>
               <div class="header-menu__notificationBlock">
                     <div class="header-menu__notificationBlockHeader">
                        Заявки в друзья
                     </div>
                     <div class="header-menu__notificationBlockContent">
                     <?php if($notificationUsers->num_rows > 0) { ?>
                        <?php while($notificationUser = $notificationUsers->fetch_assoc()) { ?>
                           <div class="header-menu__notificationBlockContent__user">
                              <a href="./profile.php?id=<?php echo $notificationUser['friend_id']; ?>" class="header-menu__notificationBlockContent__userA">
                                 <img src="./assets/files/users/avatars/<?php if($notificationUser['avatar'] != NULL) { echo $notificationUser['avatar']; } else { echo "default-avatar.jpg"; } ?>" alt="<?php echo $notificationUser['login']; ?>" class="header-menu__notificationBlockContent__userIcon">
                                 <?php echo $notificationUser['login']; ?>
                              </a>
                              <form action="./app/action/user/add-friend.php" method="POST" class="header-menu__notificationBlockContent__userForm">
                                 <input type="hidden" name="notification_id" value="<?php echo $notificationUser['id']; ?>">
                                 <input type="hidden" name="friend_id" value="<?php echo $notificationUser['user_id']; ?>">
                                 <button name="accept" class="header-menu__notificationBlockContent__userFormBtn">Принять</button>
                                 <button name="reject" class="header-menu__notificationBlockContent__userFormBtn">Отклонить</button>
                              </form>
                           </div>
                        <?php } ?>
                     <?php } else { ?>
                        <div class="main-central__feedPosts__noPosts main-central__feedPosts__noPostsMargin">
                           Нет заявок
                        </div>
                     <?php } ?>
                     </div>
                  </div>
               <?php } ?>