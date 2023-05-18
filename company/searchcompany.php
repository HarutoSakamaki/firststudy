
<script src = '../js/jquery-3.6.4.min.js'></script>
<link rel="stylesheet" href="../css/validationEngine.jquery.css">
<script src="../js/jquery.validationEngine.js"></script>
<script src="../js/jquery.validationEngine-ja.js" charset="UTF-8"></script>


<?php
    require_once '../link.php';
    $database = database('staff');
    $rangelimit = 20;

    session_start();
    session_regenerate_id(true);
    if(isset($_SESSION['login'])){
        
    }else{
        $_SESSION['againlogin'] = true;
        header("Location: ../others/login.php");
        exit();
    }

    $tablearray = array();
    $rangelimit = $rangelimit-1;
    $settextsearchcompany = '';
    $settextminemployees = 0;
    $settextmaxemployees = '';
    if(isset($_POST['delete'])){
        $companyid = $_POST['id'];
        $changeup_date = ' update_at = \''.date("Y-m-d H:i:s").'\' ';
        try{
            $query = 'UPDATE company SET del = true , '.$changeup_date.' WHERE company.id = \''.$companyid.'\'';
            $database -> query($query);
            
        }catch(Exception $e){
            
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
        $company = $_POST['searchcompany'];
        if($_POST['minemployees']==''){
            $minemployees = '0';
        }else{
            $minemployees = $_POST['minemployees'];
        }
        $maxemployees = $_POST['maxemployees'];
        $minestablish = $_POST['minyear'].'-'.$_POST['minmonth'].'-1 ';
        $maxestablish = $_POST['maxyear'].'-'.$_POST['maxmonth'].'-31 ';
        if($company != ""){
            $companyterms = ' company LIKE \'%'.$company.'%\' ';
        }else{
            $companyterms = ' company LIKE \'%\' ';
        }
        if($maxemployees == ''){
            $employeesterms = '  numberofemployees >= '.$minemployees.' ';
        }else{
            $employeesterms = '  numberofemployees BETWEEN '.$minemployees.' and '.$maxemployees;
        }
        $establishterms = '  establishdate BETWEEN DATE(\''.$minestablish.'\') and DATE(\''.$maxestablish.'\') ';
        
        
        try{
            $query = 'SELECT * FROM company WHERE '.$companyterms.' AND '.$employeesterms.' AND '.$establishterms.' AND del = false ORDER BY numberofemployees DESC';
            
            $searchresult = $database -> query($query);
            $searchquery = $query;
            while($row = mysqli_fetch_assoc($searchresult)){
                $tablearray[] = ['id' =>$row['id'],'company' =>$row['company'],'numberofemployees'=>$row['numberofemployees'],'establishdate'=>$row['establishdate']];
            }
            $_SESSION['table'] = $tablearray;
        }catch(Exception $e){
            echo "エラー発生:" . $e->getMessage().'<br>';
            echo "検索できませんでした";
        }
        $_SESSION['firstkey'] = 0;
    }

    if(isset($_POST['sort'])){
        $tablearray = $_SESSION['table'];
        if($_POST['sort'] == 'companysortdesc'){
            array_multisort(array_column($tablearray,'company'), SORT_DESC , $tablearray);
        }elseif($_POST['sort'] == 'numberofemployeessortdesc'){
            array_multisort(array_column($tablearray,'numberofemployees'), SORT_DESC , $tablearray);
        }elseif($_POST['sort'] == 'establishdatesortdesc'){
            array_multisort(array_column($tablearray,'establishdate'), SORT_DESC , $tablearray);
        }elseif($_POST['sort'] == 'joincompanydaysortdesc'){
            array_multisort(array_column($tablearray,'joincompanyday'), SORT_DESC , $tablearray);
        }elseif($_POST['sort'] == 'companysortasc'){
            array_multisort(array_column($tablearray,'company'), SORT_ASC , $tablearray);
        }elseif($_POST['sort'] == 'numberofemployeessortasc'){
            array_multisort(array_column($tablearray,'numberofemployees'), SORT_ASC , $tablearray);
        }elseif($_POST['sort'] == 'establishdatesortasc'){
            array_multisort(array_column($tablearray,'establishdate'), SORT_ASC , $tablearray);
        }elseif($_POST['sort'] == 'joincompanydaysortasc'){
            array_multisort(array_column($tablearray,'joincompanyday'), SORT_ASC , $tablearray);
        }
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
    if ($postflag == true){
        if(count($tablearray)==0){
            $tabletext .= '<div class=\'center\'><a class = \'failfont\'>該当する会社はありませんでした</div></a>';
        }else{
            $tabletext .= <<<EDO
                <table border="1" class = 'table1'>
                    <tr>
                        <form action = 'searchcompany.php' method = 'post'>
                            <th>会社名<button name = 'sort' value = 'companysortdesc'>&darr;</button><button name = 'sort' value = 'companysortasc'>&uarr;</button></th>
                            <th>従業員数<button name = 'sort' value = 'numberofemployeesortdesc'>&darr;</button><button name = 'sort' value = 'numberofemployeessortasc'>&uarr;</button></th>
                            <th>設立日<button name = 'sort' value = 'establishdatesortdesc'>&darr;</button><button name = 'sort' value = 'establishdatesortasc'>&uarr;</button></th>
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
                    $companytable = htmlentities($tablecontent['company']);
                    $numberofemployeestable = htmlentities($tablecontent['numberofemployees']);
                    $establishdatetable = htmlentities($tablecontent['establishdate']);

                    $tabletext .= <<<EDO
                        <tr>
                            <td>{$companytable}</td><td>{$numberofemployeestable}</td><td>{$establishdatetable}</td>
                            <td><form action = 'searchcompany.php' method=post>
                                    <button type = 'button' class='commonbutton' name='del'value='削除' onClick = 'deleteform({$tablecontent['id']},"{$companytable}")' id = '{$tablecontent['id']}' >
                                        <img src="../img/deleteicon.png" alt=""/>削除
                                    </button>
                                    <input type= 'hidden' name=  'id' value =  '{$tablecontent['id']}'>
                                    <input type = 'hidden' name = 'delete' value = '削除'>
                                    <input id = 'inputsearchquery' type = 'hidden' name=  'key' value =  '{$key}'>
                                </form>
                            </td>
                            <td><form action = 'detailcompany.php' method=post>
                                    <input type = 'submit'class = 'commonbutton' name='detail'value='詳細と変更'>
                                    <input type='hidden' name=  'companyid' value =  '{$tablecontent['id']}'>
                                </form>
                            </td>
                        </tr>
                    EDO;
                }
            }
        
            $tabletext.='</table>';
            $tablecounttext = '<div class = \'center\'>';
            if($firstkey!=0){
                $sendkey = $firstkey-1;
                $tablecounttext .= <<<EOD
                    <form name = 'tableforward 'action = 'searchcompany.php' method ='post'>
                        <button name = 'tableforward' value = '{$sendkey}'>&larr;</button>
                    </form>
                EOD;
            }
            $tablecounttext .= count($tablearray).'件中 '.($firstkey+1).'~'.$firstkey+$keycount.'件表示';
            if($firstkey+$keycount< count($tablearray)){
                $sendkey = $firstkey+$keycount;
                $tablecounttext .= <<<EOD
                    <form name = 'tableback 'action = 'searchcompany.php' method ='post'>
                        <button name = 'tableback' value = '{$sendkey}'>&rarr;</button>
                    </form>
                EOD;
            }
            $tablecounttext .= '</div>';
        }
    }
     
    require_once('html/searchcompanyview.php');
?>

