
<?php
        require_once '../link.php';
        $database = database('staff');

        
        $id = $_POST['staffid'];
        try{
            $query = "SELECT * FROM staffname WHERE del = false AND id = ".$id;
            $result = $database -> query($query);
            $row1 = mysqli_fetch_assoc($result);
            echo '詳細を取得しました';
        }catch(Exception $e){
            echo "エラー発生:" . $e->getMessage().'<br>';
            echo "  詳細を取得できませんでした。";
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
        $workhistorytext = '';
        $count = 0;
        while(isset($workhistoryarray[$count])){
            $workhistorytext.=  $count.'.'.$workhistoryarray[$count].'<br>';
            $count++;
        }
        $count = 0;
        $licensetext = '';
        while(isset($licensearray[$count])){
            $licensetext.=$count.'.'.$licensearray[$count].'<br>';
            $count++;
        }



        //ここからstaffhistory系
        



        if(isset($_POST['staffid'])){
            $staffid = $_POST['staffid'];
            $_SESSION['staffid'] = $staffid;
        }else{
            $staffid = $_SESSION['staffid'];
        }
    
        if(isset($_POST['decideenddate'])){
            try{
                $update_at = date("Y-m-d H:i:s");
                $query = <<<EDO
                    UPDATE staffhistory SET 
                    enddate = '{$_POST['enddate']}' , update_at = '{$update_at}' 
                    WHERE id = {$_POST['historyid']};
                EDO;
                $result = $database -> query($query);
                echo $query;
                echo '終了日時を設定できました';
            }catch(Exception $e){
                echo "エラー発生:" . $e->getMessage().'<br>';
                echo "  外勤先を取得できませんでした";
            } 
        }
        if(isset($_POST['finishwork'])){
            try{
                $update_at = date("Y-m-d H:i:s");
                $query = <<<EDO
                    UPDATE staffhistory SET 
                    working = 0 , update_at = '{$update_at}' 
                    WHERE id = {$_POST['historyid']};
                EDO;
                $result = $database -> query($query);
            }catch(Exception $e){
                echo "エラー発生:" . $e->getMessage().'<br>';
                echo "  外勤先を取得できませんでした";
            } 
        }
        if(isset($_POST['changeenddate'])){
            try{
                $update_at = date("Y-m-d H:i:s");
                $query = <<<EDO
                    UPDATE staffhistory SET 
                    enddate =  '{$_POST["enddate"]}', update_at = '{$update_at}' 
                    WHERE id = {$_POST['historyid']};
                EDO;
                $result = $database -> query($query);
            }catch(Exception $e){
                echo "エラー発生:" . $e->getMessage().'<br>';
                echo "  外勤先を取得できませんでした";
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
                echo "エラー発生:" . $e->getMessage().'<br>';
                echo "  外勤先を取得できませんでした";
            }
        }
    
    
    
    
        try{
            $query = 'SELECT  staffhistory.id as id, company.company as company, staffhistory.startdate as startdate, staffhistory.enddate as enddate,
            staffname.name as name , staffhistory.working as working 
            FROM staffhistory LEFT JOIN company ON staffhistory.companyid = company.id LEFT JOIN staffname ON staffname.id = staffhistory.staffid 
            WHERE staffhistory.staffid = '.$staffid.' AND staffhistory.del = 0 
            ORDER BY startdate DESC';
            /* echo $query.'</br>'; */
            $result = $database -> query($query);
            /* $row = mysqli_fetch_assoc($result); */
            echo '外勤先を取得しました';
        }catch(Exception $e){
            echo "エラー発生:" . $e->getMessage().'<br>';
            echo "  外勤先を取得できませんでした";
        } 
        $settext = array();
        $nowsettext = array();
        $nowsettextflag = false;
        $settextflag = false;
        while($row = mysqli_fetch_assoc($result)){
    
            if($row['working'] == true){
                $nowsettext[] = ['company'=>$row['company'],'startdate'=>$row['startdate'],'enddate'=>$row['enddate'],'id'=>$row['id']];
                $nowsettextflag =true;
            }else{
                $settext[] = ['company'=>$row['company'],'startdate'=>$row['startdate'],'enddate'=>$row['enddate'],'id'=>$row['id']];
                $settextflag = true;
            }
        }
        $nowcompanytext = '';
        if($nowsettextflag==false){
            $nowcompanytext =  <<<EOD
            現在所属している会社はありません。
            <button id = 'subwindowbutton' onClick = 'disp("../subwindow/selectcompany.php")' value = '外勤先の選択'>外勤先の追加</button>
            EOD;
        }else{
            $nowcompanytext .= '<button id = \'subwindowbutton\' onClick = \'disp("\../subwindow/selectcompany.php")\' value = \'外勤先の選択\'>外勤先の追加</button>';
            foreach($nowsettext as $nowsettext){
                $nowcompanytext.=<<<EDO
                    <div>
                        <form action = 'detailoutsoucer.php' method = 'post'>
                            会社名:{$nowsettext['company']}  仕事開始日:{$nowsettext['startdate']}<br>
                            
                            <a>仕事終了予定日:{$nowsettext['enddate']}<input type = 'checkbox' class = 'checknextnext'>
                            <input type = 'date' value = '{$nowsettext['enddate']}' min = '{$nowsettext['startdate']}' name= 'enddate'><input type = 'submit' name = 'changeenddate' value = 変更>
                            </a><br>
                        
                            <input type = 'checkbox' class = 'checknextnext'><input type = 'submit'  name = 'finishwork' value = '仕事の完了'><input type = 'submit' name ='delete' value = '削除'>
                            <input type ='hidden' name = 'historyid' value = '{$nowsettext['id']}'>
                            <input type = 'hidden' name = 'staffid' value = '{$staffid}'>
                        </form>
                    </div>
                    EDO;
    
            }
        }
        $historycompanytext='';
        if($settextflag  == true){
    
            $historycompanytext .= '<div><table border = \'1\'><tr><th>会社</th><th>仕事開始日</th><th>仕事終了日</th><th>履歴の削除</th></tr>';
            foreach($settext as $settext){
                $historycompanytext = $historycompanytext.<<<EOD
                    <tr>
                        <td>{$settext['company']}</td>
                        <td>{$settext['startdate']}</td>
                        <td>{$settext['enddate']}</td>
                        <td><form action = 'detailoutsoucer.php' method = 'post' class = 'margin0'>
                            <input type = 'checkbox' class = 'checknext'><input type = 'submit' name = 'delete' value = '削除'><input type = 'hidden' name = 'historyid' value = '{$settext['id']}'>
                        </form></td>
                    </tr>
                EOD;
            }
            $historycompanytext .= '</table></div>';
        }else{
            
    
        }
        

        require_once('html/detailoutsoucerview.php');
?>