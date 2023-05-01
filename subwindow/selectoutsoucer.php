

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
        $searchname = $_POST['searchname'];
        $minbirth = $_POST['birthminyear'].'-'.$_POST['birthminmonth'].'-'.$_POST['birthminday'];
        $maxbirth = $_POST['birthmaxyear'].'-'.$_POST['birthmaxmonth'].'-'.$_POST['birthmaxday'];
        $minjoin = $_POST['joinminyear'].'-'.$_POST['joinminmonth'].'-'.$_POST['joinminday'];
        $maxjoin = $_POST['joinmaxyear'].'-'.$_POST['joinmaxmonth'].'-'.$_POST['joinmaxday'];
    /*  $searchcompany = $_POST['company']; */
        if($searchname != ""){
            $searchnameterms = ' name LIKE \'%'.$searchname.'%\' ';
        }else{
            $searchnameterms = ' name LIKE \'%\' ';
        }
        
        $birthterms = ' AND birthday BETWEEN DATE(\''.$minbirth.'\') and DATE(\''.$maxbirth.'\') ';
        $jointerms = ' AND joincompanyday BETWEEN DATE(\''.$minjoin.'\') and DATE(\''.$maxjoin.'\') ';
        
        /* if($searchcompany != ""){
            $companyterms = ' AND company LIKE \'%'.$searchcompany.'%\' ';
        }else{
            $companyterms = ' AND company LIKE \'%\' ';
            $companyterms = '';
        }  */
        
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
    $tabletext ='';
    if (isset($_POST['search'])){
        $tabletext.=<<<EDO
            <table border="1">
            <tr>
            <th>名前</th>
            <th>生年月日</th>
            <th>入社日</th>
            <th>選択</th>
            </tr>
        EDO;
        $searchquery = formquery($searchquery);
        while($row = mysqli_fetch_assoc($searchresult)){
            $tabletext.=<<<EDO
                <tr>
                <td>{$row['name']}</td><td>{$row['birthday']}</td><td>{$row['joincompanyday']}</td>
                <td><form action = 'selectoutsoucer.php' method=post><input type = 'submit'name='selectcompany'value='選択' >
                <input type= 'hidden' name=  'staffid' value =  '{$row['id']}'>
                <input type= 'hidden' name=  'searchoutsoucer' value =  '{$row['name']}'>
                <input id = 'inputsearchquery' type='hidden' name=  'searchquery' value =  '{$searchquery}'>
                </form></td>
                </tr>
            EDO;
        }
        $tabletext.='</table>';
    }
?>
<?php
    if(isset($_POST['staffid'])){

        /* echo 'なぜできない'; */
        $settextstaff = $_POST['searchoutsoucer'];
        $settextstaffid = $_POST['staffid'];
    }else{
        $settextstaff = '';
        $settextstaffid = '';
    }


    if(isset($_POST['register'])){
        if($_POST['startdate']!=''or $settextstaffid != ''){
            try{
                $numberringquery = "UPDATE numbering SET numbering = LAST_INSERT_ID(numbering + 1) WHERE tablename = 'staffhistory'";
                $database -> query($numberringquery);
                $numberringquery = 'SELECT numbering FROM numbering where tablename = \'staffhistory\' ';
                $numberring = mysqli_fetch_assoc($database -> query($numberringquery));
                $numberringid = $numberring['numbering'];
                $query = 'INSERT staffhistory (id , staffid , companyid, startdate, enddate) VALUES('.$numberringid.','.$settextstaffid.','.$_SESSION['companyid'].',\''.$_POST['startdate'].'\',\''.$_POST['enddate'].'\')';
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
        /* echo 'ここが問題か？'; */
    }
    function destruction(){
        echo <<<EDO
            <script>
                window.opener.location.reload();
                open('about:blank', '_self').close();
            </script>
        EDO;
    }


    require_once('html/selectoutsoucerview.php');
?>


<!-- <script>
    document.write('{$_POST['searchcompany']}');
    window.opener.choicecompany({$_POST['id']},'{$_POST['searchcompany']}');
    open('about:blank', '_self').close();
</script> -->