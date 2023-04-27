<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="../css/searchoutsoucer.css">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>外勤先の変更</title>
</head>

<body>

    <?php
    include("../header.php");
    ?>
    <br><br><br><br><br>
    <h2>外勤先の変更</h2>
    <?php
        echo $nowcompanytext;
        echo $historycompanytext;
    ?>
</body>



</html>


<script src = ../subwindow/subwindow.js>

</script>

<script>
    window.addEventListener('DOMContentLoaded', function(){
    // 0.5秒ごとに実行
        setInterval(() => {
            ablejudge();
        }, 100);
    });
    function ablejudge(){

        var enddateelem = document.getElementById('inputenddate');
        var button = document.getElementById('enddatebutton');
        if(enddateelem.value == ''){
            button.disabled = 'disabled';
        }else{
            button.disabled = null;
        }

    }

</script>