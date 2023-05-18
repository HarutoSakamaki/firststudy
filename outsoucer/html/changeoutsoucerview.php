<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="../css/changeoutsoucer.css">
<script src = "../js/change.js"></script>
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>アウトソーサーの詳細変更</title>
</head>
<body>
<?php
    include("../header.php");
?>
<br><br><br><br><br>
<div class = 'backbox'><form action = detailoutsoucer.php method = 'post'>
        <button type = submit name = 'staffid' value = <?php echo $id; ?> class = 'commonbutton'><img src="../img/backbutton.png" alt=""/>詳細画面に戻る</button>
    </form>
</div>
<div class = "box formsize">
    <div class = "boxtitle">
        詳細と変更</div>
    <div class = "boxcontent">
        <form action="changeoutsoucer.php" id = 'changeform' method="post">
            <?php echo $changesuccesstext; ?>
            <table class = 'changetable'>
                <tr>
                    <th>名前</th>
                    <td><input type = 'text' name = 'name' class = 'validate[required]' value = <?php echo $settextname; ?>><br>
                        <a class = 'failfont'><?php echo $namefailtext; ?></a>
                    </td>
                    <th>フリガナ</th>
                    <td><input type = 'text' name = 'furigana' placeholder="カタカナのみ" class = 'validate[optional,custom[katakana]]' value = <?php echo $settextfurigana; ?>><br>
                        <a class = 'failfont'><?php echo $furiganafailtext; ?></a>
                    </td>
                </tr>
                
                <tr>
                    <th>生年月日</th>
                    <td><?php echo $birthdaytext;?></td>
                    <th>入社日</th>
                    <td><?php echo $joindaytext;?></td>
                </tr>
                <tr>
                    <th>現住所</th>
                        <td colspan = '3'>
                        (都道府県)<select name = 'prefectures'>
                            <?php if($settextprefectures != ''){echo '<option value = '.$settextprefectures.'>'.getpref($settextprefectures).'</option>';}?>
                            <?php selectpref();?>
                        </select>
                    
                    <div>(市区町村以下)
                    <input type = 'text' style = 'width:600px;' name = 'address' value = <?php echo $settextaddress; ?>></div></td>
                </tr>
                
                <tr>
                    <th>電話番号(ハイフンなし)</th>
                    <td><input type = 'number' name = 'phonenumber' class = 'validate[custom[phone]]' value = '<?php echo $settextphonenumber; ?>' placeholder="09099998888"><br>
                        <a class = 'failfont'><?php echo $phonenumberfailtext; ?></a>
                    </td>
                    <th>メールアドレス</th>
                    <td><input type = 'text' name = 'mailaddress' class = 'validate[custom[email]]' value = '<?php echo $settextmailaddress; ?>' placeholder="~@~"><br>
                        <a class = 'failfont'><?php echo $mailaddressfailtext; ?></a>
                    </td>
                </tr>
                <tr>
                    <th>職歴</th>
                    <td class = 'arrayitem'>
                        <input type = "button" class = 'commonbutton' value ='追加' id = 'workhistoryincreasetext'><input type = "button" class = 'commonbutton'value ='削除' id = 'workhistorydecreasetext'>
                        <?php echo $workhistorytext;?>
                    </td>
                    <th>免許や資格</th>
                    <td class = 'arrayitem'>
                        <input type = "button" class = 'commonbutton' value ='追加' id = 'licenseincreasetext'><input type = "button" class = 'commonbutton' value ='削除' id = 'licensedecreasetext'>
                        <?php echo $licensetext;?>
                    </td>
                </tr>
                
                <tr>
                    <th>志望理由</th>
                    <td><textarea name = "motivation" cols = '30' row = '5' class = 'smalltext textarea'><?php echo $settextmotivation; ?></textarea></td>
                    <th>社員番号</th>
                    <td>
                        <a><?php echo $settextemployeeid; ?></a>
                        <input type = 'hidden' name = 'employeeid' value = <?php echo $settextemployeeid; ?> placeholder="半角数字4桁以上" ><br>
                        
                    </td>
                </tr>
            </table>
            
            
            <input type = 'hidden' name = 'id' value = <?php echo '\''.$id.'\'' ?>>
            <p><button type = 'submit' class = 'btn' name = 'change' value='change'>変更</button></p>
        </form>
    </div>
</div>


</body>
<script>
    let workhistoryfunc = inputfield('workhistory',<?php echo $workhistorycount_json; ?>);
    let licensefunc = inputfield('license',<?php echo $licensecount_json; ?>);
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

    /* $(function(){
        //<form>タグのidを指定
        $("#changeform").validationEngine(
            'attach', {
                promptPosition: "topRight"//エラーメッセージ位置の指定
            }
        );
    }); */


</script>

</html>