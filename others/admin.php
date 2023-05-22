
<script src = '../js/jquery-3.6.4.min.js'></script>
<link rel="stylesheet" href="../css/validationEngine.jquery.css">
<script src="../js/jquery.validationEngine.js"></script>
<script src="../js/jquery.validationEngine-ja.js" charset="UTF-8"></script>


<?php
    require_once '../link.php';
	$database = database('staff');
	
	session_start();
    session_regenerate_id(true);
    if(isset($_SESSION['adminlogin'])){
        
    }else{
        $_SESSION['againlogin'] = true;
        header("Location: ../others/login.php");
        exit();
    }

    $addaccountsuccesstext = '';
    $settextaddusername = '';
    $settextpassword = '';
    $settextsearchusername = '';
    $searchfailtext = '';
    $searchtable = '';
    $usernamefailtext = '';
    $passwordfailtext ='';
    $passwordagainfailtext = '';

    //アカウントの登録
    $addsuccess = false;
    if(isset($_POST['addaccount'])){
        
        if($_POST['password'] == $_POST['passwordagain'] and preg_match('/^[a-zA-Z0-9_]{8,}$/',$_POST['password']) and preg_match("/^[a-zA-Z0-9]+$/",$_POST['username'])
        and preg_match('/[0-9]/',$_POST['password']) and preg_match("/[a-zA-Z]/", $_POST['password'])){

            try{
                $query = 'SELECT * FROM tbm_login WHERE nm_username = \''.$_POST['username'].'\' AND flg_del = 0 ORDER BY pk_id_login DESC';
                $result = $database -> query($query);
            }catch(Exception $e){
                echo "エラー発生:" . $e->getMessage().'<br>';
                exit();
            }
            if(isset(mysqli_fetch_assoc($result)['pk_id_login'])){
                $usernamefailtext .= htmlspecialchars($_POST['username']).'はすでに存在しています<br>';
            }else{
                if($_POST['adminregi'] == 'はい'){
                    $admin = 1;
                }else{
                    $admin = 0;
                }
                try{
                    $numberingquery = "SELECT no_tuban FROM tbs_saiban WHERE pk_id_saiban = 4";
                    $result = $database -> query($numberingquery);
                    $tuban = (mysqli_fetch_assoc($searchresult)['no_tuban'])+1;
                    $hashpassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $query = <<<EDO
                        INSERT INTO tbm_login (pk_id_login,nm_username,nm_password,flg_admin) 
                        VALUES ({$tuban},'{$_POST['username']}','{$hashpassword}',{$admin}) 
                    EDO;
                    $database -> query($query);
                    $numberingquery = <<<EDO
                        UPDATE tbs_saiban SET no_tuban = {$tuban} WHERE pk_id_saiban = 4;
                    EDO;
                    $database -> query($numberingquery);
                    $newid = $tuban;
                    $addaccountsuccesstext .= <<<EOD
                        <div class = 'successbox'>登録に成功しました</div>
                    EOD;
                    $addsuccess = true;
                }catch(Exception $e){
                    echo "エラー発生:" . $e->getMessage().'<br>';
                    exit();
                }
            }
        }else{
            if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['username'])){
                $usernamefailtext .= '半角英数字で入力してください<br>';
            }
            if(!preg_match('/^[a-zA-Z0-9_]{8,}$/',$_POST['password'])){
                $passwordfailtext .= '半角数字8文字以上で入力してください<br>';
            }
            if($_POST['password'] != $_POST['passwordagain']){
                $passwordagainfailtext .= '二つのpasswordの値が違います<br>';
            }
            if(!preg_match('/[0-9]/',$_POST['password'])){
                $passwordfailtext .= '半角数字を含んでください<br>';
            }
            if(!preg_match('/[a-zA-Z]/',$_POST['password'])){
                $passwordfailtext .= '半角英字を含んでください<br>';
            }
        }
        if($addsuccess == false){
            $settextaddusername = $_POST['username'];
            $settextpassword = $_POST['password'];
        }
    }
    


    //delete処理

    if(isset($_POST['delete'])){
        $id = $_POST['delid'];
        $changeup_date = ' upd_date = \''.date("Y-m-d H:i:s").'\' ';
        try{
            $query = "UPDATE tbm_login SET flg_del = 1 , ".$changeup_date." WHERE pk_id_login = {$id} ";
            /* echo $query; */
            $database -> query($query);
            /* echo $query;
            echo '削除しました'; */
        }catch(Exception $e){
            /* echo "エラー発生:" . $e->getMessage().'<br>';
            echo "削除できませんでした"; */
        }
    }



    //検索アルゴリズム

    //queryの設定
    $search = false;
    if(isset($_POST['searchaccount'])){
        
        if(preg_match("/^[a-zA-Z0-9]+$/",$_POST['username']) or $_POST['username'] ==  ''){

            if($_POST['username'] != ""){
                $searchusernameterms = ' nm_username LIKE \'%'.$_POST['username'].'%\' ';
            }else{
                $searchusernameterms = ' nm_username LIKE \'%\' ';
            }
            $query = 'SELECT * FROM tbm_login  
                WHERE '.$searchusernameterms.' AND flg_del = 0 ORDER BY pk_id_login DESC';

            $search = true;
        }else{
            $searchfailtext .= '半角英数字で入力して下さい';
            $search = false;
        }
    }elseif(isset($_POST['delete'])){
        $searchquery = $_POST['sendsearchquery'];
        $query = formbackquery($searchquery);
        $search = true;
    }

    //検索と表示
    if($search == true){
        try{
            $searchresult = $database -> query($query);
            $searchquery = $query;
            
        }catch(Exception $e){
            echo "エラー発生:" . $e->getMessage().'<br>';
            echo "検索できませんでした";
        }
        $searcharray = array();
        while($row = mysqli_fetch_assoc($searchresult)){
            $searcharray[] = array('id' => $row['pk_id_login'], 'username' => $row['nm_username'] , 'password' => $row['nm_password'] , 'admin' => $row['flg_admin']);
        }
        if(count($searcharray)>0){
            $searchtable .= <<<EOD
                <table class = 'table1'>
                    <tr>
                        <th>username</th>
                        <th>password</th>
                        <th>authority</th>
                        <th>削除</th>
                    </tr>
                EOD;
            foreach($searcharray as $searcharray){
                if($searcharray['admin']==1){
                    $setauthority = '管理者';
                }else{
                    $setauthority = '社員';
                }
                $sendsearchquery = formquery($searchquery);
                $tableusername = htmlspecialchars($searcharray['username']);
                $tablepassword = htmlspecialchars($searcharray['password']);
                $searchtable .= <<<EOD
                    <tr>
                        <td>{$tableusername}</td>
                        <td>{$tablepassword}</td>
                        <td>{$setauthority}</td>
                        <td>
                            <form action = 'admin.php' method=post onSubmit='return deletecheck()'>
                                <button type = 'submit'class = 'commonbutton'name='del'value='削除' id = '{$searcharray['id']}'>
                                    <img src="../img/deleteicon.png" alt=""/>削除
                                </button>
                                <input type='hidden' name=  'delid' value =  '{$searcharray['id']}'>
                                <input type='hidden' name = 'delete' value = '削除'>
                                <input id = 'inputsearchquery' type='hidden' name=  'sendsearchquery' value =  '{$sendsearchquery}'>
                            </form>
                        </td>
                        
                    </tr>
                    EOD;
            }
            $searchtable.='</table>';
        }else{
            if($_POST['searchaccount']){
                $searchfailtext .= '該当するアカウントはありませんでした';
            }
        }
    }



    require_once('html/adminview.php');
?>