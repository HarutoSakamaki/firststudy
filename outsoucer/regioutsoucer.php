<script src = '../js/jquery-3.6.4.min.js'></script>
<link rel="stylesheet" href="../css/validationEngine.jquery.css">
<script src="../js/jquery.validationEngine.js"></script>
<script src="../js/jquery.validationEngine-ja.js" charset="UTF-8"></script>
<script src="../js/common.js" charset="UTF-8"></script>


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
	$furiganafailtext = '';
	$phonenumberfailtext = '';
	$mailaddressfailtext = '';

	$settextflag = false;
	$settextname = '';
	$settextfurigana = '';
	$settextprefecture = '';
	$settextaddress = '';
	$settextphonenumber = '';
	$settextmailaddress = '';
	$settextworkhistory = '';
	$settextlicense = '';
	$settextmotivation = '';
	$employeeidfailtext = '';
	$workhistorytext = '';
	$licensetext = '';


	if(isset($_POST['addoutsoucer'])){
		if($_POST['name']==''){
			$namefailtext .= '空欄になっています<br>';
			$inputrule = false;
		}
		if($_POST['furigana'] != '' and !preg_match('/^[ァ-ヾ]+$/u', $_POST['furigana'])){
            $inputrule =false;
            $furiganafailtext .= 'フリガナはカタカナで入力してください<br>';
        }
		if($_POST['mailaddress'] != '' and !preg_match('/^[a-z0-9._+^~-]+@[a-z0-9.-]+$/i', $_POST['mailaddress'])){
            $inputrule = false;
            $mailaddressfailtext .= 'メールアドレスが正しくありません<br>';
        }
		if($_POST['phonenumber'] != '' and !preg_match("/^[0-9]{10,11}$/", $_POST['phonenumber'])){
            $inputrule = false;
            $phonenumberfailtext .= '電話番号はハイフン無しの数字で10、11桁で入力してください<br>';
        }

		if($inputrule == true){
			
			$regibirth = $_POST['birthyear'].'-'.$_POST['birthmonth'].'-'.$_POST['birthday'];
			$regijoin = $_POST['joinyear'].'-'.$_POST['joinmonth'].'-'.$_POST['joinday'];
			$workhistory = array_filter($_POST['workhistory']);
			$workhistoryjson = json_encode($workhistory, JSON_UNESCAPED_UNICODE);
			$license = array_filter($_POST['license']);
			$licensejson = json_encode($license, JSON_UNESCAPED_UNICODE);
			try{
				$numberingquery = "SELECT no_tuban FROM tbs_saiban WHERE pk_id_saiban = 1";
				$result = $database -> query($numberingquery);
				$tuban = (mysqli_fetch_assoc($result)['no_tuban'])+1;
				$setemployeeid = $tuban+10000;
				
				$query = <<<EDO
					INSERT INTO tbm_staffname_kiso (pk_id_staffname,nm_name,no_employeeid,dt_birthday,dt_joincompanyday,nm_furigana,kbn_prefectures,nm_address,nm_mailaddress,su_phonenumber,nm_workhistory,nm_license,etc_motivation)
					VALUES('{$tuban}','{$_POST['name']}','{$setemployeeid}','{$regibirth}','{$regijoin}','{$_POST['furigana']}','{$_POST['prefectures']}'
					,'{$_POST['address']}','{$_POST['mailaddress']}','{$_POST['phonenumber']}','{$workhistoryjson}','{$licensejson}','{$_POST['motivation']}')
				EDO;
				$database -> query($query);

				$numberingquery = <<<EDO
					UPDATE tbs_saiban SET no_tuban = {$tuban} WHERE pk_id_saiban = 1;
				EDO;
				$database -> query($numberingquery);
				$newid = $tuban;

				$regiflag = true;
				$regisuccesstext .= <<<EDO
					<div class = 'successbox' >登録しました
					</div>
				EDO;
			}catch (Exception $e){
				echo "エラー発生:" . $e->getMessage().'/n';
				echo "登録できませんでした";  
				$regisuccesstext .= '<div class = \'failbox\'>少し時間をおいてもう一度お試しください</div>';
			}
			
		}elseif($inputrule == false){
			$regisuccesstext .= '<div class = \'failbox\'>もう一度入力して下さい</div>';
		}
		if($regiflag == false){
			$settextflag = true;
		}
	}
	if($settextflag == true){
		$settextname = $_POST['name'];
		$settextfurigana = $_POST['furigana'];
		$settextprefectures = $_POST['prefectures'];
		$settextaddress = $_POST['address'];
		$settextphonenumber = $_POST['phonenumber'];
		$settextmailaddress = $_POST['mailaddress'];
		$settextworkhistory = $_POST['workhistory'];
		$settextlicense = $_POST['license'];
		$settextmotivation = $_POST['motivation'];
	}



	$workhistorytext= '';
    $workhistorytext.= '<input type = \'hidden\' id = \'workhistory-1\' >';
    if(isset($settextworkhistory[0])){
        $workhistorytext.='<br id=\'workhistorybr0\'><input type = \'text\' name = \'workhistory[]\' value = \''.$settextworkhistory[0].'\' id = \'workhistory0\' >';
        $count = 1;
    }else{
        $workhistorytext.='<br id=\'workhistorybr0\'><input type = \'text\' name = \'workhistory[]\' value = \'\' id = \'workhistory0\' >';
        $count = 1;
    }
    while(isset($settextworkhistory[$count])){
        $workhistorytext .='<br id=\'workhistorybr'.$count.'\'><input type = \'text\' name = \'workhistory[]\' value = \''.$settextworkhistory[$count].'\' id = \'workhistory'.$count.'\'>';
        $count++;
    }
    $workhistorycount_json = json_encode($count);

	$licensetext = '';

    $licensetext.='<input type = \'hidden\' id = \'license-1\' >';
    if(isset($settextlicense[0])){
        $licensetext.='<br id = \'licensebr0\'><input type = \'text\' name = \'license[]\' value = \''.$settextlicense[0].'\' id = \'license0\' >';
        $count = 1;
    }else{
        $licensetext.='<br id = \'licensebr0\'><input type = \'text\' name = \'license[]\' value = \'\' id = \'license0\' >';
        $count = 1;
    }
    while(isset($settextlicense[$count])){
        $licensetext.='<br id = \'licensebr'.$count.'\'><input type = \'text\' name = \'license[]\' value = \''.$settextlicense[$count].'\' id = \'license'.$count.'\'>';
        $count++;
    }
    $licensecount_json = json_encode($count);

	
	$daytext = '';
	$daytext.='<p><select name="birthyear">'. "\n";
	if($settextflag == true){$daytext.='<option value = '.$_POST['birthyear'].'>'.$_POST['birthyear'].'</option>\n';}
	for($i = date('Y'); $i >= 1900; $i--) {
		$daytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
	}
	$daytext.='</select>年' . "\n".
		'<select name="birthmonth">' . "\n";
	if($settextflag == true){$daytext.='<option value = '.$_POST['birthmonth'].'>'.$_POST['birthmonth'].'</option>\n';}
	for ($i = 1; $i <= 12; $i++) {
		$daytext.= '<option value="' .$i . '">' . $i .'</option>'. "\n";
	}
	$daytext.='</select>月' . "\n".
		'<select name="birthday">' . "\n";
	if($settextflag == true){$daytext.='<option value = '.$_POST['birthday'].'>'.$_POST['birthday'].'</option>\n';}
	for ($i = 1; $i <= 31; $i++) {
		$daytext .= '<option value="' .$i . '">' . $i .'</option>'. "\n";
	}
	$daytext.='</select>日</p>' . "\n";

	$joindaytext = '';

	$joindaytext.='<p><select name="joinyear">'. "\n";
	if($settextflag == true){$joindaytext.='<option value = '.$_POST['joinyear'].'>'.$_POST['joinyear'].'</option>\n';}
	for($i = date('Y'); $i >= 1900; $i--) {
		$joindaytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
	}
	$joindaytext.='</select>年' . "\n".
		'<select name="joinmonth">' . "\n";
	if($settextflag == true){$joindaytext.='<option value = '.$_POST['joinmonth'].'>'.$_POST['joinmonth'].'</option>\n';}
	for ($i = 1; $i <= 12; $i++) {
		$joindaytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
	}
	$joindaytext.='</select>月' . "\n".
		'<select name="joinday">' . "\n";
	if($settextflag == true){$joindaytext.='<option value = '.$_POST['joinday'].'>'.$_POST['joinday'].'</option>\n';}
	for ($i = 1; $i <= 31; $i++) {
		$joindaytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
	}
	$joindaytext.='</select>日</p>' . "\n";

    require_once('html/regioutsoucerview.php');
	
?>
