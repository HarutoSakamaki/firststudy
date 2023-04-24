<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="outsoucer.css">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>アウトソーサーの一覧</title>
</head>

  <br><br><br><br><br>
<body>

<?php
  include("header.php");
?>
<h2>アウトソーサー一覧</h2>

<?php
    require_once 'link.php';
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
    if(isset($_POST['restdata'])){
        try{
            $query = "UPDATE staffname SET del = false";
            $database -> query($query);
            $query2 = "SET @i := 0";
            $database -> query($query2);
            $query3 = 'UPDATE staffname SET id = (@i := @i +1) WHERE del = true';
            $database -> query($query3);
            echo '削除しました';
        }catch(Exception $e){
            echo "エラー発生:" . $e->getMessage().'<br>';
            echo '復元しました';
        }
    }

    try{
        $query = "SELECT * FROM staffname WHERE del = false";
        $result = $database -> query($query);
    }catch (Exception $e){
        echo "エラー発生:" . $e->getMessage().'<br>';
        echo "取得できませんでした";
    }
?>
<body>
    <table border="1">
        <tr>
        <th>名前</th>
        <th>生年月日</th>
        <th>入社日</th>
        <th>外勤先</th>
        <th id = "deltd">削除</th>
        <th id = "deltd">詳細と変更</th>
        </tr>
        <?php
            while($row = mysqli_fetch_assoc($result)){
                echo '<tr>';
                echo '<td>'.$row['name'].'</td><td>'.$row['birthday'].'</td><td>'.$row['joincompanyday'].'</td><td>'.$row['company'].'</td>';
                echo '<td id = "deltd"><form action = \'outsoucer.php\' method=post><input type = \'submit\'name=\'delete\'value=\'削除\'>
                    <input type=\'hidden\' name=  \'id\' value =  \''.$row['id'].'\'>
                    </form></td>';
                    echo '<td id = "deltd"><form action = \'detailoutsoucer.php\' method=post><input type = \'submit\'name=\'detail\'value=\'詳細と変更\'>
                    <input type=\'hidden\' name=  \'id\' value =  \''.$row['id'].'\'>
                    </form></td>';
                echo '</tr>';
            }
        ?>
    </table>
    <br>
    
    
</body>

<body>
<br>
<p class = 'floatclear'></p>

<br><br>


</body>


</html>