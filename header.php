<?php
  $admintext = '';
  if(isset($_SESSION['adminlogin'])){
    $admintext .= <<<EDO
      <li><input type="button" onclick="location.href='../others/admin.php'" value="管理者ページ" class = 'headerbtn'></li>
      EDO;
  }
?>


<header>
  <h1 class="headline">
  <div><img src='../img/asparklogo.png' alt='アスパークのロゴ' width = 80% height=80%></div>
  社員管理システム
  </h1>
  
  <ul class="nav-list">
    <li class="nav-list-item"><div class = 'rotation'>その他&#9660;</div>
        <ul class = 'submenu'>
          <?php echo $admintext?>
          <li><input type="button" onclick="location.href='../others/changepassword.php'" value="パスワード変更" class = 'headerbtn'></li>
        </ul>
    </li>
    <li class="nav-list-item"><div class = 'rotation'>アウトソーサー&#9660;</div>
      <ul class = 'submenu'>
        <li><input type="button" onclick="location.href='../outsoucer/searchoutsoucer.php'" value="アウトソーサーの検索" class = 'headerbtn'></li>
        <li><input type="button" onclick="location.href='../outsoucer/regioutsoucer.php'" value="アウトソーサーの登録" class = 'headerbtn'></li>
      </ul>
    </li>
    <li class="nav-list-item"><div class = 'rotation'>登録会社&#9660;</div>
      <ul class = 'submenu'>
        <li><input type="button" onclick="location.href='../company/searchcompany.php'" value="登録会社の検索" class = 'headerbtn'></li>
        <li><input type="button" onclick="location.href='../company/regicompany.php'" value="会社の登録" class = 'headerbtn'></li>
      </ul>
    </li>
    

    
    
    
  </ul>
</header>