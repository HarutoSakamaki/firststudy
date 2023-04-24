
<?php
    require_once '../link.php';
    $database = database('staff');
    
    if(isset($_POST['delete'])){
        $id = $_POST['id'];
        try{
            $query = "UPDATE staffname SET del = true where id = '{$id}'";
            $database -> query($query);
            
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
        $minbirth = $_POST['birthminyear'].'-'.$_POST['birthminmonth'].'-'.$_POST['birthminday'];
        $maxbirth = $_POST['birthmaxyear'].'-'.$_POST['birthmaxmonth'].'-'.$_POST['birthmaxday'];
        $minjoin = $_POST['joinminyear'].'-'.$_POST['joinminmonth'].'-'.$_POST['joinminday'];
        $maxjoin = $_POST['joinmaxyear'].'-'.$_POST['joinmaxmonth'].'-'.$_POST['joinmaxday'];
        $searchcompany = $_POST['company'];
        if($searchname != ""){
            $searchnameterms = ' name LIKE \'%'.$searchname.'%\' ';
        }else{
            $searchnameterms = ' name LIKE \'%\' ';
        }
        
        $birthterms = ' AND birthday BETWEEN DATE(\''.$minbirth.'\') and DATE(\''.$maxbirth.'\') ';
        $jointerms = ' AND joincompanyday BETWEEN DATE(\''.$minjoin.'\') and DATE(\''.$maxjoin.'\') ';
        
        if($searchcompany != ""){
            $companyterms = ' AND company LIKE \'%'.$searchcompany.'%\' ';
        }else{
            /* $companyterms = ' AND company LIKE \'%\' '; */
            $companyterms = '';
        }
        
        try{
            $query = 'SELECT staffname.company AS companyid , company.company ,staffname.id , name, birthday, joincompanyday 
            FROM staffname INNER JOIN company ON staffname.company = company.id 
            WHERE '.$searchnameterms.$birthterms.$jointerms.$companyterms.' AND staffname.del = false ORDER BY ID ASC';
            $searchresult = $database -> query($query);
            $searchquery = $query;
            echo $query;
        }catch(Exception $e){
            echo "エラー発生:" . $e->getMessage().'<br>';
            echo "検索できませんでした";
        }
    }
    require_once('html/searchoutsoucerview.php');
?>

            
          
    
    
