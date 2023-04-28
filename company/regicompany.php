
<?php
	require_once '../link.php';
	$database = database('staff');

?>
<?php
	
	if(isset($_POST['addcompany'])){
		/* 入力規則チェック */
		$numberofemployeesflag = false;
		if($_POST['numberofemployees'] == '' or preg_match("/^[0-9]+$/", $_POST['numberofemployees'])){
			$numberofemployeesflag = true;
		}

		if($_POST['companyname']==''or$_POST['year']==''or$_POST['month']==''or$_POST['day']==''or$_POST['numberofemployees']==''){
			echo "必要事項に空欄があります<br>";
		}else{
			$regidate = $_POST['year'].'-'.$_POST['month'].'-'.$_POST['day'];
			$info = '\''.$_POST['companyname'].'\',\''.$regidate.'\',\''.$_POST['numberofemployees'].'\'';
			
			if($numberofemployeesflag == true){
				try{
					$numberringquery = "UPDATE numberring SET id = LAST_INSERT_ID(id + 1) WHERE tablename = 'company'";
					$database -> query($numberringquery);
					$numberringquery = 'SELECT id FROM numberring where tablename = \'company\' ';
					$numberringid = mysqli_fetch_assoc($database -> query($numberringquery));
					$info = '\''.$numberringid['id'].'\',\''.$_POST['companyname'].'\',\''.$regidate.'\',\''.$_POST['numberofemployees'].'\'';
					$query = "INSERT INTO company (id , company , establishdate, numberofemployees)VALUES(".$info.")";
					$database -> query($query);
					echo '会社を登録しました';
				}catch (Exception $e){
					echo "エラー発生:" . $e->getMessage().'<br>';
					echo "登録できませんでした";
				}
			}else{
				echo '有効な値を入力して下さい';
			}
		}
	}

	$birthdaytext = '<p>設立日<select name="year">'. "\n";
	if(isset($_POST['addcompany'])){$birthdaytext.='<option value = '.$_POST['year'].'>'.$_POST['year'].'</option>\n';}
	for($i = date('Y'); $i >= 0; $i--) {
		$birthdaytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
	}
	$birthdaytext.='</select>年' . "\n".'<select name="month">' . "\n";
	if(isset($_POST['addcompany'])){$birthdaytext.='<option value = '.$_POST['month'].'>'.$_POST['month'].'</option>\n';}
	for ($i = 1; $i <= 12; $i++) {
		$birthdaytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
	}
	$birthdaytext.='</select>月' . "\n".'<select name="day">' . "\n";
	if(isset($_POST['addcompany'])){$birthdaytext.='<option value = '.$_POST['day'].'>'.$_POST['day'].'</option>\n';}
	for ($i = 1; $i <= 31; $i++) {
		$birthdaytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
	}
	$birthdaytext.='</select>日</p>' . "\n";

	
	require('html/regicompanyview.php');
	



	/* echo '<p>設立日<select name="year">'. "\n";
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
	echo '</select>日</p>' . "\n"; */
?>