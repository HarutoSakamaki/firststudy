<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="../css/changecompany.css">
<script src = "../change.js"></script>
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>登録会社の詳細変更</title>
</head>
<body>
<?php
    include("../header.php");
?>
<br><br><br><br><br>
<h2>登録会社の詳細変更</h2>

<div class="box formsize">
    <div class='boxtitle'><h3>詳細と変更</h3></div>
    <div class = 'boxcontent'>
        <form action="changecompany.php" method="post">
            <p>会社名: <input type = 'text' name = 'company' value = <?php echo $settextcompany; ?>></p>
            <p>社長名: <input type = 'text' name = 'president' value = <?php echo $settextpresident; ?>></p>
            <p>事業内容: <input type = "button" class = 'commonbutton' value ='入力欄を増やす' id = 'businessdetailsincreasetext'><input type = "button" class = 'commonbutton' value ='入力欄を減らす' id = 'businessdetailsdecreasetext'>
                <?php
                    echo $businessdetailtext;
                    /* echo '<input type = \'hidden\' id = \'businessdetails-1\' >';
                    if(isset($settextbusinessdetails[0])){
                        echo '<br id = \'businessdetailsbr0\'><input type = \'text\' name = \'businessdetails0\' value = \''.$settextbusinessdetails[0].'\' id = \'businessdetails0\' >';
                        $count = 1;
                    }else{
                        $count = 0;
                    }
                    while(isset($settextbusinessdetails[$count])){
                        echo '<br id = \'businessdetailsbr'.$count.'\'><input type = \'text\' name = \'businessdetails'.$count.'\' value = \''.$settextbusinessdetails[$count].'\' id = \'businessdetails'.$count.'\'>';
                        $count++;
                    }
                    $businessdetailscount_json = json_encode($count); */
                ?>

                <script>
                    let businessdetailsfunc = inputfield('businessdetails',<?php echo $businessdetailscount_json; ?>);
                </script>
            </p>
            <p>本社所在地: <br>
            都道府県
            <select name = 'prefectures'>
                <?php if($settextprefectures != ''){echo '<option value = '.$settextprefectures.'>'.getpref($settextprefectures).'</option>';}?>
                <?php selectpref();?>
            </select>
                
            <br>市区町村以下<input type = 'text' name = 'location' value = <?php echo $settextlocation; ?>></p>
            <p>従業員数: <input type = 'text' name = 'numberofemployees' value = <?php echo $settextnumberofemployees; ?>></p>
            <p>設立日:
                <?php
                    echo $joindaytext;
                    /* echo '<select name=\'establishyear\'>'."\n".
                        "<option value = {$settextestablishyear}>{$settextestablishyear}</option>\n";
                    for($i = date('Y'); $i >= 0; $i--) {
                        echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
                    }
                    echo '</select>年' . "\n";
                    echo '<select name=\'establishmonth\' >' . "\n".
                        "<option value = {$settextestablishmonth} >{$settextestablishmonth}</option>\n";
                    for ($i = 1; $i <= 12; $i++) {
                        echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
                    }
                    echo '</select>月' . "\n";
                    echo '<select name=\'establishday\'>' . "\n".
                        "<option value = {$settextestablishday} >{$settextestablishday}</option>\n";
                    for ($i = 1; $i <= 31; $i++) {
                        echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
                    }
                    echo '</select>日' . "\n"; */
                ?>
            </p>
            <p>ホームページアドレス: <input type = 'text' name = 'homepage' value = <?php echo $settexthomepage; ?>></p>
            <input type = 'hidden' name = 'id' value = <?php echo $id; ?>>
            <p><button type = 'submit' class = 'btn' name = 'change' value='change'>変更</button></p>
        </form>
    </div>
</div>


</body>