
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
      $_SESSION = array();
      session_regenerate_id();
      $_SESSION['login'] = 'success';
      if($row['admin']==1){
        $_SESSION['adminlogin'] = 'success';
      }
      header("Location: ../outsoucer/searchoutsoucer.php");
      exit();
    }else{
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



