<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="html/searchcompany.css">
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
<h2>登録会社の検索</h2>
<?php
    require_once '../link.php';
?>
<body>
    
    <br>
    <div class = 'box'>
    <form action = 'searchcompanycontroller.php' method = post class = 'formsize'>
        <p>一つ以上入力してください</p>
        <p>会社名(一部でも):<input type = 'text' id = 'inputsearchcompany' name = 'searchcompany' value = "<?php echo $model->getsettextcompany(); ?>"></p>
        <p>従業員数:<input type = 'text' id  = 'inputminemployees' name = 'minemployees' value = "<?php echo $model->getsettextminemployees(); ?>">~<input type = 'text' id = 'inputmaxemployees'name = 'maxemployees' value = "<?php echo $model->getsettextmaxemployees(); ?>"></p>
        <?php
            datein("設立日","",['delete','search'],[$model->getsettextminyear(),$model->getsettextminmonth(),$model->getsettextminday(),$model->getsettextmaxyear(),$model->getsettextmaxmonth(),$model->getsettextmaxday()]);
        ?> 
        <button type = 'submit' class = 'btn' value='検索する' name = 'search'>検索</button>
        </form>
    </div>
        <?php
            
            if ($model->getpostflag()){
                if($model->getsearchsuccess() and $model->getsearch()){
                    echo '検索できました';
                }else if($model->getsearch()){
                    echo '検索できませんでした';
                }
        ?>
    </form>
    </div>
    <table border="1">
                    <tr>
                    <th>会社名</th>
                    <th>従業員数</th>
                    <th>設立日</th>
                    <th>削除</th>
                    <th>詳細と変更</th>
                    </tr>
                    <?php
                    foreach($model->searcharray as $searcharray){
                        echo '<tr>';
                        echo '<td>'.$searcharray['company'].'</td><td>'.$searcharray['numberofemployees'].'</td><td>'.$searcharray['establishdate'].'</td>';
                        echo '<td><form action = \'searchcompanycontroller.php\' method=post><input type = \'button\'name=\'delete\'value=\'削除\' onClick = \'deleteform()\'>
                            <input type= \'hidden\' name=  \'id\' value =  \''.$searcharray['id'].'\'>
                            <input id = \'inputsearchquery\' type=\'hidden\' name=  \'searchquery\' value =  \''.$model->formsearchquery.'\'>
                            </form></td>';
                        echo '<td><form action = \'detailcompanycontroller.php\' method=post><input type = \'submit\'name=\'detail\'value=\'詳細と変更\'>
                            <input type=\'hidden\' name=  \'id\' value =  \''.$searcharray['id'].'\'>
                            <input type=\'hidden\' name=  \'searchquery\' value =  \''.$model->formsearchquery.'\'>
                            </form></td>';
                        echo '</tr>';
                    }
                }
                    ?>
    </table>
    <br><br><br>
</body>
</html>

<script type="text/javascript">
    
    function deleteform(){
        console.log('ここは');
        let parentelement = document.getElementById('inputsearchquery');
        let minyear = document.getElementById('inputminyear');
        let minyearelement = createhidden('minyear', minyear.value,parentelement);
        let minmonth = document.getElementById('inputminmonth');
        let minmonthelement = createhidden('minmonth', minmonth.value,parentelement);
        let minday = document.getElementById('inputminday');
        let mindayelement = createhidden('minday', minday.value,parentelement);
        let maxyear = document.getElementById('inputmaxyear');
        let maxyearelement = createhidden('maxyear', maxyear.value,parentelement);
        let maxmonth = document.getElementById('inputmaxmonth');
        let maxmonthelement = createhidden('maxmonth', maxmonth.value,parentelement);
        let maxday = document.getElementById('inputmaxday');
        let maxdayelement = createhidden('maxday', maxday.value,parentelement);
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
    function createhidden(name, value , parentelement){
        var Element = document. createElement('input');
        Element.setAttribute('type','hidden');
        Element.setAttribute('name',name);
        Element.setAttribute('value',value);
        parentelement.after(Element);
        return Element;
    }
    
 </script>