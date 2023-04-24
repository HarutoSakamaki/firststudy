<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="outsoucer.css">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>削除したアウトソーサーの検索</title>
</head>
<body>

<?php
  include("header.php");
?>


  <br><br><br><br><br>

<h2>削除したアウトソーサーの検索</h2>

<?php
    require_once 'link.php';
    $database = database('staff');
    
    if(isset($_POST['restore'])){
        $id = $_POST['id'];
        try{
            $query = "UPDATE staffname SET del = false where id = '{$id}'";
            $database -> query($query);
            
            echo '復元しました';
        }catch(Exception $e){
            echo "エラー発生:" . $e->getMessage().'<br>';
            echo "復元できませんでした";
        }
    }
?>
<body>
   
    <br>
    <div class = 'box'>
        <form action = 'deloutsoucer.php' method = post class = 'formsize'>
            <p>一つ以上入力してください</p>
            名前(一部でも):<input type = 'text' name = 'searchname' value = ""><br>
            
            <?php
                datein('生年月日','birth',['restore','search']);
                datein('入社日','join',['restore','search']);
            ?>
            外勤先企業(一部でも):<input type = 'text' name = 'company' value = ''><br><br>
            <button type = 'submit' class = 'btn' name = 'search' value='検索'>検索</button>
        
        </form>
    </div >
        <?php
            if(isset($_POST['restore'])){
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
                    $companyterms = ' AND company LIKE \'%'.$searchmonpany.'%\' ';
                }else{
                    $companyterms = ' AND company LIKE \'%\' ';
                }
                
                try{
                    $query = 'SELECT * FROM staffname WHERE '.$searchnameterms.$birthterms.$jointerms.$companyterms.' AND del = true ORDER BY ID ASC';
                    $searchresult = $database -> query($query);
                    $searchquery = $query;
                    
                }catch(Exception $e){
                    echo "エラー発生:" . $e->getMessage().'<br>';
                    echo "検索できませんでした";
                }
            }
            if(isset($_POST['search']) or isset($_POST['restore'])){

        ?>
         <table border="1">
                <tr>
                <th>名前</th>
                <th>生年月日</th>
                <th>入社日</th>
                <th>外勤先</th>
                <th id = "deltd">復元</th>
                </tr>
                <?php

                $searchquery = formquery($searchquery);
                while($row = mysqli_fetch_assoc($searchresult)){
                    echo '<tr>';
                    echo '<td>'.$row['name'].'</td><td>'.$row['birthday'].'</td><td>'.$row['joincompanyday'].'</td><td>'.$row['company'].'</td>';
                    echo '<td id = "deltd"><form action = \'deloutsoucer.php\' method=post><input type = \'submit\'name=\'restore\'value=\'復元\'>
                        <input type=\'hidden\' name=  \'id\' value =  \''.$row['id'].'\'>
                        <input type=\'hidden\' name=  \'searchquery\' value =  \''.$searchquery.'\'>
                        </form></td>';
                    echo '</tr>';
                }
            }
                
                ?>
            </table>
    
    
</body>




</html>