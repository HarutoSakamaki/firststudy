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
        
        <form class="login-form" action = 'login.php' method = 'post' id = 'loginformid'>
            <div><a><?php echo $logintext; ?></a></div>
            <div>社員のログインフォーム</div>
            <div><input type="text"  name = 'username' class = 'validate[required]' placeholder="username"/></div>
            <div><input type="password" name = 'password' class = validate[required] placeholder="password"/></div>
            
          <input type = 'hidden'  name = 'login' value = 'login' >
          <button  name = 'login' value = 'login'>login</button>
          
        </form>
      </div>
    </div>
  </body>
</html>


<script>

    

</script>