
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
<h2>登録フォーム</h2>
</body>

<body>


<div id = "regicompany" class = 'formsize box'>
	<div class = "boxtitle">会社の登録</div>
	<div class = "boxcontent">
		<form action="regicompany.php" method="post">
			<p> 会社名: <input type="text" name="companyname" value="<?php if(isset($_POST['addcompany'])){echo $_POST['companyname'];}?>"></p>
			<p> 従業員数: <input type="text" name="numberofemployees" value="<?php if(isset($_POST['addcompany'])){echo $_POST['numberofemployees'];}?>"></p>
			
			<?php
				echo '<p>設立日<select name="year">'. "\n";
				if(isset($_POST['addcompany'])){echo '<option value = '.$_POST['year'].'>'.$_POST['year'].'</option>\n';}
				for($i = date('Y'); $i >= 0; $i--) {
					echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
				}
				echo '</select>年' . "\n";
				echo '<select name="month">' . "\n";
				if(isset($_POST['addcompany'])){echo '<option value = '.$_POST['month'].'>'.$_POST['month'].'</option>\n';}
				for ($i = 1; $i <= 12; $i++) {
					echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
				}
				echo '</select>月' . "\n";
				echo '<select name="day">' . "\n";
				if(isset($_POST['addcompany'])){echo '<option value = '.$_POST['day'].'>'.$_POST['day'].'</option>\n';}
				for ($i = 1; $i <= 31; $i++) {
					echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
				}
				echo '</select>日</p>' . "\n";
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