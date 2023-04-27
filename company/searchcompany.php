
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
        $minemployees = $_POST['minemployees'];
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
            /* echo $query; */
            $searchresult = $database -> query($query);
            $searchquery = $query;
            
        }catch(Exception $e){
            echo "エラー発生:" . $e->getMessage().'<br>';
            echo "検索できませんでした";
        }
    }
    
    require_once('html/searchcompanyview.php');
?>

