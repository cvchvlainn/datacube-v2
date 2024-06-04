// асинхронный лайк
$(document).ready(function() {
   $(".main-central__postFooter__btn[name='like']").click(function(e) {
       e.preventDefault();

       var post_id = $(this).siblings("input[name='post_id']").val();
       var button = $(this);

       $.ajax({
           url: "./app/action/post/post-btns.php",
           method: "POST",
           data: { like: true, post_id: post_id },
           success: function(response) {
               var data = JSON.parse(response);
               if (data.liked) {
                   button.find("img").attr("src", "./assets/files/like-active.svg");
               } else {
                   button.find("img").attr("src", "./assets/files/inactive-like.svg");
               }
               button.find(".like-count").text(data.count_likes);
           }
       });
   });
});

// асинхронное сохранение
$(document).ready(function() {
    $(".main-central__postFooter__btn[name='save']").click(function(e) {
        e.preventDefault();
 
        var post_id = $(this).siblings("input[name='post_id']").val();
        var button = $(this);
 
        $.ajax({
            url: "./app/action/post/post-btns.php",
            method: "POST",
            data: { save: true, post_id: post_id },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.saved) {
                    button.find("img").attr("src", "./assets/files/save-active.svg");
                    button.find(".save").text("Сохранено");
                } else {
                    button.find("img").attr("src", "./assets/files/inactive-save.svg");
                    button.find(".save").text("Сохранить");
                }
            }
        });
    });
 });

 // асинхронное добавление в друзья
 $(document).ready(function() {
    $(".main-right__postCommunity__subscribeForm, .main-central__postCommunity__subscribeForm").submit(function(e) {
        e.preventDefault();
 
        var user_id = $(this).find("input[name='user_id']").val();
        var friend_id = $(this).find("input[name='friend_id']").val();
        var button = $(this).find("button");
 
        $.ajax({
            url: "./app/action/user/add-friend.php",
            method: "POST",
            data: { friend: true, user_id: user_id, friend_id: friend_id },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.addFriend) {
                    button.text("Заявка отправлена");
                } else {
                    button.text("Добавить в друзья");
                }
            }
        });
    });
});