
<script src = '../js/jquery-3.6.4.min.js'></script>
<link rel="stylesheet" href="../css/validationEngine.jquery.css">
<script src="../js/jquery.validationEngine.js"></script>
<script src="../js/jquery.validationEngine-ja.js" charset="UTF-8"></script>


<?php
    require_once '../link.php';
    $database = database('staff');
    if(isset($_POST['delete'])){
        $companyid = $_POST['id'];
        echo $companyid;
        try{
            $query = 'UPDATE company SET del = true WHERE company.id = \''.$companyid.'\'';
            $database -> query($query);
            echo "削除しました";
        }catch(Exception $e){
            echo "エラー発生:" . $e->getMessage().'<br>';
            echo "削除できませんでした";
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
        $minestablish = $_POST['minyear'].'-'.$_POST['minmonth'].'-'.$_POST['minday'];
        $maxestablish = $_POST['maxyear'].'-'.$_POST['maxmonth'].'-'.$_POST['maxday'];
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
        
        
        try{
            
            $query = 'SELECT * FROM company WHERE '.$companyterms.$employeesterms.$establishterms.' AND del = false AND id != 1 ORDER BY numberofemployees DESC';
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
            
            <table border="1">
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
                        <input type = 'button' class='commonbutton' name='delete'value='削除' onClick = 'deleteform({$row['id']},"{$companytable}")' id = '{$row['id']}' >
                        <input type= 'hidden' name=  'id' value =  '{$row['id']}'>
                        <input id = 'inputsearchquery' type='hidden' name=  'searchquery' value =  '{$searchquery}'>
                    </form></td>
                    <td><form action = 'detailcompany.php' method=post><input type = 'submit'class = 'commonbutton' name='detail'value='詳細と変更'>
                        <input type='hidden' name=  'companyid' value =  '{$row['id']}'>
                        <input type='hidden' name=  'searchquery' value =  '{$searchquery}'>
                    </form></td>
                </tr>
                EDO;
        }
        $tabletext.='</table>';

    }
    require_once('html/searchcompanyview.php');
?>

