
    <?php
        require_once '../link.php';
        $database = database('staff');

        if(isset($_POST['detail'])){
            $id = $_POST['id'];
            echo $id;
            try{
                $query = "SELECT * FROM company WHERE del = false AND id = ".$id;
                $result = $database -> query($query);
                $row = mysqli_fetch_assoc($result);
                echo '詳細を取得しました';
            }catch(Exception $e){
                echo "エラー発生:" . $e->getMessage().'<br>';
                echo "  詳細を取得できませんでした。";
            }
        }
        $establishdatearray = explode('-', $row['establishdate']);
        $eastablishyear = $establishdatearray[0];
        $establishmonth = $establishdatearray[1];
        $establishday = $establishdatearray[2];
        

        $businessdetailsarray = json_decode($row['businessdetails'],true);
        

        $businessdetailtext = '';
        $count = 0;
        while(isset($businessdetailsarray[$count])){
            $businessdetailtext.=$count.'.'.$businessdetailsarray[$count].'<br>';
            $count++;
        }

        require_once('html/detailcompanyview.php');

    ?>