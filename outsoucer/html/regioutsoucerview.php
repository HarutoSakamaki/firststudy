
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../css/regioutsoucer.css">
<script src = "../js/change.js"></script>
<title>アウトソーサーの登録フォーム</title>
</head>
<!-- header -->

<body>
<?php
  include("../header.php");
?>
<p>
</p>
<br><br><br><br><br><br>
<!-- <h2>登録フォーム</h2> -->

<p class = 'formclear'></p>
<div id = 'regiemployees' class = 'formsize box'>
	<div class = "boxtitle">アウトソーサーの登録</div>
	<div class = "boxcontent">
		<?php echo $regisuccesstext; ?>
		<form action="regioutsoucer.php" method="post" id = "regiform">
			

			<table class = 'inputtable'>
				<tr>
					<th>名前<a class = 'failfont'>(必須)</a></th>
					<td><input type="text" name="name" class = "validate[required]" value ="<?php echo htmlentities($settextname);?>"><br>
						<a class = 'failfont'><?php echo $namefailtext; ?></a>
					</td>
					<th>フリガナ</th>
					<td>
						<input type="text" name="furigana" class = "" value ="<?php echo htmlentities($settextfurigana);?>"><br>
						<a class = 'failfont'><?php echo $furiganafailtext; ?></a>
					</td>
				</tr>
				<tr>
					<th>生年月日</th>
					<td><?php echo $daytext ?></td>
					<th>入社日</th>
					<td><?php echo $joindaytext ?></td>
				</tr>
				<tr>
					<th>現住所</th>
                        <td colspan = '3'>
                        (郵便番号)〒<input type = 'number' style = 'width:80px;' placeholder="3桁" id='postcode1' name = 'postcode1' value = '<?php echo $settextpostcode1?>'>-<input type = 'number' size = '4' style = 'width:80px;' placeholder="4桁" id = 'postcode2' name = 'postcode2' value = '<?php echo $settextpostcode2?>'>
						<button type = 'button' class = 'commonbutton' id = 'searchaddress'>住所検索</button><br>
						(都道府県)<select name = 'prefectures' id = 'prefecturesselect'>
                            <?php if($settextprefectures != ''){echo '<option value = '.$settextprefectures.'>'.getpref($settextprefectures).'</option>';}?>
                            <?php selectpref();?>
                        </select>
                    
                    <div>(市区町村以下)
                    	<input type = 'text' style = 'width:600px;' name = 'address' value = '<?php echo $settextaddress; ?>' id = 'addresstext'></div>
					<a class = 'failfont'><?php echo $postcodefailtext;?></a>
				</td>
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
                    <td colspan = '2'><textarea name = "motivation" cols = '30' row = '5' class = 'smalltext textarea'><?php echo $settextmotivation; ?></textarea></td>
                    
                </tr>

			</table>

			<p><button type="subit" name="addoutsoucer" class = "btn">従業員の登録</button></p>
			
			
		</form>
	</div>
</div>
</div> 

<p class = 'floatclear'></p>

</body>
</html>


<script>
    let workhistoryfunc = inputfield('workhistory',<?php echo $workhistorycount_json; ?>);
    let licensefunc = inputfield('license',<?php echo $licensecount_json; ?>);

	$(function () {
		$("#searchaddress").on('click', function () {
			var postcode1 = document.getElementById('postcode1');
			var postcode2 = document.getElementById('postcode2');
			if(postcode1.value.length == 3 && postcode2.value.length == 4 ){
				var postcode = String(postcode1.value) + String(postcode2.value);
				// ajax通信開始
				$.ajax({
					url: "http://zipcloud.ibsnet.co.jp/api/search?zipcode=" + postcode,
					// 現在のドメインと、データ取得先のドメインが異なるため 'jsonp' を指定
					dataType: 'jsonp',
				}).then(
					// 通信成功時の処理
					function (data) {
						if (data.results) {
							// 住所情報を取得
							var result = data.results[0];
							console.log(result);
							// フォーム入力欄の「都道府県」「市区町村」「住所」に値をセット
							var setprefectures = getpref(result.address1);
							$("#prefecturesselect option[value='"+setprefectures+"']").prop('selected', true);
							$('#addresstext').val(result.address2+' '+result.address3);
						} else {
							alert('住所が見つかりません');
						}
					},
					// 通信失敗時の処理
					function () {
						alert("検索失敗");
					}
				);
			}else{
                alert('郵便番号が無効です');
            }
		});
	});

</script>






