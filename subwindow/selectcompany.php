<!DOCTYPE html>
<html lang="ja">
<!-- <link rel="stylesheet" href="company.css"> -->
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>外勤際の選択</title>
</head>

  
<h2>外勤先の選択</h2>
<?php
    require_once '../link.php';
    $database = database('staff');


    /* try{
        $query = "SELECT * FROM company WHERE del = false ORDER BY numberofemployees DESC";
        $result = $database -> query($query);
    }catch (Exception $e){
        echo "エラー発生:" . $e->getMessage().'<br>';
        echo "取得できませんでした";
    } */
    
    $postflag = false;
    if(isset($_POST['search'])){
        $postflag = true;
    }
    
        
?>
<body>
    
    
    <div class = 'box'>
    <form action = 'selectcompany.php' method = post class = 'formsize'>
        <p>一つ以上入力してください</p>
        <p>会社名(一部でも):<input type = 'text' id = 'inputsearchcompany' name = 'searchcompany' value = "<?php if($postflag){echo $_POST['searchcompany'];}?>"></p>
        <p>従業員数:<input type = 'text' id  = 'inputminemployees' name = 'minemployees' value = "<?php if($postflag){echo $_POST['minemployees'];}else{echo '0';}?>">~<input type = 'text' id = 'inputmaxemployees'name = 'maxemployees' value = "<?php if($postflag){echo $_POST['maxemployees'];}?>"></p>
        <!-- <?php
            datein("設立日","",['delete','search']);
        ?>  -->
        <button type = 'submit' class = 'btn' value='検索する' name = 'search'>検索</button>
        </form>
    </div>
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
            if (isset($_POST['search'])){
            
        ?>
                
    </form>
    </div>
    <table border="1">
                    <tr>
                    <th>会社名</th>
                    <th>従業員数</th>
                    <th>選択</th>
                    </tr>
                    <?php

                    
                    $searchquery = formquery($searchquery);

                    while($row = mysqli_fetch_assoc($searchresult)){
                        echo '<tr>';
                        echo '<td>'.$row['company'].'</td><td>'.$row['numberofemployees'].'</td>';
                        echo '<td><form action = \'selectcompany.php\' method=post><input type = \'submit\'name=\'selectcompany\'value=\'選択\' >
                            <input type= \'hidden\' name=  \'id\' value =  \''.$row['id'].'\'>
                            <input type= \'hidden\' name=  \'searchcompany\' value =  \''.$row['company'].'\'>
                            <input id = \'inputsearchquery\' type=\'hidden\' name=  \'searchquery\' value =  \''.$searchquery.'\'>
                            </form></td>';
                        echo '</tr>';
                    }
                }
                    
                    ?>
    </table>
    <br><br><br>
</body>


<?php
    if(isset($_POST['selectcompany'])){
        echo 'なぜできない';
        echo <<<EOM
                <script>
                    document.write('{$_POST['searchcompany']}');
                    window.opener.choicecompany({$_POST['id']},'{$_POST['searchcompany']}');
                    open('about:blank', '_self').close();
                </script>
            EOM;
    }
?>
</html>
