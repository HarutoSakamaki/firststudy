    <script src="../js/jquery-3.6.4.min.js"></script>
    <?php
        require_once '../link.php';
        $database = database('staff');
        session_start();
        
        
        $id = $_POST['companyid'];
        $companyid = $id;
        $_SESSION['companyid'] = $id;
        try{
            $query = "SELECT * FROM company WHERE del = false AND id = ".$id;
            $result = $database -> query($query);
            $row1 = mysqli_fetch_assoc($result);
            /* echo '詳細を取得しました'; */
        }catch(Exception $e){
            /* echo "エラー発生:" . $e->getMessage().'<br>';
            echo "  詳細を取得できませんでした。"; */
        }
        
        $establishdatearray = explode('-', $row1['establishdate']);
        $eastablishyear = $establishdatearray[0];
        $establishmonth = $establishdatearray[1];
        $establishday = $establishdatearray[2];
        if( $row1['prefectures'] == ''){
            $settextlocation = $row1['location'];
        }else{
            $settextlocation = getpref($row1['prefectures']).' '.$row1['location'];
        }
        $businessdetailsarray = json_decode($row1['businessdetails'],true);
        $bankarray = json_decode($row1['bank'],true);
        

        $businessdetailtext = '';
        $count = 0;
        while(isset($businessdetailsarray[$count])){
            $businessdetailtext.=($count+1).'.'.$businessdetailsarray[$count].'<br>';
            $count++;
        }

        $banktext = '';
        $count = 0;
        while(isset($bankarray[$count])){
            $banktext.=($count+1).'.'.$bankarray[$count].'<br>';
            $count++;
        }

        if($row1['averageage'] ==''){
            $settextaverageage = '';
        }else{
            $settextaverageage = $row1['averageage'].'歳';
        }
        if($row1['closingmonth'] ==''){
            $settextclosingmonth = '';
        }else{
            $settextclosingmonth = $row1['closingmonth'].'月';
        }
        $settextsales = $row1['sales'];
        $settextdigit = '';
        if($settextsales != ''){
            $settextsales = $settextsales;
            $digit =  1;
            while($settextsales%10000 == 0 and $settextsales !=0){
                $settextsales = $settextsales/10000;
                $digit = $digit*10000;
            }if($digit == 1){
                $settextsales = $settextsales.'円';
            }elseif($digit == 10000){
                $settextsales = $settextsales.'万円';
            }elseif($digit == 100000000){
                $settextsales = $settextsales.'億円';
            }elseif($digit == 1000000000000){
                $settextsales = $settextsales.'兆円';
            }
        }



        //ここからworkplace系

        if(isset($_POST['companyid'])){
            $companyid = $_POST['companyid'];
            $_SESSION['comapnyid'] = $companyid;
        }else{
            $companyid = $_SESSION['companyid'];
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
                /* echo $query; */
                /* echo '終了日時を設定できました'; */
            }catch(Exception $e){
                /* echo "エラー発生:" . $e->getMessage().'<br>';
                echo "  外勤先を取得できませんでした"; */
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
                /* echo "エラー発生:" . $e->getMessage().'<br>';
                echo "  外勤先を取得できませんでした"; */
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


        //ここからworkplace系

        try{
            
            $query = 'SELECT  staffhistory.id as id, company.company as company, staffhistory.startdate as startdate, staffhistory.enddate as enddate,
            staffname.name as staffname  
            FROM staffhistory LEFT JOIN company ON staffhistory.companyid = company.id LEFT JOIN staffname ON staffname.id = staffhistory.staffid 
            WHERE staffhistory.companyid = '.$companyid.' AND staffhistory.del = 0 
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
            $settext[] = ['company'=>$row['company'],'startdate'=>$row['startdate'],'enddate'=>$row['enddate'],'id'=>$row['id'],'staffname'=>$row['staffname']];
            $settextflag = true;
        }

        $historyoutsoucertext='';
        if($settextflag  == true){
            $nowdate = new DateTime(date('Y-m-d'));
            $historyoutsoucertext .= '<div><table class = \'workplacetable\'><tr><th>アウトソーサー</th><th>仕事開始日</th><th>仕事終了日</th><th>状態</th><th>履歴の削除</th></tr>';
            foreach($settext as $settext){
                $setcompany = htmlentities($settext['company']);
                $setstaffname = htmlentities($settext['staffname']);
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
                $historyoutsoucertext .=<<<EOD
                    <tr>
                        <td>{$setstaffname}</td>
                        <td>{$setstartdate}</td>
                        <td>{$setenddate}</td>
                        <td>{$status}</td>
                        <td><form action = 'detailoutsoucer.php' method = 'post' class = 'margin0' id = 'delete{$settext['id']}' onsubmit="return deleteform()">
                            <input type = 'submit' name = 'delete' value = '削除' >
                            <input type = 'hidden' name = 'historyid' value = '{$settext['id']}'>
                            <input type = 'hidden' name = 'companyid' value = '{$companyid}'>
                        </form></td>
                    </tr>
                EOD;
            }
            $historyoutsoucertext .= '</table></div>';
        }else{
            $historyoutsoucertext .= '履歴がありません';
    
        }



        require_once('html/detailcompanyview.php');

    ?>