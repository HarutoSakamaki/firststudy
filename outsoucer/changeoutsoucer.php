<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="changeoutsoucer.css">
<script src = "changeoutsoucer.js"></script>
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>アウトソーサーの詳細変更</title>
</head>
<body>
<br><br><br><br><br>
<h2>アウトソーサーの詳細変更</h2>

<?php
    include("../header.php");
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
        $changecompany = ' company = \'' .$_POST['companyid'].'\'';
        $changechangedate = ' changedate = \''.date('Y-m-d').'\'';
        $changequery = "UPDATE staffname SET ".$changefurigana. ','.$changebirthday. ','.$changeaddress. ','.$changeprefectures.','
            .$changemailaddress. ','.$changephonenumber. ',' .$changeworkhistory. ','.$changelicense. ','.$changemotivation. ','.$changejoincompanyday. ','
            .$changecompany. ','.$changechangedate.' WHERE del = false AND id = '.$id;

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
        /* try{
            $query = "SELECT * FROM staffname WHERE del = false AND id = '{$id}'";
            $result = $database -> query($query);
            $row = mysqli_fetch_assoc($result);
            echo '詳細を取得しました';
        }catch(Exception $e){
            echo "エラー発生:" . $e->getMessage().'<br>';
            echo "  詳細を取得できませんでした。";
        } */


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
        $settextcompany = $_POST['company'];
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
        $settextcompany = $row['company'];
    }
    

?>
<h2>詳細と変更</h2>
    <form action="changeoutsoucer.php" method="post">
        <p>名前: <input type = 'text' name = 'name' value = <?php echo $settextname; ?>></p>
        <p>フリガナ: <input type = 'text' name = 'furigana' value = <?php echo $settextfurigana; ?>></p>
        <p>生年月日:
            <?php
                echo '<select name=\'birthyear\'>'."\n".
                    "<option value = {$settextbirthyear}>{$settextbirthyear}</option>\n";
                for($i = date('Y'); $i >= 0; $i--) {
                    echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
                }
                echo '</select>年' . "\n";
                echo '<select name=\'birthmonth\' >' . "\n".
                    "<option value = {$settextbirthmonth} >{$settextbirthmonth}</option>\n";
                for ($i = 1; $i <= 12; $i++) {
                    echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
                }
                echo '</select>月' . "\n";
                echo '<select name=\'birthday\'>' . "\n".
                    "<option value = {$settextbirthday} >{$settextbirthday}</option>\n";
                for ($i = 1; $i <= 31; $i++) {
                    echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
                }
                echo '</select>日' . "\n";
            ?>
            
        </p>
        </p>
        <p>現住所: <br>都道府県<input type = 'text' name = 'prefectures' value  = <?php echo $settextprefectures; ?>><br>
        市区町村以下<input type = 'text' name = 'address' value = <?php echo $settextaddress; ?>></p>
        <p>メールアドレス: <input type = 'text' name = 'mailaddress' value = <?php echo $settextmailaddress; ?>></p>
        <p>電話番号(ハイフンなし): <input type = 'text' name = 'phonenumber' value = <?php echo $settextphonenumber; ?>></p>
        <p>職歴: <input type = "button" value ='入力欄を増やす' id = 'workhistoryincreasetext'><input type = "button" value ='入力欄を減らす' id = 'workhistorydecreasetext'><br>
            <?php
                echo '<input type = \'hidden\' id = \'workhistory-1\' >';
                if(isset($settextworkhistory[0])){
                    echo '<input type = \'text\' name = \'workhistory0\' value = \''.$settextworkhistory[0].'\' id = \'workhistory0\' >';
                    $count = 1;
                }else{
                    $count = 0;
                }
                while(isset($settextworkhistory[$count])){
                    echo '<input type = \'text\' name = \'workhistory'.$count.'\' value = \''.$settextworkhistory[$count].'\' id = \'workhistory'.$count.'\'>';
                    $count++;
                }
                $workhistorycount_json = json_encode($count);
            ?>

            <script>
                let workhistoryfunc = inputfield('workhistory',<?php echo $workhistorycount_json; ?>);
            </script>

            


        <p id = 'licensep'>免許や資格: <input type = "button" value ='入力欄を増やす' id = 'licenseincreasetext'><input type = "button" value ='入力欄を減らす' id = 'licensedecreasetext'><br>
            <?php
                echo '<input type = \'hidden\' id = \'license-1\' >';
                if(isset($settextlicense[0])){
                    echo '<input type = \'text\' name = \'license0\' value = \''.$settextlicense[0].'\' id = \'license0\' >';
                    $count = 1;
                }else{
                    $count = 0;
                }
                while(isset($settextlicense[$count])){
                    echo '<input type = \'text\' name = \'license'.$count.'\' value = \''.$settextlicense[$count].'\' id = \'license'.$count.'\'>';
                    $count++;
                }
                $licensecount_json = json_encode($count);
            ?>

            <script>
                let licensefunc = inputfield('license',<?php echo $licensecount_json; ?>);
            </script>
        </p>
        <p>志望理由:<br><textarea name = "motivation" cols = '30' row = '5' class = 'smalltext textarea' ><?php echo $settextmotivation; ?></textarea></p>
        <p>入社日:
            <?php

                echo '<select name=\'joinyear\'>'."\n".
                    "<option value = {$settextjoinyear}>{$settextjoinyear}</option>\n";
                for($i = date('Y'); $i >= 0; $i--) {
                    echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
                }
                echo '</select>年' . "\n";
                echo '<select name=\'joinmonth\' >' . "\n".
                    "<option value = {$settextjoinmonth} >{$settextjoinmonth}</option>\n";
                for ($i = 1; $i <= 12; $i++) {
                    echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
                }
                echo '</select>月' . "\n";
                echo '<select name=\'joinday\'>' . "\n".
                    "<option value = {$settextjoinday} >{$settextjoinday}</option>\n";
                for ($i = 1; $i <= 31; $i++) {
                    echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
                }
                echo '</select>日' . "\n";
            ?>
        </p>
        <p>外勤先(待機中なら無しとお答えください):
            <input type = 'button' id = 'subwindowbutton' onClick = 'disp("../subwindow/selectcompany.php")' value = '外勤先の変更'>
                <?php
                    if(isset($_POST['changeform'])){
                        $query = "SELECT * FROM company WHERE del = false AND id = ".$settextcompany;
                        $result = $database -> query($query);
                        $rowcompany = mysqli_fetch_assoc($result);
                        $postcompany = $rowcompany['company'];
                        $postid = $rowcompany['id'];
                    }else{
                        $postcompany = $_POST['company'];
                        $postid = $_POST['companyid'];
                    }
                        echo <<<EOM
                            <p class = 'choicecompany'>$postcompany</p>
                            <input type = 'hidden' name = 'companyid' class = 'choicecompany' value = ' $postid '>
                            <input type = 'hidden' name = 'company' class = 'choicecompany' value = ' $postcompany '>
                            EOM;
                ?>

                
            <!-- <?php
                $companyquery = $database->query('SELECT company FROM company ORDER BY numberofemployees DESC');
                echo '<select name="company">'. "\n";
                echo '<option value = '.$settextcompany.'>'.$settextcompany.'</option>';
                if($row['company']!='無し'){
                    echo '<option value = \'無し\'>無し</option>';
                }
                while($value = mysqli_fetch_assoc($companyquery)){
                    echo '<option value = '.$value['company'].'>'.$value['company'].'</option>'.'\n';
                }
                echo '</select>' . "\n";
            ?> -->
            
        </p>
        <input type = 'hidden' name = 'id' value = <?php echo '\''.$id.'\'' ?>>
        <p><button type = 'submit' class = 'btn' name = 'change' value='change'>変更</button></p>
    </form>



</body>
<script>
    function choicecompany(id,company){
        /* ここからはリムーブコード */
        var removeelements = document.getElementsByClassName('choicecompany');
        for (var i = 0; i < removeelements.length; i++) {
            var e = removeelements[i];
            if (e) {
                e.parentNode.removeChild(e);
            }
        }
        /* 次からは追加コード */

        parentelement = document.getElementById('subwindowbutton');
        var companydisp = document.createElement('p');
        companydisp.textContent = company;
        companydisp.setAttribute('class','choicecompany');
        parentelement.after(companydisp);
        var idelement = document.createElement('input');
        idelement.setAttribute('class','choicecompany');''
        idelement.setAttribute('type','hidden');
        idelement.setAttribute('name','companyid');
        idelement.setAttribute('value',id);
        parentelement.after(idelement);
        var companyelement = document.createElement('input');
        companyelement.setAttribute('class','choicecompany');''
        companyelement.setAttribute('type','hidden');
        companyelement.setAttribute('name','company');
        companyelement.setAttribute('value',company);
        parentelement.after(companyelement);
    }
    function disp(url){
	console.log("クリック");
	var subw = 800;   // サブウインドウの横幅
	var subh = 600;   // サブウインドウの高さ
	// 表示座標の計算
	console.log(screen.availWidth);
	
	var subx = ( screen.width  - subw ) / 2;   // X座標
	var suby = ( screen.width - subh ) / 2;   // Y座標
	console.log(subx);

	// サブウインドウのオプション文字列を作る
	var SubWinOpt = " width=" + subw + ", height=" + subh + ", top=" + suby + ", left=" + subx+ "";
	/* var SubWinOpt = "width=400,height=300,top=445,left=860"; */
	console.log(SubWinOpt);
	window.open(url,"_blank", SubWinOpt);
}


</script>

</html>