<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="../css/operation.css">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>外勤際の選択</title>
</head>



<body>
    <div><h2>履歴の操作</h2></div>
    
    <div class = 'regibox'>
        <form action = 'operation.php' method = 'post' id = 'addcompanyform'>
            <a class = 'failfont'><?php echo $allfailtext; ?></a>
            <table class = 'operationtable'>
                <tr>
                    <td>会社名</td>
                    <td>
                        <button type='button' name = 'search' onclick = 'disp("../subwindow/selectcompany.php")'> 外勤先の選択 </button>
                        <a id = 'selectcompanya'><?php echo $settext['company']; ?></a><input type = 'hidden' name = 'selectcompanyid' id = 'selectcompanyidhidden' value = '<?php echo $settext['companyid'] ?>'>
                        <input type = 'hidden' name = 'companyname' id = 'selectcompanyhidden' value = '<?php echo $settext['company']?>'>
                    </td>
                </tr>
                <tr>
                    <td>仕事開始日</td>
                    <td><div><input type = 'date' name= 'startdate' class = "validate[required]" id = 'inputstartdate' onchange = 'settextstartenddate()' value = '<?php echo $settext['startdate']; ?>'></div></td>
                </tr>
                <tr>
                    <td>仕事終了日</td>
                    <td><div><input type = 'date' name= 'enddate' class = "validate[required]" id = 'inputenddate' onchange = 'settextstartenddate()' value = '<?php echo $settext['enddate']; ?>'></div></td>
                </tr>
            </table>
            <input type = 'hidden' name = 'staffid' value = '<?php echo $settext['staffid'] ?>' >
            <input type = 'hidden' name = 'id' value = <?php echo $settext['id'];?>>
            <div class = 'flex'><button type = 'submit' class = 'changehistory center' name ='change' value = '変更' >変更</button><button type = 'submit' class = 'deletehistory center' name = 'delete' value = '削除'>削除</button></div>
            
        </form>
    </div>
    

    <?php
        echo $tabletext;
    ?>

    <br><br><br>
</body>
</html>



<script>

    function settextstartenddate(){
        console.log('i');
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
    settextstartenddate();
    function disp(url){
        console.log("クリック");
        var subw = 800;   // サブウインドウの横幅
        var subh = 600;   // サブウインドウの高さ
        // 表示座標の計算
        console.log(screen.availWidth);
        
        var subx = ( screen.width*1.2  - subw ) / 2;   // X座標
        var suby = ( screen.height*1.2 - subh ) / 2;   // Y座標
        console.log(subx);

        // サブウインドウのオプション文字列を作る
        var SubWinOpt = " width=" + subw + ", height=" + subh + ", top=" + suby + ", left=" + subx+ "";
        /* var SubWinOpt = "width=400,height=300,top=445,left=860"; */
        console.log(SubWinOpt);
        window.open(url,"_blank", SubWinOpt);
        /* window.open(url,target = this.target,SubWinOpt); */
        return false;
    }

    function setcompany(companyname,companyid){
        document.getElementById('selectcompanya').textContent = companyname;
        document.getElementById('selectcompanyidhidden').setAttribute('value',companyid);
        document.getElementById('selectcompanyhidden').setAttribute('value',companyname);
        return 'いけてるよ';
    }

</script>
