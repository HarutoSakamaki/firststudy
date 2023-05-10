
<script src = '../js/jquery-3.6.4.min.js'></script>
<link rel="stylesheet" href="../css/validationEngine.jquery.css">
<script src="../js/jquery.validationEngine.js"></script>
<script src="../js/jquery.validationEngine-ja.js" charset="UTF-8"></script>

<?php
    require_once '../link.php';
    $database = database('staff');
    session_start();
    

    $postflag = false;
    if(isset($_POST['search'])){
        $postflag = true;
    }   

    if(isset($_POST['search'])){

        $company = $_POST['searchcompany'];
        $minemployees = $_POST['minemployees'];
        $maxemployees = $_POST['maxemployees'];

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
        try{
            $query = 'SELECT * FROM company WHERE '.$companyterms.$employeesterms.' AND del = false ORDER BY numberofemployees DESC';
            /* echo $query; */
            $searchresult = $database -> query($query);
            $searchquery = $query;
            
        }catch(Exception $e){
            echo "エラー発生:" . $e->getMessage().'<br>';
            echo "検索できませんでした";
        }
    }

    /* if(isset($_POST['companyid'])){

        $settextcompany = $_POST['searchcompany'];
        $settextcompanyid = $_POST['companyid'];
    }else{
        $settextcompany = '';
        $settextcompanyid = '';
    } */

    if(isset($_POST['selectcompany'])){

        echo <<<EOM
            <script>
                window.opener.setcompany('{$_POST['searchcompany']}','{$_POST['companyid']}');
                window.close();

            </script>
        EOM;
    }
    


    $tabletext ='';
        if (isset($_POST['search'])){
        $tabletext.=<<<EDO
            <table border="1">
            <tr>
            <th>会社名</th>
            <th>従業員数</th>
            <th>選択</th>
            </tr>
        EDO;
        $searchquery = formquery($searchquery);
        while($row = mysqli_fetch_assoc($searchresult)){
            $setcompany = htmlentities($row['company']);
            $setnumberofemployees = htmlentities($row['numberofemployees']);
            $tabletext.=<<<EDO
                <tr>
                <td>{$setcompany}</td><td>{$setnumberofemployees}</td>
                <td><form action = 'selectcompany.php' method=post><input type = 'submit'name='selectcompany'value='選択' >
                <input type= 'hidden' name=  'companyid' value =  '{$row['id']}'>
                <input type= 'hidden' name=  'searchcompany' value =  '{$setcompany}'>
                <input id = 'inputsearchquery' type='hidden' name=  'searchquery' value =  '{$searchquery}'>
                </form></td>
                </tr>
            EDO;
        }
        $tabletext.='</table>';
    }


    require_once('html/selectcompanyview.php');
?>