
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

    $changesuccesstext = '';
    if(isset($_POST['change'])){
        $id = $_POST['id'];
        $changename = ' name = \'' . $_POST['name'].'\' ';
        $changefurigana = ' furigana = \'' .$_POST['furigana'].'\'';
        $changeprefectures = 'prefectures = \''.$_POST['prefectures'].'\'';
        $changebirthday = ' birthday = \'' . $_POST['birthyear'].'-'.$_POST['birthmonth'].'-'.$_POST['birthday'].'\'';
        $changeaddress = ' address = \'' .$_POST['address'].'\'';
        $changemailaddress = ' mailaddress = \'' .$_POST['mailaddress'].'\'';
        $changephonenumber = ' phonenumber = \'' .$_POST['phonenumber'].'\'';
        $changeemployeeid = ' employeeid = \''.$_POST['employeeid'].'\' ';

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
        $changelicense = ' license = \'' .$licensejson.'\'';
        $changeworkhistory = ' workhistory = \'' .$workhistoryjson.'\'';
        $changemotivation = ' motivation = \'' .$_POST['motivation'].'\'';
        $changejoincompanyday = ' joincompanyday = \'' .$_POST['joinyear'].'-'.$_POST['joinmonth'].'-'.$_POST['joinday'].'\'';
        
        $changechangedate = ' update_at = \''.date("Y-m-d H:i:s").'\'';
        $changequery = "UPDATE staffname SET ".$changefurigana. ','.$changeemployeeid.','.$changebirthday. ','.$changeaddress. ','.$changeprefectures.','
            .$changemailaddress. ','.$changephonenumber. ',' .$changeworkhistory. ','.$changelicense. ','.$changemotivation. ','.$changejoincompanyday. ','
            .$changechangedate.' WHERE id = '.$id;
        $changeemployeeidused = employeeidused($_POST['employeeid']);

        //ここから入力規則を確認
        $inputrule = true;
        if($_POST['name'] == ''){
            $inputrule = false;
            $changesuccesstext .= '名前が空欄になっています<br>';
        }
        if($_POST['furigana'] != '' and !preg_match('/^[ァ-ヾ]+$/u', $_POST['furigana'])){
            $inputrule =false;
            $changesuccesstext .= 'フリガナはカタカナで入力してください<br>';
        }
        if($_POST['mailaddress'] != '' and !preg_match('/^[a-z0-9._+^~-]+@[a-z0-9.-]+$/i', $_POST['mailaddress'])){
            $inputrule = false;
            $changesuccesstext .= 'メールアドレスが正しくありません<br>';
        }
        if($_POST['phonenumber'] != '' and !preg_match("/^[0-9]{10,11}$/", $_POST['phonenumber'])){
            $inputrule = false;
            $changesuccesstext .= '電話番号はハイフン無しの数字で10、11桁で入力してください<br>';
        }
        if($changeemployeeidused == true){
            $inputrule = false;
            $changesuccesstext .= 'この社員番号は既に使われています<br>';
        }
        if(!preg_match('/^[0-9]{4,}$/',$_POST['employeeid'])){
            $inputrule = false;
            $changesuccesstext .= '社員番号は4桁以上の半角数字にしてください';
        }
        if($inputrule == true){
            try{
                $database -> query($changequery);
                $changesuccesstext .= '登録しました';
            }catch (Exception $e){
                /* echo "エラー発生:" . $e->getMessage().'/n';
                echo "登録できませんでした"; */
                $changesuccesstext .= '少し時間をおいてもう一度お試しください';
            }

        }
        

    }
    
    if(isset($_POST['changeform'])){
        
        $id = $_POST['id'];
        try{
            $query = "SELECT * FROM staffname WHERE del = false AND id = ".$id;
            $result = $database -> query($query);
            $row = mysqli_fetch_assoc($result);
            /* echo '詳細を取得しました'; */
        }catch(Exception $e){
           /*  echo "エラー発生:" . $e->getMessage().'<br>';
            echo "  詳細を取得できませんでした。"; */
        }
        $birtharray = explode('-', $row['birthday']);
        $birthyear = $birtharray[0];
        $birthmonth = $birtharray[1];
        $birthday = $birtharray[2];
        $joinarray = explode('-', $row['joincompanyday']);
        $joinyear = $joinarray[0];
        $joinmonth = $joinarray[1];
        $joinday = $joinarray[2];
        $license = json_decode($row['license'],true);
        $workhistory = json_decode($row['workhistory'],true);
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
        $settextname = htmlentities($row['name']);
        $settextfurigana = htmlentities($row['furigana']);
        $settextbirthyear = htmlentities($birtharray[0]);
        $settextbirthmonth = htmlentities($birtharray[1]);
        $settextbirthday = htmlentities($birtharray[2]);
        $settextprefectures = htmlentities($row['prefectures']);
        $settextaddress = htmlentities($row['address']);
        $settextmailaddress = htmlentities($row['mailaddress']);
        $settextphonenumber = htmlentities($row['phonenumber']);
        $settextemployeeid = htmlentities($row['employeeid']);
        $settextworkhistory = $workhistory;
        $settextlicense = $license;
        $settextmotivation = htmlentities($row['motivation']);
        $settextjoinyear = htmlentities($joinarray[0]);
        $settextjoinmonth = htmlentities($joinarray[1]);
        $settextjoinday = htmlentities($joinarray[2]);
        /* $settextcompany = $row['company']; */
    }

    function changeemployeeidused($employeeid){
		require_once '../link.php';
    	$database = database('staff');
		$employeequery = 'SELECT * FROM staffname 
            WHERE employeeid = '.$employeeid.' AND staffname.del = false;';
        $employeeresult = $database -> query($employeequery);
		$row = mysqli_fetch_assoc($employeeresult);
		if(isset($row['id'])){
            if($row['employeeid'] == $employeeid){
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