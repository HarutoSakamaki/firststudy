
<?php
        require_once '../link.php';
        $database = database('staff');

        if(isset($_POST['detail'])){
            $id = $_POST['id'];
            try{
                $query = "SELECT * FROM staffname WHERE del = false AND id = ".$id;
                $result = $database -> query($query);
                $row = mysqli_fetch_assoc($result);
                echo '詳細を取得しました';
            }catch(Exception $e){
                echo "エラー発生:" . $e->getMessage().'<br>';
                echo "  詳細を取得できませんでした。";
            }
        }
        $birtharray = explode('-', $row['birthday']);
        $birthyear = $birtharray[0];
        $birthmonth = $birtharray[1];
        $birthday = $birtharray[2];
        $joinarray = explode('-', $row['joincompanyday']);
        $joinyear = $joinarray[0];
        $joinmonth = $joinarray[1];
        $joinday = $joinarray[2];
        $licensearray = json_decode($row['license'],true);
        $workhistoryarray = json_decode($row['workhistory'],true);

        require_once('html/detailoutsoucerview.php');
?>