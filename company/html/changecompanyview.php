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
        <form action="changecompany.php" method="post" id = 'changeform'>
            <p>会社名: <input type = 'text' name = 'company' class = 'validate[required]' value = <?php echo htmlentities($settextcompany); ?>></p>
            <p>社長名: <input type = 'text' name = 'president' value = <?php echo htmlentities($settextpresident); ?>></p>
            <p>事業内容: <input type = "button" class = 'commonbutton' value ='入力欄を増やす' id = 'businessdetailsincreasetext'><input type = "button" class = 'commonbutton' value ='入力欄を減らす' id = 'businessdetailsdecreasetext'>
                <?php
                    echo $businessdetailtext;
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
                
            <br>市区町村以下<input type = 'text' name = 'location' value = <?php echo htmlentities($settextlocation); ?>></p>
            <p>従業員数: <input type = 'text' class = "validate[optional,custom[integer]]" name = 'numberofemployees' value = <?php echo htmlentities($settextnumberofemployees); ?>></p>
            <p>設立日:
                <?php
                    echo $joindaytext;
                    
                ?>
            </p>
            <p>ホームページアドレス: <input type = 'text' class = 'validate[optional,custom[url]]' name = 'homepage' value = <?php echo htmlentities($settexthomepage); ?>></p>
            <input type = 'hidden' name = 'id' value = <?php echo $id; ?>>
            <p><button type = 'submit' class = 'btn' name = 'change' value='change'>変更</button></p>
        </form>
    </div>
</div>


</body>

<script>
    $(function(){
        //<form>タグのidを指定
        $("#changeform").validationEngine(
            'attach', {
                promptPosition: "topRight"//エラーメッセージ位置の指定
            }
        );
    });
</script>