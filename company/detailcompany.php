    <script src="../js/jquery-3.6.4.min.js"></script>
    <?php
        require_once '../link.php';
        $database = database('staff');
        session_start();
        
        
        $id = $_POST['companyid'];
        $companyid = $id;
        $_SESSION['companyid'] = $id;
        echo $id;
        try{
            $query = "SELECT * FROM company WHERE del = false AND id = ".$id;
            $result = $database -> query($query);
            $row1 = mysqli_fetch_assoc($result);
            echo '詳細を取得しました';
        }catch(Exception $e){
            echo "エラー発生:" . $e->getMessage().'<br>';
            echo "  詳細を取得できませんでした。";
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
        

        $businessdetailtext = '';
        $count = 0;
        while(isset($businessdetailsarray[$count])){
            $businessdetailtext.=$count.'.'.$businessdetailsarray[$count].'<br>';
            $count++;
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


        //ここからworkplace系

        try{
            /* $query = 'SELECT  staffhistory.id as id, company.company as company, staffhistory.startdate as startdate, staffhistory.enddate as enddate,
            staffname.name as staffname , staffhistory.working as working 
            FROM staffhistory LEFT JOIN company ON staffhistory.companyid = company.id LEFT JOIN staffname ON staffname.id = staffhistory.staffid 
            WHERE staffhistory.companyid = '.$companyid.' AND staffhistory.del = 0 
            ORDER BY startdate DESC'; */
            $query = 'SELECT  staffhistory.id as id, company.company as company, staffhistory.startdate as startdate, staffhistory.enddate as enddate,
            staffname.name as staffname , staffhistory.working as working 
            FROM staffhistory LEFT JOIN company ON staffhistory.companyid = company.id LEFT JOIN staffname ON staffname.id = staffhistory.staffid 
            WHERE staffhistory.companyid = '.$companyid.' AND working = true AND staffhistory.del = 0 
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
                $nowsettext[] = ['company'=>$row['company'],'startdate'=>$row['startdate'],'enddate'=>$row['enddate'],'id'=>$row['id'],'staffname'=>$row['staffname']];
                $nowsettextflag =true;
            }else{
                $settext[] = ['company'=>$row['company'],'startdate'=>$row['startdate'],'enddate'=>$row['enddate'],'id'=>$row['id'],'staffname'=>$row['staffname']];
                $settextflag = true;
            }
        }

        $nowoutsoucertext = '';
        if($nowsettextflag==false){
            /* $nowoutsoucertext =  <<<EOD
            現在所属しているアウトソーサーはいません。
            <button id = 'subwindowbutton' onClick = 'disp("../subwindow/selectoutsoucer.php")' value = 'アウトソーサーの選択'>アウトソーサーの追加</button> 
            EOD; */
            $nowoutsoucertext = '現在所属しているアウトソーサーはいません';
        }else{
            /* $nowoutsoucertext .= '<button id = \'subwindowbutton\' onClick = \'disp("\../subwindow/selectoutsoucer.php")\' value = \'アウトソーサーの選択\'>アウトソーサーの追加</button>'; */
            foreach($nowsettext as $nowsettext){
                /* $nowoutsoucertext.=<<<EDO
                    <div>
                        <form action = 'detailcompany.php' method = 'post'>
                            <div class = 'left'>名前:{$nowsettext['staffname']}  仕事開始日:{$nowsettext['startdate']}</div><br>
                            <div class = 'left'><a>仕事終了予定日:{$nowsettext['enddate']}</div><div class = 'left'><input type = 'checkbox' class = 'checknextnext'>
                            <input type = 'date' value = '{$nowsettext['enddate']}' min = '{$nowsettext['startdate']}' name= 'enddate'><input type = 'submit' name = 'changeenddate' value = 変更></div>
                            </a><br>
                        
                            <div class = 'left'><input type = 'checkbox' class = 'checknext'><input type = 'submit'  name = 'finishwork' value = '仕事の完了'></div><div class = 'left'><input type = 'checkbox' class = 'checknext'><input type = 'submit' name ='delete' value = '削除'></div>
                            <input type ='hidden' name = 'historyid' value = '{$nowsettext['id']}'>
                            <input type = 'hidden' name = 'companyid' value = '{$companyid}'>
                        </form>
                    </div>
                    EDO; */
                $nowstaffname = htmlentities($nowsettext['staffname']);
                $nowstartdate = htmlentities($nowsettext['startdate']);
                $nowenddate = htmlentities($nowsettext['enddate']);
                $nowoutsoucertext.= <<<EDO
                    <div>
                        <form action = 'detailcompany.php' method = 'post'>
                            <div class = 'left'>名前:{$nowstaffname}  仕事開始日:{$nowstartdate}</div><br>
                            <div class = 'left'><a>仕事終了予定日:{$nowenddate}</div>
                        </form>
                    </div>
                    EDO;
            }
        }
        $historyoutsoucertext='';
        if($settextflag  == true){
    
            $historyoutsoucertext .= '<div><table border = \'1\'><tr><th>名前</th><th>仕事開始日</th><th>仕事終了日</th><th>履歴の削除</th></tr>';
            foreach($settext as $settext){
                $setstaffname = htmlentities($settext['staffname']);
                $setstartdate = htmlentities($settext['startdate']);
                $setenddate = htmlentities($settext['enddate']);
                $historyoutsoucertext = $historyoutsoucertext.<<<EOD
                    <tr>
                        <td>{$setstaffname}</td>
                        <td>{$setstartdate}</td>
                        <td>{$setenddate}</td>
                        <td><form action = 'detailcompany.php' method = 'post' class = 'margin0'>
                            <input type = 'checkbox' class = 'checknext'><input type = 'submit' name = 'delete' value = '削除'><input type = 'hidden' name = 'historyid' value = '{$settext['id']}'>
                        </form></td>
                    </tr>
                EOD;
            }
            $historyoutsoucertext .= '</table></div>';
        }else{
            
    
        }



        require_once('html/detailcompanyview.php');

    ?>