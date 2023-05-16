
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

    if(isset($_POST['delete'])){
        $companyid = $_POST['id'];
        $changeup_date = ' update_at = \''.date("Y-m-d H:i:s").'\' ';
        try{
            $query = 'UPDATE company SET del = true , '.$changeup_date.' WHERE company.id = \''.$companyid.'\'';
            $database -> query($query);
            /* echo "削除しました"; */
        }catch(Exception $e){
            /* echo "エラー発生:" . $e->getMessage().'<br>';
            echo "削除できませんでした"; */
        }
    }

    /* try{
        $query = "SELECT * FROM company WHERE del = false ORDER BY numberofemployees DESC";
        $result = $database -> query($query);
    }catch (Exception $e){
        echo "エラー発生:" . $e->getMessage().'<br>';
        echo "取得できませんでした";
    } */
    $postflag = false;
    if(isset($_POST['search']) or isset($_POST['delete'])){
        $postflag = true;
    }
        
?>

<?php
    if(isset($_POST['delete'])){
        $searchquery = $_POST['searchquery'];
        $searchquery = formbackquery($searchquery);
        try{
            $searchresult = $database -> query($searchquery);
        }catch(Exception $e){
            echo "エラー発生:" . $e->getMessage().'<br>';
            echo "検索できませんでした";
        }
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
            $employeesterms = ' AND numberofemployees >= '.$minemployees.' ';
        }else{
            $employeesterms = ' AND numberofemployees BETWEEN '.$minemployees.' and '.$maxemployees;
        }
        $establishterms = ' AND establishdate BETWEEN DATE(\''.$minestablish.'\') and DATE(\''.$maxestablish.'\') ';
        $ordertext = '';
        if($_POST['inorder'] == 'employeedesc'){
            $ordertext .= ' ORDER BY numberofemployees DESC ';
        }elseif($_POST['inorder'] == 'employeeasc'){
            $ordertext .= ' ORDER BY numberofemployees ASC ';
        }elseif($_POST['inorder'] == 'regidesc'){
            $ordertext .= ' ORDER BY created_at DESC ';
        }elseif($_POST['inorder'] == 'regiasc'){
            $ordertext .= ' ORDER BY created_at ASC ';
        }elseif($_POST['inorder'] == 'establishdesc'){
            $ordertext .= ' ORDER BY establishdate DESC  ';
        }elseif($_POST['inorder'] == 'establishasc'){
            $ordertext .= ' ORDER BY establishdate ASC  ';
        }elseif($_POST['inorder'] == 'comapnynamedesc'){
            $ordertext .= ' ORDER BY company DESC  ';
        }elseif($_POST['inorder'] == 'comapnynameasc'){
            $ordertext .= ' ORDER BY company ASC  ';
        }
        
        
        try{
            
            $query = 'SELECT * FROM company WHERE '.$companyterms.$employeesterms.$establishterms.' AND del = false AND id != 1 '.$ordertext;
            /* echo $query;  */
            $searchresult = $database -> query($query);
            $searchquery = $query;
            
        }catch(Exception $e){
            echo "エラー発生:" . $e->getMessage().'<br>';
            echo "検索できませんでした";
        }
    }
    $tabletext = '';
    if (isset($_POST['search']) or isset($_POST['delete'])){
        $tabletext .= <<<EDO
            
            <table border="1" class = 'table1'>
                <tr>
                    <th>会社名</th>
                    <th>従業員数</th>
                    <th>設立日</th>
                    <th>削除</th>
                    <th>詳細と変更</th>
                </tr>
            EDO;
        $searchquery = formquery($searchquery);
        while($row = mysqli_fetch_assoc($searchresult)){
            $companytable = htmlentities($row['company']);
            $numberofemployeestable = htmlentities($row['numberofemployees']);
            $establishdatetable = htmlentities($row['establishdate']);

            $tabletext .= <<<EDO
                <tr>
                    <td>{$companytable}</td><td>{$numberofemployeestable}</td><td>{$row['establishdate']}</td>
                    <td><form action = 'searchcompany.php' method=post>
                            <button type = 'button' class='commonbutton' name='del'value='削除' onClick = 'deleteform({$row['id']},"{$companytable}")' id = '{$row['id']}' >
                                <img src="../img/deleteicon.png" alt=""/>削除
                            </button>
                            <input type= 'hidden' name=  'id' value =  '{$row['id']}'>
                            <input type = 'hidden' name = 'delete' value = '削除'>
                            <input id = 'inputsearchquery' type='hidden' name=  'searchquery' value =  '{$searchquery}'>
                        </form>
                    </td>
                    <td><form action = 'detailcompany.php' method=post><input type = 'submit'class = 'commonbutton' name='detail'value='詳細と変更'>
                        <input type='hidden' name=  'companyid' value =  '{$row['id']}'>
                        <input type='hidden' name=  'searchquery' value =  '{$searchquery}'>
                    </form></td>
                </tr>
                EDO;
        }
        $tabletext.='</table>';
    }

    //ラジオボタンの生成
    $settextorder = '';
    if(!isset($_POST['inorder'])){
        $_POST['inorder'] = 'employeedesc';
    }

    $settextorder .= <<<EDO
        <input type = 'radio' name = 'inorder' value = 'employeedesc' 
        EDO;
    if($_POST['inorder'] == 'employeedesc'){
        $settextorder .= ' checked ';
    }
    $settextorder .= <<<EDO
        >従業員数降順  <input type = 'radio' name = 'inorder' value = 'employeeasc'
        EDO;

    if($_POST['inorder'] == 'employeeasc'){
        $settextorder .= ' checked ';
    }
    $settextorder .= <<<EDO
        >従業員数昇順<input type = 'radio' name = 'inorder' value = 'regidesc'
        EDO;
    if($_POST['inorder'] == 'regidesc'){
        $settextorder .= ' checked ';
    }
    $settextorder .= <<<EDO
        >登録新着順  <input type = 'radio' name = 'inorder' value = 'regiasc'
        EDO;
    
    if($_POST['inorder'] == 'regiasc'){
        $settextorder .= ' checked ';
    }
    $settextorder .= <<<EDO
        >登録投稿順 <br><input type = 'radio' name = 'inorder' value = 'establishdesc'
        EDO;
    if($_POST['inorder'] == 'establishdesc'){
        $settextorder .= ' checked ';
    }
    $settextorder .= <<<EDO
        >設立日降順  <input type = 'radio' name = 'inorder' value = 'establishasc'
        EDO;

    if($_POST['inorder'] == 'establishasc'){
        $settextorder .= ' checked ';
    }
    $settextorder .= <<<EDO
        >設立日昇順<input type = 'radio' name = 'inorder' value = 'companynamedesc'
        EDO;

    if($_POST['inorder'] == 'companynamedesc'){
        $settextorder .= ' checked ';
    }
    $settextorder .= <<<EDO
        >会社名降順  <input type = 'radio' name = 'inorder' value = 'companynameasc'
        EDO;

    if($_POST['inorder'] == 'companynameasc'){
        $settextorder .= ' checked ';
    }
    $settextorder .= <<<EDO
        >会社名昇順
        EDO;
    
    
    require_once('html/searchcompanyview.php');
?>

