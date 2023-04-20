


<?php
    function database($databasename){
		$link = new mysqli('localhost', 'user', '', $databasename);

		// 接続状況をチェックします
		if (mysqli_connect_errno()) {
			die("データベースにアクセスできません。少し時間をおいてアクセスして下さい" . mysqli_connect_error() . "\n");
		} 
		return $link;
	}
    function datein($indate , $firstdatename , $postname){
        $postnameflag = false;
        foreach($postname as $value){
            if(isset($_POST[$value])){
                $postnameflag = true;
            }
        }
        $minyearset = 0;
        $minmonthset = 1;
        $mindayset = 1;
        $maxyearset = date('Y');
        $maxmonthset = 12;
        $maxdayset = 31;
        if($postnameflag==true){
            $minyearset = $_POST[$firstdatename.'minyear'];
            $minmonthset = $_POST[$firstdatename.'minmonth'];
            $mindayset = $_POST[$firstdatename.'minday'];
            $maxyearset = $_POST[$firstdatename.'maxyear'];
            $maxmonthset = $_POST[$firstdatename.'maxmonth'];
            $maxdayset = $_POST[$firstdatename.'maxday'];
        }else{
            $minyearset = 0;
            $minmonthset = 1;
            $mindayset = 1;
            $maxyearset = date('Y');
            $maxmonthset = 12;
            $maxdayset = 31;
        }
        echo '<p>'.$indate.':<select id = \'input'.$firstdatename.'minyear\' name=\''.$firstdatename.'minyear\'>'. "\n".
            '<option value = \''.$minyearset.'\' >'.$minyearset.'</option>\n';
        for($i = date('Y'); $i >= 0; $i--) {
            echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
        }
        echo '</select>年' . "\n";
        echo '<select id = \'input'.$firstdatename.'minmonth\' name=\''.$firstdatename.'minmonth\' >' . "\n".
            '<option value = \''.$minmonthset.'\' >'.$minmonthset.'</option>\n';
        for ($i = 1; $i <= 12; $i++) {
            echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
        }
        echo '</select>月' . "\n";
        echo '<select id = \'input'.$firstdatename.'minday\' name=\''.$firstdatename.'minday\'>' . "\n".
            '<option value = \''.$mindayset.'\' >'.$mindayset.'</option>\n';
        for ($i = 1; $i <= 31; $i++) {
            echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
        }
        echo '</select>日~' ;
        echo '<select id = \'input'.$firstdatename.'maxyear\' name=\''.$firstdatename.'maxyear\'>'. "\n".
            '<option value = \''.$maxyearset.'\' >'.$maxyearset.'</option>\n';
        for($i = date('Y'); $i >= 0; $i--) {
            echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
        }
        echo '</select>年' . "\n";
        echo '<select id = \'input'.$firstdatename.'maxmonth\' name=\''.$firstdatename.'maxmonth\'>' . "\n".
            '<option value = \''.$maxmonthset.'\' >'.$maxmonthset.'</option>\n';
        for ($i = 1; $i <= 11; $i++) {
            echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
        }
        echo '</select>月' . "\n";
        echo '<select id = \'input'.$firstdatename.'maxday\' name=\''.$firstdatename.'maxday\'>' . "\n".
            '<option value = \''.$maxdayset.'\' >'.$maxdayset.'</option>\n';
        for ($i = 1; $i <= 30; $i++) {
            echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
        }
        echo '</select>日</p>' . "\n";
    }
    function uuid(){
        return preg_replace_callback(
            '/x|y/',
            function($m) {
              return dechex($m[0] === 'x' ? random_int(0, 15) : random_int(8, 11));
            },
            'xxxxxxxx_xxxx_4xxx_yxxx_xxxxxxxxxxxx'
        );
    }
    function formquery($searchquery){
        $searchquery = str_replace('>', '大なり', $searchquery);
        $searchquery = str_replace('\'', 'シングルクオーテーション', $searchquery);
        return $searchquery;
    }
    function formbackquery($searchquery){
        $searchquery = str_replace('大なり', '>', $searchquery);
        $searchquery = str_replace('シングルクオーテーション', '\'', $searchquery);
        return $searchquery;
    }

    
?>