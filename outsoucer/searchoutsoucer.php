
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
        
        
        
        try{
            $query = 'SELECT * FROM staffname 
            WHERE '.$searchnameterms.$searchemployeeidterms.$birthterms.$jointerms.' AND staffname.del = false ORDER BY ID ASC';
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



    require_once('html/searchoutsoucerview.php');
?>

            
          
    
    
