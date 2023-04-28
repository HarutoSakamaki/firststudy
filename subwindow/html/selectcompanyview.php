<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="../css/selectcompany.css">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>外勤際の選択</title>
</head>

<h2>外勤先の選択</h2>

<body>
    
    
    <div class = 'box'>
        <form action = 'selectcompany.php' method = post class = 'formsize'>
            <p>一つ以上入力してください</p>
            <p>会社名:<input type = 'text' id = 'inputsearchcompany' name = 'searchcompany' value = "<?php if($postflag){echo $_POST['searchcompany'];}?>"></p>
            <p>従業員数:<input type = 'text' id  = 'inputminemployees' name = 'minemployees' value = "<?php if($postflag){echo $_POST['minemployees'];}else{echo '0';}?>">~<input type = 'text' id = 'inputmaxemployees'name = 'maxemployees' value = "<?php if($postflag){echo $_POST['maxemployees'];}?>"></p>
            <!-- <?php
                datein("設立日","",['delete','search']);
            ?>  -->
            <button type = 'submit' class = 'btn' value='検索する' name = 'search'>検索</button>
        </form>
    </div>
    <?php
        echo $tabletext;
    ?>
        
    
    
    <div>
        <p>仕事開始日の選択<input type = 'date' id = 'inputstartdate' value = '<?php echo $settextstartdate?>'></p>
        <p>仕事終了予定日の選択<input type = 'date' id = 'inputenddate' value = '<?php echo $settextenddate?>'></p>
        <form action = '../subwindow/selectcompany.php' method='post'>

        
            <p>会社名:<?php echo $settextcompany ?></p>
            <p>仕事開始日:<a id = 'asettextstartdate'></a></p>
            <p>仕事終了予定日:<a id = 'asettextenddate'></a></p>
            <input type = 'hidden' name = 'searchcompany' value = '<?php echo $settextcompany?>'>
            <input type = 'hidden' name = 'startdate' id = 'startdateform'>
            <input type = 'hidden' name = 'enddate' id = 'enddateform'>
            <input type = 'hidden' name = 'companyid' id = 'companyidform' value = <?php echo $settextcompanyid;?> >
            <input type = 'submit' name = 'register' id = 'registerbutton' value = '登録'>


        </form>
    </div>



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
</script>
