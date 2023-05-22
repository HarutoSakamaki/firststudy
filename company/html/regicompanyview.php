
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../css/regicompany.css">
<title>会社の登録フォーム</title>
</head>
<!-- header -->

<body>
<?php
  include("../header.php");
?>
<p>
	<?php
		require_once '../link.php';
		$database = database('staff');
	?>
</p>
<br><br><br><br><br><br>
<!-- <h2>登録フォーム</h2> -->
</body>

<body>


<div id = "regicompany" class = 'formsize box'>
	<div class = "boxtitle">会社の登録</div>
	<div class = "boxcontent">
		<?php echo $regisuccesstext; ?>
		<form action="regicompany.php" method="post" id = "regicompanyform">
			
			<table class = 'inputtable'>
			<tr>
                    <th>会社名<a class = 'failfont'>(必須)</a></th>
                    <td><input type = 'text' name = 'company' class = 'validate[required]' value = <?php echo htmlentities($settextcompany); ?>><br>
                        <a class = 'failfont'><?php echo $companynamefailtext; ?></a>
                    </td>
                    <th>社長名</th>
                    <td><input type = 'text' name = 'president' value = <?php echo htmlentities($settextpresident); ?>></td>
                </tr>
                
                <tr>
                    <th>本社所在地</th>
                    <td colspan = '3'>
                        (郵便番号)〒<input type = 'number' style = 'width:80px;' placeholder="3桁" id='postcode1'>-<input type = 'number' size = '5' style = 'width:80px;' placeholder="4桁" id = 'postcode2'>
                        <button type = 'button' class = 'commonbutton' id = 'searchaddress'>住所検索</button>
                        (都道府県)<select name = 'prefectures' id = 'prefecturesselect'>
                        <?php if($settextprefectures != ''){echo '<option value = '.$settextprefectures.'>'.getpref($settextprefectures).'</option>';}?>
                        <?php selectpref();?>
                        </select>
                
                    <div>(市区町村以下)
                    <input type = 'text' name = 'location' style = 'width:600px'value = '<?php echo htmlentities($settextlocation); ?>' id = 'localtext' ></div></td>
                </tr>
                
                <tr>
                    <th>従業員数</th>
                    <td><input type = 'text' class = "validate[optional,custom[integer]]" name = 'numberofemployees' value = '<?php echo htmlentities($settextnumberofemployees); ?>' placeholder="半角数字" >人<br>
                        <a class = 'failfont'><?php echo $numberofemployeesfailtext; ?></a>
                    </td>
                    <th>平均年齢</th>
                    <td><input type = 'text' name = 'averageage' class = 'validate[optional,custom[number]]' value = '<?php echo htmlentities($settextaverageage); ?>' placeholder="半角数字か.(小数も可)" >歳<br>
                        <a class = 'failfont'><?php echo $averageagefailtext; ?></a>
                    </td>
                </tr>
                
                <tr>
                    <th>売上高</th>
                    <td>
                        <input type = 'number' name = 'sales' class = 'validate[optional,custom[integer]]' value = <?php echo $settextsales; ?> placeholder="半角数字" >
                        <select name = 'digit'>
                            <option value = '<?php echo $settextdigit?>'><?php echo $settextdigit2 ?></option>
                            <option value = '1000'>千円</option>
                            <option value = '1000000'>百万円</option>
                            <option value = '1000000000'>十億円</option>
                            <option value = '1000000000000'>兆円</option>
                        </select><br>
                        <a class = 'failfont'><?php echo $salesfailtext; ?></a>
                    </td>
                    <th>資本金</th>
                    <td><input type = 'number' name = 'capital' class = 'validate[optional,custom[integer]]' value = <?php echo $settextcapital; ?> placeholder="半角数字">
                        <select name = 'capitaldigit'>
                            <option value = '<?php echo $settextcapitaldigit?>'><?php echo $settextcapitaldigit2 ?></option>
                            <option value = '1'>円</option>
                            <option value = '1000'>千円</option>
                            <option value = '1000000'>百万円</option>
                            <option value = '1000000000'>十億円</option>
                            <option value = '1000000000000'>兆円</option>
                        </select>
                        <a class = 'failfont' ><?php echo $capitalfailtext; ?></a>
                    </td>
                    
                </tr>
                
                <tr>
                    <th>設立日</th>
                    <td><?php echo $establishdaytext;?></td>
                    <th>決算月</th>
                    <td>
                        <select name = 'closingmonth' class = 'validate[optional,custom[integer]]'></p>
                            <option value = '<?php echo htmlentities($settextclosingmonth); ?>'><?php echo htmlentities($settextclosingmonth);?>月</option>
                            <option value = '1'>1月</option>
                            <option value = '2'>2月</option>
                            <option value = '3'>3月</option>
                            <option value = '4'>4月</option>
                            <option value = '5'>5月</option>
                            <option value = '6'>6月</option>
                            <option value = '7'>7月</option>
                            <option value = '8'>8月</option>
                            <option value = '9'>9月</option>
                            <option value = '10'>10月</option>
                            <option value = '11'>11月</option>
                            <option value = '12'>12月</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>事業内容</th>
                    <td class = 'arrayitem'>
                        <input type = "button" class = 'commonbutton' value ='追加' id = 'businessdetailsincreasetext'><input type = "button" class = 'commonbutton' value ='削除' id = 'businessdetailsdecreasetext'>
                        <?php echo $businessdetailtext;?>
                    </td>
                    <th>取引銀行</th>
                    <td class = 'arrayitem'>
                        <input type = "button" class = 'commonbutton' value ='追加' id = 'bankincreasetext'><input type = "button" class = 'commonbutton' value ='削除' id = 'bankdecreasetext'>
                        <?php echo $banktext;?>
                    </td>
                    
                </tr>
                <tr>
                    <th>ホームページURL</th>
                    <td colspan="2"><input type = 'text' class = 'validate[optional,custom[url]]' name = 'homepage' value = <?php echo htmlentities($settexthomepage); ?>></td>
                </tr>
			</table>
			<button type="subit" name="addcompany" class = "btn">外勤先の会社の登録</button>

			
		</form>
	</div>
</div>
<p class = 'floatclear'></p>
	<?php
		if (isset($_POST['displayoutsoucer'])) {
			$result = "登録しました";
		}
	?>
</body>
</html>


<script>
	let businessdetailsfunc = inputfield('businessdetails',<?php echo $businessdetailscount_json; ?>);
    let bankfunc = inputfield('bank',<?php echo $bankcount_json; ?>);


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
							$('#localtext').val(result.address2+' '+result.address3);
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