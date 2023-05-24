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


    <div class = "alldetail">
        <h3>会社名:<?php echo htmlentities($row1['nm_company']); ?></h3>
        <div class = "alldetailcontent">
            <div class = 'floatclear'></div>
            <div class = "middletextsize detailbox">
                <div class = "detailboxcontent">
                    
                    <table class = 'detailtable'>
                        <tr>
                            <th>会社名</th>
                            <td><?php echo htmlentities($row1['nm_company']); ?></td>
                        </tr>
                        <tr>
                            <th>社長名</th>
                            <td><?php echo htmlentities($row1['nm_president']); ?></td>
                        </tr>
                        <tr>
                            <th>事業内容</th>
                            <td><?php echo $businessdetailtext;?></td>
                        </tr>
                        <tr>
                            <th>売上高</th>
                            <td><?php echo htmlentities($settextsales); ?></td>
                        </tr>
                        <tr>
                            <th>本社住所</th>
                            <td><?php echo $settextlocation; ?></td>
                        </tr>
                        <tr>
                            <th>従業員数</th>
                            <td><?php echo htmlentities($row1['su_numberofemployees']); ?>人</td>
                        </tr>
                        <tr>
                            <th>設立日</th>
                            <td><?php echo htmlentities($eastablishyear.'年'.$establishmonth.'月'.$establishday.'日'); ?></td>
                        </tr>
                        <tr>
                            <th>資本金</th>
                            <td><?php echo htmlentities($settextcapital); ?></td>
                        </tr>
                        <tr>
                            <th>平均年齢</th>
                            <td><?php echo htmlentities($settextaverageage); ?></td>
                        </tr>
                        <tr>
                            <th>決算月</th>
                            <td><?php echo htmlentities($settextclosingmonth)?></td>
                        </tr>
                        <tr>
                            <th>取引銀行</th>
                            <td><?php echo $banktext;?></td>
                        </tr>
                        <tr>
                            <th>ホームページ</th>
                            <td><?php echo '<a href = '.htmlentities($row1['nm_homepage']).'>'.htmlentities($row1['nm_company']).'</a>';?></td>
                        </tr>

                    </table>

                    <form action = 'changecompany.php' method = 'post'>
                        <button type = 'submit' class = 'btn' name = 'changeform' >変更フォームへ</button>
                        <input type = 'hidden' name = 'id' value = '<?php echo $id; ?>'>
                    </form>
                </div>
            </div>
            <div class = 'workplacebox'>
                <?php
                    echo $historyoutsoucertext;
                ?>
            </div>
        </div>
    </div>


</body>

<script src = ../subwindow/subwindow.js>
    
</script>

<script>
    function deleteform(formname){
        return window.confirm('本当に削除しますか');
    }

</script>

</html>