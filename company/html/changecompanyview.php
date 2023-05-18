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
<br><br><br><br><br><br>

<div class = 'backbox'><form action = detailcompany.php method = 'post'>
        <button type = submit name = 'companyid' value = <?php echo $id; ?> class = 'commonbutton'><img src="../img/backbutton.png" alt=""/>詳細画面に戻る</button>
    </form>
</div>

<div class="box formsize">
    <div class='boxtitle'>詳細と変更</div>
    <div class = 'boxcontent'>
        <form action="changecompany.php" method="post" id = 'changeform' novalidate>
            <a class = 'failfont'><?php echo $changesuccesstext; ?></a>
            <table class = 'changetable'>
                <tr>
                    <th>会社名</th>
                    <td><input type = 'text' name = 'company' class = 'validate[required]' value = <?php echo htmlentities($settextcompany); ?>><br>
                        <a class = 'failfont'><?php echo $companynamefailtext; ?></a>
                    </td>
                    <th>社長名</th>
                    <td><input type = 'text' name = 'president' value = <?php echo htmlentities($settextpresident); ?>></td>
                </tr>
                
                <tr>
                    <th>本社所在地</th>
                    <td colspan = '3'>(都道府県)<select name = 'prefectures'>
                        <?php if($settextprefectures != ''){echo '<option value = '.$settextprefectures.'>'.getpref($settextprefectures).'</option>';}?>
                        <?php selectpref();?>
                        </select>
                
                    <div>(市区町村以下)
                    <input type = 'text' name = 'location' style = 'width:600px'value = '<?php echo htmlentities($settextlocation); ?>' ></div></td>
                </tr>
                
                <tr>
                    <th>従業員数</th>
                    <td><input type = 'text' class = "validate[optional,custom[integer]]" name = 'numberofemployees' value = '<?php echo htmlentities($settextnumberofemployees); ?>' placeholder="半角数字" >人<br>
                        <a class = 'failfont'><?php echo $numberofemployeesfailtext; ?></a>
                    </td>
                    <th>平均年齢</th>
                    <td><input type = 'text' name = 'averageage' class = 'validate[optional,custom[number]]' value = '<?php echo htmlentities($settextaverageage); ?>' placeholder="半角数字か.(小数も可)" >歳<br>
                        <a class = 'failfont'><?php echo $averageagefailtext; ?></a>
                    </td>
                </tr>
                
                <tr>
                    <th>売上高</th>
                    <td>
                        <input type = 'number' name = 'sales' class = 'validate[optional,custom[integer]]' value = <?php echo $settextsales; ?> placeholder="半角数字" >
                        <select name = 'digit'>
                            <option value = '<?php echo $settextdigit?>'><?php echo $settextdigit2 ?></option>
                            <option value = '1000'>千円</option>
                            <option value = '1000000'>百万円</option>
                            <option value = '1000000000'>十億円</option>
                            <option value = '1000000000000'>兆円</option>
                        </select><br>
                        <a class = 'failfont'><?php echo $salesfailtext; ?></a>
                    </td>
                    <th>資本金</th>
                    <td><input type = 'number' name = 'capital' class = 'validate[optional,custom[integer]]' value = <?php echo $settextcapital; ?> placeholder="半角数字">
                        <select name = 'capitaldigit'>
                            <option value = '<?php echo $settextcapitaldigit?>'><?php echo $settextcapitaldigit2 ?></option>
                            <option value = '1'>円</option>
                            <option value = '1000'>千円</option>
                            <option value = '1000000'>百万円</option>
                            <option value = '1000000000'>十億円</option>
                            <option value = '1000000000000'>兆円</option>
                        </select>
                        <a class = 'failfont' ><?php echo $capitalfailtext; ?></a>
                    </td>
                    
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
                        <input type = "button" class = 'commonbutton' value ='追加' id = 'businessdetailsincreasetext'><input type = "button" class = 'commonbutton' value ='削除' id = 'businessdetailsdecreasetext'>
                        <?php echo $businessdetailtext;?>
                    </td>
                    <th>取引銀行</th>
                    <td class = 'arrayitem'>
                        <input type = "button" class = 'commonbutton' value ='追加' id = 'bankincreasetext'><input type = "button" class = 'commonbutton' value ='削除' id = 'bankdecreasetext'>
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
    /* $(function(){
        //<form>タグのidを指定
        $("#changeform").validationEngine(
            'attach', {
                promptPosition: "topRight"//エラーメッセージ位置の指定
            }
        );
    }); */
    let businessdetailsfunc = inputfield('businessdetails',<?php echo $businessdetailscount_json; ?>);
    let bankfunc = inputfield('bank',<?php echo $bankcount_json; ?>);
</script>