
<?php
    require_once '../link.php';
    $database = database('staff');
    if(isset($_POST['change'])){
        $id = $_POST['id'];
        $changename = ' name = \'' . $_POST['name'].'\' ';
        $changefurigana = ' furigana = \'' .$_POST['furigana'].'\'';
        $changeprefectures = 'prefectures = \''.$_POST['prefectures'].'\'';
        $changebirthday = ' birthday = \'' . $_POST['birthyear'].'-'.$_POST['birthmonth'].'-'.$_POST['birthday'].'\'';
        $changeaddress = ' address = \'' .$_POST['address'].'\'';
        $changemailaddress = ' mailaddress = \'' .$_POST['mailaddress'].'\'';
        $changephonenumber = ' phonenumber = \'' .$_POST['phonenumber'].'\'';
        $licensestack = array();
        $count = 0;
        while(isset($_POST['license'.$count])){
            if($_POST['license'.$count]!=''){
                $licensestack[] = $_POST['license'.$count];
            }
            $count++;
        }
        $licensejson = json_encode($licensestack, JSON_UNESCAPED_UNICODE);
        $workhistorystack = array();
        $count = 0;
        while(isset($_POST['workhistory'.$count])){
            if($_POST['workhistory'.$count]!=''){
                $workhistorystack[] = $_POST['workhistory'.$count];
            }
            $count++;
        }
        $workhistoryjson = json_encode($workhistorystack, JSON_UNESCAPED_UNICODE);
        $changelicense = ' license = \'' .$licensejson.'\'';
        $changeworkhistory = ' workhistory = \'' .$workhistoryjson.'\'';
        $changemotivation = ' motivation = \'' .$_POST['motivation'].'\'';
        $changejoincompanyday = ' joincompanyday = \'' .$_POST['joinyear'].'-'.$_POST['joinmonth'].'-'.$_POST['joinday'].'\'';
        /* $changecompany = 'company=\''.$_POST['companyid'].'\''; */
        $changechangedate = ' update_at = \''.date('Y-m-d').'\'';
        $changequery = "UPDATE staffname SET ".$changefurigana. ','.$changebirthday. ','.$changeaddress. ','.$changeprefectures.','
            .$changemailaddress. ','.$changephonenumber. ',' .$changeworkhistory. ','.$changelicense. ','.$changemotivation. ','.$changejoincompanyday. ','
            .$changechangedate.' WHERE id = '.$id;
        /* echo $changequery; */
        /* ここから入力規則を見ていく */
        $furiganaflag = false;
        if($_POST['furigana'] == '' or preg_match('/^[ァ-ヾ]+$/u', $_POST['furigana']) ){
            $furiganaflag = true;
        }
        $mailaddressflag = false;
        if ($_POST['mailaddress'] == '' or preg_match('/^[a-z0-9._+^~-]+@[a-z0-9.-]+$/i', $_POST['mailaddress'])) {
            $mailaddressflag = true;
        }
        $phonenumberflag = false;
        if ($_POST['phonenumber'] == '' or preg_match("/^[0-9]+$/", $_POST['phonenumber'])) {
            $phonenumberflag = true;
        } 
        if($furiganaflag and $mailaddressflag and $phonenumberflag){
            try{
                $database -> query($changequery);
                echo '変更できました<br>';
            }catch(Exception $e){
                echo "エラー発生:" . $e->getMessage().'<br>';
                echo "  更新できませんでした。";
            }
        }else{
            echo '値が有効ではありません';
        }
    }
    
    if(isset($_POST['changeform'])){
        
        $id = $_POST['id'];
        try{
            $query = "SELECT * FROM staffname WHERE del = false AND id = ".$id;
            $result = $database -> query($query);
            $row = mysqli_fetch_assoc($result);
            echo '詳細を取得しました';
        }catch(Exception $e){
            echo "エラー発生:" . $e->getMessage().'<br>';
            echo "  詳細を取得できませんでした。";
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
        $settextname = $_POST['name'];
        $settextfurigana = $_POST['furigana'];
        $settextbirthyear = $_POST['birthyear'];
        $settextbirthmonth = $_POST['birthmonth'];
        $settextbirthday = $_POST['birthday'];
        $settextprefectures = $_POST['prefectures'];
        $settextaddress = $_POST['address'];
        $settextmailaddress = $_POST['mailaddress'];
        $settextphonenumber = $_POST['phonenumber'];
        $count = 0;
        while(true){
            if(isset($_POST['workhistory'.$count])){
                $settextworkhistory[] = $_POST['workhistory'.$count];
                $count++;
            }else{
                break;
            }
        }
        $count = 0;
        while(true){
            if(isset($_POST['license'.$count])){
                $settextlicense[] = $_POST['license'.$count];
                $count++;
            }else{
                break;
            }
        }
        $settextmotivation = $_POST['motivation'];
        $settextjoinyear = $_POST['joinyear'];
        $settextjoinmonth = $_POST['joinmonth'];
        $settextjoinday = $_POST['joinday'];
        /* $settextcompany = $_POST['company']; */
    }else{
        $settextname = $row['name'];
        $settextfurigana = $row['furigana'];
        $settextbirthyear = $birtharray[0];
        $settextbirthmonth = $birtharray[1];
        $settextbirthday = $birtharray[2];
        $settextprefectures = $row['prefectures'];
        $settextaddress = $row['address'];
        $settextmailaddress = $row['mailaddress'];
        $settextphonenumber = $row['phonenumber'];
        $settextworkhistory = $workhistory;
        $settextlicense = $license;
        $settextmotivation = $row['motivation'];
        $settextjoinyear = $joinarray[0];
        $settextjoinmonth = $joinarray[1];
        $settextjoinday = $joinarray[2];
        /* $settextcompany = $row['company']; */
    }
   
    /* if(isset($_POST['changeform'])){
        $query = "SELECT * FROM company WHERE del = false AND id = ".$settextcompany;
        $result = $database -> query($query);
        $rowcompany = mysqli_fetch_assoc($result);
        print_r($rowcompany);
        if($rowcompany != null){
            $postcompany = $rowcompany['company'];
            $postid = $rowcompany['id'];
        }else{
            $postcompany = '無し';
            $postid = '1';
        }
    }else{
        $postcompany = $_POST['company'];
        $postid = $_POST['companyid'];
    } */
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
        $count = 0;
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
        $count = 0;
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