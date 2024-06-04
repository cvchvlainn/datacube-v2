<div class="darkening">
   <div class="modal-window">
      <div class="modal-window__header">
         <div class="modal-window__close">
            <img src="./assets/files/cross.svg" alt="Крестик" class="modal-window__close__iconCross">
         </div>
      </div>
      <div class="modal-window__content modal-window__auth">
         <div class="modal-window__content__title">
            Авторизация
         </div>
         <?php if (isset($_SESSION['authorization_error'])) { ?>
            <div class="modal-window__content_error"><?php echo $_SESSION['authorization_error']; unset($_SESSION['authorization_error']); ?></div>
         <?php } ?>
         <form action="./app/action/user/authorization.php" method="POST" class="modal-window__content__form">
            <div class="modal-window__content__formBlock">
               <input type="text" name="login" class="modal-window__content__field" placeholder="Логин" required>
               <input type="password" name="password" class="modal-window__content__field" placeholder="Пароль" required>
               <div class="modal-window__content__or">
                  или продолжите с помощью
               </div>
               <a class="modal-window__content__google"><img src="./assets/files/google.svg" alt="Гугл" class="modal-window__content__iconGoogle"></a>
            </div>
            <div class="modal-window__content__formBlock modal-window__content__formBlockMargin">
               <div class="modal-window__content__note">
                  Впервые на Datacube? <a href="#" class="modal-window__content__noteRegAuth modal-window__content__noteReg">Регистрация</a>
               </div>
               <button name="authorization" class="modal-window__content__btnRegAuth">Авторизоваться</button>
            </div>
         </form>
      </div>
      <div class="modal-window__content modal-window__reg">
         <div class="modal-window__content__title">
            Регистрация
         </div>
         <?php if (isset($_SESSION['registration_error'])) { ?>
            <div class="modal-window__content_error"><?php echo $_SESSION['registration_error']; unset($_SESSION['registration_error']); ?></div>
         <?php } ?>
         <form action="./app/action/user/registration.php" method="POST" class="modal-window__content__form">
            <div class="modal-window__content__formBlock">
               <input type="text" name="login" class="modal-window__content__field" placeholder="Логин" required>
               <input type="password" name="password" class="modal-window__content__field" placeholder="Пароль" required>
               <input type="email" name="email" class="modal-window__content__field" placeholder="E-mail" required>
               <div class="modal-window__content__or">
                  или продолжите с помощью
               </div>
               <a class="modal-window__content__google"><img src="./assets/files/google.svg" alt="Гугл" class="modal-window__content__iconGoogle"></a>
            </div>
            <div class="modal-window__content__formBlock modal-window__content__formBlockMargin">
               <div class="modal-window__content__note">
                  Уже есть аккаунт на Datacube? <a href="#" class="modal-window__content__noteRegAuth modal-window__content__noteAuth">Авторизация</a>
               </div>
               <button name="registration" class="modal-window__content__btnRegAuth">Зарегестрироваться</button>
            </div>
         </form>
      </div>
   </div>
</div>