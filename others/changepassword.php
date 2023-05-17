
<script src = '../js/jquery-3.6.4.min.js'></script>
<link rel="stylesheet" href="../css/validationEngine.jquery.css">
<script src="../js/jquery.validationEngine.js"></script>
<script src="../js/jquery.validationEngine-ja.js" charset="UTF-8"></script>


<?php
    require_once '../link.php';
	$database = database('staff');
	
	session_start();
    if(isset($_SESSION['login'])){
        
    }else{
        $_SESSION['againlogin'] = true;
        header("Location: ../others/login.php");
        exit();
    }

    //login情報の獲得
    $settextusername = '';
    $loginid = $_SESSION['loginid'];
    try{
        $query = <<<EOD
            SELECT * FROM login WHERE id = {$loginid}
            EOD;
        $result = $database -> query($query);
        $row = mysqli_fetch_assoc($result);
        $settextusername = $row['username'];
    }catch(Exception $e){

        echo "エラー発生:" . $e->getMessage().'<br>';
        exit();
    }



    $changesuccesstext = '';
    $changesuccess = '';
    $settextoldpassword = '';
    $settextnewpassword = '';
    $failoldpasswordtext = '';
    $failnewpasswordtext = '';
    $failnewpasswordagaintext = '';
    if(isset($_POST['change'])){
        $inputrule = true;
        
        //入力規則の検証
        if($_POST['newpassword'] != $_POST['newpasswordagain']){
            $failnewpasswordagaintext .= 'passwordの値が異なっています<br>';
            $inputrule = false;
        }
        if(!preg_match('/^[a-zA-Z0-9_]{8,}$/',$_POST['newpassword'])){
            $failnewpasswordtext .= '半角英数字8文字以上にしてください<br>';
            $inputrule = false;
        }
        if(!preg_match('/[0-9]/',$_POST['newpassword'])){
            $failnewpasswordtext .= '半角数字を含んでください<br>';
            $inputrule = false;
        }
        if(!preg_match('/[a-zA-Z]/',$_POST['newpassword'])){
            $failnewpasswordtext .= '半角英字を含んでください<br>';
            $inputrule = false;
        }
        if($inputrule == true){
            $loginid = $_SESSION['loginid'];
            $query = 'SELECT * FROM login WHERE id = '.$loginid.' AND del = 0 ORDER BY ID DESC';
            $result = $database -> query($query);
            $row = mysqli_fetch_assoc($result);
            if(password_verify($_POST['oldpassword'], $row['password'])){
                $sendnewpassword = password_hash($_POST['newpassword'], PASSWORD_DEFAULT);
                $changedate = date("Y-m-d H:i:s");
                $query = <<<EOD
                    UPDATE login SET password = '{$sendnewpassword}' , created_at = '{$changedate}' WHERE id = {$loginid}
                    EOD;
                try{
                    $database -> query($query);
                    $changesuccesstext .= '<div class = \'successbox\'>新しいpasswordを設定しました</div>';
                    $changesuccess = true;
                }catch(Exception $e){
                    echo "エラー発生:" . $e->getMessage().'<br>';
                    exit();
                }
            }else{
                $failoldpasswordtext .= '古いpasswordが違います<br>';
            }

        }
        if($changesuccess == true){

        }else{
            $settextoldpassword = $_POST['oldpassword'];
            $settextnewpassword = $_POST['newpassword'];
        }

    }

    



    require_once('html/changepasswordview.php');
?>