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
        <form action="changeoutsoucer.php" id = 'changeform' method="post">
            <table>
                <tr>
                    <th>名前</th>
                    <td><input type = 'text' name = 'name' class = 'validate[required]' value = <?php echo $settextname; ?>></td>
                </tr>
                <tr>
                    <th>フリガナ</th>
                    <td><input type = 'text' name = 'furigana' class = 'validate[optional,custom[katakana]]' value = <?php echo $settextfurigana; ?>></td>
                </tr>
                <tr>
                    <th>現住所(都道府県)</th>
                    <td>
                        <select name = 'prefectures'>
                            <?php if($settextprefectures != ''){echo '<option value = '.$settextprefectures.'>'.getpref($settextprefectures).'</option>';}?>
                            <?php selectpref();?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>現住所(市区町村以下)</th>
                    <td><input type = 'text' name = 'address' value = <?php echo $settextaddress; ?>></td>
                </tr>
                <tr>
                    <th>メールアドレス</th>
                    <td><input type = 'text' name = 'mailaddress' class = 'validate[custom[email]]' value = <?php echo $settextmailaddress; ?>></td>
                </tr>
                <tr>
                    <th>電話番号(ハイフンなし)</th>
                    <td><input type = 'number' name = 'phonenumber' class = 'validate[custom[phone]]' value = <?php echo $settextphonenumber; ?>></td>
                </tr>
                <tr>
                    <th>職歴<input type = "button" class = 'commonbutton' value ='入力欄を増やす' id = 'workhistoryincreasetext'><input type = "button" class = 'commonbutton'value ='入力欄を減らす' id = 'workhistorydecreasetext'></th>
                    <td><?php echo $workhistorytext;?></td>
                </tr>
                <tr>
                    <th>免許や資格<input type = "button" class = 'commonbutton' value ='入力欄を増やす' id = 'licenseincreasetext'><input type = "button" class = 'commonbutton' value ='入力欄を減らす' id = 'licensedecreasetext'></th>
                    <td><?php echo $licensetext;?></td>
                </tr>
                <tr>
                    <th>志望理由</th>
                    <td><textarea name = "motivation" cols = '30' row = '5' class = 'smalltext textarea' ><?php echo $settextmotivation; ?></textarea></td>
                </tr>
                <tr>
                    <th>入社日</th>
                    <td><?php echo $joindaytext;?></td>
                </tr>
            </table>
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

    $(function(){
        //<form>タグのidを指定
        $("#changeform").validationEngine(
            'attach', {
                promptPosition: "topRight"//エラーメッセージ位置の指定
            }
        );
    });


</script>

</html>