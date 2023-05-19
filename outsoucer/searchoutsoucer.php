
<script src = '../js/jquery-3.6.4.min.js'></script>
<link rel="stylesheet" href="../css/validationEngine.jquery.css">
<script src="../js/jquery.validationEngine.js"></script>
<script src="../js/jquery.validationEngine-ja.js" charset="UTF-8"></script>

<?php
    require_once '../link.php';
    $database = database('staff');
    $rangelimit = 2;

    session_start();
    session_regenerate_id(true);
    $tablearray = array();
    if(isset($_SESSION['login'])){
        
    }else{
        $_SESSION['againlogin'] = true;
        header("Location: ../others/login.php");
        exit();
    }
    
    $deletesuccesstext = '';
    $rangelimit = $rangelimit-1;
    $settextname = '';
    $settextemployeeid= '';
    

    if(isset($_POST['delete'])){
        $id = $_POST['staffid'];
        $changeup_date = ' upd_date = \''.date("Y-m-d H:i:s").'\' ';
        try{
            $query = "UPDATE tbm_staffname SET flg_del = 'true' , ".$changeup_date." WHERE pk_id_staffname = '{$id}' ";
            $database -> query($query);
            /* echo $query;
            echo '削除しました'; */
            $deletesuccesstext = '<div>削除しました</div>';
        }catch(Exception $e){
            /* echo "エラー発生:" . $e->getMessage().'<br>';
            echo "削除できませんでした"; */
        }
    }
    $postflag = false;
    if(isset($_POST['search']) or isset($_POST['delete']) or isset($_POST['sort']) or isset($_POST['tableforward']) or isset($_POST['tableback'])){
        $postflag = true;
    }
?>

<?php
    if(isset($_POST['delete'])){
        $tablearray = $_SESSION['table'];
        unset($tablearray[$_POST['key']]);
        $tablearray = array_merge($tablearray);
        $_SESSION['table'] = $tablearray;
    }

    if(isset($_POST['search'])){
        unset($_SESSION['table']);
        $searchname = $_POST['searchname'];
        $searchemployeeid = $_POST['employeeid'];
        $minbirth = $_POST['birthminyear'].'-'.$_POST['birthminmonth'].'-0 ';
        $maxbirth = $_POST['birthmaxyear'].'-'.$_POST['birthmaxmonth'].'-31 ';
        $minjoin = $_POST['joinminyear'].'-'.$_POST['joinminmonth'].'-0 ';
        $maxjoin = $_POST['joinmaxyear'].'-'.$_POST['joinmaxmonth'].'-31 ';
       
        if($searchname != ""){
            $searchnameterms = ' nm_name LIKE \'%'.$searchname.'%\' ';
        }else{
            $searchnameterms = ' nm_name LIKE \'%\' ';
        }
        if($searchemployeeid != ''){
            $searchemployeeidterms = ' AND no_employeeid LIKE \'%'.$searchemployeeid.'%\' ';
        }else{
            $searchemployeeidterms = ' AND no_employeeid LIKE \'%\' ';
        }
        
        $birthterms = ' AND dt_birthday BETWEEN DATE(\''.$minbirth.'\') and DATE(\''.$maxbirth.'\') ';
        $jointerms = ' AND dt_joincompanyday BETWEEN DATE(\''.$minjoin.'\') and DATE(\''.$maxjoin.'\') ';
        
        try{
            $query = 'SELECT * FROM tbm_staffname 
            WHERE '.$searchnameterms.$searchemployeeidterms.$birthterms.$jointerms.' AND tbm_staffname.flg_del = false ORDER BY dt_birthday ASC';

            $searchresult = $database -> query($query);
            $searchquery = $query;
            while($row = mysqli_fetch_assoc($searchresult)){
                $tablearray[] = ['id' =>$row['pk_id_staffname'],'name' =>$row['nm_name'],'employeeid'=>$row['no_employeeid'],'birthday'=>$row['dt_birthday'],'joincompanyday'=>$row['dt_joincompanyday']];
            }
            $_SESSION['table'] = $tablearray;
        }catch(Exception $e){
            /* echo "エラー発生:" . $e->getMessage().'<br>';
            echo "検索できませんでした"; */
        }
        $_SESSION['firstkey'] = 0;
    }
    if(isset($_POST['sort'])){
        $tablearray = $_SESSION['table'];
        
        if($_POST['sort'] == 'namesortdesc'){
            array_multisort(array_column($tablearray,'name'), SORT_DESC , $tablearray);
        }elseif($_POST['sort'] == 'employeeidsortdesc'){
            array_multisort(array_column($tablearray,'employeeid'), SORT_DESC , $tablearray);
        }elseif($_POST['sort'] == 'birthdaysortdesc'){
            array_multisort(array_column($tablearray,'birthday'), SORT_DESC , $tablearray);
        }elseif($_POST['sort'] == 'joincompanydaysortdesc'){
            array_multisort(array_column($tablearray,'joincompanyday'), SORT_DESC , $tablearray);
        }elseif($_POST['sort'] == 'namesortasc'){
            array_multisort(array_column($tablearray,'name'), SORT_ASC , $tablearray);
        }elseif($_POST['sort'] == 'employeeidsortasc'){
            array_multisort(array_column($tablearray,'employeeid'), SORT_ASC , $tablearray);
        }elseif($_POST['sort'] == 'birthdaysortasc'){
            array_multisort(array_column($tablearray,'birthday'), SORT_ASC , $tablearray);
        }elseif($_POST['sort'] == 'joincompanydaysortasc'){
            array_multisort(array_column($tablearray,'joincompanyday'), SORT_ASC , $tablearray);
        }
        


        $_SESSION['table'] = $tablearray;

    }
    if(isset($_POST['tableforward'])){
        if($_POST['tableforward']-$rangelimit>=0){
            $firstkey = $_POST['tableforward']-$rangelimit;
        }else{
            $firstkey = 0;
        }
        $_SESSION['firstkey'] = $firstkey;
        $tablearray = $_SESSION['table'];
    }elseif(isset($_POST['tableback'])){
        $firstkey = $_POST['tableback'];
        $_SESSION['firstkey'] = $firstkey;
        $tablearray = $_SESSION['table'];
    }

    $tabletext = '';
    $tablecounttext = '';
    if($postflag == true){
        if(count($tablearray)==0){
            $tabletext .= '<div class=\'center\'><a class = \'failfont\'>該当するアウトソーサーはいません</a></div>';
        }else{
            $tabletext.= <<<EDO
                <table class = 'table1'>
                    <tr>
                        <form action = 'searchoutsoucer.php' method = 'post'>
                            <th>名前<button name = 'sort' value = 'namesortdesc'>&darr;</button><button name = 'sort' value = 'namesortasc'>&uarr;</button></th>
                            <th>社員番号<button name = 'sort' value = 'employeeidsortdesc'>&darr;</button><button name = 'sort' value = 'employeeidsortasc'>&uarr;</button></th>
                            <th>生年月日<button name = 'sort' value = 'birthdaysortdesc'>&darr;</button><button name = 'sort' value = 'birthdaysortasc'>&uarr;</button></th>
                            <th>入社日<button name = 'sort' value = 'joincompanydaysortdesc'>&darr;</button><button name = 'sort' value = 'joincompanydaysortasc'>&uarr;</button></th>
                            <th>削除</th>
                            <th>詳細と変更</th>
                            
                        </form>
                    </tr>
                EDO;
            $keycount = 0;
            $firstkey = $_SESSION['firstkey'];
            foreach($tablearray as $key => $tablecontent){
                if($key>=$firstkey && $key <= $firstkey+$rangelimit){
                    $keycount++;
                    $setname = htmlentities($tablecontent['name']);
                    $setemployeeid = htmlentities($tablecontent['employeeid']);
                    $setbirthday = htmlentities($tablecontent['birthday']);
                    $setjoincompanyday = htmlentities($tablecontent['joincompanyday']);

                    $tabletext .= <<<EDO
                        <tr>
                            <td>{$setname}</td><td>{$setemployeeid}</td><td>{$setbirthday}</td><td>{$setjoincompanyday}</td>
                            <td>
                                <form action = 'searchoutsoucer.php' method=post>
                                    <button type = 'button'class = 'commonbutton'name='del'value='削除' onClick = 'deleteform({$tablecontent['id']},"{$setname}")' id = '{$tablecontent['id']}'>
                                        <img src="../img/deleteicon.png" alt=""/>削除
                                    </button>
                                    <input type='hidden' name=  'staffid' value =  '{$tablecontent['id']}'>
                                    <input type='hidden' name = 'delete' value = '削除'>
                                    <input id = 'inputsearchquery' type = 'hidden' name=  'key' value =  '{$key}'>
                                </form>
                            </td>
                            <td><form action = 'detailoutsoucer.php' method=post><input class='commonbutton' type = 'submit' name='detail' value='詳細と変更'>
                            <input type='hidden' name=  'staffid' value =  '{$tablecontent['id']}'>
                            </form></td>
                        </tr>
                    EDO;
                }
            }
            $tabletext.='</table>';

            $tablecounttext = '<div class = \'center\'>';
            if($firstkey!=0){
                $sendkey = $firstkey-1;
                $tablecounttext .= <<<EOD
                    <form name = 'tableforward 'action = 'searchoutsoucer.php' method ='post'>
                        <button name = 'tableforward' value = '{$sendkey}'>&larr;</button>
                    </form>
                EOD;
            }
            $tablecounttext .= count($tablearray).'件中 '.($firstkey+1).'~'.$firstkey+$keycount.'件表示';
            if($firstkey+$keycount < count($tablearray)){
                $sendkey = $firstkey+$keycount;
                $tablecounttext .= <<<EOD
                    <form name = 'tableback 'action = 'searchoutsoucer.php' method ='post'>
                        <button name = 'tableback' value = '{$sendkey}'>&rarr;</button>
                    </form>
                EOD;
            }
            $tablecounttext .= '</div>';
        }
    }

    


    require_once('html/searchoutsoucerview.php');
?>

            
          
    
    
