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
                    <?php echo $changesuccesstext; ?>
                    <table class = 'inputtable'>
                        <tr>
                            <th>古いpassword</th>
                            <td><input type = 'text' name = 'oldpassword' value = "<?php echo $settextoldpassword ?>"><br>
                            <a class = 'failfont'><?php echo $failoldpasswordtext; ?></a></td>
                        </tr>
                        <tr>
                            <th>新しいpassword</th>
                            <td><input type = 'password' name = 'newpassword' value = "<?php echo $settextnewpassword ?>" placeholder="半角英数字をそれぞれ1種類以上含む8文字以上" class = 'passwordplaceholder'>
                            <br><a class = 'failfont'><?php echo $failnewpasswordtext; ?></a>
                            </td>
                        </tr>
                        <tr>
                            <th>新しいpassword(again)</th>
                            <td><input type = 'passwordagain' name = 'newpasswordagain' value = ""><br>
                            <a class = 'failfont'><?php echo $failnewpasswordagaintext; ?></a></td>
                        </tr>
                    </table>
                    <button type = 'submit' class = 'btn' name = 'change' value='変更'>変更</button>
                
                </form>
            </div>
    </div >



    </body>

</html>