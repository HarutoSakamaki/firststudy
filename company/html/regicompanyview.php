
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
		<form action="regicompany.php" method="post" id = "regicompanyform">
			<p> 会社名: <input type="text" class = "validate[required]" name="companyname" value="<?php if(isset($_POST['addcompany'])){echo htmlentities($_POST['companyname']);}?>"></p>
			<p> 従業員数: <input type="text" class = "validate[required,custom[integer]]" name="numberofemployees" value="<?php if(isset($_POST['addcompany'])){echo htmlentities($_POST['numberofemployees']);}?>"></p>
			
			<?php
				echo $birthdaytext;
			?>
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


	$(function(){
        //<form>タグのidを指定
        $("#regicompanyform").validationEngine(
            'attach', {
                promptPosition: "topRight"//エラーメッセージ位置の指定
            }
        );
    });


</script>