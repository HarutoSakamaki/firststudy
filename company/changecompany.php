<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="changecompany.css">
<script src = "changeoutsoucer.js"></script>
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>登録会社の詳細変更</title>
</head>
<body>
<br><br><br><br><br>
<h2>登録会社の詳細変更</h2>

<?php
    include("../header.php");
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
                $businessdetailsstack[] = $_POST['businessdetails'.$count];
            }
            $count++;
        }
        $businessdetailsjson = json_encode($businessdetailsstack, JSON_UNESCAPED_UNICODE);
        
        $changebusinessdetails = ' businessdetails = \'' .$businessdetailsjson.'\'';
        $changechangedate = ' changedate = \''.date('Y-m-d').'\'';
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
        /* try{
            $query = "SELECT * FROM company WHERE del = false AND company = '{$company}'";
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
    

?>
<h2>詳細と変更</h2>
    <form action="changecompany.php" method="post">
        <p>会社名: <input type = 'text' name = 'company' value = <?php echo $settextcompany; ?>></p>
        <p>社長名: <input type = 'text' name = 'president' value = <?php echo $settextpresident; ?>></p>
        <p>事業内容: <input type = "button" value ='入力欄を増やす' id = 'businessdetailsincreasetext'><input type = "button" value ='入力欄を減らす' id = 'businessdetailsdecreasetext'><br>
            <?php
                echo '<input type = \'hidden\' id = \'businessdetails-1\' >';
                if(isset($settextbusinessdetails[0])){
                    echo '<input type = \'text\' name = \'businessdetails0\' value = \''.$settextbusinessdetails[0].'\' id = \'businessdetails0\' >';
                    $count = 1;
                }else{
                    $count = 0;
                }
                while(isset($settextbusinessdetails[$count])){
                    echo '<input type = \'text\' name = \'businessdetails'.$count.'\' value = \''.$settextbusinessdetails[$count].'\' id = \'businessdetails'.$count.'\'>';
                    $count++;
                }
                $businessdetailscount_json = json_encode($count);
            ?>

            <script>
                let businessdetailsfunc = inputfield('businessdetails',<?php echo $businessdetailscount_json; ?>);
            </script>
        </p>
        <p>本社所在地: <br>都道府県<input type = 'text' name = 'prefectures' value = <?php echo $settextprefectures; ?>>
            <br>市区町村以下<input type = 'text' name = 'location' value = <?php echo $settextlocation; ?>></p>
        <p>従業員数: <input type = 'text' name = 'numberofemployees' value = <?php echo $settextnumberofemployees; ?>></p>
        <p>設立日:
            <?php
                echo '<select name=\'establishyear\'>'."\n".
                    "<option value = {$settextestablishyear}>{$settextestablishyear}</option>\n";
                for($i = date('Y'); $i >= 0; $i--) {
                    echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
                }
                echo '</select>年' . "\n";
                echo '<select name=\'establishmonth\' >' . "\n".
                    "<option value = {$settextestablishmonth} >{$settextestablishmonth}</option>\n";
                for ($i = 1; $i <= 12; $i++) {
                    echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
                }
                echo '</select>月' . "\n";
                echo '<select name=\'establishday\'>' . "\n".
                    "<option value = {$settextestablishday} >{$settextestablishday}</option>\n";
                for ($i = 1; $i <= 31; $i++) {
                    echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
                }
                echo '</select>日' . "\n";
            ?>
        </p>
        <p>ホームページアドレス: <input type = 'text' name = 'homepage' value = <?php echo $settexthomepage; ?>></p>

        
        


       
        <input type = 'hidden' name = 'id' value = <?php echo $id; ?>>
        <p><button type = 'submit' class = 'btn' name = 'change' value='change'>変更</button></p>
    </form>



</body>