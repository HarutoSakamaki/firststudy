
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
        
    $id = $_POST['staffid'];
    $_SESSION['staffid'] = $id;
    
    try{
        $query = "SELECT pk_id_staffname , nm_name , no_employeeid , dt_birthday , dt_joincompanyday , nm_furigana , kbn_postcode , kbn_prefectures , nm_address , 
         nm_mailaddress , su_phonenumber , nm_workhistory , nm_license , etc_motivation 
         FROM tbm_staffname_kiso WHERE flg_del = false AND pk_id_staffname = ".$id;
        $result = $database -> query($query);
        $row1 = mysqli_fetch_assoc($result);
        
    }catch(Exception $e){
        echo "エラー発生:" . $e->getMessage().'<br>';
        exit();
    }
    
    $birtharray = explode('-', $row1['dt_birthday']);
    $birthyear = $birtharray[0];
    $birthmonth = $birtharray[1];
    $birthday = $birtharray[2];
    $joinarray = explode('-', $row1['dt_joincompanyday']);
    $joinyear = $joinarray[0];
    $joinmonth = $joinarray[1];
    $joinday = $joinarray[2];
    $licensearray = json_decode($row1['nm_license'],true);
    $workhistoryarray = json_decode($row1['nm_workhistory'],true);
    
    $workhistorytext = '';
    if( $row1['kbn_prefectures'] == ''){
        $settextaddress = $row1['nm_address'];
    }else{
        $settextaddress = getpref($row1['kbn_prefectures']).' '.$row1['nm_address'];
    }
    if($row1['kbn_postcode'] != '' and strlen($row1['kbn_postcode']) == 7){
        $pos = str_split($row1['kbn_postcode']);
        $settextaddress = '〒'.$pos['0'].$pos['1'].$pos['2'].'-'.$pos['3'].$pos['4'].$pos['5'].$pos['6'].$settextaddress;
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
                SELECT dt_startdate , dt_enddate FROM tbm_staffhistory_kiso WHERE no_staffid = {$staffid} AND flg_del = false;
                EDO;
            try{
                $result = $database -> query($query);
            }catch(e){
                echo "エラー発生:" . $e->getMessage().'<br>';
                exit();
            }
            $overlapping = false;
            while($row = mysqli_fetch_assoc($result)){
                $datastart = strtotime($row['dt_startdate']);
                $dataend = strtotime($row['dt_enddate']);
                $inputstart = strtotime($_POST['startdate']);
                $inputend = strtotime($_POST['enddate']);
                if($datastart<=$inputend && $dataend>=$inputstart){
                    $overlapping = true;
                }else{

                }
            }
            if($overlapping == false){
                try{
                    $numberingquery = "SELECT no_tuban FROM tbs_saiban WHERE pk_id_saiban = 3";
                    $result = $database -> query($numberingquery);
                    $tuban = (mysqli_fetch_assoc($result)['no_tuban'])+1;
                    $query = 'INSERT tbm_staffhistory_kiso (pk_id_staffhistory , no_staffid , no_companyid, dt_startdate, dt_enddate) VALUES('.$tuban.','.$staffid.','.$companyid.',\''.$_POST['startdate'].'\',\''.$_POST['enddate'].'\')';
                    $result = $database -> query($query);
                    $query = <<<EDO
                        UPDATE tbs_saiban SET no_tuban = {$tuban} WHERE pk_id_saiban = 3
                    EDO;
				    $database -> query($query);
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
                UPDATE tbm_staffhistory_kiso SET 
                flg_del =  1, upd_date = '{$update_at}' 
                WHERE pk_id_staffhistory = {$_POST['historyid']};
            EDO;
            $result = $database -> query($query);
        }catch(Exception $e){
            /* echo "エラー発生:" . $e->getMessage().'<br>';
            echo "  外勤先を取得できませんでした"; */
        }
    }
    

    try{
        $query = 'SELECT  tbm_staffhistory_kiso.pk_id_staffhistory as id, 
                        tbm_company_kiso.nm_company as company, 
                        tbm_staffhistory_kiso.dt_startdate as startdate, 
                        tbm_staffhistory_kiso.dt_enddate as enddate, 
                        tbm_staffname_kiso.nm_name as name  
                  FROM tbm_staffhistory_kiso 
                  LEFT JOIN tbm_company_kiso ON tbm_staffhistory_kiso.no_companyid = tbm_company_kiso.pk_id_company LEFT JOIN tbm_staffname_kiso ON tbm_staffname_kiso.pk_id_staffname = tbm_staffhistory_kiso.no_staffid 
        WHERE tbm_staffhistory_kiso.no_staffid = '.$staffid.' AND tbm_staffhistory_kiso.flg_del = 0 
        ORDER BY dt_enddate DESC';
        $result = $database -> query($query);
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
        $historycompanytext .= '<div><table class = \'workplacetable\'><tr><th>会社</th><th>仕事開始日</th><th>仕事終了日</th><th>状態</th><th>履歴の削除</th><th>操作</th></tr>';
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
                    <td id>{$setcompany}</td>
                    <td id>{$setstartdate}</td>
                    <td id>{$setenddate}</td>
                    <td id>{$status}</td>
                    <td><form action = 'detailoutsoucer.php' method = 'post' class = 'margin0' id = 'delete{$settext['id']}' onsubmit="return deleteform()">
                        <button type = 'submit' class = 'commonbutton' name = 'delete' value = '削除' ><img src="../img/deleteicon.png" alt=""/>削除</button>
                        <input type = 'hidden' name = 'historyid' value = '{$settext['id']}'>
                        <input type = 'hidden' name = 'staffid' value = '{$staffid}'>
                        </form>
                    </td>
                    <td>
                        <button type = 'button' class = 'commonbutton' onclick = 'operation('{$settext['id']}')'>操作</button>
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