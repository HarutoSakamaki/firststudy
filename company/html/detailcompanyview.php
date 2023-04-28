<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="../css/detailcompany.css">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>登録会社の詳細</title>
</head>
<body>
<?php
    include("../header.php");
?>
<br><br><br><br><br>
<h2>登録会社の詳細</h2>
    <div class = "middletextsize detailbox">
        <h3>会社名:<?php echo $row['company']; ?></h3>
        <div class = "detailboxcontent">
            <p>社長名:<?php echo $row['president']; ?></p>
            
            <p>事業内容:<br>
                <?php 
                    echo $businessdetailtext;
                    /* $count = 0;
                    while(isset($businessdetailsarray[$count])){
                        echo $count.'.'.$businessdetailsarray[$count].'<br>';
                        $count++;
                    } */
                ?>
            </p>
            <p>本社住所:<?php echo $row['location']; ?></p>
            <p>従業員数:<?php echo $row['numberofemployees']; ?></p>
            <p>設立日:<?php echo $eastablishyear.'年'.$establishmonth.'月'.$establishday.'日'; ?></p>
            <p>ホームページ:<?php echo '<a href = '.$row['homepage'].'>'.$row['location'].'</a>';?></p>

            <form action = 'changecompany.php' method = 'post'>
                <p><button type = 'submit' class = 'btn' name = 'changeform' >変更フォームへ</button></p>
                <input type = 'hidden' name = 'company' value = '<?php echo $company; ?>'>
                <input type = 'hidden' name = 'id' value = '<?php echo $id; ?>'>
            </form>
        </div>
    </div>


</body>