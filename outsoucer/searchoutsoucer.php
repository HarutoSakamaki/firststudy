
<?php
    require_once '../link.php';
    $database = database('staff');
    
    if(isset($_POST['delete'])){
        $id = $_POST['staffid'];
        $changeup_date = ' update_at = \''.date("Y-m-d H:i:s").'\' ';
        try{
            $query = "UPDATE staffname SET del = true".$changeup_date." where id = '{$id}'";
            $database -> query($query);
            echo $query;
            echo '削除しました';
        }catch(Exception $e){
            echo "エラー発生:" . $e->getMessage().'<br>';
            echo "削除できませんでした";
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
            echo "エラー発生:" . $e->getMessage().'<br>';
            echo "検索できませんでした";
        }

    }

    if(isset($_POST['search'])){
        $searchname = $_POST['searchname'];
        $minbirth = $_POST['birthminyear'].'-'.$_POST['birthminmonth'].'-0 ';
        $maxbirth = $_POST['birthmaxyear'].'-'.$_POST['birthmaxmonth'].'-31 ';
        $minjoin = $_POST['joinminyear'].'-'.$_POST['joinminmonth'].'-0 ';
        $maxjoin = $_POST['joinmaxyear'].'-'.$_POST['joinmaxmonth'].'-31 ';
       /*  $searchcompany = $_POST['company']; */
        if($searchname != ""){
            $searchnameterms = ' name LIKE \'%'.$searchname.'%\' ';
        }else{
            $searchnameterms = ' name LIKE \'%\' ';
        }
        
        $birthterms = ' AND birthday BETWEEN DATE(\''.$minbirth.'\') and DATE(\''.$maxbirth.'\') ';
        $jointerms = ' AND joincompanyday BETWEEN DATE(\''.$minjoin.'\') and DATE(\''.$maxjoin.'\') ';
        
        
        
        try{
            $query = 'SELECT * FROM staffname 
            WHERE '.$searchnameterms.$birthterms.$jointerms.' AND staffname.del = false ORDER BY ID ASC';
            $searchresult = $database -> query($query);
            $searchquery = $query;
            /* echo $query; */
        }catch(Exception $e){
            echo "エラー発生:" . $e->getMessage().'<br>';
            echo "検索できませんでした";
        }
    }
    $tabletext= '';
    if(isset($_POST['delete']) or isset($_POST['search'])){
        $tabletext.= <<<EDO
            <table border="1" class = 'table1'>
                <tr>
                    <th>名前</th>
                    <th>生年月日</th>
                    <th>入社日</th>
                    <th id = "deltd">削除</th>
                    <th id = "deltd">詳細と変更</th>
                </tr>
            EDO;
                
        $searchquery = formquery($searchquery);
        while($row = mysqli_fetch_assoc($searchresult)){
            $setname = htmlentities($row['name']);
            $setbirthday = htmlentities($row['birthday']);
            $setjoincompanyday = htmlentities($row['joincompanyday']);
            $tabletext .= <<<EDO
                <tr>
                    <td>{$setname}</td><td>{$setbirthday}</td><td>{$setjoincompanyday}</td>
                    <td id = "deltd"><form action = 'searchoutsoucer.php' method=post><input type = 'button'class = 'commonbutton'name='delete'value='削除' onClick = 'deleteform({$row['id']},"{$setname}")' id = '{$row['id']}'>
                    <input type='hidden' name=  'staffid' value =  '{$row['id']}'>
                    <input id = 'inputsearchquery' type='hidden' name=  'searchquery' value =  '{$searchquery}'>
                    </form></td>
                    <td id = "deltd"><form action = 'detailoutsoucer.php' method=post><input class='commonbutton' type = 'submit' name='detail' value='詳細と変更'>
                    <input type='hidden' name=  'staffid' value =  '{$row['id']}'>
                    </form></td>
                </tr>
            EDO;
        }
        $tabletext.='</table>';
    }



    require_once('html/searchoutsoucerview.php');
?>

            
          
    
    
