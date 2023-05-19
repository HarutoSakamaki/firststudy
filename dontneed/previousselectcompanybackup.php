
<script src = '../js/jquery-3.6.4.min.js'></script>
<link rel="stylesheet" href="../css/validationEngine.jquery.css">
<script src="../js/jquery.validationEngine.js"></script>
<script src="../js/jquery.validationEngine-ja.js" charset="UTF-8"></script>

<?php
    require_once '../link.php';
    $database = database('staff');
    session_start();
    

    $postflag = false;
    if(isset($_POST['search'])){
        $postflag = true;
    }   

    if(isset($_POST['search'])){

        $company = $_POST['searchcompany'];
        $minemployees = $_POST['minemployees'];
        $maxemployees = $_POST['maxemployees'];

        if($company != ""){
            $companyterms = ' company LIKE \'%'.$company.'%\' ';
        }else{
            $companyterms = ' company LIKE \'%\' ';
        }
        if($maxemployees == ''){
            $employeesterms = ' AND numberofemployees >= '.$minemployees.' ';
        }else{
            $employeesterms = ' AND numberofemployees BETWEEN '.$minemployees.' and '.$maxemployees;
        }
        try{
            $query = 'SELECT * FROM company WHERE '.$companyterms.$employeesterms.' AND del = false ORDER BY numberofemployees DESC';
            /* echo $query; */
            $searchresult = $database -> query($query);
            $searchquery = $query;
            
        }catch(Exception $e){
            echo "エラー発生:" . $e->getMessage().'<br>';
            echo "検索できませんでした";
        }
    }
?>
<?php
    if(isset($_POST['companyid'])){

        /* echo 'なぜできない'; */
        $settextcompany = $_POST['searchcompany'];
        $settextcompanyid = $_POST['companyid'];
    }else{
        $settextcompany = '';
        $settextcompanyid = '';
    }


    if(isset($_POST['register'])){
        if($_POST['startdate']!=''or $settextcompanyid != ''){
            try{
                $numberringquery = "UPDATE numbering SET numbering = LAST_INSERT_ID(numbering + 1) WHERE tablename = 'staffhistory'";
                $database -> query($numberringquery);
                $numberringquery = 'SELECT numbering FROM numbering where tablename = \'staffhistory\' ';
                $numberring = mysqli_fetch_assoc($database -> query($numberringquery));
                $numberringid = $numberring['numbering'];
                $query = 'INSERT staffhistory (id , staffid , companyid, startdate, enddate) VALUES('.$numberringid.','.$_SESSION['staffid'].','.$settextcompanyid.',\''.$_POST['startdate'].'\',\''.$_POST['enddate'].'\')';
                echo $query;
                $result = $database -> query($query);
                echo '成功';
                destruction();
            }catch(Exception $e){
                echo "エラー発生:" . $e->getMessage().'<br>';
                echo "登録できませんでした";
            }
        }else{
            echo 'すべて入力してください';
        }
    }else{
        /* echo 'ここが問題か？'; */
    }
    function destruction(){
        echo <<<EDO
            <script>
                window.opener.location.reload();
                open('about:blank', '_self').close();
            </script>
        EDO;
    }


    $tabletext ='';
        if (isset($_POST['search'])){
        $tabletext.=<<<EDO
            <table border="1">
            <tr>
            <th>会社名</th>
            <th>従業員数</th>
            <th>選択</th>
            </tr>
        EDO;
        $searchquery = formquery($searchquery);
        while($row = mysqli_fetch_assoc($searchresult)){
            $setcompany = htmlentities($row['company']);
            $setnumberofemployees = htmlentities($row['numberofemployees']);
            $tabletext.=<<<EDO
                <tr>
                <td>{$setcompany}</td><td>{$setnumberofemployees}</td>
                <td><form action = 'selectcompany.php' method=post><input type = 'submit'name='selectcompany'value='選択' >
                <input type= 'hidden' name=  'companyid' value =  '{$row['id']}'>
                <input type= 'hidden' name=  'searchcompany' value =  '{$setcompany}'>
                <input id = 'inputsearchquery' type='hidden' name=  'searchquery' value =  '{$searchquery}'>
                </form></td>
                </tr>
            EDO;
        }
        $tabletext.='</table>';
    }


    require_once('html/selectcompanyview.php');
?>


<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="../css/selectcompany.css">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>外勤際の選択</title>
</head>



<body>
    <div><h2>外勤先の選択</h2></div>
    
    <div class = 'box left selectbox'>
        <form action = 'selectcompany.php' id = 'selectform' method = post class = 'formsize'>
            <p>一つ以上入力してください</p>
            <p>会社名:<input type = 'text' id = 'inputsearchcompany' name = 'searchcompany' value = "<?php if($postflag){echo htmlentities($_POST['searchcompany']);}?>"></p>
            <p>従業員数:<input type = 'number' id  = 'inputminemployees' class = 'validate[optional,custom[integer]]' name = 'minemployees' value = "<?php if($postflag){echo htmlentities($_POST['minemployees']);}else{echo '0';}?>">~<input type = 'text' id = 'inputmaxemployees' class = 'validate[optional,custom[integer]]' name = 'maxemployees' value = "<?php if($postflag){echo htmlentities($_POST['maxemployees']);}?>"></p>
            <!-- <?php
                datein("設立日","",['delete','search']);
            ?>  -->
            <button type = 'submit' class = 'btn' value='検索する' name = 'search'>検索</button>
        </form>
    
    <!-- <?php
        echo $tabletext;
    ?> -->
        
    
    
    
        <p>仕事開始日の選択<input type = 'date' id = 'inputstartdate' value = '<?php echo htmlentities($settextstartdate)?>'></p>
        <p>仕事終了予定日の選択<input type = 'date' id = 'inputenddate' value = '<?php echo htmlentities($settextenddate)?>'></p>
    </div>
    <div class = 'box left regibox'>
        <form action = '../subwindow/selectcompany.php'  method='post'>

        
            <p>会社名:<?php echo htmlentities($settextcompany) ?></p>
            <p>仕事開始日:<a id = 'asettextstartdate'  ></a></p>
            <p>仕事終了予定日:<a id = 'asettextenddate'  ></a></p>
            <input type = 'hidden' name = 'searchcompany' value = '<?php echo htmlentities($settextcompany)?>'>
            <input type = 'hidden' name = 'startdate' id = 'startdateform'>
            <input type = 'hidden' name = 'enddate' id = 'enddateform'>
            <input type = 'hidden' name = 'companyid' id = 'companyidform' value = <?php echo htmlentities($settextcompanyid);?> >
            <input type = 'submit' name = 'register' id = 'registerbutton' value = '登録'>


        </form>
    </div>

    <?php
        echo $tabletext;
    ?>

    <br><br><br>
</body>
</html>



<script>
    settextstartenddate();
    window.addEventListener('DOMContentLoaded', function(){
    // 0.5秒ごとに実行
        setInterval(() => {
            startenddatereflect();
        }, 100);
    });

    function startenddatereflect(){
        var startdateelement = document.getElementById('inputstartdate');
        var astartdateelement = document.getElementById('asettextstartdate');
        var startdateform = document.getElementById('startdateform');
        startdateform.value = startdateelement.value;
        astartdateelement.textContent = startdateelement.value;
        window.sessionStorage.setItem('startdate',startdateelement.value);
        var enddateelement = document.getElementById('inputenddate');
        var aenddateelement = document.getElementById('asettextenddate');
        var enddateform = document.getElementById('enddateform');
        enddateform.value = enddateelement.value;
        aenddateelement.textContent = enddateelement.value;
        window.sessionStorage.setItem('enddate',enddateelement.value);
        var companyform = document.getElementById('companyidform');
        var submitbutton = document.getElementById('registerbutton');
        if(startdateelement.value != ''){
            enddateelement.setAttribute('min',startdateelement.value);
        }else{
            enddateelement.setAttribute('min','');
        }
        if(enddateelement.value != ''){
            startdateelement.setAttribute('max',enddateelement.value);
        }else{
            startdateelement.setAttribute('max','');
        }
        if(startdateform.value !='' && enddateform.value !='' && companyform.value !=''){
            submitbutton.disabled = false;
        }else{
            submitbutton.disabled = true;
        }
    }
    function settextstartenddate(){
        var startdateelement = document.getElementById('inputstartdate');
        startdateelement.value = sessionStorage.getItem('startdate');
        var enddateelement = document.getElementById('inputenddate');
        enddateelement.value = sessionStorage.getItem('enddate');
    }

    $(function(){
        //<form>タグのidを指定
        $("#selectform").validationEngine(
            'attach', {
                promptPosition: "topRight"//エラーメッセージ位置の指定
            }
        );
    });

</script>



