<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="../css/detailoutsoucer.css">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>アウトソーサーの詳細</title>
</head>
<body>
<?php
    require_once('../header.php');
?>
<br><br><br><br><br>
<h2>アウトソーサーの詳細</h2>
    <div class = "middletextsize detailbox">
        <h3>名前:<?php echo $row['name']; ?></h3>
        <p>フリガナ:<?php echo $row['furigana']; ?></p>
        <p>生年月日:<?php echo $birthyear.'年'.$birthmonth.'月'.$birthday.'日'; ?></p>
        <p>現住所:<?php echo $row['address']; ?></p>
        <p>メールアドレス:<?php echo $row['mailaddress']; ?></p>
        <p>電話番号:<?php echo $row['phonenumber']; ?></p>
        <p>職歴:<br>
            <?php
                echo $workhistorytext;
            ?>
        </p>
        <p>免許や資格:<br>
            <?php 
                echo $licensetext;
                /* $count = 0;
                while(isset($licensearray[$count])){
                    echo  $count.'.'.$licensearray[$count].'<br>';
                    $count++;
                } */
            ?>
        </p>
        <p>志望理由:<br>
            <div class = 'smalltext'><?php echo $row['motivation']; ?></div>
        </p>
        <p>入社日:<?php echo $joinyear.'年'.$joinmonth.'月'.$joinday.'日'; ?></p>
        <!-- <p>外勤先:<?php echo $row['company']; ?></p> -->
        <form action = 'changeoutsoucer.php' method = 'post'>
            <p><button type = 'submit' class = 'btn' name = 'changeform' >変更フォームへ</button></p>
            <input type = 'hidden' name = 'id' value = '<?php echo $id; ?>'>
        </form>
    </div>

    <div>

        <form action = 'changeworkplace.php' method = 'post'>
            <p><button type = 'submit' class = 'btn' name = 'changeworkplace' >外勤先の変更</button></p>
            <input type = 'hidden' name = 'staffid' value = '<?php echo $id; ?>'>
        </form>

    </div>


</body>