
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
    $id = $_POST['id'];


    $changesuccesstext = '';
    $companynamefailtext = '';
    $numberofemployeesfailtext = '';
    $averageagefailtext = '';
    $capitalfailtext = '';
    $salesfailtext = '';
    $postcodefailtext = '';
    
    if(isset($_POST['change'])){

        /* ここから入力規則のチェック */
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
        if(($_POST['postcode1'] != '' or $_POST['postcode2'] != '') and (!preg_match("/^([0-9]{3})$/",$_POST['postcode1']) or !preg_match("/^([0-9]{4})$/",$_POST['postcode2']))){
			$inputrule =false;
			$postcodefailtext .= '郵便番号は半角数字3桁、4桁で入力して下さい<br>';
		}

        if($inputrule == true){
            $setcapital = $_POST['capital']*$_POST['capitaldigit'];
            $setsales = $_POST['sales']*$_POST['digit'];
            $id = $_POST['id'];
            $company = $_POST['company'];
            $changecompany = ' nm_company = \'' . $_POST['company'].'\' ';
            $changepresident = ' nm_president = \'' .$_POST['president'].'\'';
            $changesales = ' su_sales = \''.$setsales.'\' ';
            if($_POST['postcode1'] != ''){
                $changepostcode = ' kbn_postcode = \''.$_POST['postcode1'].$_POST['postcode2'].'\' ';
            }else{
                $changepostcode = ' kbn_postcode = \'\' ';
            }
            $changeprefectures = ' kbn_prefectures = \''.$_POST['prefectures'].'\' ';
            $changelocation = ' nm_location = \'' .$_POST['location'].'\'';
            $changenumberofemployees = ' su_numberofemployees = \'' .$_POST['numberofemployees'].'\'';
            $changeestablishdate = ' dt_establishdate = \'' . $_POST['establishyear'].'-'.$_POST['establishmonth'].'-'.$_POST['establishday'].'\'';
            $changecapital = ' su_capital = \''.$setcapital.'\' ';
            $changeclosingmonth = ' kbn_closingmonth = \''.$_POST['closingmonth'].'\' ';
            $changeaverageage = ' su_averageage = \''.$_POST['averageage'].'\' ';
            $changehomepage = ' nm_homepage = \'' .$_POST['homepage'].'\' ';
            $businessdetailsstack = array();
            
            $businessdetailsstack = $_POST['businessdetails'];
            $businessdetailsjson = json_encode($businessdetailsstack, JSON_UNESCAPED_UNICODE);

            $bankjson = json_encode($_POST['bank'],JSON_UNESCAPED_UNICODE);
            
            $changebusinessdetails = ' nm_businessdetails = \'' .$businessdetailsjson.'\'';
            $changebank = ' nm_bank = \''.$bankjson.'\' ';
            $changechangedate = ' upd_date = \''.date('Y-m-d H:i:s').'\'';
            $changequery = "UPDATE tbm_company_kiso SET ".$changecompany. ','.$changepresident. ','.$changesales.','.$changepostcode.','.$changeprefectures.','.$changelocation. ','
                .$changenumberofemployees. ','.$changeestablishdate. ','.$changecapital.','.$changeaverageage.','.$changeclosingmonth.','.$changehomepage. ','
                .$changebusinessdetails.','.$changebank.','.$changechangedate.
                ' WHERE flg_del = false AND pk_id_company = \''.$id.'\'';

           
            try{
                $database -> query($changequery);
                $changesuccesstext .= '<div class = \'successbox\'>変更しました</div>';
                $changesuccess = true;
            }catch(Exception $e){
                echo "エラー発生:" . $e->getMessage();
                exit();
            }

        }else{
            $changesuccesstext .= '<div class = \'failbox\'>もう一度入力してください</div>';
        }
    }

    if(isset($_POST['changeform'])){
        $id = $_POST['id'];
        try{
            $query = "SELECT pk_id_company , nm_company , su_numberofemployees , dt_establishdate , kbn_postcode , kbn_prefectures , nm_location , nm_president , nm_businessdetails , 
             nm_homepage , kbn_closingmonth , su_sales , su_capital , su_averageage , nm_bank 
             FROM tbm_company_kiso WHERE flg_del = false AND pk_id_company = '".$id."'";
            $result = $database -> query($query);
            $row = mysqli_fetch_assoc($result);
            /* echo '詳細を取得しました'; */
        }catch(Exception $e){
            /* echo "エラー発生:" . $e->getMessage().'<br>';
            echo "  詳細を取得できませんでした。"; */
        }
    
        $establishdatearray = explode('-', $row['dt_establishdate']);
        $establishyear = $establishdatearray[0];
        $establishmonth = $establishdatearray[1];
        $establishday = $establishdatearray[2];
        $businessdetails = json_decode($row['nm_businessdetails'],true);
        $bank = json_decode($row['nm_bank'],true);
        $postflag = false;
    }
    if(isset($_POST['change'])){
        $postflag = true;
        $settextcompany = $_POST['company'];
        $settextpresident = $_POST['president'];
        
        $settextbusinessdetails = $_POST['businessdetails'];
        $settextbank = $_POST['bank'];
              

        $settextprefectures = $_POST['prefectures'];
        $settextsales = $_POST['sales'];
        $settextdigit = $_POST['digit'];
        $settextpostcode1 = $_POST['postcode1'];
        $settextpostcode2 = $_POST['postcode2'];
        $settextlocation = $_POST['location'];
        $settextnumberofemployees = $_POST['numberofemployees'];
        $settextestablishyear = $_POST['establishyear'];
        $settextestablishmonth = $_POST['establishmonth'];
        $settextestablishday = $_POST['establishday'];
        $settextcapitaldigit = $_POST['capitaldigit'];
        $settextcapital = $_POST['capital'];
        $settextaverageage = $_POST['averageage'];
        $settextclosingmonth = $_POST['closingmonth'];
        /* $settextbank = $_POST['bank']; */
        $settexthomepage = $_POST['homepage'];
    }else{
        $settextcompany = $row['nm_company'];
        $settextpresident = $row['nm_president'];
        $settextbusinessdetails = $businessdetails;
        $settextbank = $bank;
        $settextprefectures = $row['kbn_prefectures'];
        $settextlocation = $row['nm_location'];
        $settextnumberofemployees = $row['su_numberofemployees'];
        $settextestablishyear = $establishdatearray[0];
        $settextestablishmonth = $establishdatearray[1];
        $settextestablishday = $establishdatearray[2];
        $settextcapital = $row['su_capital'];
        $settextaverageage = $row['su_averageage'];
        $settextclosingmonth = $row['kbn_closingmonth'];
        $settexthomepage = $row['nm_homepage'];

        if($row['kbn_postcode'] != '' and strlen($row['kbn_postcode'])==7){
            $pos = str_spilit($row['kbn_postcode']);
            $settextpostcode1 = $pos['0'].$pos['1'].$pos['2'];
            $settextpostcode2 = $pos['3'].$pos['4'].$pos['5'].$pos['6'];
        }else{
            $settextpostcode1 = '';
            $settextpostcode2 = '';
        }

        $settextdigit = 1;
        $settextsales = $row['su_sales'];
        if($settextsales != ''){
            $settextsales = $settextsales/1;
            while($settextsales % 1000 == 0 and $settextsales != 0){
                $settextsales = $settextsales/1000;
                $settextdigit = $settextdigit*1000;
            }
        }
        $settextcapitaldigit = 1;
        if($settextcapital != ''){
            $settextcapital = $row['su_capital']/1;
            while($settextcapital % 1000 == 0 and $settextcapital != 0){
                $settextcapital = $settextcapital/1000;
                $settextcapitaldigit = $settextcapitaldigit*1000;
            }
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




    $joindaytext = '';

    $joindaytext.='<select name=\'establishyear\'>'."\n".
        "<option value = {$settextestablishyear}>{$settextestablishyear}</option>\n";
    for($i = date('Y'); $i >= 0; $i--) {
        $joindaytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
    }
    $joindaytext.='</select>年' . "\n".
        '<select name=\'establishmonth\' >' . "\n".
        "<option value = {$settextestablishmonth} >{$settextestablishmonth}</option>\n";
    for ($i = 1; $i <= 12; $i++) {
        $joindaytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
    }
    $joindaytext.= '</select>月' . "\n".
        '<select name=\'establishday\'>' . "\n".
        "<option value = {$settextestablishday} >{$settextestablishday}</option>\n";
    for ($i = 1; $i <= 31; $i++) {
        $joindaytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
    }
    $joindaytext.='</select>日' . "\n";

    require_once('html/changecompanyview.php');
?>
