
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../css/regioutsoucer.css">
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
					<th>名前</th>
					<td><input type="text" name="name" class = "validate[required]" value ="<?php echo htmlentities($settextname);?>"><br>
						<a class = 'failfont'><?php echo $namefailtext; ?></a>
					</td>
				</tr>
				<tr>
					<th>生年月日</th>
					<td><?php echo $daytext ?></td>
				</tr>
				<tr>
					<th>入社日</th>
					<td><?php echo $joindaytext ?></td>
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


	/* $(function(){
        //<form>タグのidを指定
        $("#regiform").validationEngine(
            'attach', {
                promptPosition: "topRight"//エラーメッセージ位置の指定
            }
        );
    }); */

</script>