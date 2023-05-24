
<script src = '../js/jquery-3.6.4.min.js'></script>
<link rel="stylesheet" href="../css/validationEngine.jquery.css">
<script src="../js/jquery.validationEngine.js"></script>
<script src="../js/jquery.validationEngine-ja.js" charset="UTF-8"></script>



<?php
    require_once '../link.php';
    $database = database('staff');
    
    session_start();
    if(isset($_SESSION['login'])){
        
    }else{
        $_SESSION['againlogin'] = true;
        header("Location: ../others/login.php");
        exit();
    }
    if(isset($_GET['id'])){
        $id = $_GET['id'];
    }

    $allfailtext = '';
    $tabletext = '';
    $settext = [];

    if(isset($_POST['change']) or isset($_POST['delete'])){
        $settext = ['company'=>$_POST['companyname'],'startdate'=>$_POST['startdate'],'enddate'=>$_POST['enddate'],'id'=>$_POST['id'] ,'companyid'=>$_POST['selectcompanyid'] , 'staffid' =>$_POST['staffid']];
        if(isset($_POST['change'])){
            $changehistorysuccess = false;
            $companyid = $_POST['selectcompanyid'];
            $inputrule = false;
            $overlapping = false;
            //入力規則(空欄があるかどうか)
            $empty = true;
            if($settext['companyid'] == '' or $settext['startdate'] == '' or $settext['enddate'] == ''){
                $empty = true;
                $allfailtext .= '空欄があります<br>';
            }else{
                $empty = false;
            }
            
            if($empty == false){
                //重複チェック
                $query = <<<EDO
                    SELECT dt_startdate , dt_enddate FROM tbm_staffhistory_kiso WHERE no_staffid = {$settext['staffid']} AND flg_del = false AND tbm_staffhistory_kiso.pk_id_staffhistory != {$settext['id']} ;
                EDO;
                
                try{
                    $result = $database -> query($query);
                }catch(e){
                    echo "エラー発生:" . $e->getMessage().'<br>';
                    exit();
                }
                $overlapping = false;
                while($row = mysqli_fetch_assoc($result)){
                    $datastart = strtotime($row['dt_startdate']);
                    $dataend = strtotime($row['dt_enddate']);
                    $inputstart = strtotime($settext['startdate']);
                    $inputend = strtotime($settext['enddate']);
                    if($datastart<=$inputend && $dataend>=$inputstart){
                        $overlapping = true;
                    }else{
    
                    }
                }
            }

            if($overlapping == true){
                $allfailtext .= '期間が重複してます';
            }
            if($empty == false and $overlapping == false){
                try{
                    $query = <<<EDO
                        UPDATE tbm_staffhistory_kiso SET
                        dt_startdate = '{$settext['startdate']}' ,
                        dt_enddate = '{$settext['enddate']}' ,
                        no_companyid = {$settext['companyid']}
                        WHERE pk_id_staffhistory = {$settext['id']}
                    EDO;
                    $database -> query($query);
                    $changehistorysuccess = true;
                }catch(e){
                    /* echo "エラー発生:" . $e->getMessage().'<br>';
                    echo "  外勤先を取得できませんでした"; */
                }
            }
            //成功したのでクローズする
            if($changehistorysuccess == true){
                echo <<<EDO
                    <script>
                        window.opener.location.reload()
                        window.close();
                    </script>
                EDO;
            }

        }elseif(isset($_POST['delete'])){
            $deletesuccess = false;
            $query = <<<EDO
                UPDATE tbm_staffhistory_kiso SET 
                 flg_del = 1 
                WHERE pk_id_staffhistory = {$settext['id']}
            EDO;
            try{
                $database -> query($query);
                $deletesuccess = true;
            }catch(e){
                /* echo "エラー発生:" . $e->getMessage().'<br>';
                echo "  外勤先を取得できませんでした"; */
            }
            if($deletesuccess == false){
                $allfailtext .= '削除できませんでした';
            }elseif($deletesuccess == true){
                echo <<<EDO
                <script>
                    window.opener.location.reload()
                    window.close();
                </script>
                EDO;
            }
        }

    }else{
        $query = <<<EOD
            SELECT  tbm_staffhistory_kiso.pk_id_staffhistory as id, 
                tbm_company_kiso.nm_company as company, 
                tbm_staffhistory_kiso.dt_startdate as startdate, 
                tbm_staffhistory_kiso.dt_enddate as enddate, 
                tbm_staffname_kiso.nm_name as name,
                tbm_staffhistory_kiso.no_companyid as companyid ,
                tbm_staffhistory_kiso.no_staffid as staffid 
            FROM tbm_staffhistory_kiso 
            LEFT JOIN tbm_company_kiso ON tbm_staffhistory_kiso.no_companyid = tbm_company_kiso.pk_id_company LEFT JOIN tbm_staffname_kiso ON tbm_staffname_kiso.pk_id_staffname = tbm_staffhistory_kiso.no_staffid 
            WHERE tbm_staffhistory_kiso.pk_id_staffhistory = {$id}; 
        EOD;
        try{
            $result = $database -> query($query);
        }catch(Exception $e){
            echo "エラー発生:" . $e->getMessage().'<br>';
            echo "  外勤先を取得できませんでした";
            exit();
        }

        while($row = mysqli_fetch_assoc($result)){
            $settext = ['company'=>$row['company'],'startdate'=>$row['startdate'],'enddate'=>$row['enddate'],'id'=>$row['id'] ,'companyid'=>$row['companyid'] , 'staffid' =>$row['staffid']];
        }
        
    }

    
    







    require_once('html/operationview.php');
?>