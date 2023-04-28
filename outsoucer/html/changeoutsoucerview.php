<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="../css/changeoutsoucer.css">
<script src = "../change.js"></script>
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>アウトソーサーの詳細変更</title>
</head>
<body>
<br><br><br><br><br>
<!-- <h2>アウトソーサーの詳細変更</h2> -->

<?php
    include("../header.php");
?>
<div class = "box formsize">
    <div class = "boxtitle">変更</div>
    <div class = "boxcontent">
        <form action="changeoutsoucer.php" method="post">
            <p>名前: <input type = 'text' name = 'name' value = <?php echo $settextname; ?>></p>
            <p>フリガナ: <input type = 'text' name = 'furigana' value = <?php echo $settextfurigana; ?>></p>
            <p>生年月日:
                <?php
                    echo $birthdaytext;
                    /* echo '<select name=\'birthyear\'>'."\n".
                        "<option value = {$settextbirthyear}>{$settextbirthyear}</option>\n";
                    for($i = date('Y'); $i >= 0; $i--) {
                        echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
                    }
                    echo '</select>年' . "\n";
                    echo '<select name=\'birthmonth\' >' . "\n".
                        "<option value = {$settextbirthmonth} >{$settextbirthmonth}</option>\n";
                    for ($i = 1; $i <= 12; $i++) {
                        echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
                    }
                    echo '</select>月' . "\n";
                    echo '<select name=\'birthday\'>' . "\n".
                        "<option value = {$settextbirthday} >{$settextbirthday}</option>\n";
                    for ($i = 1; $i <= 31; $i++) {
                        echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
                    }
                    echo '</select>日' . "\n"; */
                ?>
                
            </p>
            </p>
            <p>現住所: <br>
            都道府県<select name = 'prefectures'>
                    <?php if($settextprefectures != ''){echo '<option value = '.$settextprefectures.'>'.getpref($settextprefectures).'</option>';}?>
                    <?php selectpref();?>
                </select>
            
            <br>市区町村以下<input type = 'text' name = 'address' value = <?php echo $settextaddress; ?>></p>
            <p>メールアドレス: <input type = 'text' name = 'mailaddress' value = <?php echo $settextmailaddress; ?>></p>
            <p>電話番号(ハイフンなし): <input type = 'text' name = 'phonenumber' value = <?php echo $settextphonenumber; ?>></p>
            <p>職歴: <input type = "button" class = 'commonbutton' value ='入力欄を増やす' id = 'workhistoryincreasetext'><input type = "button" class = 'commonbutton'value ='入力欄を減らす' id = 'workhistorydecreasetext'>
                <?php
                    echo $workhistorytext;
                    /* echo '<input type = \'hidden\' id = \'workhistory-1\' >';
                    if(isset($settextworkhistory[0])){
                        echo '<br id=\'workhistorybr0\'><input type = \'text\' name = \'workhistory0\' value = \''.$settextworkhistory[0].'\' id = \'workhistory0\' >';
                        $count = 1;
                    }else{
                        $count = 0;
                    }
                    while(isset($settextworkhistory[$count])){
                        echo '<br id=\'workhistorybr'.$count.'\'><input type = \'text\' name = \'workhistory'.$count.'\' value = \''.$settextworkhistory[$count].'\' id = \'workhistory'.$count.'\'>';
                        $count++;
                    }
                    $workhistorycount_json = json_encode($count); */
                ?>

                <script>
                    let workhistoryfunc = inputfield('workhistory',<?php echo $workhistorycount_json; ?>);
                </script>

            <p id = 'licensep'>免許や資格: <input type = "button" class = 'commonbutton' value ='入力欄を増やす' id = 'licenseincreasetext'><input type = "button" class = 'commonbutton' value ='入力欄を減らす' id = 'licensedecreasetext'>
                <?php
                    echo $licensetext;
                    /* echo '<input type = \'hidden\' id = \'license-1\' >';
                    if(isset($settextlicense[0])){
                        echo '<br id = \'licensebr0\'><input type = \'text\' name = \'license0\' value = \''.$settextlicense[0].'\' id = \'license0\' >';
                        $count = 1;
                    }else{
                        $count = 0;
                    }
                    while(isset($settextlicense[$count])){
                        echo '<br id = \'licensebr'.$count.'\'><input type = \'text\' name = \'license'.$count.'\' value = \''.$settextlicense[$count].'\' id = \'license'.$count.'\'>';
                        $count++;
                    }
                    $licensecount_json = json_encode($count); */
                ?>

                <script>
                    let licensefunc = inputfield('license',<?php echo $licensecount_json; ?>);
                </script>
            </p>
            <p>志望理由:<br><textarea name = "motivation" cols = '30' row = '5' class = 'smalltext textarea' ><?php echo $settextmotivation; ?></textarea></p>
            <p>入社日:
                <?php
                    echo $joindaytext;
                    /* echo '<select name=\'joinyear\'>'."\n".
                        "<option value = {$settextjoinyear}>{$settextjoinyear}</option>\n";
                    for($i = date('Y'); $i >= 0; $i--) {
                        echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
                    }
                    echo '</select>年' . "\n";
                    echo '<select name=\'joinmonth\' >' . "\n".
                        "<option value = {$settextjoinmonth} >{$settextjoinmonth}</option>\n";
                    for ($i = 1; $i <= 12; $i++) {
                        echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
                    }
                    echo '</select>月' . "\n";
                    echo '<select name=\'joinday\'>' . "\n".
                        "<option value = {$settextjoinday} >{$settextjoinday}</option>\n";
                    for ($i = 1; $i <= 31; $i++) {
                        echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
                    }
                    echo '</select>日' . "\n"; */
                ?>
            </p>
            
            <input type = 'hidden' name = 'id' value = <?php echo '\''.$id.'\'' ?>>
            <p><button type = 'submit' class = 'btn' name = 'change' value='change'>変更</button></p>
        </form>
    </div>
</div>


</body>
<script>
    function choicecompany(id,company){
        /* ここからはリムーブコード */
        var removeelements = document.getElementsByClassName('choicecompany');
        for (var i = 0; i < removeelements.length; i++) {
            var e = removeelements[i];
            if (e) {
                e.parentNode.removeChild(e);
            }
        }
        /* 次からは追加コード */

        parentelement = document.getElementById('subwindowbutton');
        var companydisp = document.createElement('p');
        companydisp.textContent = company;
        companydisp.setAttribute('class','choicecompany');
        parentelement.after(companydisp);
        var idelement = document.createElement('input');
        idelement.setAttribute('class','choicecompany');''
        idelement.setAttribute('type','hidden');
        idelement.setAttribute('name','companyid');
        idelement.setAttribute('value',id);
        parentelement.after(idelement);
        var companyelement = document.createElement('input');
        companyelement.setAttribute('class','choicecompany');''
        companyelement.setAttribute('type','hidden');
        companyelement.setAttribute('name','company');
        companyelement.setAttribute('value',company);
        parentelement.after(companyelement);
    }
    function disp(url){
	console.log("クリック");
	var subw = 800;   // サブウインドウの横幅
	var subh = 600;   // サブウインドウの高さ
	// 表示座標の計算
	console.log(screen.availWidth);
	
	var subx = ( screen.width  - subw ) / 2;   // X座標
	var suby = ( screen.width - subh ) / 2;   // Y座標
	console.log(subx);

	// サブウインドウのオプション文字列を作る
	var SubWinOpt = " width=" + subw + ", height=" + subh + ", top=" + suby + ", left=" + subx+ "";
	/* var SubWinOpt = "width=400,height=300,top=445,left=860"; */
	console.log(SubWinOpt);
	window.open(url,"_blank", SubWinOpt);
}


</script>

</html>