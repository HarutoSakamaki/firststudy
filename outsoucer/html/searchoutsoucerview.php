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

<body>
   
    <br>
    <div class = 'box formsize'>
        <div class = "boxtitle">アウトソーサーの検索</div>
        <div class = "boxcontent">
            <form action = 'searchoutsoucer.php' method = post class = 'formsize'>
                <table class = 'inputtable'>
                    <tr>
                        <th>名前</th>
                        <td><input type = 'text' id = 'inputsearchname' name = 'searchname' value = "<?php echo htmlentities($settextname);?>"></td>
                    </tr>
                    <tr>
                        <th>社員番号</th>
                        <td><input type = 'number' id = 'inputemployeeid' name = 'employeeid' value = "<?php echo htmlentities($settextemployeeid);?>"></td>
                    </tr>
                    <tr>
                        <th>生年月日</th>
                        <td><?php datein('','birth',['delete','search'],[0,1,1,2000,1,1],1900) ?></td>
                    </tr>
                    <tr>
                        <th>入社日</th>
                        <td><?php datein('','join',['delete','search'],[0,1,1,2000,1,1],1900) ?></td>
                    </tr>
                    
                </table>
                <button type = 'submit' class = 'btn' name = 'search' value='検索'>検索</button>
            
            </form>
        </div>
    </div >
    <?php
        echo $tablecounttext;
        echo $tabletext;
    ?>
         
</body>
<script type="text/javascript">
    
    function deleteform($staffid,$staffname){
        var result = window.confirm($staffname+'を本当に削除しますか');
        console.log('ここは');
        if(result == true){
            let parentelement = document.getElementById($staffid);
            let searchname = document.getElementById('inputsearchname');
            let searchnameelement = createhidden('searchname', searchname.value,parentelement);
            let employeeid = document.getElementById('inputemployeeid');
            let employeeidelement = createhidden('employeeid', employeeid.value,parentelement);
            let birthminyear = document.getElementById('inputbirthminyear');
            let birthminyearelement = createhidden('birthminyear', birthminyear.value,parentelement);
            let birthminmonth = document.getElementById('inputbirthminmonth');
            let birthminmonthelement = createhidden('birthminmonth', birthminmonth.value,parentelement);
            
            let birthmaxyear = document.getElementById('inputbirthmaxyear');
            let birthmaxyearelement = createhidden('birthmaxyear', birthmaxyear.value,parentelement);
            let birthmaxmonth = document.getElementById('inputbirthmaxmonth');
            let birthmaxmonthelement = createhidden('birthmaxmonth', birthmaxmonth.value,parentelement);
            
            let joinminyear = document.getElementById('inputjoinminyear');
            let joinminyearelement = createhidden('joinminyear', joinminyear.value,parentelement);
            let joinminmonth = document.getElementById('inputjoinminmonth');
            let joinminmonthelement = createhidden('joinminmonth', joinminmonth.value,parentelement);
            
            let joinmaxyear = document.getElementById('inputjoinmaxyear');
            let joinmaxyearelement = createhidden('joinmaxyear', joinmaxyear.value,parentelement);
            let joinmaxmonth = document.getElementById('inputjoinmaxmonth');
            let joinmaxmonthelement = createhidden('joinmaxmonth', joinmaxmonth.value,parentelement);
            
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

    
    
 </script>



</html>