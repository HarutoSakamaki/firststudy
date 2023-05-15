
<script src = '../js/jquery-3.6.4.min.js'></script>
<link rel="stylesheet" href="../css/validationEngine.jquery.css">
<script src="../js/jquery.validationEngine.js"></script>
<script src="../js/jquery.validationEngine-ja.js" charset="UTF-8"></script>


<?php


    require_once '../link.php';
	$database = database('staff');
	
	session_start();
    if(isset($_SESSION['adminlogin'])){
        
    }else{
        $_SESSION['againlogin'] = true;
        header("Location: ../others/login.php");
        exit();
    }

    $addaccountsuccesstext = '';
    $settextaddusername = '';
    $settextsearchusername = '';
    $searchfailtext = '';
    $searchtable = '';

    //アカウントの登録
    $addsuccess = false;
    if(isset($_POST['addaccount'])){
        
        if($_POST['password'] == $_POST['passwordagain'] and preg_match('/^[a-zA-Z0-9_]{8,}$/',$_POST['password']) and preg_match("/^[a-zA-Z0-9]+$/",$_POST['username'])
        and preg_match('/[0-9]/',$_POST['login']) and preg_match("/[a-zA-Z]/", $_POST['login'])){

            try{
                $query = 'SELECT * FROM login WHERE username = \''.$_POST['username'].'\' AND del = 0 ORDER BY ID DESC';
                $result = $database -> query($query);
            }catch(Exception $e){
                echo "エラー発生:" . $e->getMessage().'<br>';
                exit();
            }
            if(isset(mysqli_fetch_assoc($result)['id'])){
                $addaccountsuccesstext .= htmlspecialchars($_POST['username']).'はすでに存在しています<br>';
            }else{
                if($_POST['adminregi'] == 'はい'){
                    $admin = 1;
                }else{
                    $admin = 0;
                }
                try{
                    $numberingquery = "UPDATE numbering SET numbering = LAST_INSERT_ID(numbering + 1) WHERE tablename = 'login'";
                    $database -> query($numberingquery);
                    $numberingquery = 'SELECT numbering FROM numbering where tablename = \'login\' ';
                    $numberingid = mysqli_fetch_assoc($database -> query($numberingquery));
                    $query = <<<EDO
                        INSERT INTO login (id,username,password,admin) VALUES ({$numberingid['numbering']},'{$_POST['username']}','{$_POST['password']}',{$admin}) 
                        EDO;
                    $result = $database -> query($query);
                    $addaccountsuccesstext .= '登録に成功しました';
                    $addsuccess = true;
                }catch(Exception $e){
                    echo "エラー発生:" . $e->getMessage().'<br>';
                    exit();
                }
            }
        }else{
            
            if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['username'])){
                $addaccountsuccesstext .= 'usernameは半角英数字で入力してください<br>';
            }
            if(!preg_match('/^[a-zA-Z0-9_]{8,}$/',$_POST['password'])){
                $addaccountsuccesstext .= 'passwordは半角数字8文字以上で入力してください<br>';
            }
            if($_POST['password'] != $_POST['passwordagain']){
                $addaccountsuccesstext .= '二つのpasswordの値が違います<br>';
            }
            if(!preg_match('/[0-9]/',$_POST['password'])){
                $addaccountsuccesstext .= 'passwordは半角数字を含んでください<br>';
            }
            if(!preg_match('/[a-zA-Z]/',$_POST['password'])){
                $addaccountsuccesstext .= 'passwordは半角英字を含んでください<br>';
            }
        }
        if($addsuccess == false){
            $settextaddusername = $_POST['username'];
        }
    }
    


    //delete処理

    if(isset($_POST['delete'])){
        $id = $_POST['delid'];
        $changeup_date = ' update_at = \''.date("Y-m-d H:i:s").'\' ';
        try{
            $query = "UPDATE login SET del = 1 , ".$changeup_date." WHERE id = {$id} ";
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
                $searchusernameterms = ' username LIKE \'%'.$_POST['username'].'%\' ';
            }else{
                $searchusernameterms = ' username LIKE \'%\' ';
            }
            $query = 'SELECT * FROM login 
                WHERE '.$searchusernameterms.' AND del = 0 ORDER BY ID DESC';

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
            $searcharray[] = array('id' => $row['id'], 'username' => $row['username'] , 'password' => $row['password'] , 'admin' => $row['admin']);
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