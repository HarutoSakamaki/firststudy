<!-- <script>
	function choicecompany(id,company){
		console.log('チョイス')
		parentelement = document.getElementById('subwindowbutton');
		var companydisp = document.createElement('p');
		companydisp.textContent = company;
		companydisp.setAttribute('class','choicecompany');
		parentelement.after(companydisp);
		var idelement = document.createElement('input');
		idelement.setAttribute('class','choicecompany');''
		idelement.setAttribute('type','hidden');
		idelement.setAttribute('name','companyid');
		idelement.setAttribute('value',id);
		parentelement.after(idelement);
	}
</script> -->



<?php
    require_once '../link.php';
    $database = database('staff');
?>
<?php
	$regiflag = false;
		if(isset($_POST['addoutsoucer'])){
			if($_POST['name']==''or$_POST['birthyear']==''or$_POST['birthmonth']==''or$_POST['birthday']==''or$_POST['joinyear']==''or$_POST['joinmonth']==''or$_POST['joinday']==''){
				echo "必要事項に空欄があります<br>";
			}else{
				$regibirth = $_POST['birthyear'].'-'.$_POST['birthmonth'].'-'.$_POST['birthday'];
				$regijoin = $_POST['joinyear'].'-'.$_POST['joinmonth'].'-'.$_POST['joinday'];
				/* $info = '\''.uuid().'\',\''.$_POST['name'].'\',\''.$regibirth.'\',\''.$regijoin.'\',\''.$_POST['company'].'\''; 
				echo $info; */
				try{
					$numberringquery = "UPDATE numbering SET numbering = LAST_INSERT_ID(numbering + 1) WHERE tablename = 'staffname'";
					$database -> query($numberringquery);
					$numberringquery = 'SELECT numbering FROM numbering where tablename = \'staffname\' ';
					$numberringid = mysqli_fetch_assoc($database -> query($numberringquery));
					$info = '\''.$numberringid['numbering'].'\',\''.$_POST['name'].'\',\''.$regibirth.'\',\''.$regijoin.'\'';
					$query = "INSERT INTO staffname (id,name,birthday,joincompanyday)VALUES(".$info.")";
					$database -> query($query);
					$regiflag = true;
					echo '従業員を登録しました';
					$newid =  "".$numberringid['numbering']."";
					echo <<<EOM
						<form action = 'changeoutsoucer.php' method = 'post'>
							<input type = 'submit' name = 'changeform' value = '詳細を設定する'>
							<input type = 'hidden' name = 'id' value = '{$newid}'>
						</form>
						EOM;
				}catch (Exception $e){
					echo "エラー発生:" . $e->getMessage().'/n';
					echo "登録できませんでした";
				}
			}
		}

    require_once('html/regioutsoucerview.php');
    
    
?>
