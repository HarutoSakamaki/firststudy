<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="../css/searchoutsoucer.css">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>アウトソーサーの検索</title>
</head>
<body>

<?php
  include("../header.php");
?>


  <br><br><br><br><br>

<h2>アウトソーサーの検索</h2>
<body>
   
    <br>
    <div class = 'box formsize'>
        <div class = "boxtitle">アウトソーサーの検索</div>
        <div class = "boxcontent">
            <form action = 'searchoutsoucer.php' method = post class = 'formsize'>
                <!-- <p>一つ以上入力してください</p> -->
                名前:<input type = 'text' id = 'inputsearchname' name = 'searchname' value = "<?php if($postflag){echo $_POST['searchname'];}?>"><br>
                
                <?php
                    datein('生年月日','birth',['delete','search']);
                    datein('入社日','join',['delete','search']);
                ?>
                <!-- 外勤先企業(一部でも):<input type = 'text' name = 'company' id = 'companyname' value = '<?php if($postflag){echo $_POST['company'];}?>'><br><br> -->
                <button type = 'submit' class = 'btn' name = 'search' value='検索'>検索</button>
            
            </form>
        </div>
    </div >
        <?php
            if(isset($_POST['delete']) or isset($_POST['search'])){
        ?>
         <table border="1">
                <tr>
                <th>名前</th>
                <th>生年月日</th>
                <th>入社日</th>
                <!-- <th>外勤先</th> -->
                <th id = "deltd">削除</th>
                <th id = "deltd">詳細と変更</th>
                </tr>
                <?php
                    $searchquery = formquery($searchquery);
                    while($row = mysqli_fetch_assoc($searchresult)){
                        echo '<tr>';
                        echo '<td>'.$row['name'].'</td><td>'.$row['birthday'].'</td><td>'.$row['joincompanyday'].'</td>';
                        echo '<td id = "deltd"><form action = \'searchoutsoucer.php\' method=post><input type = \'button\'class = \'commonbutton\'name=\'delete\'value=\'削除\' onClick = \'deleteform()\'>
                            <input type=\'hidden\' name=  \'id\' value =  \''.$row['id'].'\'>
                            <input id = \'inputsearchquery\' type=\'hidden\' name=  \'searchquery\' value =  \''.$searchquery.'\'>
                            </form></td>';
                        echo '<td id = "deltd"><form action = \'detailoutsoucer.php\' method=post><input class=\'commonbutton\' type = \'submit\'name=\'detail\'value=\'詳細と変更\'>
                            <input type=\'hidden\' name=  \'id\' value =  \''.$row['id'].'\'>
                            </form></td>';
                        echo '</tr>';
                    }
            }
                
                ?>
            </table>
    
    
</body>
<script type="text/javascript">
    
    function deleteform(){
        console.log('ここは');
        let parentelement = document.getElementById('inputsearchquery');
        let searchname = document.getElementById('inputsearchname');
        let searchnameelement = createhidden('searchname', searchname.value,parentelement);
        let birthminyear = document.getElementById('inputbirthminyear');
        let birthminyearelement = createhidden('birthminyear', birthminyear.value,parentelement);
        let birthminmonth = document.getElementById('inputbirthminmonth');
        let birthminmonthelement = createhidden('birthminmonth', birthminmonth.value,parentelement);
        let birthminday = document.getElementById('inputbirthminday');
        let birthmindayelement = createhidden('birthminday', birthminday.value,parentelement);
        let birthmaxyear = document.getElementById('inputbirthmaxyear');
        let birthmaxyearelement = createhidden('birthmaxyear', birthmaxyear.value,parentelement);
        let birthmaxmonth = document.getElementById('inputbirthmaxmonth');
        let birthmaxmonthelement = createhidden('birthmaxmonth', birthmaxmonth.value,parentelement);
        let birthmaxday = document.getElementById('inputbirthmaxday');
        let birthmaxdayelement = createhidden('birthmaxday', birthmaxday.value,parentelement);
        let joinminyear = document.getElementById('inputjoinminyear');
        let joinminyearelement = createhidden('joinminyear', joinminyear.value,parentelement);
        let joinminmonth = document.getElementById('inputjoinminmonth');
        let joinminmonthelement = createhidden('joinminmonth', joinminmonth.value,parentelement);
        let joinminday = document.getElementById('inputjoinminday');
        let joinmindayelement = createhidden('joinminday', joinminday.value,parentelement);
        let joinmaxyear = document.getElementById('inputjoinmaxyear');
        let joinmaxyearelement = createhidden('joinmaxyear', joinmaxyear.value,parentelement);
        let joinmaxmonth = document.getElementById('inputjoinmaxmonth');
        let joinmaxmonthelement = createhidden('joinmaxmonth', joinmaxmonth.value,parentelement);
        let joinmaxday = document.getElementById('inputjoinmaxday');
        let joinmaxdayelement = createhidden('joinmaxday', joinmaxday.value,parentelement);
        /* let companyname = document.getElementById('companyname');
        let companyelement = createhidden('company', companyname.value,parentelement); */
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



</html>