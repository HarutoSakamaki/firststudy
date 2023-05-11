<!DOCTYPE html>
<html lang="ja">
  <link rel="stylesheet" href="../css/login.css">
  
  <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>login</title>
  </head>


  <body>


    <div class="login-page">
      <div class="form">
        <form class="register-form">
          <input type="text" placeholder="name"/>
          <input type="password" placeholder="password"/>
          <input type="text" placeholder="email address"/>
          <button>create</button>
          <p class="message">Already registered? <a href="#">Sign In</a></p>
        </form>
        <form class="login-form" action = 'login.php' method = 'post' id = 'loginformid'>
            <?php echo $logintext; ?>
          <input type="text"  name = 'username' class = 'validate[required]'placeholder="username"/>
          <input type="password" name = 'password' class = validate[required] placeholder="password"/>
          <input type = 'hidden'  name = 'login' value = 'login' >
          <button  name = 'login' value = 'login'>login</button>
          <p class="message">Not registered? <a href="#">Create an account</a></p>
        </form>
      </div>
    </div>
  </body>
</html>


<script>

  $('.message a').click(function(){
      $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
  });

  $(function(){
        //<form>タグのidを指定
        $("#loginformid").validationEngine(
            'attach', {
                promptPosition: "topRight"//エラーメッセージ位置の指定
            }
        );
    });

</script>