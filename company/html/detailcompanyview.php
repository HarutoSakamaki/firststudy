<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="../css/detailcompany.css">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>登録会社の詳細</title>
</head>
<body>
<?php
    include("../header.php");
?>
<br><br><br><br><br>
<!-- <h2>登録会社の詳細</h2> -->
<h3>会社名:<?php echo $row1['company']; ?></h3>
    <div class = "alldetail">
        <div class = "middletextsize detailbox">
            <!-- <h3>会社名:<?php echo $row1['company']; ?></h3> -->
            <div class = "detailboxcontent">
                <p>社長名:<?php echo $row1['president']; ?></p>
                
                <p>事業内容:<br>
                    <?php 
                        echo $businessdetailtext;
                    ?>
                </p>
                <p>本社住所:<?php echo $row1['location']; ?></p>
                <p>従業員数:<?php echo $row1['numberofemployees']; ?></p>
                <p>設立日:<?php echo $eastablishyear.'年'.$establishmonth.'月'.$establishday.'日'; ?></p>
                <p>ホームページ:<?php echo '<a href = '.$row1['homepage'].'>'.$row1['location'].'</a>';?></p>

                <form action = 'changecompany.php' method = 'post'>
                    <p><button type = 'submit' class = 'btn' name = 'changeform' >変更フォームへ</button></p>
                    <input type = 'hidden' name = 'company' value = '<?php echo $company; ?>'>
                    <input type = 'hidden' name = 'id' value = '<?php echo $id; ?>'>
                </form>
            </div>
        </div>
        <div class = 'workplacebox'>
            <?php
                echo $nowoutsoucertext;
                echo $historyoutsoucertext;
            ?>
        </div>

    </div>


</body>

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
        var checkchangedate = document.querySelectorAll(".checknextnext");
        var checknext = document.querySelectorAll(".checknext");
        /* console.log(checkchangedate); */
        checkchangedate.forEach(function(element){
            if(element.checked){
                var nextelement = element.nextElementSibling;
                var nextnextelement = nextelement.nextElementSibling;
                nextelement.disabled = false;
                nextnextelement.disabled = false;
            }else{
                var nextelement = element.nextElementSibling;
                var nextnextelement = nextelement.nextElementSibling;
                nextelement.disabled = true;
                nextnextelement.disabled = true;
            }
        });
        checknext.forEach(function(element){
            if(element.checked){
                var nextelement = element.nextElementSibling;
                nextelement.disabled = false;
            }else{
                var nextelement = element.nextElementSibling;
                nextelement.disabled = true;
            }
        });
    }

</script>

</html>