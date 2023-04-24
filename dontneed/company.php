<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="company.css">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>登録会社の一覧</title>
</head>
<?php
  include("header.php");
?>
  <br><br><br><br><br>
<h2>登録会社一覧</h2>
<?php

    require_once 'link.php';
    $database = database('staff');

    if(isset($_POST['restdata'])){
        try{
            $query = 'UPDATE company SET del = false';
            $database -> query($query);
            echo "復元しました";
        }catch(Exception $e){
            echo "エラー発生:" . $e->getMessage().'<br>';
            echo "削除できませんでした";
        }
    }
    
    if(isset($_POST['delete'])){
        $company = $_POST['company'];
        echo $company;
        try{
            $query = 'UPDATE company SET del = true WHERE company.company = \''.$company.'\'';
            $database -> query($query);
            echo "削除しました";
        }catch(Exception $e){
            echo "エラー発生:" . $e->getMessage().'<br>';
            echo "削除できませんでした";
        }
    }

    try{
        $query = "SELECT * FROM company WHERE del = false ORDER BY numberofemployees DESC";
        $result = $database -> query($query);
    }catch (Exception $e){
        echo "エラー発生:" . $e->getMessage().'<br>';
        echo "取得できませんでした";
    }
?>
<body>
    <table border="1">
        <tr>
        <th>会社名</th>
        <th>従業員数</th>
        <th>設立日</th>
        <th id = "deltd">削除</th>
        <th id = "deltd">詳細と変更</th>
        </tr>
        <?php
            while($row = mysqli_fetch_assoc($result)){
                echo '<tr>';
                echo '<td>'.$row['company'].'</td><td>'.$row['numberofemployees'].'</td><td>'.$row['establishdate'].'</td>';
                echo '<td id = "deltd"><form action = \'company.php\' method=post><input type = \'submit\'name=\'delete\'value=\'削除\'>
                    <input type=\'hidden\' name=  \'company\' value =  \''.$row['company'].'\'>
                    </form></td>';
                echo '<td id = "deltd"><form action = \'detailcompany.php\' method=post><input type = \'submit\'name=\'detail\'value=\'詳細と変更\'>
                    <input type=\'hidden\' name=  \'company\' value =  \''.$row['company'].'\'>
                    </form></td>';
                echo '</tr>';
            }
        ?>
    </table>
    <br>
    
    <br><br><br>
</body>



</html>