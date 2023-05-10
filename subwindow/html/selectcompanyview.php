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
            <p>会社名:<input type = 'text' id = 'inputsearchcompany' name = 'searchcompany' value = "<?php if($postflag){echo htmlentities($_POST['searchcompany']);}?>"></p>
            <p>従業員数:<input type = 'number' id  = 'inputminemployees' class = 'validate[optional,custom[integer]]' name = 'minemployees' value = "<?php if($postflag){echo htmlentities($_POST['minemployees']);}else{echo '0';}?>">
            ~<input type = 'text' id = 'inputmaxemployees' class = 'validate[optional,custom[integer]]' name = 'maxemployees' value = "<?php if($postflag){echo htmlentities($_POST['maxemployees']);}?>"></p>
            
            <button type = 'submit' class = 'btn' value='検索する' name = 'search'>検索</button>
        </form>
    </div>
    

    <?php
        echo $tabletext;
    ?>

    <br><br><br>
</body>
</html>



<script>
    

</script>
