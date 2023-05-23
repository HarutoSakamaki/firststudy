    <script src="../js/jquery-3.6.4.min.js"></script>
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
            
        
        $id = $_POST['companyid'];
        $companyid = $id;
        $_SESSION['companyid'] = $id;
        try{
            $query = "SELECT pk_id_company , nm_company , su_numberofemployees , dt_establishdate , kbn_postcode , kbn_prefectures , nm_location , nm_president 
             , nm_businessdetails , nm_homepage , kbn_closingmonth , su_sales , su_capital , su_averageage , nm_bank 
             FROM tbm_company_kiso WHERE flg_del = false AND pk_id_company = ".$id;
            $result = $database -> query($query);
            $row1 = mysqli_fetch_assoc($result);
            /* echo '詳細を取得しました'; */
        }catch(Exception $e){
            /* echo "エラー発生:" . $e->getMessage().'<br>';
            echo "  詳細を取得できませんでした。"; */
        }
        
        $establishdatearray = explode('-', $row1['dt_establishdate']);
        $eastablishyear = $establishdatearray[0];
        $establishmonth = $establishdatearray[1];
        $establishday = $establishdatearray[2];
        if( $row1['kbn_prefectures'] == ''){
            $settextlocation = $row1['nm_location'];
        }else{
            $settextlocation = getpref($row1['kbn_prefectures']).' '.$row1['nm_location'];
        }
        if($row1['kbn_postcode'] != '' and strlen($row1['kbn_postcode']) == 7){
            $pos = str_split($row1['kbn_postcode']);
            $settextlocation .= '〒'.$pos['0'].$pos['1'].$pos['2'].'-'.$pos['3'].$pos['4'].$pos['5'].$pos['6'].$settextlocation;
        }
        $businessdetailsarray = json_decode($row1['nm_businessdetails'],true);
        $bankarray = json_decode($row1['nm_bank'],true);
        

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

        if($row1['su_averageage'] ==''){
            $settextaverageage = '';
        }else{
            $settextaverageage = $row1['su_averageage'].'歳';
        }
        if($row1['kbn_closingmonth'] ==''){
            $settextclosingmonth = '';
        }else{
            $settextclosingmonth = $row1['kbn_closingmonth'].'月';
        }
        $settextsales = $row1['su_sales'];
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

        $settextcapital = $row1['su_capital'];
        $settextdigit = '';
        if($settextcapital != ''){
            $digit =  1;
            while($settextcapital%10000 == 0 and $settextcapital !=0){
                $settextcapital = $settextcapital/10000;
                $digit = $digit*10000;
            }if($digit == 1){
                $settextcapital = $settextcapital.'円';
            }elseif($digit == 10000){
                $settextcapital = $settextcapital.'万円';
            }elseif($digit == 100000000){
                $settextcapital = $settextcapital.'億円';
            }elseif($digit == 1000000000000){
                $settextcapital = $settextcapital.'兆円';
            }
        }



        //ここからworkplace系

        if(isset($_POST['companyid'])){
            $companyid = $_POST['companyid'];
            $_SESSION['comapnyid'] = $companyid;
        }else{
            $companyid = $_SESSION['companyid'];
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


        //ここからworkplace系

        try{
            
            $query = 'SELECT  tbm_staffhistory_kiso.pk_id_staffhistory as id, tbm_company_kiso.nm_company as company, tbm_staffhistory_kiso.dt_startdate as startdate, tbm_staffhistory_kiso.dt_enddate as enddate,
            tbm_staffname_kiso.nm_name as staffname  
            FROM tbm_staffhistory_kiso LEFT JOIN tbm_company_kiso ON tbm_staffhistory_kiso.no_companyid = tbm_company_kiso.pk_id_company LEFT JOIN tbm_staffname_kiso ON tbm_staffname_kiso.pk_id_staffname = tbm_staffhistory_kiso.no_staffid 
            WHERE tbm_staffhistory_kiso.no_companyid = '.$companyid.' AND tbm_staffhistory_kiso.flg_del = 0 
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
            $historyoutsoucertext .= '<div><table class = \'workplacetable\'><tr><th>アウトソーサー</th><th>仕事開始日</th><th>仕事終了日</th><th>状態</th></tr>';
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
                        
                    </tr>
                EOD;
            }
            $historyoutsoucertext .= '</table></div>';
        }else{
            $historyoutsoucertext .= '履歴がありません';
        }

   

        require_once('html/detailcompanyview.php');

    ?>