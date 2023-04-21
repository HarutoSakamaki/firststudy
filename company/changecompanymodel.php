
<?php
    require_once '../link.php';
    class Model{
        protected $id;
        protected $settextcompany;
        protected $settextpresident;
        protected $settextbusinessdetails;
        protected $settextprefectures;
        protected $settextlocation;
        protected $settextnumberofemployees;
        protected $settextestablishyear;
        protected $settextestablishmonth;
        protected $settextestablishday;
        protected $settexthomepage;

        public function value($postarray){
            $database = database('staff');
            if(isset($postarray['change'])){
                $id = $postarray['id'];
                $company = $postarray['company'];
                $changecompany = ' company = \'' . $postarray['company'].'\' ';
                $changepresident = ' president = \'' .$postarray['president'].'\'';
                $changeprefectures = ' prefectures = \''.$postarray['prefectures'].'\' ';
                $changelocation = ' location = \'' .$postarray['location'].'\'';
                $changenumberofemployees = ' numberofemployees = \'' .$postarray['numberofemployees'].'\'';
                $changeestablishdate = ' establishdate = \'' . $postarray['establishyear'].'-'.$postarray['establishmonth'].'-'.$postarray['establishday'].'\'';
                $changehomepage = ' homepage = \'' .$postarray['homepage'].'\'';

                $businessdetailsstack = array();
                $count = 0;
                while(isset($postarray['businessdetails'.$count])){
                    if($postarray['businessdetails'.$count]!=''){
                        $businessdetailsstack[] = $postarray['businessdetails'.$count];
                    }
                    $count++;
                }
                $businessdetailsjson = json_encode($businessdetailsstack, JSON_UNESCAPED_UNICODE);
                $changebusinessdetails = ' businessdetails = \'' .$businessdetailsjson.'\'';
                $changechangedate = ' changedate = \''.date('Y-m-d').'\'';
                $changequery = "UPDATE company SET ".$changecompany. ','.$changepresident. ','.$changeprefectures.','.$changelocation. ','
                    .$changenumberofemployees. ','.$changeestablishdate. ',' .$changehomepage. ','.$changebusinessdetails. ','.$changechangedate.
                    ' WHERE del = false AND id = \''.$id.'\'';

                /* echo $changequery; */
                /* ここから入力規則のチェック */
                $numberofemployeesflag = false;
                if($postarray['numberofemployees'] == '' or preg_match("/^[0-9]+$/", $postarray['numberofemployees'])){
                    $numberofemployeesflag = true;
                }
                if($numberofemployeesflag){
                    try{
                        $database -> query($changequery);
                        echo '変更できました<br>';
                    }catch(Exception $e){
                        echo "エラー発生:" . $e->getMessage().'<br>';
                        echo "  更新できませんでした。";
                    }
                }else{
                    echo '有効な値を入力してください';
                }
            }
            if(isset($postarray['changeform'])){
                $id = $postarray['id'];
                $company = $postarray['company'];
                try{
                    $query = "SELECT * FROM company WHERE del = false AND id = '".$id."'";
                    $result = $database -> query($query);
                    $row = mysqli_fetch_assoc($result);
                    echo '詳細を取得しました';
                }catch(Exception $e){
                    echo "エラー発生:" . $e->getMessage().'<br>';
                    echo "  詳細を取得できませんでした。";
                }
                $establishdatearray = explode('-', $row['establishdate']);
                $establishyear = $establishdatearray[0];
                $establishmonth = $establishdatearray[1];
                $establishday = $establishdatearray[2];
                $businessdetails = json_decode($row['businessdetails'],true);
                $postflag = false;
                $settextcompany = $row['company'];
                $settextpresident = $row['president'];
                $settextcompany = $row['company'];
                $settextbusinessdetails = $businessdetails;
                $settextprefectures = $row['prefectures'];
                $settextlocation = $row['location'];
                $settextnumberofemployees = $row['numberofemployees'];
                $settextestablishyear = $establishdatearray[0];
                $settextestablishmonth = $establishdatearray[1];
                $settextestablishday = $establishdatearray[2];
                $settexthomepage = $row['homepage'];
            }
            if(isset($postarray['change'])){
                $postflag = true;
                $settextcompany = $postarray['company'];
                $settextpresident = $postarray['president'];
                $settextbusinessdetails = array();
                $count = 0;
                while(true){
                    if(isset($postarray['businessdetails'.$count])){
                        $settextbusinessdetails[] = $postarray['businessdetails'.$count];
                        $count++;
                    }else{
                        break;
                    }
                }
                $settextprefectures = $postarray['prefectures'];
                $settextlocation = $postarray['location'];
                $settextnumberofemployees = $postarray['numberofemployees'];
                $settextestablishyear = $postarray['establishyear'];
                $settextestablishmonth = $postarray['establishmonth'];
                $settextestablishday = $postarray['establishday'];
                $settexthomepage = $postarray['homepage'];
            }
            $this->id = $id;
            $this->settextcompany = $settextcompany;
            $this->settextpresident = $settextpresident;
            $this->settextbusinessdetails = $settextbusinessdetails;
            $this->settextprefectures = $settextprefectures;
            $this->settextlocation = $settextlocation;
            $this->settextnumberofemployees = $settextnumberofemployees;
            $this->settextestablishyear = $settextestablishyear;
            $this->settextestablishmonth = $settextestablishmonth;
            $this->settextestablishday = $settextestablishday;
            $this->settexthomepage = $settexthomepage;
        }
        public function getid(){
            return $this->id;
        }
        public function getsettextcompany(){
            return $this->settextcompany;
        }
        public function getsettextpresident(){
            return $this->settextpresident;
        }
        public function getsettextbusinessdetails(){
            return $this->settextbusinessdetails;
        }
        public function getsettextprefectures(){
            return $this->settextprefectures;
        }
        public function getsettextlocation(){
            return $this->settextlocation;
        }
        public function getsettextnumberofemployees(){
            return $this->settextnumberofemployees;
        }
        public function getsettextestablishyear(){
            return $this->settextestablishyear;
        }
        public function getsettextestablishmonth(){
            return $this->settextestablishmonth;
        }
        public function getsettextestablishday(){
            return $this->settextestablishday;
        }
        public function getsettexthomepage(){
            return $this->settexthomepage;
        }
    }
?>
