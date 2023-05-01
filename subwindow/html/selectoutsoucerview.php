<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="../css/selectoutsoucer.css">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>アウトソーサーの選択</title>
</head>



<body>
    
    <div><h2>アウトソーサーの選択</h2></div>
    <div class = 'box left selectbox'>
        <form action = 'selectoutsoucer.php' method = post class = 'formsize'>
            <!-- <p>一つ以上入力してください</p> -->
            名前:<input type = 'text' id = 'inputsearchname' name = 'searchname' value = "<?php if($postflag){echo $_POST['searchname'];}?>"><br>
            
            <?php
                datein('生年月日','birth',['delete','search']);
                datein('入社日','join',['delete','search']);
            ?>
            <!-- 外勤先企業(一部でも):<input type = 'text' name = 'company' id = 'companyname' value = '<?php if($postflag){echo $_POST['company'];}?>'><br><br> -->
            <button type = 'submit' class = 'btn' name = 'search' value='検索'>検索</button>
        </form>
    
    <!-- <?php
        echo $tabletext;
    ?> -->
        <p>仕事開始日の選択<input type = 'date' id = 'inputstartdate' value = '<?php echo $settextstartdate?>'></p>
        <p>仕事終了予定日の選択<input type = 'date' id = 'inputenddate' value = '<?php echo $settextenddate?>'></p>
    </div>
    
    <div class = 'box left regibox'>
        <form action = '../subwindow/selectoutsoucer.php' method='post'>

        
            <p>名前:<?php echo $settextstaff ?></p>
            <p>仕事開始日:<a id = 'asettextstartdate'></a></p>
            <p>仕事終了予定日:<a id = 'asettextenddate'></a></p>
            <input type = 'hidden' name = 'searchcompany' value = '<?php echo $settextstaff?>'>
            <input type = 'hidden' name = 'startdate' id = 'startdateform'>
            <input type = 'hidden' name = 'enddate' id = 'enddateform'>
            <input type = 'hidden' name = 'staffid' id = 'staffidform' value = <?php echo $settextstaffid;?> >
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
        var staffform = document.getElementById('staffidform');
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
        if(startdateform.value !='' && enddateform.value !='' && staffform.value !=''){
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
</script>
