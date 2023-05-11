<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="../css/changecompany.css">
<script src = "../js/change.js"></script>
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>登録会社の詳細変更</title>
</head>
<body>
<?php
    include("../header.php");
?>
<br><br><br><br><br>


<div class="box formsize">
    <div class='boxtitle'><h3>詳細と変更</h3></div>
    <div class = 'boxcontent'>
        <form action="changecompany.php" method="post" id = 'changeform'>
            <table class = 'changetable'>
                <tr>
                    <th>会社名</th>
                    <td><input type = 'text' name = 'company' class = 'validate[required]' value = <?php echo htmlentities($settextcompany); ?>></td>
                    <th>社長名</th>
                    <td><input type = 'text' name = 'president' value = <?php echo htmlentities($settextpresident); ?>></td>
                </tr>
                
                <tr>
                    <th>本社所在地(都道府県)</th>
                    <td><select name = 'prefectures'>
                        <?php if($settextprefectures != ''){echo '<option value = '.$settextprefectures.'>'.getpref($settextprefectures).'</option>';}?>
                        <?php selectpref();?>
                        </select>
                    </td>
                    <th>本社所在地(市区町村以下)</th>
                    <td><input type = 'text' name = 'location' value = '<?php echo htmlentities($settextlocation); ?>' ></td>
                </tr>
                
                <tr>
                    <th>従業員数</th>
                    <td><input type = 'text' class = "validate[optional,custom[integer]]" name = 'numberofemployees' value = <?php echo htmlentities($settextnumberofemployees); ?>></td>
                    <th>平均年齢</th>
                    <td><input type = 'text' name = 'averageage' class = 'validate[optional,custom[number]]' value = '<?php echo htmlentities($settextaverageage); ?>'>歳</td>
                </tr>
                
                <tr>
                    <th>売上高</th>
                    <td>
                        <input type = 'number' name = 'sales' class = 'validate[optional,custom[integer]]' value = '<?php echo $settextsales; ?>'><select name = 'digit'>
                            <option value = '<?php echo $settextdigit?>'><?php echo $settextdigit2 ?></option>
                            <option value = '1000'>千円</option>
                            <option value = '1000000'>百万円</option>
                            <option value = '1000000000'>十億円</option>
                            <option value = '1000000000000'>兆円</option>
                        </select>
                    </td>
                    <th>資本金</th>
                    <td><input type = 'number' name = 'capital' class = 'validate[optional,custom[integer]]' value = '<?php echo $settextcapital; ?>' ></td>
                    
                </tr>
                
                <tr>
                    <th>設立日</th>
                    <td><?php echo $joindaytext;?></td>
                    <th>決算月</th>
                    <td>
                        <select name = 'closingmonth' class = 'validate[optional,custom[integer]]'></p>
                            <option value = '<?php echo htmlentities($settextclosingmonth); ?>'><?php echo htmlentities($settextclosingmonth);?>月</option>
                            <option value = '1'>1月</option>
                            <option value = '2'>2月</option>
                            <option value = '3'>3月</option>
                            <option value = '4'>4月</option>
                            <option value = '5'>5月</option>
                            <option value = '6'>6月</option>
                            <option value = '7'>7月</option>
                            <option value = '8'>8月</option>
                            <option value = '9'>9月</option>
                            <option value = '10'>10月</option>
                            <option value = '11'>11月</option>
                            <option value = '12'>12月</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>事業内容</th>
                    <td class = 'arrayitem'>
                        <input type = "button" class = 'commonbutton' value ='入力欄を増やす' id = 'businessdetailsincreasetext'><input type = "button" class = 'commonbutton' value ='入力欄を減らす' id = 'businessdetailsdecreasetext'>
                        <?php echo $businessdetailtext;?>
                    </td>
                    <th>取引銀行</th>
                    <td class = 'arrayitem'>
                        <input type = "button" class = 'commonbutton' value ='入力欄を増やす' id = 'bankincreasetext'><input type = "button" class = 'commonbutton' value ='入力欄を減らす' id = 'bankdecreasetext'>
                        <?php echo $banktext;?>
                    </td>
                    
                </tr>
                <tr>
                    <th>ホームページURL</th>
                    <td colspan="2"><input type = 'text' class = 'validate[optional,custom[url]]' name = 'homepage' value = <?php echo htmlentities($settexthomepage); ?>></td>
                </tr>
            </table>
            
            <input type = 'hidden' name = 'id' value = <?php echo $id; ?>>
            <p><button type = 'submit' class = 'btn' name = 'change' value='change'>変更</button></p>
        </form>
    </div>
</div>


</body>

<script>
    $(function(){
        //<form>タグのidを指定
        $("#changeform").validationEngine(
            'attach', {
                promptPosition: "topRight"//エラーメッセージ位置の指定
            }
        );
    });
    let businessdetailsfunc = inputfield('businessdetails',<?php echo $businessdetailscount_json; ?>);
    let bankfunc = inputfield('bank',<?php echo $bankcount_json; ?>);
</script>