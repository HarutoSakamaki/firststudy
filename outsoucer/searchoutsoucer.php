
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
        $id = $_POST['staffid'];
        $changeup_date = ' update_at = \''.date("Y-m-d H:i:s").'\' ';
        try{
            $query = "UPDATE staffname SET del = 'true' , ".$changeup_date." WHERE id = '{$id}' ";
            $database -> query($query);
            /* echo $query;
            echo '削除しました'; */
        }catch(Exception $e){
            /* echo "エラー発生:" . $e->getMessage().'<br>';
            echo "削除できませんでした"; */
        }
    }
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
            /* echo "エラー発生:" . $e->getMessage().'<br>';
            echo "検索できませんでした"; */
        }

    }

    if(isset($_POST['search'])){
        $searchname = $_POST['searchname'];
        $searchemployeeid = $_POST['employeeid'];
        $minbirth = $_POST['birthminyear'].'-'.$_POST['birthminmonth'].'-0 ';
        $maxbirth = $_POST['birthmaxyear'].'-'.$_POST['birthmaxmonth'].'-31 ';
        $minjoin = $_POST['joinminyear'].'-'.$_POST['joinminmonth'].'-0 ';
        $maxjoin = $_POST['joinmaxyear'].'-'.$_POST['joinmaxmonth'].'-31 ';
       
        if($searchname != ""){
            $searchnameterms = ' name LIKE \'%'.$searchname.'%\' ';
        }else{
            $searchnameterms = ' name LIKE \'%\' ';
        }
        if($searchemployeeid != ''){
            $searchemployeeidterms = ' AND employeeid LIKE \'%'.$searchemployeeid.'%\' ';
        }else{
            $searchemployeeidterms = ' AND employeeid LIKE \'%\' ';
        }
        
        $birthterms = ' AND birthday BETWEEN DATE(\''.$minbirth.'\') and DATE(\''.$maxbirth.'\') ';
        $jointerms = ' AND joincompanyday BETWEEN DATE(\''.$minjoin.'\') and DATE(\''.$maxjoin.'\') ';

        $ordertext = '';
        if($_POST['inorder'] == 'birthdesc'){
            $ordertext .= ' ORDER BY birthday ASC ';
        }elseif($_POST['inorder'] == 'birthasc'){
            $ordertext .= ' ORDER BY birthday DESC ';
        }elseif($_POST['inorder'] == 'joindesc'){
            $ordertext .= ' ORDER BY joincompanyday DESC  ';
        }elseif($_POST['inorder'] == 'joinasc'){
            $ordertext .= ' ORDER BY joincompanyday ASC  ';
        }elseif($_POST['inorder'] == 'regidesc'){
            $ordertext .= ' ORDER BY created_at DESC ';
        }elseif($_POST['inorder'] == 'regiasc'){
            $ordertext .= ' ORDER BY created_at ASC ';
        }elseif($_POST['inorder'] == 'namedesc'){
            $ordertext .= ' ORDER BY name DESC  ';
        }elseif($_POST['inorder'] == 'nameasc'){
            $ordertext .= ' ORDER BY name ASC  ';
        }
        
        try{
            $query = 'SELECT * FROM staffname 
            WHERE '.$searchnameterms.$searchemployeeidterms.$birthterms.$jointerms.' AND staffname.del = false '.$ordertext;

            $searchresult = $database -> query($query);
            $searchquery = $query;
            
        }catch(Exception $e){
            /* echo "エラー発生:" . $e->getMessage().'<br>';
            echo "検索できませんでした"; */
        }
    }
    $tabletext= '';
    if(isset($_POST['delete']) or isset($_POST['search'])){
        $tabletext.= <<<EDO
            <table class = 'table1'>
                <tr>
                    <th>名前</th>
                    <th>社員番号</th>
                    <th>生年月日</th>
                    <th>入社日</th>
                    <th>削除</th>
                    <th>詳細と変更</th>
                </tr>
            EDO;
                
        $searchquery = formquery($searchquery);
        while($row = mysqli_fetch_assoc($searchresult)){
            $setname = htmlentities($row['name']);
            $setemployeeid = htmlentities($row['employeeid']);
            $setbirthday = htmlentities($row['birthday']);
            $setjoincompanyday = htmlentities($row['joincompanyday']);
            $tabletext .= <<<EDO
                <tr>
                    <td>{$setname}</td><td>{$setemployeeid}</td><td>{$setbirthday}</td><td>{$setjoincompanyday}</td>
                    <td>
                        <form action = 'searchoutsoucer.php' method=post>
                            <button type = 'button'class = 'commonbutton'name='del'value='削除' onClick = 'deleteform({$row['id']},"{$setname}")' id = '{$row['id']}'>
                                <img src="../img/deleteicon.png" alt=""/>削除
                            </button>
                            <input type='hidden' name=  'staffid' value =  '{$row['id']}'>
                            <input type='hidden' name = 'delete' value = '削除'>
                            <input id = 'inputsearchquery' type='hidden' name=  'searchquery' value =  '{$searchquery}'>
                        </form>
                    </td>
                    <td><form action = 'detailoutsoucer.php' method=post><input class='commonbutton' type = 'submit' name='detail' value='詳細と変更'>
                    <input type='hidden' name=  'staffid' value =  '{$row['id']}'>
                    </form></td>
                </tr>
            EDO;
        }
        $tabletext.='</table>';
    }

    //ラジオボタンの生成
    $settextorder = '';
    if(!isset($_POST['inorder'])){
        $_POST['inorder'] = 'birthdesc';
    }

    $settextorder .= <<<EDO
        <input type = 'radio' name = 'inorder' value = 'birthdesc' 
        EDO;
    if($_POST['inorder'] == 'birthdesc'){
        $settextorder .= ' checked ';
    }
    $settextorder .= <<<EDO
        >年齢降順  <input type = 'radio' name = 'inorder' value = 'birthasc'
        EDO;

    if($_POST['inorder'] == 'birthasc'){
        $settextorder .= ' checked ';
    }
    $settextorder .= <<<EDO
        >年齢昇順<input type = 'radio' name = 'inorder' value = 'joindesc'
        EDO;
    if($_POST['inorder'] == 'joindesc'){
        $settextorder .= ' checked ';
    }
    $settextorder .= <<<EDO
        >入社日降順  <input type = 'radio' name = 'inorder' value = 'joinasc'
        EDO;
    
    if($_POST['inorder'] == 'joinasc'){
        $settextorder .= ' checked ';
    }
    $settextorder .= <<<EDO
        >入社日昇順 <br><input type = 'radio' name = 'inorder' value = 'regidesc'
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
        >登録投稿順<input type = 'radio' name = 'inorder' value = 'namedesc'
        EDO;

    if($_POST['inorder'] == 'namedesc'){
        $settextorder .= ' checked ';
    }
    $settextorder .= <<<EDO
        >名前降順  <input type = 'radio' name = 'inorder' value = 'nameasc'
        EDO;

    if($_POST['inorder'] == 'nameasc'){
        $settextorder .= ' checked ';
    }
    $settextorder .= <<<EDO
        >名前昇順
        EDO;



    require_once('html/searchoutsoucerview.php');
?>

            
          
    
    
