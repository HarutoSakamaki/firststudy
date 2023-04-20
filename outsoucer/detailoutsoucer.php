<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="detailoutsoucer.css">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>アウトソーサーの詳細</title>
</head>
<body>
<br><br><br><br><br>
<h2>アウトソーサーの詳細<h2>
    <?php
        include("../header.php");
        require_once '../link.php';
        $database = database('staff');

        if(isset($_POST['detail'])){
            $id = $_POST['id'];
            try{
                $query = "SELECT * FROM staffname WHERE del = false AND id = ".$id;
                $result = $database -> query($query);
                $row = mysqli_fetch_assoc($result);
                echo '詳細を取得しました';
            }catch(Exception $e){
                echo "エラー発生:" . $e->getMessage().'<br>';
                echo "  詳細を取得できませんでした。";
            }
        } 
        $birtharray = explode('-', $row['birthday']);
        $birthyear = $birtharray[0];
        $birthmonth = $birtharray[1];
        $birthday = $birtharray[2];
        $joinarray = explode('-', $row['joincompanyday']);
        $joinyear = $joinarray[0];
        $joinmonth = $joinarray[1];
        $joinday = $joinarray[2];
        $licensearray = json_decode($row['license'],true);
        $workhistoryarray = json_decode($row['workhistory'],true);


    ?>
    
    <div class = "middletextsize">
        <p>名前:<?php echo $row['name']; ?></p>
        <p>フリガナ:<?php echo $row['furigana']; ?></p>
        <p>生年月日:<?php echo $birthyear.'年'.$birthmonth.'月'.$birthday.'日'; ?></p>
        <p>現住所:<?php echo $row['address']; ?></p>
        <p>メールアドレス:<?php echo $row['mailaddress']; ?></p>
        <p>電話番号:<?php echo $row['phonenumber']; ?></p>
        <p>職歴:<br>
            <?php 
                $count = 0;
                while(isset($workhistoryarray[$count])){
                    echo  $count.'.'.$workhistoryarray[$count].'<br>';
                    $count++;
                }
            ?>
        </p>
        <p>免許や資格:<br>
            <?php 
                $count = 0;
                while(isset($licensearray[$count])){
                    echo  $count.'.'.$licensearray[$count].'<br>';
                    $count++;
                }
            ?>
        </p>
        <p>志望理由:<br>
            <div class = 'smalltext'><?php echo $row['motivation']; ?></div>
        </p>
        <p>入社日:<?php echo $joinyear.'年'.$joinmonth.'月'.$joinday.'日'; ?></p>
        <p>外勤先:<?php echo $row['company']; ?></p>
        <form action = 'changeoutsoucer.php' method = 'post'>
            <p><button type = 'submit' class = 'btn' name = 'changeform' >変更フォームへ</button></p>
            <input type = 'hidden' name = 'id' value = '<?php echo $id; ?>'>
        </form>
    </div>


</body>