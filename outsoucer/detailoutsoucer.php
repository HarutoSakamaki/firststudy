
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
        
    $id = $_POST['staffid'];
    $_SESSION['staffid'] = $id;
    try{
        $query = "SELECT * FROM staffname WHERE del = false AND id = ".$id;
        $result = $database -> query($query);
        $row1 = mysqli_fetch_assoc($result);
        
    }catch(Exception $e){
        echo "エラー発生:" . $e->getMessage().'<br>';
        exit();
    }
    
    $birtharray = explode('-', $row1['birthday']);
    $birthyear = $birtharray[0];
    $birthmonth = $birtharray[1];
    $birthday = $birtharray[2];
    $joinarray = explode('-', $row1['joincompanyday']);
    $joinyear = $joinarray[0];
    $joinmonth = $joinarray[1];
    $joinday = $joinarray[2];
    $licensearray = json_decode($row1['license'],true);
    $workhistoryarray = json_decode($row1['workhistory'],true);
    if(json_last_error() !== JSON_ERROR_NONE){
        // エラーが発生
        print json_last_error_msg(); // エラーメッセージを出力
    }
    $workhistorytext = '';
    if( $row1['prefectures'] == ''){
        $settextaddress = $row1['address'];
    }else{
        $settextaddress = getpref($row1['prefectures']).' '.$row1['address'];
    }
    $count = 0;
    while(isset($workhistoryarray[$count])){
        $workhistorytext.=  $workhistoryarray[$count].'<br>';
        $count++;
    }
    $count = 0;
    $licensetext = '';
    while(isset($licensearray[$count])){
        $licensetext.= $licensearray[$count].'<br>';
        $count++;
    }



    //ここからstaffhistory系
    



    if(isset($_POST['staffid'])){
        $staffid = $_POST['staffid'];
        $_SESSION['staffid'] = $staffid;
    }else{
        $staffid = $_SESSION['staffid'];
    }
    $settextcompanyname = '未選択';
    $settextcompanyid = '';
    $settextstartdate = '';
    $settextenddate = '';

    $addcompanysuccesstext = '';
    $addcompanyfailtext = '';
    if(isset($_POST['addcompany'])){
        $addcompanysuccess = false;
        $companyid = $_POST['selectcompanyid'];
        //入力規則(空欄があるかどうか)
        $empty = true;
        
        if($_POST['selectcompanyid'] == '' or $_POST['startdate'] == '' or $_POST['enddate'] == ''){
            $empty = true;
            $addcompanyfailtext .= '空欄があります<br>';
        }else{
            $empty = false;
        }

        if($empty == false){
            //重複チェック
            $query = <<<EDO
                SELECT * FROM staffhistory WHERE staffid = {$staffid}
                EDO;
            try{
                $result = $database -> query($query);
            }catch(e){
                echo "エラー発生:" . $e->getMessage().'<br>';
                exit();
            }
            $overlapping = false;
            while($row = mysqli_fetch_assoc($result)){
                $datastart = strtotime($row['startdate']);
                $dataend = strtotime($row['enddate']);
                $inputstart = strtotime($_POST['startdate']);
                $inputend = strtotime($_POST['enddate']);
                if($datastart<=$inputend && $dataend>=$inputstart){
                    $overlapping = true;
                }else{

                }
            }
            if($overlapping == false){
                try{
                    $numberringquery = "UPDATE numbering SET numbering = LAST_INSERT_ID(numbering + 1) WHERE tablename = 'staffhistory'";
                    $database -> query($numberringquery);
                    $numberringquery = 'SELECT numbering FROM numbering where tablename = \'staffhistory\' ';
                    $numberring = mysqli_fetch_assoc($database -> query($numberringquery));
                    $numberringid = $numberring['numbering'];
                    $query = 'INSERT staffhistory (id , staffid , companyid, startdate, enddate) VALUES('.$numberringid.','.$staffid.','.$companyid.',\''.$_POST['startdate'].'\',\''.$_POST['enddate'].'\')';
                    $result = $database -> query($query);
                    $addcompanysuccess = true;
                    $addcompanysuccesstext .= '<div class = \'successbox\'>登録しました</div>';
                    /* echo '成功'; */
                }catch(e){
                    /* echo "エラー発生:" . $e->getMessage().'<br>';
                    echo "  外勤先を取得できませんでした"; */
                }

            }else{
                $addcompanyfailtext .= '外勤先の期間が重複してます<br>';
            }
        }
        if($addcompanysuccess == false){
            $settextcompanyid = $_POST['selectcompanyid'];
            $settextcompanyname = $_POST['selectcompanyname'];
            $settextstartdate = $_POST['startdate'];
            $settextenddate = $_POST['enddate'];
            $addcompanysuccesstext = '<div class = \'failbox\'>もう一度入力して下さい</div>';
        }
    }


    if(isset($_POST['delete'])){
        try{
            $update_at = date("Y-m-d H:i:s");
            $query = <<<EDO
                UPDATE staffhistory SET 
                del =  1, update_at = '{$update_at}' 
                WHERE id = {$_POST['historyid']};
            EDO;
            $result = $database -> query($query);
        }catch(Exception $e){
            /* echo "エラー発生:" . $e->getMessage().'<br>';
            echo "  外勤先を取得できませんでした"; */
        }
    }
    

    try{
        $query = 'SELECT  staffhistory.id as id, company.company as company, staffhistory.startdate as startdate, staffhistory.enddate as enddate, 
        staffname.name as name  
        FROM staffhistory LEFT JOIN company ON staffhistory.companyid = company.id LEFT JOIN staffname ON staffname.id = staffhistory.staffid 
        WHERE staffhistory.staffid = '.$staffid.' AND staffhistory.del = 0 
        ORDER BY enddate DESC';
        /* echo $query.'</br>'; */
        $result = $database -> query($query);
        /* $row = mysqli_fetch_assoc($result); */
        /* echo '外勤先を取得しました'; */
    }catch(Exception $e){
        /* echo "エラー発生:" . $e->getMessage().'<br>';
        echo "  外勤先を取得できませんでした"; */
    }
    $settext = array();
    $nowsettext = array();
    $nowsettextflag = false;
    $settextflag = false;
    while($row = mysqli_fetch_assoc($result)){
        $settext[] = ['company'=>$row['company'],'startdate'=>$row['startdate'],'enddate'=>$row['enddate'],'id'=>$row['id']];
        $settextflag = true;
    }
    
    $historycompanytext='';
    if($settextflag  == true){
        $nowdate = new DateTime(date('Y-m-d'));
        $historycompanytext .= '<div><table class = \'workplacetable\'><tr><th>会社</th><th>仕事開始日</th><th>仕事終了日</th><th>状態</th><th>履歴の削除</th></tr>';
        foreach($settext as $settext){
            $setcompany = htmlentities($settext['company']);
            $setstartdate = htmlentities($settext['startdate']);
            $setenddate = htmlentities($settext['enddate']);
            $comparestart = new DateTime($setstartdate);
            $compareend = new DateTime($setenddate);
            if($comparestart > $nowdate){
                $status = '予定';
            }elseif($compareend < $nowdate){
                $status = '完了';
            }else{
                $status = '外勤中';
            }
            $historycompanytext = $historycompanytext.<<<EOD
                <tr>
                    <td>{$setcompany}</td>
                    <td>{$setstartdate}</td>
                    <td>{$setenddate}</td>
                    <td>{$status}</td>
                    <td><form action = 'detailoutsoucer.php' method = 'post' class = 'margin0' id = 'delete{$settext['id']}' onsubmit="return deleteform()">
                        <button type = 'submit' class = 'commonbutton' name = 'delete' value = '削除' ><img src="../img/deleteicon.png" alt=""/>削除</button>
                        <input type = 'hidden' name = 'historyid' value = '{$settext['id']}'>
                        <input type = 'hidden' name = 'staffid' value = '{$staffid}'>
                        </form>
                    </td>
                </tr>
            EOD;
        }
        $historycompanytext .= '</table></div>';
    }else{
        $historycompanytext .= '履歴がありません';
    }
    

    require_once('html/detailoutsoucerview.php');
?>