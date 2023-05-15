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

	$regiflag = false;
	$inputrule = true;
	$regisuccesstext = '';
	$settextname = '';
	$settextemployeeid = '';
	if(isset($_POST['addoutsoucer'])){
		if($_POST['name']==''or $_POST['employeeid']=='' or$_POST['birthyear']==''or$_POST['birthmonth']==''or$_POST['birthday']==''or$_POST['joinyear']==''or$_POST['joinmonth']==''or$_POST['joinday']==''){
			$regisuccesstext .= "必要事項に空欄があります<br>";
			$inputrule = false;
		}else{
			if(preg_match('/^[0-9]{4,}$/',$_POST['employeeid'])){
				$inputrule = true;
			}else{
				$regisuccesstext .= "社員番号は半角数字4文字以上にしてください<br>";
				$inputrule = false;
			}
		}
		if($inputrule == true){

			$used = employeeidused($_POST['employeeid']);

			if($used == true){
				$regisuccesstext .= 'すでにその社員番号は使われています';
			}
			elseif($used == false){
				$regibirth = $_POST['birthyear'].'-'.$_POST['birthmonth'].'-'.$_POST['birthday'];
				$regijoin = $_POST['joinyear'].'-'.$_POST['joinmonth'].'-'.$_POST['joinday'];
				try{
					$numberringquery = "UPDATE numbering SET numbering = LAST_INSERT_ID(numbering + 1) WHERE tablename = 'staffname'";
					$database -> query($numberringquery);
					$numberringquery = 'SELECT numbering FROM numbering where tablename = \'staffname\' ';
					$numberringid = mysqli_fetch_assoc($database -> query($numberringquery));
					$info = '\''.$numberringid['numbering'].'\',\''.$_POST['name'].'\',\''.$_POST['employeeid'].'\',\''.$regibirth.'\',\''.$regijoin.'\'';
					$query = "INSERT INTO staffname (id,name,employeeid,birthday,joincompanyday)VALUES(".$info.")";
					$database -> query($query);
					$regiflag = true;
					$regisuccesstext .= '登録しました';
					$newid =  "".$numberringid['numbering']."";
					
				}catch (Exception $e){
					/* echo "エラー発生:" . $e->getMessage().'/n';
					echo "登録できませんでした"; */
					$regisuccesstext .= '少し時間をおいてもう一度お試しください';
				}
			}
		}elseif($inputrule == false){
			
		}
		if($regiflag == false){
			$settextname = $_POST['name'];
			$settextemployeeid = $_POST['employeeid'];
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
