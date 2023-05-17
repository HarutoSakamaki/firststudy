
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
			
			<a class = 'failfont'><?php echo $emptytext; ?></a>
			<table class = 'inputtable'>
				<tr>
					<th>会社名</th>
					<td><input type="text" class = "validate[required]" name="companyname" value = "<?php echo htmlentities($settextcompanyname); ?>"><br>
						
					</td>
				</tr>
				<tr>
					<th>従業員数</th>
					<td><input type="number" class = "validate[required,custom[integer]]" name="numberofemployees" value="<?php echo htmlentities($settextnumberofemployees);?>" placeholder="半角数字4桁以上"><br>
					<a class = 'failfont'><?php echo $numberofemployeesfailtext; ?></a>
					</td>
				</tr>
				<tr>
					<th>設立日</th>
					<td><?php echo $establishdaytext ?></td>
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


	/* $(function(){
        //<form>タグのidを指定
        $("#regicompanyform").validationEngine(
            'attach', {
                promptPosition: "topRight"//エラーメッセージ位置の指定
            }
        );
    }); */


</script>