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


<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="regioutsoucer.css">
<title>アウトソーサーの登録フォーム</title>
</head>
<!-- header -->

<body>
<?php
  include("../header.php");
?>
<p>
	<?php
		require_once '../link.php';
		$database = database('staff');
	?>
</p>
<br><br><br><br><br><br>
<h2>登録フォーム<h2>
</body>
	<?php
	$regiflag = false;
		if(isset($_POST['addoutsoucer']) and isset($_POST['companyid'])){
			if($_POST['name']==''or$_POST['birthyear']==''or$_POST['birthmonth']==''or$_POST['birthday']==''or$_POST['joinyear']==''or$_POST['joinmonth']==''or$_POST['joinday']==''or$_POST['companyid']==''){
				echo "必要事項に空欄があります<br>";
			}else{
				$regibirth = $_POST['birthyear'].'-'.$_POST['birthmonth'].'-'.$_POST['birthday'];
				$regijoin = $_POST['joinyear'].'-'.$_POST['joinmonth'].'-'.$_POST['joinday'];
				/* $info = '\''.uuid().'\',\''.$_POST['name'].'\',\''.$regibirth.'\',\''.$regijoin.'\',\''.$_POST['company'].'\''; 
				echo $info; */
				try{
					$numberringquery = "UPDATE numberring SET id = LAST_INSERT_ID(id + 1) WHERE tablename = 'staffname'";
					$database -> query($numberringquery);
					$numberringquery = 'SELECT id FROM numberring where tablename = \'staffname\' ';
					$numberringid = mysqli_fetch_assoc($database -> query($numberringquery));
					$info = '\''.$numberringid['id'].'\',\''.$_POST['name'].'\',\''.$regibirth.'\',\''.$regijoin.'\',\''.$_POST['companyid'].'\'';
					$query = "INSERT INTO staffname (id,name,birthday,joincompanyday,company)VALUES(".$info.")";
					$database -> query($query);
					$regiflag = true;
					echo '従業員を登録しました';
					$newid =  "".$numberringid['id']."";
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
		}else{
			echo '外勤先を入力してください';
		}
	?>
<body>
<p class = 'formclear'></p>
<div id = 'regiemployees' class = 'formsize box'>
	<form action="regioutsoucer.php" method="post">
		<p> 名前: <input type="text" name="name" value ="<?php if(isset($_POST['addoutsoucer'])){echo $_POST['name'];}?>"></p>
		
		<?php
			echo '<p>生年月日:<select name="birthyear">'. "\n";
			if(isset($_POST['addoutsoucer'])){echo '<option value = '.$_POST['birthyear'].'>'.$_POST['birthyear'].'</option>\n';}
			for($i = date('Y'); $i >= 1900; $i--) {
				echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
			}
			echo '</select>年' . "\n";
			echo '<select name="birthmonth">' . "\n";
			if(isset($_POST['addoutsoucer'])){echo '<option value = '.$_POST['birthmonth'].'>'.$_POST['birthmonth'].'</option>\n';}
			for ($i = 1; $i <= 12; $i++) {
				echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
			}
			echo '</select>月' . "\n";
			echo '<select name="birthday">' . "\n";
			if(isset($_POST['addoutsoucer'])){echo '<option value = '.$_POST['birthday'].'>'.$_POST['birthday'].'</option>\n';}
			for ($i = 1; $i <= 31; $i++) {
				echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
			}
			echo '</select>日</p>' . "\n";
		
			echo '<p>入社日(アスパーク)<select name="joinyear">'. "\n";
			if(isset($_POST['addoutsoucer'])){echo '<option value = '.$_POST['joinyear'].'>'.$_POST['joinyear'].'</option>\n';}
			for($i = date('Y'); $i >= 1900; $i--) {
				echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
			}
			echo '</select>年' . "\n";
			echo '<select name="joinmonth">' . "\n";
			if(isset($_POST['addoutsoucer'])){echo '<option value = '.$_POST['joinmonth'].'>'.$_POST['joinmonth'].'</option>\n';}
			for ($i = 1; $i <= 12; $i++) {
				echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
			}
			echo '</select>月' . "\n";
			echo '<select name="joinday">' . "\n";
			if(isset($_POST['addoutsoucer'])){echo '<option value = '.$_POST['joinday'].'>'.$_POST['joinday'].'</option>\n';}
			for ($i = 1; $i <= 31; $i++) {
				echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
			}
			echo '</select>日</p>' . "\n";
			
			$companyquery = $database->query('SELECT company id FROM company  WHERE del = false ORDER BY company DESC ');
			/* echo '<p>外勤先会社名(待機中なら無しとお答えください):<select name="companyid">'. "\n";
			if(isset($_POST['addoutsoucer'])){echo '<option value = '.$_POST['companyid'].'>'.$_POST['company'].'</option>\n';}
			while($value = mysqli_fetch_assoc($companyquery)){
				echo '<option value = '.$value['id'].'>'.$value['company'].'</option>';
				echo $value['id'];
			}
			
			echo '</select></p>' . "\n";

			echo '<input type=\'button\' onClick=\'disp(\'subwindow/selectcompany.php\')\' value = \'外勤先の選択\'>'; */
			
			
		?>
		<input type = 'button' id = 'subwindowbutton' onClick = 'disp("../subwindow/selectcompany.php")' value = '外勤先の選択'>
		<?php
			if($regiflag){
					$postid = "".$_POST['companyid']."";
					$postcompany = "".$_POST['company']."";
					echo <<<EOM
						<p class = 'choicecompany'>$postcompany</p>
						<input type = 'hidden' name = 'companyid' class = 'choicecompany' value = ' $postid '>
						<input type = 'hidden' name = 'company' class = 'choicecompany' value = ' $postcompany '>
						EOM;
			}
		?>
		<p><button type="subit" name="addoutsoucer" class = "btn">従業員の登録</button></p>
		
		
	</form>
</div>

<p class = 'floatclear'></p>
	

	<?php
		if (isset($_POST['displayoutsoucer'])) {
			$result = "登録しました";
		}
	?>
</body>
</html>

<script>

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

function disp(url){
	console.log("クリック");
	var subw = 800;   // サブウインドウの横幅
	var subh = 600;   // サブウインドウの高さ
	// 表示座標の計算
	console.log(screen.availWidth);
	
	var subx = ( screen.width  - subw ) / 2;   // X座標
	var suby = ( screen.width - subh ) / 2;   // Y座標
	console.log(subx);

	// サブウインドウのオプション文字列を作る
	var SubWinOpt = " width=" + subw + ", height=" + subh + ", top=" + suby + ", left=" + subx+ "";
	/* var SubWinOpt = "width=400,height=300,top=445,left=860"; */
	console.log(SubWinOpt);
	window.open(url,"_blank", SubWinOpt);
}
/* choicecompany('id','company'); */
function choicecompany(id,company){
	/* ここからはリムーブコード */
	var removeelements = document.getElementsByClassName('choicecompany');
	for (var i = 0; i < removeelements.length; i++) {
		var e = removeelements[i];
		if (e) {
			e.parentNode.removeChild(e);
		}
	}
	/* 次からは追加コード */

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
	var companyelement = document.createElement('input');
	companyelement.setAttribute('class','choicecompany');''
	companyelement.setAttribute('type','hidden');
	companyelement.setAttribute('name','company');
	companyelement.setAttribute('value',company);
	parentelement.after(companyelement);
}
</script>