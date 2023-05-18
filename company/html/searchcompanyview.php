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
                        <td><input type = 'text' id = 'inputsearchcompany' name = 'searchcompany' value = "<?php echo $settextsearchcompany?>"></td>
                    </tr>
                    <tr>
                        <th>従業員数</th>
                        <td><input type = 'number' id  = 'inputminemployees' class = 'validate[optional,custom[integer]]'name = 'minemployees' value = "<?php echo $settextminemployees; ?>" onselect = 'numbercheck(this)'>
                            ~<input type = 'number' id = 'inputmaxemployees' class = 'validate[optional,custom[integer]]' name = 'maxemployees' value = "<?php echo $settextmaxemployees;?>" onselect = 'numbercheck(this)' > </td>
                    </tr>
                    <tr>
                        <th>設立日</th>
                        <td><?php datein('','',['delete','search']); ?></td>
                    </tr>
                    
			    </table>
                <button type = 'submit' class = 'btn searchbutton' value='検索する' name = 'search'>検索</button>
            </form>
        </div>
    </div>
        <?php
            echo $tablecounttext;
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