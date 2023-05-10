


<?php
    function database($databasename){
		$link = new mysqli('localhost', 'user', '', $databasename);

		// 接続状況をチェックします
		if (mysqli_connect_errno()) {
			die("データベースにアクセスできません。少し時間をおいてアクセスして下さい" . mysqli_connect_error() . "\n");
		} 
		return $link;
	}
    
    function datein($indate , $firstdatename , $postname , $initialvalue = [0,1,1,123456,12,31],$minminyear = 0){
        $postnameflag = false;
        foreach($postname as $value){
            if(isset($_POST[$value])){
                $postnameflag = true;
            }
        }
        $minyearset = $initialvalue[0];
        $minmonthset = $initialvalue[1];
        $mindayset = $initialvalue[2];
        if(isset($initialvalue[3])){
            $maxyearset = date('Y');
        }
        $maxmonthset = $initialvalue[4];
        $maxdayset = $initialvalue[5];
        if($postnameflag==true){
            $minyearset = $_POST[$firstdatename.'minyear'];
            $minmonthset = $_POST[$firstdatename.'minmonth'];
            /* $mindayset = $_POST[$firstdatename.'minday']; */
            $maxyearset = $_POST[$firstdatename.'maxyear'];
            $maxmonthset = $_POST[$firstdatename.'maxmonth'];
            /* $maxdayset = $_POST[$firstdatename.'maxday']; */
        }else{
            $minyearset = 0;
            $minmonthset = 1;
            /* $mindayset = 1; */
            $maxyearset = date('Y');
            $maxmonthset = 12;
            /* $maxdayset = 31; */
        }
        echo '<p>'.$indate.':<select id = \'input'.$firstdatename.'minyear\' name=\''.$firstdatename.'minyear\'>'. "\n".
            '<option value = \''.$minyearset.'\' >'.$minyearset.'</option>\n';
        for($i = date('Y'); $i >= $minminyear; $i--) {
            echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
        }
        echo '</select>年' . "\n";
        echo '<select id = \'input'.$firstdatename.'minmonth\' name=\''.$firstdatename.'minmonth\' >' . "\n".
            '<option value = \''.$minmonthset.'\' >'.$minmonthset.'</option>\n';
        for ($i = 1; $i <= 12; $i++) {
            echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
        }
        echo '</select>月' . "\n";
        /* echo '<select id = \'input'.$firstdatename.'minday\' name=\''.$firstdatename.'minday\'>' . "\n".
            '<option value = \''.$mindayset.'\' >'.$mindayset.'</option>\n';
        for ($i = 1; $i <= 31; $i++) {
            echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
        }
        echo '</select>日~' ; */
        echo '~';
        echo '<select id = \'input'.$firstdatename.'maxyear\' name=\''.$firstdatename.'maxyear\'>'. "\n".
            '<option value = \''.$maxyearset.'\' >'.$maxyearset.'</option>\n';
        for($i = date('Y'); $i >= $minminyear; $i--) {
            echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
        }
        echo '</select>年' . "\n";
        echo '<select id = \'input'.$firstdatename.'maxmonth\' name=\''.$firstdatename.'maxmonth\'>' . "\n".
            '<option value = \''.$maxmonthset.'\' >'.$maxmonthset.'</option>\n';
        for ($i = 1; $i <= 11; $i++) {
            echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
        }
        echo '</select>月' . "\n";
        /* echo '<select id = \'input'.$firstdatename.'maxday\' name=\''.$firstdatename.'maxday\'>' . "\n".
            '<option value = \''.$maxdayset.'\' >'.$maxdayset.'</option>\n';
        for ($i = 1; $i <= 30; $i++) {
            echo '<option value="' .$i . '">' . $i .'</option>'. "\n";
        }
        echo '</select>日</p>' . "\n"; */
        echo '</p>';
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

    function selectpref(){
        $prefarray = ['北海道','青森県','岩手県','宮城県','秋田県','山形県','福島県','茨城県','栃木県','群馬県','埼玉県','千葉県','東京都'
            ,'神奈川県','新潟県','富山県','石川県','福井県','山梨県','長野県','岐阜県','静岡県','愛知県','三重県','滋賀県','京都府','大阪府'
            ,'兵庫県','奈良県','和歌山県','鳥取県','島根県','岡山県','広島県','山口県','徳島県','香川県','愛媛県','高知県','福岡県','佐賀県','長崎県'
            ,'熊本県','大分県','宮崎県','鹿児島県','沖縄県'];

        foreach($prefarray as $key => $pref){
            echo '<option value = '.$key.'>'.$pref.'</option>\n';
        }

    }
    function getpref($number){
        $prefarray = ['北海道','青森県','岩手県','宮城県','秋田県','山形県','福島県','茨城県','栃木県','群馬県','埼玉県','千葉県','東京都'
            ,'神奈川県','新潟県','富山県','石川県','福井県','山梨県','長野県','岐阜県','静岡県','愛知県','三重県','滋賀県','京都府','大阪府'
            ,'兵庫県','奈良県','和歌山県','鳥取県','島根県','岡山県','広島県','山口県','徳島県','香川県','愛媛県','高知県','福岡県','佐賀県','長崎県'
            ,'熊本県','大分県','宮崎県','鹿児島県','沖縄県'];
        return $prefarray[$number];
    }

    
?>