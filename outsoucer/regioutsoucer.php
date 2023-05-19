<script src = '../js/jquery-3.6.4.min.js'></script>
<link rel="stylesheet" href="../css/validationEngine.jquery.css">
<script src="../js/jquery.validationEngine.js"></script>
<script src="../js/jquery.validationEngine-ja.js" charset="UTF-8"></script>



<?php
    require_once '../link.php';
    $database = database('staff');

	session_start();
	session_regenerate_id(true);
    if(isset($_SESSION['login'])){
        
    }else{
        $_SESSION['againlogin'] = true;
        header("Location: ../others/login.php");
        exit();
    }

	$regiflag = false;
	$inputrule = true;
	$regisuccesstext = '';
	$regifailtext = '';
	$namefailtext = '';
	$settextname = '';
	$settextemployeeid = '';
	$employeeidfailtext = '';
	if(isset($_POST['addoutsoucer'])){
		if($_POST['name']==''or$_POST['birthyear']==''or$_POST['birthmonth']==''or$_POST['birthday']==''or$_POST['joinyear']==''or$_POST['joinmonth']==''or$_POST['joinday']==''){
			if($_POST['name'] == ''){
				$namefailtext .= '空欄になっています<br>';
			}
			$inputrule = false;
		}
		if($inputrule == true){
			
			$regibirth = $_POST['birthyear'].'-'.$_POST['birthmonth'].'-'.$_POST['birthday'];
			$regijoin = $_POST['joinyear'].'-'.$_POST['joinmonth'].'-'.$_POST['joinday'];
			try{
				$numberingquery = "UPDATE tbs_numbering SET no_numbering = LAST_INSERT_ID(no_numbering + 1) WHERE nm_tablename = 'staffname'";
				$database -> query($numberingquery);
				$numberingquery = 'SELECT no_numbering FROM tbs_numbering where nm_tablename = \'staffname\' ';
				$numberingid = mysqli_fetch_assoc($database -> query($numberingquery));
				$setemployeeid = $numberingid['no_numbering']+10000;
				$info = '\''.$numberingid['no_numbering'].'\',\''.$_POST['name'].'\',\''.$setemployeeid.'\',\''.$regibirth.'\',\''.$regijoin.'\'';
				$query = "INSERT INTO tbm_staffname (pk_id_staffname,nm_name,no_employeeid,dt_birthday,dt_joincompanyday)VALUES(".$info.")";
				$database -> query($query);
				$regiflag = true;
				$newid =  $numberingid['no_numbering'];
				$regisuccesstext .= <<<EDO
					<div class = 'successbox' >登録しました
						<form action = 'changeoutsoucer.php' method = 'post'>
							<input type = 'submit' class = 'commonbutton' name = 'changeform' value = '詳細を設定する'>
							<input type = 'hidden' name = 'id' value = '{$newid}'>
						</form>
					</div>
					EDO;
			}catch (Exception $e){
				/* echo "エラー発生:" . $e->getMessage().'/n';
				echo "登録できませんでした"; */
				$regisuccesstext .= '<div class = \'failbox\'>少し時間をおいてもう一度お試しください</div>';
			}
			
		}elseif($inputrule == false){
			$regisuccesstext .= '<div class = \'failbox\'>もう一度入力して下さい</div>';
		}
		if($regiflag == false){
			$settextname = $_POST['name'];
		}
	}

	
	$daytext = '';
	$daytext.='<p><select name="birthyear">'. "\n";
	if(isset($_POST['addoutsoucer'])){$daytext.='<option value = '.$_POST['birthyear'].'>'.$_POST['birthyear'].'</option>\n';}
	for($i = date('Y'); $i >= 1900; $i--) {
		$daytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
	}
	$daytext.='</select>年' . "\n".
		'<select name="birthmonth">' . "\n";
	if(isset($_POST['addoutsoucer'])){$daytext.='<option value = '.$_POST['birthmonth'].'>'.$_POST['birthmonth'].'</option>\n';}
	for ($i = 1; $i <= 12; $i++) {
		$daytext.= '<option value="' .$i . '">' . $i .'</option>'. "\n";
	}
	$daytext.='</select>月' . "\n".
		'<select name="birthday">' . "\n";
	if(isset($_POST['addoutsoucer'])){$daytext.='<option value = '.$_POST['birthday'].'>'.$_POST['birthday'].'</option>\n';}
	for ($i = 1; $i <= 31; $i++) {
		$daytext .= '<option value="' .$i . '">' . $i .'</option>'. "\n";
	}
	$daytext.='</select>日</p>' . "\n";

	$joindaytext = '';

	$joindaytext.='<p><select name="joinyear">'. "\n";
	if(isset($_POST['addoutsoucer'])){$joindaytext.='<option value = '.$_POST['joinyear'].'>'.$_POST['joinyear'].'</option>\n';}
	for($i = date('Y'); $i >= 1900; $i--) {
		$joindaytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
	}
	$joindaytext.='</select>年' . "\n".
		'<select name="joinmonth">' . "\n";
	if(isset($_POST['addoutsoucer'])){$joindaytext.='<option value = '.$_POST['joinmonth'].'>'.$_POST['joinmonth'].'</option>\n';}
	for ($i = 1; $i <= 12; $i++) {
		$joindaytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
	}
	$joindaytext.='</select>月' . "\n".
		'<select name="joinday">' . "\n";
	if(isset($_POST['addoutsoucer'])){$joindaytext.='<option value = '.$_POST['joinday'].'>'.$_POST['joinday'].'</option>\n';}
	for ($i = 1; $i <= 31; $i++) {
		$joindaytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
	}
	$joindaytext.='</select>日</p>' . "\n";

    require_once('html/regioutsoucerview.php');
	
?>
