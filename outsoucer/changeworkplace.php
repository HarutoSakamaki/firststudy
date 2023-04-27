
<?php
    require_once '../link.php';
    session_start();
    $database = database('staff');

    if(isset($_POST['staffid'])){
        $staffid = $_POST['staffid'];
        $_SESSION['staffid'] = $staffid;
    }else{
        $staffid = $_SESSION['staffid'];
    }

    if(isset($_POST['decideenddate'])){
        try{
            $update_at = date("Y-m-d H:i:s");
            $query = <<<EDO
                UPDATE staffhistory SET 
                enddate = '{$_POST['enddate']}' , update_at = '{$update_at}' 
                WHERE id = {$_POST['historyid']};
            EDO;
            $result = $database -> query($query);
            echo $query;
            echo '終了日時を設定できました';
        }catch(Exception $e){
            echo "エラー発生:" . $e->getMessage().'<br>';
            echo "  外勤先を取得できませんでした";
        } 
    }




    try{
        $query = 'SELECT  staffhistory.id as id, company.company as company, staffhistory.startdate as startdate, staffhistory.enddate as enddate,
        staffname.name as name 
        FROM staffhistory LEFT JOIN company ON staffhistory.companyid = company.id LEFT JOIN staffname ON staffname.id = staffhistory.staffid 
        WHERE staffhistory.staffid = '.$staffid.' AND staffhistory.del = 0 
        ORDER BY startdate DESC';
        /* echo $query.'</br>'; */
        $result = $database -> query($query);
        /* $row = mysqli_fetch_assoc($result); */
        echo '外勤先を取得しました';
    }catch(Exception $e){
        echo "エラー発生:" . $e->getMessage().'<br>';
        echo "  外勤先を取得できませんでした";
    } 
    $settext = array();
    $nowsettext = array();
    $nowsettextflag = false;
    $settextflag = false;
    while($row = mysqli_fetch_assoc($result)){

        if($row['enddate'] == ''){
            $nowsettext = ['company'=>$row['company'],'startdate'=>$row['startdate'],'enddate'=>$row['enddate'],'id'=>$row['id']];
            $nowsettextflag =true;
        }else{
            $settext[] = ['company'=>$row['company'],'startdate'=>$row['startdate'],'enddate'=>$row['enddate'],'id'=>$row['id']];
            $settextflag = true;
        }
        /* $settextcompany = $row['company'];
        $settextstartdate = $row['startdate'];
        $settextenddate = $row['enddate']; */
    }

    if($nowsettextflag==false){
        $nowcompanytext =  <<<EOD
        現在所属している会社はありません。
        <button id = 'subwindowbutton' onClick = 'disp("../subwindow/selectcompany.php")' value = '外勤先の選択'>外勤先の選択</button>
        EOD;
    }else{
        $nowcompanytext = <<<EOD
        <div>
            現在の勤め先<br>
            <table border = '1'>
                <tr>
                    <td>会社名</td>
                    <td>{$nowsettext['company']}</td>
                </tr>
                <tr>
                    <td>仕事開始日</td>
                    <td>{$nowsettext['startdate']}</td>
                </tr>
                <tr>
                    <td>仕事終了日</td>
                    <td>
                        <form action = 'changeworkplace.php' method = 'post'>
                            <input name = 'enddate' type="date" id = 'inputenddate'>
                            <input type = 'submit' id = 'enddatebutton' name = 'decideenddate' disabled>
                            <input type ='hidden' name = 'historyid' value = '{$nowsettext['id']}'>
                            <input type = 'hidden' name = 'staffid' value = '{$staffid}'>
                        </form>
                    </td>
                </tr>
            </table>
        </div>
        EOD;
    }
    $historycompanytext='';
    if($settextflag  == true){
        foreach($settext as $settext){
            $historycompanytext = $historycompanytext.<<<EOD
            <div>
                <table border = '1'>
                    <tr>
                        <td>会社名</td>
                        <td>{$settext['company']}</td>
                    </tr>
                    <tr>
                        <td>仕事開始日</td>
                        <td>{$settext['startdate']}</td>
                    </tr>
                    <tr>
                        <td>仕事終了日</td>
                        <td>{$settext['enddate']}</td>
                    </tr>
                </table>
            </div>
            EOD;
        }
    }else{

    }







    
    
    require_once('html/changeworkplaceview.php');
?>

            
          
    
    
