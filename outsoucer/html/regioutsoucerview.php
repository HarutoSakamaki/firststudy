
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
		<form action="regioutsoucer.php" method="post" id = "regiform">
			<p> 名前: <input type="text" name="name" class = "validate[required]" value ="<?php if(isset($_POST['addoutsoucer'])){echo htmlentities($_POST['name']);}?>"></p>
			<p>社員番号: <input type = "number" name = "employeeid" class = "validate[required]" value = ""></p>
			<?php
				echo $daytext;
			?>
			<p><button type="subit" name="addoutsoucer" class = "btn">従業員の登録</button></p>
			
			
		</form>
	</div>
</div>
<div>
	<?php
		if($regiflag){
			echo <<<EOM
			<p>登録できました</p>
			<form action = 'changeoutsoucer.php' method = 'post'>
				<input type = 'submit' name = 'changeform' value = '詳細を設定する'>
				<input type = 'hidden' name = 'id' value = '{$newid}'>
			</form>

			EOM;
		}
	?>
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


	/* $(function(){
        //<form>タグのidを指定
        $("#regiform").validationEngine(
            'attach', {
                promptPosition: "topRight"//エラーメッセージ位置の指定
            }
        );
    }); */

</script>