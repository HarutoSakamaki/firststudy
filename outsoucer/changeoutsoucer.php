
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

    $changesuccesstext = '';
    $namefailtext = '';
    $furiganafailtext = '';
    $phonenumberfailtext = '';
    $mailaddressfailtext = '';
    
    if(isset($_POST['change'])){
        $id = $_POST['id'];
        $changename = ' nm_name = \'' . $_POST['name'].'\' ';
        $changefurigana = ' nm_furigana = \'' .$_POST['furigana'].'\'';
        $changeprefectures = ' kbn_prefectures = \''.$_POST['prefectures'].'\'';
        $changebirthday = ' dt_birthday = \'' . $_POST['birthyear'].'-'.$_POST['birthmonth'].'-'.$_POST['birthday'].'\'';
        $changeaddress = ' nm_address = \'' .$_POST['address'].'\'';
        $changemailaddress = ' nm_mailaddress = \'' .$_POST['mailaddress'].'\'';
        $changephonenumber = ' su_phonenumber = \'' .$_POST['phonenumber'].'\'';
        

        $licensestack = array();
        $count = 0;
        while(isset($_POST['license'.$count])){
            if($_POST['license'.$count]!=''){
                $licensestack[] = htmlentities($_POST['license'.$count]);
            }
            $count++;
        }
        $licensejson = json_encode($licensestack, JSON_UNESCAPED_UNICODE);
        $workhistorystack = array();
        $count = 0;
        while(isset($_POST['workhistory'.$count])){
            if($_POST['workhistory'.$count]!=''){
                $workhistorystack[] = htmlentities($_POST['workhistory'.$count]);
            }
            $count++;
        }
        $workhistoryjson = json_encode($workhistorystack, JSON_UNESCAPED_UNICODE);
        $changelicense = ' nm_license = \'' .$licensejson.'\'';
        $changeworkhistory = ' nm_workhistory = \'' .$workhistoryjson.'\'';
        $changemotivation = ' nm_motivation = \'' .$_POST['motivation'].'\'';
        $changejoincompanyday = ' dt_joincompanyday = \'' .$_POST['joinyear'].'-'.$_POST['joinmonth'].'-'.$_POST['joinday'].'\'';
        
        $changechangedate = ' upd_date = \''.date("Y-m-d H:i:s").'\'';
        $changequery = "UPDATE tbm_staffname SET ".$changename.','.$changefurigana. ','.$changebirthday. ','.$changeaddress. ','.$changeprefectures.','
            .$changemailaddress. ','.$changephonenumber. ',' .$changeworkhistory. ','.$changelicense. ','.$changemotivation. ','.$changejoincompanyday. ','
            .$changechangedate.' WHERE pk_id_staffname = '.$id;
        $changeemployeeidused = changeemployeeidused($_POST['employeeid']);

        //ここから入力規則を確認
        $inputrule = true;
        if($_POST['name'] == ''){
            $inputrule = false;
            $namefailtext .= '名前が空欄になっています<br>';
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
            try{
                $database -> query($changequery);
                $changesuccesstext .= '<div class = \'successbox\'>変更しました</div>';
            }catch (Exception $e){
                /* echo "エラー発生:" . $e->getMessage().'/n';
                echo "登録できませんでした"; */
                $changesuccesstext .= '<div class = \'failbox\'>少し時間をおいてもう一度お試しください</div>';
            }
        }
        else{
            $changesuccesstext .= '<div class = \'failbox\'>もう一度入力して下さい</div>';
        }
        

    }
    
    if(isset($_POST['changeform'])){
        
        $id = $_POST['id'];
        try{
            $query = "SELECT * FROM tbm_staffname WHERE flg_del = false AND pk_id_staffname = ".$id;
            $result = $database -> query($query);
            $row = mysqli_fetch_assoc($result);
            /* echo '詳細を取得しました'; */
        }catch(Exception $e){
           /*  echo "エラー発生:" . $e->getMessage().'<br>';
            echo "  詳細を取得できませんでした。"; */
        }
        $birtharray = explode('-', $row['dt_birthday']);
        $birthyear = $birtharray[0];
        $birthmonth = $birtharray[1];
        $birthday = $birtharray[2];
        $joinarray = explode('-', $row['dt_joincompanyday']);
        $joinyear = $joinarray[0];
        $joinmonth = $joinarray[1];
        $joinday = $joinarray[2];
        $license = json_decode($row['nm_license'],true);
        $workhistory = json_decode($row['nm_workhistory'],true);
    }
    if(isset($_POST['change'])){
        $settextname = htmlentities($_POST['name']);
        $settextfurigana = htmlentities($_POST['furigana']);
        $settextbirthyear = htmlentities($_POST['birthyear']);
        $settextbirthmonth = htmlentities($_POST['birthmonth']);
        $settextbirthday = htmlentities($_POST['birthday']);
        $settextprefectures = htmlentities($_POST['prefectures']);
        $settextaddress = htmlentities($_POST['address']);
        $settextmailaddress = htmlentities($_POST['mailaddress']);
        $settextphonenumber = htmlentities($_POST['phonenumber']);
        $settextemployeeid = htmlentities($_POST['employeeid']);
        $count = 0;
        while(true){
            if(isset($_POST['workhistory'.$count])){
                $settextworkhistory[] = htmlentities($_POST['workhistory'.$count]);
                $count++;
            }else{
                break;
            }
        }
        $count = 0;
        while(true){
            if(isset($_POST['license'.$count])){
                $settextlicense[] = htmlentities($_POST['license'.$count]);
                $count++;
            }else{
                break;
            }
        }
        $settextmotivation = htmlentities($_POST['motivation']);
        $settextjoinyear = htmlentities($_POST['joinyear']);
        $settextjoinmonth = htmlentities($_POST['joinmonth']);
        $settextjoinday = htmlentities($_POST['joinday']);
        /* $settextcompany = $_POST['company']; */
    }else{
        $settextname = htmlentities($row['nm_name']);
        $settextfurigana = htmlentities($row['nm_furigana']);
        $settextbirthyear = htmlentities($birtharray[0]);
        $settextbirthmonth = htmlentities($birtharray[1]);
        $settextbirthday = htmlentities($birtharray[2]);
        $settextprefectures = htmlentities($row['kbn_prefectures']);
        $settextaddress = htmlentities($row['nm_address']);
        $settextmailaddress = htmlentities($row['nm_mailaddress']);
        $settextphonenumber = htmlentities($row['su_phonenumber']);
        $settextemployeeid = htmlentities($row['no_employeeid']);
        $settextworkhistory = $workhistory;
        $settextlicense = $license;
        $settextmotivation = htmlentities($row['nm_motivation']);
        $settextjoinyear = htmlentities($joinarray[0]);
        $settextjoinmonth = htmlentities($joinarray[1]);
        $settextjoinday = htmlentities($joinarray[2]);
        
    }

    function changeemployeeidused($employeeid){
		require_once '../link.php';
    	$database = database('staff');
		$employeequery = 'SELECT * FROM tbm_staffname 
            WHERE no_employeeid = '.$employeeid.' AND tbm_staffname.flg_del = false;';
        $employeeresult = $database -> query($employeequery);
		$row = mysqli_fetch_assoc($employeeresult);
		if(isset($row['pk_id_staffname'])){
            if($row['no_employeeid'] == $employeeid){
                return false;
            }else{
			    return true;
            }
		}else{
			return false;
		}
	}
   
    
    $birthdaytext = '';

    $birthdaytext.= '<select name=\'birthyear\'>'."\n".
        "<option value = {$settextbirthyear}>{$settextbirthyear}</option>\n";
    for($i = date('Y'); $i >= 0; $i--) {
        $birthdaytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
    }
    $birthdaytext.= '</select>年' . "\n".
        '<select name=\'birthmonth\' >' . "\n".
        "<option value = {$settextbirthmonth} >{$settextbirthmonth}</option>\n";
    for ($i = 1; $i <= 12; $i++) {
        $birthdaytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
    }
    $birthdaytext.= '</select>月' . "\n".
        '<select name=\'birthday\'>' . "\n".
        "<option value = {$settextbirthday} >{$settextbirthday}</option>\n";
    for ($i = 1; $i <= 31; $i++) {
        $birthdaytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
    }
    $birthdaytext.='</select>日' . "\n";

    $workhistorytext= '';
    $workhistorytext.= '<input type = \'hidden\' id = \'workhistory-1\' >';
    if(isset($settextworkhistory[0])){
        $workhistorytext.='<br id=\'workhistorybr0\'><input type = \'text\' name = \'workhistory0\' value = \''.$settextworkhistory[0].'\' id = \'workhistory0\' >';
        $count = 1;
    }else{
        $workhistorytext.='<br id=\'workhistorybr0\'><input type = \'text\' name = \'workhistory0\' value = \'\' id = \'workhistory0\' >';
        $count = 1;
    }
    while(isset($settextworkhistory[$count])){
        $workhistorytext .='<br id=\'workhistorybr'.$count.'\'><input type = \'text\' name = \'workhistory'.$count.'\' value = \''.$settextworkhistory[$count].'\' id = \'workhistory'.$count.'\'>';
        $count++;
    }
    $workhistorycount_json = json_encode($count);

    $licensetext = '';

    $licensetext.='<input type = \'hidden\' id = \'license-1\' >';
    if(isset($settextlicense[0])){
        $licensetext.='<br id = \'licensebr0\'><input type = \'text\' name = \'license0\' value = \''.$settextlicense[0].'\' id = \'license0\' >';
        $count = 1;
    }else{
        $licensetext.='<br id = \'licensebr0\'><input type = \'text\' name = \'license0\' value = \'\' id = \'license0\' >';
        $count = 1;
    }
    while(isset($settextlicense[$count])){
        $licensetext.='<br id = \'licensebr'.$count.'\'><input type = \'text\' name = \'license'.$count.'\' value = \''.$settextlicense[$count].'\' id = \'license'.$count.'\'>';
        $count++;
    }
    $licensecount_json = json_encode($count);

    $joindaytext ='';

    $joindaytext.='<select name=\'joinyear\'>'."\n".
        "<option value = {$settextjoinyear}>{$settextjoinyear}</option>\n";
    for($i = date('Y'); $i >= 0; $i--) {
        $joindaytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
    }
    $joindaytext.='</select>年' . "\n".
        '<select name=\'joinmonth\' >' . "\n".
        "<option value = {$settextjoinmonth} >{$settextjoinmonth}</option>\n";
    for ($i = 1; $i <= 12; $i++) {
        $joindaytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
    }
    $joindaytext.='</select>月' . "\n".
        '<select name=\'joinday\'>' . "\n".
        "<option value = {$settextjoinday} >{$settextjoinday}</option>\n";
    for ($i = 1; $i <= 31; $i++) {
        $joindaytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
    }
    $joindaytext.='</select>日' . "\n";



    require_once('html/changeoutsoucerview.php');
    

?>