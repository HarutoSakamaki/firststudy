<script src = '../js/jquery-3.6.4.min.js'></script>
<link rel="stylesheet" href="../css/validationEngine.jquery.css">
<script src="../js/jquery.validationEngine.js"></script>
<script src="../js/jquery.validationEngine-ja.js" charset="UTF-8"></script>
<script src = '../js/change.js'></script>
<script src = '../js/common.js'></script>


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

	$settextcompany = '';
	$settextpresident = '';
	$settextbusinessdetails = [];
	$settextbank = [];
	$settextprefectures = '';
	$settextsales = '';
	$settextdigit = 1;
	$settextlocation = '';
	$settextnumberofemployees = '';
	$settextestablishyear = '';
	$settextestablishmonth = '';
	$settextestablishday = '';
	$settextcapitaldigit = 1;
	$settextcapital = '';
	$settextaverageage = '';
	$settextclosingmonth = '';
	$settexthomepage = '';
	
	$companynamefailtext = '';
	$averageagefailtext = '';
	$numberofemployeesfailtext = '';
	$emptytext = '';
	$regisuccesstext = '';
	$salesfailtext = '';
	$capitalfailtext = '';
	$regisuccess = false;

	if(isset($_POST['addcompany'])){
		/* 入力規則チェック */
		$inputrule = true;
		if($_POST['company'] == '' ){
            $inputrule = false;
            $companynamefailtext .= '空欄になっています<br>' ;
        }
        if($_POST['numberofemployees'] != '' and !preg_match("/^[0-9]+$/", $_POST['numberofemployees'])){
            $inputrule = false;
            $numberofemployeesfailtext .= '半角数字を入力して下さい<br>' ;
        }
        if($_POST['averageage'] != '' and !preg_match("/^[0-9.]+$/", $_POST['averageage'])){
            $inputrule = false;
            $averageagefailtext .= '半角数字(小数でも可)を入力して下さい<br>' ;
        }
        if($_POST['sales'] != '' and !preg_match("/^[0-9.]+$/", $_POST['sales'])){
            $inputrule = false;
            $salesfailtext .= '数字(小数でも可)を入力して下さい<br>' ;
        }if($_POST['capital'] != '' and !preg_match("/^[0-9]+$/",$_POST['capital'])){
            $inputrule = false;
            $capitalfailtext .= '数字を入力して下さい<br>' ;
        }
			
		if($inputrule == true){
			$establishdate = $_POST['establishyear'].'-'.$_POST['establishmonth'].'-'.$_POST['establishday'];
			if($_POST['sales']==''){
				$sendsales = '';
			}else{
				$sendsales = $_POST['sales']*$_POST['digit'];
			}
			if($_POST['capital']==''){
				$sendcapital = '';
			}else{
				$sendcapital = $_POST['capital']*$_POST['capitaldigit'];
			}
			$businessdetailsjson = json_encode($_POST['businessdetails'], JSON_UNESCAPED_UNICODE);
			$bankjson = json_encode($_POST['bank'], JSON_UNESCAPED_UNICODE);

			try{
				$numberingquery = "SELECT no_tuban FROM tbs_saiban WHERE pk_id_saiban = 2";
				$result = $database -> query($numberingquery);
				$tuban = (mysqli_fetch_assoc($result)['no_tuban'])+1;
				$query = <<<EDO
					INSERT INTO tbm_company_kiso (pk_id_company , nm_company ,su_numberofemployees, dt_establishdate,kbn_prefectures,nm_location,nm_president,nm_businessdetails,nm_homepage,kbn_closingmonth,su_sales,su_capital,su_averageage,nm_bank)
					VALUES('{$tuban}','{$_POST['company']}','{$_POST['numberofemployees']}','{$establishdate}','{$_POST['prefectures']}','{$_POST['location']}'
					,'{$_POST['president']}','{$businessdetailsjson}','{$_POST['homepage']}','{$_POST['closingmonth']}','{$sendsales}','{$sendcapital}','{$_POST['averageage']}','{$bankjson}')
				EDO;
				$database -> query($query);
				$numberingquery = <<<EDO
					UPDATE tbs_saiban SET no_tuban = {$tuban} WHERE pk_id_saiban = 2;
				EDO;
				$database -> query($numberingquery);
				$newid = $tuban;
				$regisuccesstext .= <<<EDO
					<div class = 'successbox'>登録しました</div>
					EDO;
				$regisuccess = true;
			}catch (Exception $e){
				echo "エラー発生:" . $e->getMessage().'<br>';
				echo "登録できませんでした";
			}
		}
		
		if($regisuccess == false){

			$regisuccesstext .= '<div class = \'failbox\'>もう一度入力して下さい</div>';
			$settextcompany = $_POST['company'];
			$settextpresident = $_POST['president'];
			$settextbusinessdetails = $_POST['businessdetails'];
			$settextbank = $_POST['bank'];
			$settextprefectures = $_POST['prefectures'];
			$settextsales = $_POST['sales'];
			$settextdigit = $_POST['digit'];
			$settextlocation = $_POST['location'];
			$settextnumberofemployees = $_POST['numberofemployees'];
			$settextestablishyear = $_POST['establishyear'];
			$settextestablishmonth = $_POST['establishmonth'];
			$settextestablishday = $_POST['establishday'];
			$settextcapitaldigit = $_POST['capitaldigit'];
			$settextcapital = $_POST['capital'];
			$settextaverageage = $_POST['averageage'];
			$settextclosingmonth = $_POST['closingmonth'];
			$settexthomepage = $_POST['homepage'];
		}
	}
	if($settextdigit == '1'){
        $settextdigit2 = '円';
    }elseif($settextdigit == '1000'){
        $settextdigit2 = '千円';
    }elseif($settextdigit == '1000000'){
        $settextdigit2 = '百万円';
    }elseif($settextdigit == '1000000000'){
        $settextdigit2 = '十億円';
    }elseif($settextdigit == '1000000000000'){
        $settextdigit2 = '兆円';
    }
    if($settextcapitaldigit == '1'){
        $settextcapitaldigit2 = '円';
    }elseif($settextcapitaldigit == '1000'){
        $settextcapitaldigit2 = '千円';
    }elseif($settextcapitaldigit == '1000000'){
        $settextcapitaldigit2 = '百万円';
    }elseif($settextcapitaldigit == '1000000000'){
        $settextcapitaldigit2 = '十億円';
    }elseif($settextcapitaldigit == '1000000000000'){
        $settextcapitaldigit2 = '兆円';
    }

	$businessdetailtext = '';
    $businessdetailtext.='<input type = \'hidden\' id = \'businessdetails-1\' >';
    if(isset($settextbusinessdetails[0])){
        $businessdetailtext.='<br id = \'businessdetailsbr0\'><input type = \'text\' name = \'businessdetails[]\' value = \''.htmlentities($settextbusinessdetails[0]).'\' id = \'businessdetails0\' >';
        $count = 1;
    }else{
        $businessdetailtext.='<br id = \'businessdetailsbr0\'><input type = \'text\' name = \'businessdetails[]\' value = \'\' id = \'businessdetails0\' >';
        $count = 1;
    }
    while(isset($settextbusinessdetails[$count])){
        $businessdetailtext.='<br id = \'businessdetailsbr'.$count.'\'><input type = \'text\' name = \'businessdetails[]\' value = \''.htmlentities($settextbusinessdetails[$count]).'\' id = \'businessdetails'.$count.'\'>';
        $count++;
    }
    $businessdetailscount_json = json_encode($count);

	$banktext = '';
    $banktext.='<input type = \'hidden\' id = \'bank-1\' >';
    if(isset($settextbank[0])){
        $banktext.='<br id = \'bankbr0\'><input type = \'text\' name = \'bank[]\' value = \''.htmlentities($settextbank[0]).'\' id = \'bank0\' >';
        $count = 1;
    }else{
        $banktext.='<br id = \'bankbr0\'><input type = \'text\' name = \'bank[]\' value = \'\' id = \'bank0\' >';
        $count = 1;
    }
    while(isset($settextbank[$count])){
        $banktext.='<br id = \'bankbr'.$count.'\'><input type = \'text\' name = \'bank[]\' value = \''.htmlentities($settextbank[$count]).'\' id = \'bank'.$count.'\'>';
        $count++;
    }
    $bankcount_json = json_encode($count);


	$establishdaytext = '<p><select name="establishyear">'. "\n";
	if(isset($_POST['addcompany'])){$establishdaytext.='<option value = '.$_POST['establishyear'].'>'.$_POST['establishyear'].'</option>\n';}
	for($i = date('Y'); $i >= 0; $i--) {
		$establishdaytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
	}
	$establishdaytext.='</select>年' . "\n".'<select name="establishmonth">' . "\n";
	if(isset($_POST['addcompany'])){$establishdaytext.='<option value = '.$_POST['establishmonth'].'>'.$_POST['establishmonth'].'</option>\n';}
	for ($i = 1; $i <= 12; $i++) {
		$establishdaytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
	}
	$establishdaytext.='</select>月' . "\n".'<select name="establishday">' . "\n";
	if(isset($_POST['addcompany'])){$establishdaytext.='<option value = '.$_POST['establishday'].'>'.$_POST['establishday'].'</option>\n';}
	for ($i = 1; $i <= 31; $i++) {
		$establishdaytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
	}
	$establishdaytext.='</select>日</p>' . "\n";


	
	
	require('html/regicompanyview.php');
	



	
?>