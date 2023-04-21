
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="regicompany.css">
<title>会社の登録フォーム</title>
</head>
<!-- header -->

<body>
<?php
  include("../header.php");
?>
	<?php
		require_once '../link.php';
	?>
<br><br><br><br><br><br>
<h2>登録フォーム<h2>



<div id = "regicompany" class = 'formsize box'>
	<form action="regicompanycontroller.php" method="post">
		<p> 会社名: <input type="text" name="companyname" value="<?php echo $model->getsettextcompanyname();?>"></p>
		<p> 従業員数: <input type="text" name="numberofemployees" value="<?php echo $model->getsettextnumberofemployees();?>"></p>
		
		<?php
			echo '<p>設立日<select name="year">'. "\n";
			if($model->getaddoutsoucer){echo '<option value = '.$model->getsettextyear().'>'.$model->getsettextyear().'</option>\n';}
			for($i = date('Y'); $i >= 0; $i--) {
				echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
			}
			echo '</select>年' . "\n";
			echo '<select name="month">' . "\n";
			if($model->getaddoutsoucer()){echo '<option value = '.$model->getsettextmonth().'>'.$_POST['month'].'</option>\n';}
			for ($i = 1; $i <= 12; $i++) {
				echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
			}
			echo '</select>月' . "\n";
			echo '<select name="day">' . "\n";
			if($model->getaddoutsoucer){echo '<option value = '.$model->getsettextday.'>'.$model->getsettextday.'</option>\n';}
			for ($i = 1; $i <= 31; $i++) {
				echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
			}
			echo '</select>日</p>' . "\n";
		?>



		<button type="subit" name="addcompany" class = "btn">外勤先の会社の登録</button>
		
	</form>
</div>
<p class = 'floatclear'></p>
	

	<?php
		if (isset($_POST['displayoutsoucer'])) {
			$result = "登録しました";
		}
	?>
</body>
</html>