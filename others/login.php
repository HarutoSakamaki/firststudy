
<script src = '../js/jquery-3.6.4.min.js'></script>
<link rel="stylesheet" href="../css/validationEngine.jquery.css">
<script src="../js/jquery.validationEngine.js"></script>
<script src="../js/jquery.validationEngine-ja.js" charset="UTF-8"></script>
<script src = "../js/login.js"></script>

<?php
  require_once '../link.php';
  $database = database('staff');
  session_start();
  $logintext = '';
  if(isset($_POST['login'])){
    try{
      $query = 'SELECT * FROM login WHERE username = \''.$_POST['username'].'\' AND password = \''.$_POST['password'].'\' AND del = false ';
      $result = $database -> query($query);
    }catch(Exception $e){
      echo "エラー発生:" . $e->getMessage().'<br>';
      exit();
    }
    $row = mysqli_fetch_assoc($result);
    if(isset($row['id'])){
      session_regenerate_id();
      $_SESSION['login'] = 'success';
      if(isset($_SESSION['againlogin'])){
        unset($_SESSION['againlogin']);
      }
      header("Location: ../outsoucer/searchoutsoucer.php");
      exit();
    }else{
      $logintext .= 'ログインに失敗しました。usernameとpasswordを確認してください。';
    }
  }

  $loginadmintext = '';
  if(isset($_POST['adminlogin'])){
    try{
      $query = 'SELECT * FROM login WHERE username = \''.$_POST['username'].'\' AND password = \''.$_POST['password'].'\' AND admin = true AND del = false  ';
      $result = $database -> query($query);
    }catch(Exception $e){
      echo "エラー発生:" . $e->getMessage().'<br>';
      echo "削除できませんでした";
      exit();
    }
    $row = mysqli_fetch_assoc($result);
    if(isset($row['id'])){
      session_regenerate_id();
      $_SESSION['login'] = 'success';
      $_SESSION['adminlogin'] = 'success';
      if(isset($_SESSION['againlogin'])){
        unset($_SESSION['againlogin']);
      }
      header("Location: ../others/admin.php");
      exit();
    }else{
      $loginadmintext .= 'ログインに失敗しました。usernameとpasswordを確認してください。';
    }
  }
    
  
  if(isset($_SESSION['againlogin'])){
    if($_SESSION['againlogin'] == true){
      $logintext .= 'セッションが切れてます。もう一度ログインして下さい';
      unset($_SESSION['againlogin']);
    }
  }

  if(isset($_POST['signup'])){
    /* $logintext .= <<<EOD
        メールアドレス{$_POST['mailaddress']}@aspark.co.jpにメールアドレスを送信しました。確認してください。
      EOD;
    $uniqstr = md5(uniqid());
    $url = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $sendurl = $url.'/../signupphp?uniqstr='.$uniqstr;
    mail($_POST['mailaddress'].'@aspark.co.jp','メール認証','このurlを押してください',''); */
  }




  require_once('html/loginview.php');
?>



