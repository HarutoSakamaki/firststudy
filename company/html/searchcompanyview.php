<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="../css/searchcompany.css">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>登録会社の検索</title>
</head>
<?php
  include("../header.php");
?>
  <br><br><br><br><br>
<!-- <h2>登録会社の検索</h2> -->

<body>
    
    <br>
    <div class = 'box formsize'>
        <div class = "boxtitle">登録会社の検索</div>
        <div class = "boxcontent">
            <form action = 'searchcompany.php' id = 'formCheck' method = post class = 'formsize'>

                <table class = 'inputtable'>
                    <tr>
                        <th>会社名</th>
                        <td><input type = 'text' id = 'inputsearchcompany' name = 'searchcompany' value = "<?php if($postflag){echo htmlentities($_POST['searchcompany']);}?>"></td>
                    </tr>
                    <tr>
                        <th>従業員数</th>
                        <td><input type = 'number' id  = 'inputminemployees' class = 'validate[optional,custom[integer]]'name = 'minemployees' value = "<?php if($postflag){echo htmlentities($_POST['minemployees']); }else{echo '0';}?>" onselect = 'numbercheck(this)'>
                            ~<input type = 'number' id = 'inputmaxemployees' class = 'validate[optional,custom[integer]]' name = 'maxemployees' value = "<?php if($postflag){echo htmlentities($_POST['maxemployees']);}?>" onselect = 'numbercheck(this)' > </td>
                    </tr>
                    <tr>
                        <th>設立日</th>
                        <td><?php datein('','',['delete','search']); ?></td>
                    </tr>
                    <tr>
                        <th>表示</th>
                        <td>
                            <!-- <input type = 'radio' name = 'inorder' value = 'employeedesc' checked>従業員数降順  <input type = 'radio' name = 'inorder' value = 'employeeasc'>従業員数昇順
                            <input type = 'radio' name = 'inorder' value = 'regidesc'>登録新着順  <input type = 'radio' name = 'inorder' value = 'regiasc'>登録投稿順 <br>

                            <input type = 'radio' name = 'inorder' value = 'establishdesc' >設立日降順  <input type = 'radio' name = 'inorder' value = 'establishasc'>設立日昇順
                            <input type = 'radio' name = 'inorder' value = 'companynamedesc' >会社名降順  <input type = 'radio' name = 'inorder' value = 'comapnynameasc'>会社名昇順 -->
                            <?php echo $settextorder ?>
                            
                        
                        </td>
                    </tr>
			    </table>
                <button type = 'submit' class = 'btn searchbutton' value='検索する' name = 'search'>検索</button>
            </form>
        </div>
    </div>
        <?php
            echo $tabletext;
        ?>
    
    <br><br><br>
</body>
</html>

<script type="text/javascript">
    
    function deleteform($companyid,$companyname){
        console.log('ここは');
        
        var result = window.confirm($companyname +'を削除しますか？');
        if(result  == true){
            let parentelement = document.getElementById($companyid);
            let minyear = document.getElementById('inputminyear');
            let minyearelement = createhidden('minyear', minyear.value,parentelement);
            let minmonth = document.getElementById('inputminmonth');
            let minmonthelement = createhidden('minmonth', minmonth.value,parentelement);
            /* let minday = document.getElementById('inputminday');
            let mindayelement = createhidden('minday', minday.value,parentelement); */
            let maxyear = document.getElementById('inputmaxyear');
            let maxyearelement = createhidden('maxyear', maxyear.value,parentelement);
            let maxmonth = document.getElementById('inputmaxmonth');
            let maxmonthelement = createhidden('maxmonth', maxmonth.value,parentelement);
            /* let maxday = document.getElementById('inputmaxday');
            let maxdayelement = createhidden('maxday', maxday.value,parentelement); */
            let minemployees = document.getElementById('inputminemployees');
            let minemployeeselement = createhidden('minemployees', minemployees.value,parentelement);
            let maxemployees = document.getElementById('inputmaxemployees');
            let maxemployeeselement = createhidden('maxemployees', maxemployees.value,parentelement);
            let searchcompany = document.getElementById('inputsearchcompany');
            let searchcompanyelement = createhidden('searchcompany', searchcompany.value,parentelement);
            var submitelement = document. createElement('input');
            submitelement.setAttribute('type','submit');
            submitelement.setAttribute('name','delete');
            submitelement.setAttribute('id','clicksubmit');
            submitelement.setAttribute('class','displaynone');
            parentelement.after(submitelement);
            var clickobj = document.getElementById('clicksubmit');
            clickobj.click();
        }
    }
    function createhidden(name, value , parentelement){
        var Element = document. createElement('input');
        Element.setAttribute('type','hidden');
        Element.setAttribute('name',name);
        Element.setAttribute('value',value);
        parentelement.after(Element);
        return Element;
    }

    var checknumber = true;

    //入力チェック頑張るぞい
    $(function(){
        //<form>タグのidを指定
        $("#formCheck").validationEngine(
            'attach', {
                promptPosition: "topRight"//エラーメッセージ位置の指定
            }
            
        );
    });

    
 </script>