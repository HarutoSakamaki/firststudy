
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../css/admin.css">
        <title>アウトソーサーの登録フォーム</title>
    </head>

    <body>
        <?php
            include("../header.php");
        ?>
        <br><br><br><br><br>
        <h2>管理者画面</h2>
        <div class = 'allbox'>

            <div class = 'addbox'>
                <h3>アカウントの追加</h3>
                <form action = 'admin.php' method = 'post' id = 'regiform'>
                    <?php echo $addaccountsuccesstext ;?>
                    <table class = 'addtable'>
                        <div class = 'success'><div>
                        <tr>
                            <th>username</th>
                            <td><input type = 'text' name = 'username' value = '<?php echo htmlspecialchars($settextaddusername) ?>' placeholder="半角英数字"><br>
                                <a class = 'failfont'><?php echo $usernamefailtext; ?></a>
                            <td>
                        </tr>
                        <tr>
                            <th>password</th>
                            <td><input type = 'password' name = 'password' placeholder="半角英数字をそれぞれ1種類以上含む8文字以上" class = 'passwordplaceholder' value = '<?php echo $settextpassword ?>'><br>
                                <a class = 'failfont'><?php echo $passwordfailtext; ?></a></td>
                        </tr>
                        <tr>
                            <th>password(again)</th>
                            <td><input type = 'password' name = 'passwordagain'><br>
                                <a class = 'failfont'><?php echo $passwordagainfailtext; ?></a><td>
                        </tr>
                        <tr>
                            <th>管理者として登録する</th>
                            <td><input type = 'radio' name = 'adminregi' value = 'はい'>はい  <input type = 'radio' name = 'adminregi' value = 'いいえ' checked>いいえ</td>
                        </tr>
                    </table>
                    <input type='submit' name = 'addaccount' value = '追加' class = 'commonbutton'>
                </form>

            </div>

            <div class = 'addbox'>
                <h3>アカウントの検索</h3>
                <a class = 'redfont'><?php echo htmlspecialchars($searchfailtext) ?></a>
                <form action = 'admin.php' method = 'post' id = 'regiform'>
                    <table class = 'addtable'>
                        <tr>
                            <th>username</th>
                            <td><input type = 'text' name = 'username'><td>
                        </tr>
                    </table>
                    <input type='submit' name = 'searchaccount' value = '検索する' class = 'commonbutton'>
                </form>

            </div>

        </div>
        <?php echo $searchtable?>


    </body>

</html>

<script>


	$(function(){
        //<form>タグのidを指定
        $("#regiform").validationEngine(
            'attach', {
                promptPosition: "topRight"//エラーメッセージ位置の指定
            }
        );
    });
    function deletecheck(){
        return window.confirm('本当に削除しますか');
    }

</script>