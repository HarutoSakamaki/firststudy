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



		$daytext = '';
		$daytext.='<p>生年月日:<select name="birthyear">'. "\n";
		if(isset($_POST['addoutsoucer'])){$daytext.='<option value = '.$_POST['birthyear'].'>'.$_POST['birthyear'].'</option>\n';}
		for($i = date('Y'); $i >= 1900; $i--) {
			$daytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
		}
		$daytext.='</select>年' . "\n".
			'<select name="birthmonth">' . "\n";
		if(isset($_POST['addoutsoucer'])){$daytext.='<option value = '.$_POST['birthmonth'].'>'.$_POST['birthmonth'].'</option>\n';}
		for ($i = 1; $i <= 12; $i++) {
			$daytext.= '<option value="' .$i . '">' . $i .'</option>'. "\n";
		}
		$daytext.='</select>月' . "\n".
			'<select name="birthday">' . "\n";
		if(isset($_POST['addoutsoucer'])){$daytext.='<option value = '.$_POST['birthday'].'>'.$_POST['birthday'].'</option>\n';}
		for ($i = 1; $i <= 31; $i++) {
			$daytext .= '<option value="' .$i . '">' . $i .'</option>'. "\n";
		}
		$daytext.='</select>日</p>' . "\n";
	
		$daytext.='<p>入社日(アスパーク)<select name="joinyear">'. "\n";
		if(isset($_POST['addoutsoucer'])){$daytext.='<option value = '.$_POST['joinyear'].'>'.$_POST['joinyear'].'</option>\n';}
		for($i = date('Y'); $i >= 1900; $i--) {
			$daytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
		}
		$daytext.='</select>年' . "\n".
			'<select name="joinmonth">' . "\n";
		if(isset($_POST['addoutsoucer'])){$daytext.='<option value = '.$_POST['joinmonth'].'>'.$_POST['joinmonth'].'</option>\n';}
		for ($i = 1; $i <= 12; $i++) {
			$daytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
		}
		$daytext.='</select>月' . "\n".
			'<select name="joinday">' . "\n";
		if(isset($_POST['addoutsoucer'])){$daytext.='<option value = '.$_POST['joinday'].'>'.$_POST['joinday'].'</option>\n';}
		for ($i = 1; $i <= 31; $i++) {
			$daytext.='<option value="' .$i . '">' . $i .'</option>'. "\n";
		}
		$daytext.='</select>日</p>' . "\n";

    require_once('html/regioutsoucerview.php');
    
    
?>
