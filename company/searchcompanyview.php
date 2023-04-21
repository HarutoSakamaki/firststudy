<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="searchcompany.css">
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
    <form action = 'searchcompany.php' method = post class = 'formsize'>
        <p>一つ以上入力してください</p>
        <p>会社名(一部でも):<input type = 'text' id = 'inputsearchcompany' name = 'searchcompany' value = "<?php echo $model->getsettextcompanyname(); ?>"></p>
        <p>従業員数:<input type = 'text' id  = 'inputminemployees' name = 'minemployees' value = "<?php echo $model->getsettextminemployees(); ?>">~<input type = 'text' id = 'inputmaxemployees'name = 'maxemployees' value = "<?php echo $model->getsettextmaxemployees; ?>"></p>
        <?php
            datein("設立日","",['delete','search'],[$model->getminyear(),$model->getsettextminmonth(),$model->getsettextminday(),$model->getsettextmaxyear(),$model->getsettextmaxmonth(),$model->getsettextmaxday()]);
        ?> 
        <button type = 'submit' class = 'btn' value='検索する' name = 'search'>検索</button>
        </form>
    </div>
        <?php
            if(isset($_POST['delete'])){
                $searchquery = $_POST['searchquery'];
                $searchquery = formbackquery($searchquery);
                try{
                    $searchresult = $database -> query($searchquery);
                }catch(Exception $e){
                    echo "エラー発生:" . $e->getMessage().'<br>';
                    echo "検索できませんでした";
                }
            }

            if(isset($_POST['search'])){
                $company = $_POST['searchcompany'];
                $minemployees = $_POST['minemployees'];
                $maxemployees = $_POST['maxemployees'];
                $minestablish = $_POST['minyear'].'-'.$_POST['minmonth'].'-'.$_POST['minday'];
                $maxestablish = $_POST['maxyear'].'-'.$_POST['maxmonth'].'-'.$_POST['maxday'];
                if($company != ""){
                    $companyterms = ' company LIKE \'%'.$company.'%\' ';
                }else{
                    $companyterms = ' company LIKE \'%\' ';
                }
                if($maxemployees == ''){
                    $employeesterms = ' AND numberofemployees >= '.$minemployees.' ';
                }else{
                    $employeesterms = ' AND numberofemployees BETWEEN '.$minemployees.' and '.$maxemployees;
                }
                $establishterms = ' AND establishdate BETWEEN DATE(\''.$minestablish.'\') and DATE(\''.$maxestablish.'\') ';
                
                
                try{
                    
                    $query = 'SELECT * FROM company WHERE '.$companyterms.$employeesterms.$establishterms.' AND del = false ORDER BY numberofemployees DESC';
                    /* echo $query; */
                    $searchresult = $database -> query($query);
                    $searchquery = $query;
                    
                }catch(Exception $e){
                    echo "エラー発生:" . $e->getMessage().'<br>';
                    echo "検索できませんでした";
                }
            }
            if (isset($_POST['search']) or isset($_POST['delete'])){
            
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

                    
                    $searchquery = formquery($searchquery);

                    while($row = mysqli_fetch_assoc($searchresult)){
                        echo '<tr>';
                        echo '<td>'.$row['company'].'</td><td>'.$row['numberofemployees'].'</td><td>'.$row['establishdate'].'</td>';
                        echo '<td><form action = \'searchcompany.php\' method=post><input type = \'button\'name=\'delete\'value=\'削除\' onClick = \'deleteform()\'>
                            <input type= \'hidden\' name=  \'id\' value =  \''.$row['id'].'\'>
                            <input id = \'inputsearchquery\' type=\'hidden\' name=  \'searchquery\' value =  \''.$searchquery.'\'>
                            </form></td>';
                        echo '<td><form action = \'detailcompany.php\' method=post><input type = \'submit\'name=\'detail\'value=\'詳細と変更\'>
                            <input type=\'hidden\' name=  \'id\' value =  \''.$row['id'].'\'>
                            <input type=\'hidden\' name=  \'searchquery\' value =  \''.$searchquery.'\'>
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