
<script src = '../js/jquery-3.6.4.min.js'></script>
<link rel="stylesheet" href="../css/validationEngine.jquery.css">
<script src="../js/jquery.validationEngine.js"></script>
<script src="../js/jquery.validationEngine-ja.js" charset="UTF-8"></script>
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

    $changesuccesstext = '';
    $namefailtext = '';
    $furiganafailtext = '';
    $phonenumberfailtext = '';
    $mailaddressfailtext = '';
    $postcodefailtext = '';
    
    if(isset($_POST['change'])){
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
        if(($_POST['postcode1'] != '' or $_POST['postcode2'] != '') and (!preg_match("/^([0-9]{3})$/",$_POST['postcode1']) or !preg_match("/^([0-9]{4})$/",$_POST['postcode2']))){
			$inputrule =false;
			$postcodefailtext .= '郵便番号は半角数字3桁、4桁で入力して下さい<br>';
		}
        
        $id = $_POST['id'];
        if($inputrule == true){
            $changename = ' nm_name = \'' . $_POST['name'].'\' ';
            $changefurigana = ' nm_furigana = \'' .$_POST['furigana'].'\'';
            if($_POST['postcode1'] != ''){
                $changepostcode = ' kbn_postcode = \''.$_POST['postcode1'].$_POST['postcode2'].'\' ';
            }else{
                $changepostcode = ' kbn_postcode = \'\' ';
            }
            $changeprefectures = ' kbn_prefectures = \''.$_POST['prefectures'].'\'';
            $changebirthday = ' dt_birthday = \'' . $_POST['birthyear'].'-'.$_POST['birthmonth'].'-'.$_POST['birthday'].'\'';
            $changeaddress = ' nm_address = \'' .$_POST['address'].'\'';
            $changemailaddress = ' nm_mailaddress = \'' .$_POST['mailaddress'].'\'';
            $changephonenumber = ' su_phonenumber = \'' .$_POST['phonenumber'].'\'';
            
            $license = array_filter($_POST['license']);
            $licensejson = json_encode($license, JSON_UNESCAPED_UNICODE);
            
            $workhistory = array_filter($_POST['workhistory']);
            $workhistoryjson = json_encode($workhistory, JSON_UNESCAPED_UNICODE);

            $changelicense = ' nm_license = \'' .$licensejson.'\'';
            $changeworkhistory = ' nm_workhistory = \'' .$workhistoryjson.'\'';
            $changemotivation = ' etc_motivation = \'' .$_POST['motivation'].'\'';
            $changejoincompanyday = ' dt_joincompanyday = \'' .$_POST['joinyear'].'-'.$_POST['joinmonth'].'-'.$_POST['joinday'].'\'';
            
            $changechangedate = ' upd_date = \''.date("Y-m-d H:i:s").'\'';
            $changequery = "UPDATE tbm_staffname_kiso SET ".$changename.','.$changefurigana. ','.$changebirthday. ','.$changepostcode.','.$changeaddress. ','.$changeprefectures.','
                .$changemailaddress. ','.$changephonenumber. ',' .$changeworkhistory. ','.$changelicense. ','.$changemotivation. ','.$changejoincompanyday. ','
                .$changechangedate.' WHERE pk_id_staffname = '.$id;

            
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
            $query = "SELECT pk_id_staffname , nm_name , no_employeeid , dt_birthday , dt_joincompanyday , nm_furigana , kbn_postcode , kbn_prefectures , 
             nm_address , nm_mailaddress , su_phonenumber , nm_workhistory , nm_license , etc_motivation 
             FROM tbm_staffname_kiso WHERE flg_del = false AND pk_id_staffname = ".$id;
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
        if($row['nm_license']==null){
            $license = [];
        }else{
            $license = json_decode($row['nm_license'],true);
        }
        if($row['nm_workhistory']==null){
            $workhistory = [];
        }else{
            $workhistory = json_decode($row['nm_workhistory'],true);
        }
        

    }
    if(isset($_POST['change'])){
        $settextname = htmlentities($_POST['name']);
        $settextfurigana = htmlentities($_POST['furigana']);
        $settextbirthyear = htmlentities($_POST['birthyear']);
        $settextbirthmonth = htmlentities($_POST['birthmonth']);
        $settextbirthday = htmlentities($_POST['birthday']);
        $settextpostcode1 = htmlentities($_POST['postcode1']);
        $settextpostcode2 = htmlentities($_POST['postcode2']);
        $settextprefectures = htmlentities($_POST['prefectures']);
        $settextaddress = htmlentities($_POST['address']);
        $settextmailaddress = htmlentities($_POST['mailaddress']);
        $settextphonenumber = htmlentities($_POST['phonenumber']);
        $settextemployeeid = htmlentities($_POST['employeeid']);
        $settextworkhistory = $_POST['workhistory'];
        $settextlicense = $_POST['license'];
        $settextmotivation = htmlentities($_POST['motivation']);
        $settextjoinyear = htmlentities($_POST['joinyear']);
        $settextjoinmonth = htmlentities($_POST['joinmonth']);
        $settextjoinday = htmlentities($_POST['joinday']);
        
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

        if($row['kbn_postcode'] != '' and strlen($row['kbn_postcode'])==7){
            $pos = str_split($row['kbn_postcode']);
            $settextpostcode1 = $pos['0'].$pos['1'].$pos['2'];
            $settextpostcode2 = $pos['3'].$pos['4'].$pos['5'].$pos['6'];
        }else{
            $settextpostcode1 = '';
            $settextpostcode2 = '';
        }
        $settextmotivation = htmlentities($row['etc_motivation']);
        $settextjoinyear = htmlentities($joinarray[0]);
        $settextjoinmonth = htmlentities($joinarray[1]);
        $settextjoinday = htmlentities($joinarray[2]);
        
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
    $count = 0;
    $workhistorytext.= '<input type = \'hidden\' id = \'workhistory-1\' >';
    if(count($settextworkhistory) >= 1){
        
    }else{
        $workhistorytext.='<br id=\'workhistorybr0\'><input type = \'text\' name = \'workhistory[]\' value = \'\' id = \'workhistory0\' >';
        $count = 1;
    }
    foreach($settextworkhistory as $settextworkhistory){
        $workhistorytext .='<br id=\'workhistorybr'.$count.'\'><input type = \'text\' name = \'workhistory[]\' value = \''.$settextworkhistory.'\' id = \'workhistory'.$count.'\'>';
        $count++;
    }
    
    $workhistorycount_json = json_encode($count);

    $count = 0;
    $licensetext = '';
    $licensetext.='<input type = \'hidden\' id = \'license-1\' >';
    if(count($settextlicense)){
        
    }else{
        $licensetext.='<br id = \'licensebr0\'><input type = \'text\' name = \'license[]\' value = \'\' id = \'license0\' >';
        $count = 1;
    }
    foreach( $settextlicense as $settextlicense ){
        $licensetext.='<br id = \'licensebr'.$count.'\'><input type = \'text\' name = \'license[]\' value = \''.$settextlicense.'\' id = \'license'.$count.'\'>';
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