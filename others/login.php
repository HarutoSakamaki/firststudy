
<script src = '../js/jquery-3.6.4.min.js'></script>
<link rel="stylesheet" href="../css/validationEngine.jquery.css">
<script src="../js/jquery.validationEngine.js"></script>
<script src="../js/jquery.validationEngine-ja.js" charset="UTF-8"></script>
<script src = "../js/login.js"></script>

<?php
  require_once '../link.php';
  $database = database('staff');
  session_start();
  $loginsuccess = false;
  $logintext = '';
  if(isset($_POST['login'])){
    try{
      $query = 'SELECT * FROM tbm_login WHERE nm_username = \''.$_POST['username'].'\' AND flg_del = false ';
      $result = $database -> query($query);
    }catch(Exception $e){
      echo "エラー発生:" . $e->getMessage().'<br>';
      exit();
    }
    $row = mysqli_fetch_assoc($result);
    if(isset($row['pk_id_login'])){
      if(password_verify($_POST['password'] , $row['nm_password'])){
        $_SESSION = array();
        session_regenerate_id();
        $_SESSION['login'] = 'success';
        $_SESSION['loginid'] = $row['pk_id_login'];
        if($row['flg_admin']==1){
          $_SESSION['adminlogin'] = 'success';
        }
        $loginsuccess = true;
        header("Location: ../outsoucer/searchoutsoucer.php");
        exit();
      }
    }
    if($loginsuccess == false){
      $logintext .= 'ログインに失敗しました。usernameとpasswordを確認してください。';
    }
  }


  if(isset($_SESSION['againlogin'])){
    if($_SESSION['againlogin'] == true){
      $logintext .= 'セッションが切れてます。もう一度ログインして下さい';
      unset($_SESSION['againlogin']);
    }
  }
  require_once('html/loginview.php');
?>



