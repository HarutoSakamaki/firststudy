

<?php
    require_once '../link.php';
    $database = database('staff');
    session_start();
    
    $postflag = false;
    if(isset($_POST['search'])){
        $postflag = true;
    }
    
        
?>
<?php

    if(isset($_POST['search'])){
        $company = $_POST['searchcompany'];
        $minemployees = $_POST['minemployees'];
        $maxemployees = $_POST['maxemployees'];
        /* $minestablish = $_POST['minyear'].'-'.$_POST['minmonth'].'-'.$_POST['minday'];
        $maxestablish = $_POST['maxyear'].'-'.$_POST['maxmonth'].'-'.$_POST['maxday']; */
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
        /* $establishterms = ' AND establishdate BETWEEN DATE(\''.$minestablish.'\') and DATE(\''.$maxestablish.'\') '; */
        
        
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
?>
<?php
    if(isset($_POST['companyid'])){

        echo 'なぜできない';
        $settextcompany = $_POST['searchcompany'];
        $settextcompanyid = $_POST['companyid'];
    }else{
        $settextcompany = '';
        $settextcompanyid = '';
    }


    if(isset($_POST['register'])){
        if($_POST['startdate']!=''or $settextcompanyid != ''){
            try{
                $numberringquery = "UPDATE numbering SET numbering = LAST_INSERT_ID(numbering + 1) WHERE tablename = 'staffhistory'";
                $database -> query($numberringquery);
                $numberringquery = 'SELECT numbering FROM numbering where tablename = \'staffhistory\' ';
                $numberring = mysqli_fetch_assoc($database -> query($numberringquery));
                $numberringid = $numberring['numbering'];
                $query = 'INSERT staffhistory (id , staffid , companyid, startdate) VALUES('.$numberringid.','.$_SESSION['staffid'].','.$settextcompanyid.',\''.$_POST['startdate'].'\')';
                echo $query;
                $result = $database -> query($query);
                echo '成功';
                destruction();
            }catch(Exception $e){
                echo "エラー発生:" . $e->getMessage().'<br>';
                echo "登録できませんでした";
            }
        }else{
            echo 'すべて入力してください';
        }
    }else{
        echo 'ここが問題か？';
    }
    function destruction(){
        echo <<<EDO
            <script>
                window.opener.location.reload();
                open('about:blank', '_self').close();
            </script>
        EDO;
    }
    require_once('html/selectcompanyview.php');
?>


<!-- <script>
    document.write('{$_POST['searchcompany']}');
    window.opener.choicecompany({$_POST['id']},'{$_POST['searchcompany']}');
    open('about:blank', '_self').close();
</script> -->