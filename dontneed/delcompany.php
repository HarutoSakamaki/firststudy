<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="company.css">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>削除した登録会社の検索</title>
</head>
<?php
  include("header.php");
?>
  <br><br><br><br><br>
<h2>削除した登録会社の検索</h2>
<?php
    require_once 'link.php';
    $database = database('staff');

    if(isset($_POST['restcompany'])){
            $company = $_POST['company'];
        try{
            $query = 'UPDATE company SET del = false WHERE company.company = \''.$company.'\'';
            $database -> query($query);
            echo "復元しました";
        }catch(Exception $e){
            echo "エラー発生:" . $e->getMessage().'<br>';
            echo "復元できませんでした";
        }
    }
?>
<body>
    
    <br>
    <div class = 'box'>
    <form action = 'delcompany.php' method = post class = 'formsize'>
        <p>一つ以上入力してください</p>
        <p>会社名(一部でも):<input type = 'text' name = 'searchcompany' value = ""></p>
        <p>従業員数:<input type = 'text' name = 'minemployees' value = "0">~<input type = 'text' name = 'maxemployees' value = ""></p>
        
        <?php
            datein("設立日","",['search,restcompany']);
        ?> 
        <button type = 'submit' class = 'btn' value='検索する' name = 'search'>検索</button>
        </form>
    </div>
        <?php
            if(isset($_POST['restcompany'])){
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
                    $employeesterms = ' AND numberofemployees BETWEEN '.$minestablish.' and '.$maxemployees;
                }
                $establishterms = ' AND establishdate BETWEEN DATE(\''.$minestablish.'\') and DATE(\''.$maxestablish.'\') ';
                
                
                try{
                    $query = 'SELECT * FROM company WHERE '.$companyterms.$employeesterms.$establishterms.' AND del = true ORDER BY numberofemployees DESC';
                    $searchquery = $query;
                    $searchresult = $database -> query($query);
                    
                }catch(Exception $e){
                    echo "エラー発生:" . $e->getMessage().'<br>';
                    echo "検索できませんでした";
                }
            }
            if(isset($_POST['search']) or isset($_POST['restcompany'])){
            
            
        ?>
                
    </form>
    </div>
    <table border="1">
                    <tr>
                    <th>会社名</th>
                    <th>従業員数</th>
                    <th>設立日</th>
                    <th>復元</th>
                    </tr>
                    <?php

                    
                    
                    $searchquery = formquery($searchquery);
                    while($row = mysqli_fetch_assoc($searchresult)){
                        echo '<tr>';
                        echo '<td>'.$row['company'].'</td><td>'.$row['numberofemployees'].'</td><td>'.$row['establishdate'].'</td>';
                        echo '<td><form action = \'delcompany.php\' method=post><input type = \'submit\'name=\'restcompany\'value=\'復元\'>
                            <input type=\'hidden\' name=  \'company\' value =  \''.$row['company'].'\'>
                            <input type=\'hidden\' name=  \'searchquery\' value =  \''.$searchquery.'\'>
                            </form></td>';
                        echo '</tr>';
                    }
                }
                    
                    ?>
    </table>
    <br><br><br>
</body>
<body>
<p class = 'floatclear'></p>

<br>


<br>





</body>


</html>