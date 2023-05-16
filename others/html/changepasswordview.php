<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../css/changepassword.css">
        <title>パスワードの変更</title>
    </head>

    <body>
        <?php
            include("../header.php");
        ?>
    <br><br><br><br><br><br>
        <div class = 'box formsize'>
            <div class = "boxtitle"><?php echo $settextusername ?>のパスワードの変更</div>
            <div class = "boxcontent">
                <form action = 'changepassword.php' method = post class = 'formsize'>
                    <table class = 'inputtable'>
                        <a class = 'failfont'><?php echo $changesuccesstext; ?></a>
                        <tr>
                            <th>古いpassword</th>
                            <td><input type = 'text' name = 'oldpassword' value = "<?php echo $settextoldpassword ?>"></td>
                        </tr>
                        <tr>
                            <th>新しいpassword</th>
                            <td><input type = 'password' name = 'newpassword' value = "<?php echo $settextnewpassword ?>" placeholder="半角英数字をそれぞれ1種類以上含む8文字以上" class = 'passwordplaceholder'></td>
                        </tr>
                        <tr>
                            <th>新しいpassword(again)</th>
                            <td><input type = 'password' name = 'newpasswordagain' value = ""></td>
                        </tr>
                    </table>
                    <button type = 'submit' class = 'btn' name = 'change' value='変更'>変更</button>
                
                </form>
            </div>
    </div >



    </body>

</html>