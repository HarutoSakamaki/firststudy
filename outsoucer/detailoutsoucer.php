
<script src = '../js/jquery-3.6.4.min.js'></script>
<link rel="stylesheet" href="../css/validationEngine.jquery.css">
<script src="../js/jquery.validationEngine.js"></script>
<script src="../js/jquery.validationEngine-ja.js" charset="UTF-8"></script>


<?php
        require_once '../link.php';
        $database = database('staff');
        session_start();
        
        $id = $_POST['staffid'];
        $_SESSION['staffid'] = $id;
        try{
            $query = "SELECT * FROM staffname WHERE del = false AND id = ".$id;
            $result = $database -> query($query);
            $row1 = mysqli_fetch_assoc($result);
            /* echo '詳細を取得しました'; */
        }catch(Exception $e){
            /* echo "エラー発生:" . $e->getMessage().'<br>';
            echo "  詳細を取得できませんでした。"; */
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

        if(isset($_POST['addcompany'])){
            $settextcompanyid = $_POST['selectcompanyid'];
            try{
                $numberringquery = "UPDATE numbering SET numbering = LAST_INSERT_ID(numbering + 1) WHERE tablename = 'staffhistory'";
                $database -> query($numberringquery);
                $numberringquery = 'SELECT numbering FROM numbering where tablename = \'staffhistory\' ';
                $numberring = mysqli_fetch_assoc($database -> query($numberringquery));
                $numberringid = $numberring['numbering'];
                $query = 'INSERT staffhistory (id , staffid , companyid, startdate, enddate) VALUES('.$numberringid.','.$staffid.','.$settextcompanyid.',\''.$_POST['startdate'].'\',\''.$_POST['enddate'].'\')';
                
                $result = $database -> query($query);
                /* echo '成功'; */
            }catch(e){
                /* echo "エラー発生:" . $e->getMessage().'<br>';
                echo "  外勤先を取得できませんでした"; */
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
                            <input type = 'submit' name = 'delete' value = '削除' >
                            <input type = 'hidden' name = 'historyid' value = '{$settext['id']}'>
                            <input type = 'hidden' name = 'staffid' value = '{$staffid}'>
                        </form></td>
                    </tr>
                EOD;
            }
            $historycompanytext .= '</table></div>';
        }else{
            $historycompanytext .= '履歴がありません';
    
        }
        

        require_once('html/detailoutsoucerview.php');
?>