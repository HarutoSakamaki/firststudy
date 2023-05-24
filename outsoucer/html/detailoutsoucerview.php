<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="../css/detailoutsoucer.css">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>アウトソーサーの詳細</title>
</head>
<body>
<?php
    require_once('../header.php');
?>
<br><br><br><br><br>
<!-- <h2>アウトソーサーの詳細</h2> -->
    <div class = 'alldetail'>
        <h3>名前:<?php echo htmlentities($row1['nm_name']); ?></h3>
        <div class = "alldetailcontent">
            
            <div class = 'floatclear'></div>
            <div class = "middletextsize detailbox">
                
                <table class = 'detailtable'>
                    <tr>
                        <th>名前</th>
                        <td><?php echo htmlentities($row1['nm_name']); ?></td>
                    </tr>
                    <tr>
                        <th>フリガナ</th>
                        <td><?php echo htmlentities($row1['nm_furigana']); ?></td>
                    </tr>
                    <tr>
                        <th>生年月日</th>
                        <td><?php echo htmlentities($birthyear.'年'.$birthmonth.'月'.$birthday.'日'); ?></td>
                    </tr>
                    <tr>
                        <th>現住所</th>
                        <td><?php echo $settextaddress; ?></td>
                    </tr>
                    <tr>
                        <th>メールアドレス</th>
                        <td><?php echo htmlentities($row1['nm_mailaddress']); ?></td>
                    </tr>
                    <tr>
                        <th>電話番号</th>
                        <td><?php echo htmlentities($row1['su_phonenumber']); ?></td>
                    </tr>
                    <tr>
                        <th>職歴</th>
                        <td><?php echo $workhistorytext;?></td>
                    </tr>
                    <tr>
                        <th>免許や資格</th>
                        <td><?php echo $licensetext; ?></td>
                    </tr>
                    <tr>
                        <th>志望理由</th>
                        <td><span class = 'smalltext'><?php echo htmlentities($row1['etc_motivation']); ?></span></td>
                    </tr>
                    <tr>
                        <th>入社日</th>
                        <td><?php echo htmlentities($joinyear.'年'.$joinmonth.'月'.$joinday.'日'); ?></td>
                    </tr>

                </table>
                
                <form action = 'changeoutsoucer.php' method = 'post'>
                    <button type = 'submit' class = 'btn' name = 'changeform' >変更フォームへ</button>
                    <input type = 'hidden' name = 'id' value = '<?php echo $id; ?>'>
                </form>
            </div>

            <div class = 'workplacebox'>

                <div class = 'regibox'>
                    <form action = 'detailoutsoucer.php' method = 'post' id = 'addcompanyform'>
                        <?php echo $addcompanysuccesstext; ?>
                        <a class = 'failfont'><?php echo $addcompanyfailtext; ?></a>
                        <table class = 'regitable'>
                            <tr>
                                <td>会社名</td>
                                <td>
                                    <button type='button' id = 'subwindowbutton' class = 'selectcompany validate[required]' onClick = 'disp("../subwindow/selectcompany.php")' name = 'companyid' value =''>外勤先の選択</button>
                                    <a id = 'selectcompanya'><?php echo $settextcompanyname; ?></a><input type = 'hidden' name = 'selectcompanyid' id = 'selectcompanyidhidden' value = '<?php echo $settextcompanyid ?>'><input type = 'hidden' name = 'selectcompanyname' id = 'selectcompanyhidden' value = '<?php echo $settextcompanyname?>'>
                                </td>
                            </tr>
                            <tr>
                                <td>仕事開始日</td>
                                <td><div><input type = 'date' name= 'startdate' class = "validate[required]" id = 'inputstartdate' onchange = 'settextstartenddate()' value = '<?php echo $settextstartdate; ?>'></div></td>
                            </tr>
                            <tr>
                                <td>仕事終了日</td>
                                <td><div><input type = 'date' name= 'enddate' class = "validate[required]" id = 'inputenddate' onchange = 'settextstartenddate()' value = '<?php echo $settextenddate; ?>'></div></td>
                            </tr>
                        </table>
                        <input type = 'hidden' name = 'staffid' value = '<?php echo $id ?>' >
                        <input type = 'submit' class = 'addcompany' name ='addcompany' value = '外勤先の追加' >
                    </form>
                </div>
                <?php
                    echo $historycompanytext;
                ?>
            </div>
        </div>
    </div>

</body>
<script src = ../subwindow/subwindow.js>

</script>

<script>
    

    function setcompany(companyname,companyid){
        document.getElementById('selectcompanya').textContent = companyname;
        document.getElementById('subwindowbutton').setAttribute('value',companyid);
        document.getElementById('selectcompanyidhidden').setAttribute('value',companyid);
        document.getElementById('selectcompanyhidden').setAttribute('value',companyname);
        return 'いけてるよ';
    }
    
    function settextstartenddate(){
        var startdateelement = document.getElementById('inputstartdate');
        var enddateelement = document.getElementById('inputenddate');
        if(startdateelement.value!=''){
            enddateelement.setAttribute('min',startdateelement.value);
        }else{
            enddateelement.setAttribute('min','');
        }
        if(enddateelement.value!=''){
            startdateelement.setAttribute('max',enddateelement.value);
        }else{
            startdateelement.setAttribute('max','');
        }
    }
    function deleteform(formname){
        return window.confirm('本当に削除しますか');
        
    }
    function operation(staffhistoryid){
        disp("../subwindow/operation.php?id="+staffhistoryid);
        return true;
    }


</script>

</html>