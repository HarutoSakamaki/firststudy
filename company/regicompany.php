<script src = '../js/jquery-3.6.4.min.js'></script>
<link rel="stylesheet" href="../css/validationEngine.jquery.css">
<script src="../js/jquery.validationEngine.js"></script>
<script src="../js/jquery.validationEngine-ja.js" charset="UTF-8"></script>


<?php
	require_once '../link.php';
	$database = database('staff');
	
	session_start();
    if(isset($_SESSION['login'])){
        
    }else{
        $_SESSION['againlogin'] = true;
        header("Location: ../others/login.php");
        exit();
    }

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
					$numberingquery = "UPDATE numbering SET numbering = LAST_INSERT_ID(numbering + 1) WHERE tablename = 'company'";
					$database -> query($numberingquery);
					$numberingquery = 'SELECT numbering FROM numbering where tablename = \'company\' ';
					$numberingid = mysqli_fetch_assoc($database -> query($numberingquery));
					$info = '\''.$numberingid['numbering'].'\',\''.$_POST['companyname'].'\',\''.$regidate.'\',\''.$_POST['numberofemployees'].'\'';
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

	$establishdaytext = '<p><select name="year">'. "\n";
	if(isset($_POST['addcompany'])){$establishdaytext.='<option value = '.$_POST['year'].'>'.$_POST['year'].'</option>\n';}
	for($i = date('Y'); $i >= 0; $i--) {
		$establishdaytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
	}
	$establishdaytext.='</select>年' . "\n".'<select name="month">' . "\n";
	if(isset($_POST['addcompany'])){$establishdaytext.='<option value = '.$_POST['month'].'>'.$_POST['month'].'</option>\n';}
	for ($i = 1; $i <= 12; $i++) {
		$establishdaytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
	}
	$establishdaytext.='</select>月' . "\n".'<select name="day">' . "\n";
	if(isset($_POST['addcompany'])){$establishdaytext.='<option value = '.$_POST['day'].'>'.$_POST['day'].'</option>\n';}
	for ($i = 1; $i <= 31; $i++) {
		$establishdaytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
	}
	$establishdaytext.='</select>日</p>' . "\n";

	
	require('html/regicompanyview.php');
	



	
?>