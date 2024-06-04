<?php 
   require_once "./app/components/header.php";

   if(!isset($_SESSION['user'])) {
      header("Location: ./index.php");
   }

   $stmt = $link->prepare("SELECT * FROM `topics`");
   $stmt->execute();
   $topicsCreatePost = $stmt->get_result();
?>
   <main>
      <div class="main-container">
         <?php require_once "./app/components/aside.php"; ?>
         <section class="main-central">
            <div class="main-central__header">
               <div class="main-central__header__title">
                  Создание поста
               </div>
            </div>
            <div class="separator"></div>
            <form action="./app/action/post/create-post.php" method="POST" enctype="multipart/form-data" class="main-central__addPost">
               <div class="main-central__selectTopicBlock">
                  <input type="hidden" name="topic_id">
                  <a href="#" class="main-central__selectTopic">
                     <div class="main-central__selectTopic__placeholder">Выберите тему</div>
                     <img src="./assets/files/arrow.svg" alt="Стрелочка" class="main-central__selectTopic__iconArrow">
                     <div class="main-central__selectTopic__listTopic">
                        <?php while($topic = $topicsCreatePost->fetch_assoc()) { ?>
                           <div class="main-central__listTopic__Topic" data-id="<?php echo $topic['id']; ?>">
                              <img src="./assets/files/others/topics/<?php echo $topic['topic_image']; ?>" alt="<?php echo $topic['topic']; ?>" class="main-central__listTopic__iconTopic">
                              <?php echo $topic['topic']; ?>
                           </div>
                        <?php } ?>
                     </div>
                  </a>
                  <?php if(isset($_SESSION['topic_empty'])) { ?>
                     <div class="topic_empty"><?php echo $_SESSION['topic_empty']; unset($_SESSION['topic_empty']); ?></div>
                  <?php } ?>
               </div>
               <div class="main-central__createPost">
                  <div class="main-central__createPost__headerCreatePost">
                     <a href="#" class="main-central__headerCreatePost__btn main-central__headerCreatePost__btnActive" data-id="1">
                        <img src="./assets/files/text.svg" alt="Текст" class="main-central__headerCreatePost__iconBtn">Текст
                     </a>
                     <a href="#" class="main-central__headerCreatePost__btn" data-id="2">
                        <img src="./assets/files/image-or-video.svg" alt="Текст" class="main-central__headerCreatePost__iconBtn">Изображение или видео
                     </a>
                  </div>
                  <div class="main-central__createPost__mainCreatePost main-central__createPost__mainCreatePostText" data-id="1">
                     <?php if(isset($_SESSION['title_empty1'])) { ?>
                        <div class="title_empty"><?php echo $_SESSION['title_empty1']; unset($_SESSION['title_empty1']); ?></div>
                     <?php } ?>
                     <input type="text" name="postTitle1" class="main-central__mainCreatePost__title main-central__mainCreatePost__title1" placeholder="Укажите заголовок">
                     <div class="main-central__mainCreatePost__titleMax main-central__mainCreatePost__titleMaxText">0/200</div>
                     <div class="main-central__mainCreatePost__blockActionButton">
                        <div class="main-central__mainCreatePost__actionButton" style="font-weight: bold" data-action="bold" title="Жирный текст">Ж</div>
                        <div class="main-central__mainCreatePost__actionButton" style="font-style: italic" data-action="italic" title="Курсивный текст">К</div>
                        <div class="main-central__mainCreatePost__actionButton" style="text-decoration: underline" data-action="underline" title="Подчёркнутый текст">Ч</div>
                        <div class="main-central__mainCreatePost__actionButton main-central__mainCreatePost__actionButtonThrough" style="text-decoration: line-through" data-action="line-through" title="Перечёркнутый текст">abc</div>
                     </div>
                     <textarea name="postDescription" class="main-central__mainCreatePost__description" placeholder="Введите текст (необязательно)"></textarea>
                     <div class="main-central__mainCreatePost__titleMax main-central__mainCreatePost__titleMaxDescription">0/3000</div>
                     <div class="main-central__mainCreatePost__footer">
                        <a href="./index.php" class="main-central__mainCreatePost__reject">Отмена</a>
                        <button name="create-postText" class="main-central__mainCreatePost__accept">Создать</button>
                     </div>
                  </div>
                  <div class="main-central__createPost__mainCreatePost main-central__createPost__mainCreatePostImageOrVideo" data-id="2">
                     <?php if(isset($_SESSION['title_empty2'])) { ?>
                        <div class="title_empty"><?php echo $_SESSION['title_empty2']; unset($_SESSION['title_empty2']); ?></div>
                     <?php } ?>
                     <input type="text" name="postTitle2" class="main-central__mainCreatePost__title main-central__mainCreatePost__title2" placeholder="Укажите заголовок">
                     <div class="main-central__mainCreatePost__titleMax main-central__mainCreatePost__titleMaxImageOrVideo">0/200</div>
                     <?php if(isset($_SESSION['file_error1'])) { ?>
                        <div class="title_empty"><?php echo $_SESSION['file_error1']; unset($_SESSION['file_error1']); ?></div>
                     <?php } ?>
                     <?php if(isset($_SESSION['file_error2'])) { ?>
                        <div class="title_empty"><?php echo $_SESSION['file_error2']; unset($_SESSION['file_error2']); ?></div>
                     <?php } ?>
                     <?php if(isset($_SESSION['file_error3'])) { ?>
                        <div class="title_empty"><?php echo $_SESSION['file_error3']; unset($_SESSION['file_error3']); ?></div>
                     <?php } ?>
                     <label for="file" class="main-central__mainCreatePost__label">
                        <div class="main-central__mainCreatePost__fileDivPlaceholder">Выберите файл (JPEG/JPG, PNG, GIF, MP4, WEBM)</div>
                        <input type="file" name="file" id="file" class="main-central__mainCreatePost__file" hidden>
                        <div class="main-central__mainCreatePost__fileDiv">Выбрать</div>
                     </label>
                     <div class="main-central__mainCreatePost__maxSize">Максимальный размер: 50МБ</div>
                     <div class="main-central__mainCreatePost__uploadFileView">
                        <span>Загрузите файл для предпросмотра</span>
                     </div>
                     <div class="main-central__mainCreatePost__footer">
                        <a href="./index.php" class="main-central__mainCreatePost__reject">Отмена</a>
                        <button name="create-postImageOrVideo" class="main-central__mainCreatePost__accept">Создать</button>
                     </div>
                  </div>
               </div>
            </form>
         </section>
         <section class="main-right">
            <div class="main-right__block main-right__rules">
               <div class="main-right__rules__title">
                  <img src="./assets/files/warning.svg" alt="Правила" class="main-right__rules__iconWarning">ПУБЛИКАЦИЯ НА DATACUBE
               </div>
               <div class="main-right__rules__rule main-right__rules__ruleFirst">
                  1. Будьте уважительны
               </div>
               <div class="separator"></div>
               <div class="main-right__rules__rule">
                  2. Соблюдайте выбранную тематику
               </div>
               <div class="separator"></div>
               <div class="main-right__rules__rule">
                  3. Ищите дубликаты перед публикацией
               </div>
               <div class="separator"></div>
               <div class="main-right__rules__rule">
                  4. Проверяйте орфографию и грамматику
               </div>
               <div class="separator"></div>
               <div class="main-right__rules__rule main-right__rules__ruleLast">
                  5. Указывайте источник если таковой имеется
               </div>
            </div>
         </section>
      </div>
   </main>
   <?php require_once "./app/components/notifications.php"; ?>
   <script src="./assets/scripts/header.js"></script>
   <script src="./assets/scripts/aside.js"></script>
   <script src="./assets/scripts/max-symbols.js"></script>
   <script src="./assets/scripts/create-post.js"></script>
</body>
</html>