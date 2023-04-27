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
        if (isset($_POST['search'])){
    ?>
        
    
    <table border="1">
                    <tr>
                    <th>会社名</th>
                    <th>従業員数</th>
                    <th>選択</th>
                    </tr>
                    <?php

                    
                    $searchquery = formquery($searchquery);

                    while($row = mysqli_fetch_assoc($searchresult)){
                        echo '<tr>';
                        echo '<td>'.$row['company'].'</td><td>'.$row['numberofemployees'].'</td>';
                        echo '<td><form action = \'selectcompany.php\' method=post><input type = \'submit\'name=\'selectcompany\'value=\'選択\' >
                            <input type= \'hidden\' name=  \'companyid\' value =  \''.$row['id'].'\'>
                            <input type= \'hidden\' name=  \'searchcompany\' value =  \''.$row['company'].'\'>
                            <input id = \'inputsearchquery\' type=\'hidden\' name=  \'searchquery\' value =  \''.$searchquery.'\'>
                            </form></td>';
                        echo '</tr>';
                    }
                }
                    
                    ?>
    </table>
    <p>仕事開始日の選択<input type = 'date' id = 'inputstartdate' value = '<?php echo $settextstartdate?>'></p>
    <div>
        
        <form action = '../subwindow/selectcompany.php' method='post'>
            <p><?php echo $settextcompany ?></p>
            <p><a id = 'asettextstartdate'></a></p>
            <input type = 'hidden' name = 'searchcompany' value = '<?php echo $settextcompany?>'>
            <input type = 'hidden' name = 'startdate' id = 'startdateform'>
            <input type = 'hidden' name = 'companyid' value = <?php echo $settextcompanyid;?> >
            <input type = 'submit' name = 'register' value = '登録'>

        </form>
    </div>



    <br><br><br>
</body>
</html>



<script>
    settextstartdate();
    window.addEventListener('DOMContentLoaded', function(){
    // 0.5秒ごとに実行
        setInterval(() => {
            startdatereflect();
        }, 100);
    });

    function startdatereflect(){
        var startdateelement = document.getElementById('inputstartdate');
        var astartdateelement = document.getElementById('asettextstartdate');
        var startdateform = document.getElementById('startdateform');
        startdateform.value = startdateelement.value;
        astartdateelement.textContent = startdateelement.value;
        window.sessionStorage.setItem('startdate',startdateelement.value);
    }
    function settextstartdate(){
        var startdateelement = document.getElementById('inputstartdate');
        startdateelement.value = sessionStorage.getItem('startdate');
    }
</script>
