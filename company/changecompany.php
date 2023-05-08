
<script src = '../js/jquery-3.6.4.min.js'></script>
<link rel="stylesheet" href="../css/validationEngine.jquery.css">
<script src="../js/jquery.validationEngine.js"></script>
<script src="../js/jquery.validationEngine-ja.js" charset="UTF-8"></script>


<?php
    require_once '../link.php';
    $database = database('staff');
    if(isset($_POST['change'])){
        $id = $_POST['id'];
        $company = $_POST['company'];
        $changecompany = ' company = \'' . $_POST['company'].'\' ';
        $changepresident = ' president = \'' .$_POST['president'].'\'';
        $changeprefectures = ' prefectures = \''.$_POST['prefectures'].'\' ';
        $changelocation = ' location = \'' .$_POST['location'].'\'';
        $changenumberofemployees = ' numberofemployees = \'' .$_POST['numberofemployees'].'\'';
        $changeestablishdate = ' establishdate = \'' . $_POST['establishyear'].'-'.$_POST['establishmonth'].'-'.$_POST['establishday'].'\'';
        $changehomepage = ' homepage = \'' .$_POST['homepage'].'\'';

        $businessdetailsstack = array();
        $count = 0;
        while(isset($_POST['businessdetails'.$count])){
            if($_POST['businessdetails'.$count]!=''){
                $businessdetailsstack[] = htmlentities($_POST['businessdetails'.$count]);
            }
            $count++;
        }
        $businessdetailsjson = json_encode($businessdetailsstack, JSON_UNESCAPED_UNICODE);
        
        $changebusinessdetails = ' businessdetails = \'' .$businessdetailsjson.'\'';
        $changechangedate = ' update_at = \''.date('Y-m-d').'\'';
        $changequery = "UPDATE company SET ".$changecompany. ','.$changepresident. ','.$changeprefectures.','.$changelocation. ','
            .$changenumberofemployees. ','.$changeestablishdate. ',' .$changehomepage. ','.$changebusinessdetails. ','.$changechangedate.
            ' WHERE del = false AND id = \''.$id.'\'';

        /* echo $changequery; */
        /* ここから入力規則のチェック */
        $numberofemployeesflag = false;
        if($_POST['numberofemployees'] == '' or preg_match("/^[0-9]+$/", $_POST['numberofemployees'])){
            $numberofemployeesflag = true;
        }


        if($numberofemployeesflag){
            try{
                $database -> query($changequery);
                echo '変更できました<br>';
            }catch(Exception $e){
                echo "エラー発生:" . $e->getMessage().'<br>';
                echo "  更新できませんでした。";
            }
        }else{
            echo '有効な値を入力してください';
        }
    }

    if(isset($_POST['changeform'])){
        $id = $_POST['id'];
        $company = $_POST['company'];
        try{
            $query = "SELECT * FROM company WHERE del = false AND id = '".$id."'";
            $result = $database -> query($query);
            $row = mysqli_fetch_assoc($result);
            echo '詳細を取得しました';
        }catch(Exception $e){
            echo "エラー発生:" . $e->getMessage().'<br>';
            echo "  詳細を取得できませんでした。";
        }
    
        $establishdatearray = explode('-', $row['establishdate']);
        $establishyear = $establishdatearray[0];
        $establishmonth = $establishdatearray[1];
        $establishday = $establishdatearray[2];
        $businessdetails = json_decode($row['businessdetails'],true);
        $postflag = false;
    }
    if(isset($_POST['change'])){
        $postflag = true;
        $settextcompany = $_POST['company'];
        $settextpresident = $_POST['president'];
        $count = 0;
        while(true){
            if(isset($_POST['businessdetails'.$count])){
                $settextbusinessdetails[] = $_POST['businessdetails'.$count];
                $count++;
            }else{
                break;
            }
        }
        $settextprefectures = $_POST['prefectures'];
        $settextlocation = $_POST['location'];
        $settextnumberofemployees = $_POST['numberofemployees'];
        $settextestablishyear = $_POST['establishyear'];
        $settextestablishmonth = $_POST['establishmonth'];
        $settextestablishday = $_POST['establishday'];
        $settexthomepage = $_POST['homepage'];
    }else{
        $settextcompany = $row['company'];
        $settextpresident = $row['president'];
        $settextcompany = $row['company'];
        $settextbusinessdetails = $businessdetails;
        $settextprefectures = $row['prefectures'];
        $settextlocation = $row['location'];
        $settextnumberofemployees = $row['numberofemployees'];
        $settextestablishyear = $establishdatearray[0];
        $settextestablishmonth = $establishdatearray[1];
        $settextestablishday = $establishdatearray[2];
        $settexthomepage = $row['homepage'];
    }

    $businessdetailtext = '';
    $businessdetailtext.='<input type = \'hidden\' id = \'businessdetails-1\' >';
    if(isset($settextbusinessdetails[0])){
        $businessdetailtext.='<br id = \'businessdetailsbr0\'><input type = \'text\' name = \'businessdetails0\' value = \''.htmlentities($settextbusinessdetails[0]).'\' id = \'businessdetails0\' >';
        $count = 1;
    }else{
        $count = 0;
    }
    while(isset($settextbusinessdetails[$count])){
        $businessdetailtext.='<br id = \'businessdetailsbr'.$count.'\'><input type = \'text\' name = \'businessdetails'.$count.'\' value = \''.htmlentities($settextbusinessdetails[$count]).'\' id = \'businessdetails'.$count.'\'>';
        $count++;
    }
    $businessdetailscount_json = json_encode($count);

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
