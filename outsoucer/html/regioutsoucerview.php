
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../css/regioutsoucer.css">
<title>アウトソーサーの登録フォーム</title>
</head>
<!-- header -->

<body>
<?php
  include("../header.php");
?>
<p>
</p>
<br><br><br><br><br><br>
<!-- <h2>登録フォーム</h2> -->

<p class = 'formclear'></p>
<div id = 'regiemployees' class = 'formsize box'>
	<div class = "boxtitle">アウトソーサーの登録</div>
	<div class = "boxcontent">
		<form action="regioutsoucer.php" method="post" id = "regiform">
			<p> 名前: <input type="text" name="name" class = "validate[required]" value ="<?php if(isset($_POST['addoutsoucer'])){echo htmlentities($_POST['name']);}?>"></p>
			
			<?php
				echo $daytext;
			?>
			<!-- <?php
				if($regiflag){
					echo <<<EOM
					<form action = 'changeoutsoucer.php' method = 'post'>
						<input type = 'submit' name = 'changeform' value = '詳細を設定する'>
						<input type = 'hidden' name = 'id' value = '{$newid}'>
					</form>
					EOM;
				}
			?> -->
			<p><button type="subit" name="addoutsoucer" class = "btn">従業員の登録</button></p>
			
			
		</form>
	</div>
</div>
<div>
	<?php
		if($regiflag){
			echo <<<EOM
			<p>登録できました</p>
			<form action = 'changeoutsoucer.php' method = 'post'>
				<input type = 'submit' name = 'changeform' value = '詳細を設定する'>
				<input type = 'hidden' name = 'id' value = '{$newid}'>
			</form>

			EOM;
		}
	?>
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


	$(function(){
        //<form>タグのidを指定
        $("#regiform").validationEngine(
            'attach', {
                promptPosition: "topRight"//エラーメッセージ位置の指定
            }
        );
    });

</script>