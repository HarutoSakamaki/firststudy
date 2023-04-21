<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="html/detailcompany.css">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>登録会社の詳細</title>
</head>
<body>
<br><br><br><br><br>
<h2>登録会社の詳細<h2>
    <?php
        include("../header.php");
        require_once '../link.php';
    ?>
    
    <div class = "middletextsize">
        <p>会社名:<?php echo $model->getcompany() ?></p>
        <p>社長名:<?php echo $model->getpresident() ?></p>
        
        <p>事業内容:<br>
            <?php 
                $count = 0;
                $businessdetails = $model->getbusinessdetailsarray();
                if(isset($businessdetails)){
                    foreach($model->getbusinessdetailsarray() as $businessdetails){
                        $count++;
                        echo $count.'.'.$businessdetails.'<br>';
                    }
                    $count = 0;
                }
                while(isset($businessdetailsarray[$count])){
                    echo  $count.'.'.$businessdetailsarray[$count].'<br>';
                    $count++;
                }
            ?>
        </p>
        <p>本社住所:<?php echo $model->getprefectures().$model->getprefectures().$model->getlocation(); ?></p>
        <p>従業員数:<?php echo $model->getnumberofemployees(); ?></p>
        <p>設立日:<?php echo $model->getestablishyear().'年'.$model->getestablishmonth().'月'.$model->getestablishday().'日'; ?></p>
        <p>ホームページ:<?php echo '<a href = '.$model->gethomepage().'>'.$model->gethomepage().'</a>';?></p>

        <form action = 'changecompanycontroller.php' method = 'post'>
            <p><button type = 'submit' class = 'btn' name = 'changeform' >変更フォームへ</button></p>
            <input type = 'hidden' name = 'company' value = '<?php echo $model->getcompany(); ?>'>
            <input type = 'hidden' name = 'id' value = '<?php echo $model->getid() ; ?>'>
        </form>
    </div>


</body>