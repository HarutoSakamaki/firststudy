

<?php
    require_once '../link.php';
    class Model{
        protected $post = array();
		protected $database = 0;
        protected $emptyfield;
        protected $regisuccess;
        protected $addoutsoucer;
        protected $settextcompanyname;
        protected $settextnumberofemployees;
        protected $settextyear;
        protected $settextmonth;
        protected $settextday;
        public function value($postarray){
            $this->database= database('staff');
            $database = $this ->database;
            if(isset($postarray['addcompany'])){
                $addoutsoucer = true;
                $regisuccess =  false;
                /* 入力規則チェック */
                $numberofemployeesflag = false;
                if($postarray['numberofemployees'] == '' or preg_match("/^[0-9]+$/", $postarray['numberofemployees'])){
                    $numberofemployeesflag = true;
                }
                if($postarray['companyname']==''or$postarray['year']==''or$postarray['month']==''or$postarray['day']==''or$postarray['numberofemployees']==''){
                    $emptyfield = true;
                }else{
                    $emptyfield = false;
                    $regidate = $postarray['year'].'-'.$postarray['month'].'-'.$postarray['day'];
                    $info = '\''.$postarray['companyname'].'\',\''.$regidate.'\',\''.$postarray['numberofemployees'].'\'';
                    if($numberofemployeesflag == true){
                        try{
                            $numberringquery = "UPDATE numberring SET id = LAST_INSERT_ID(id + 1) WHERE tablename = 'company'";
                            $database -> query($numberringquery);
                            $numberringquery = 'SELECT id FROM numberring where tablename = \'company\' ';
                            $numberringid = mysqli_fetch_assoc($database -> query($numberringquery));
                            $info = '\''.$numberringid['id'].'\',\''.$_POST['companyname'].'\',\''.$regidate.'\',\''.$_POST['numberofemployees'].'\'';
                            $query = "INSERT INTO company (id , company , establishdate, numberofemployees)VALUES(".$info.")";
                            $database -> query($query);
                            $regisuccess = true;
                        }catch (Exception $e){
                            $regisuccess = false;
                            echo "エラー発生:" . $e->getMessage().'<br>';
                        }
                    }else{

                        /* echo '有効な値を入力して下さい'; */
                    }
                }
                $settextcompanyname = $postarray['companyname'];
                $settextnumberofemployees = $postarray['numberofemployees'];
                $settextyear = $postarray['year'];
                $settextmonth = $postarray['month'];
                $settextday = $postarray['day'];
            }else{
                $addoutsoucer=false;
                $emptyfield = false;
                $regisuccess = false;
                $settextcompanyname = '';
                $settextnumberofemployees = '';
                $settextyear = '1';
                $settextmonth = '1';
                $settextday = '1';
            }
            $this->addoutsoucer=$addoutsoucer;
            $this->emptyfield = $emptyfield;
            $this->regisuccess = $regisuccess;
            $this->settextcompanyname = $settextcompanyname;
            $this->settextnumberofemployees=$settextnumberofemployees;
            $this->settextyear = $settextyear;
            $this->settextmonth=$settextmonth;
            $this->settextday=$settextday;
        }
        public function getaddoutsoucer(){
            return $this->addoutsoucer;
        }
        public function getemptyfield(){
            return $this->emptyfield;
        }
        public function getregisuccess(){
            return $this->regisuccess;
        }
        public function getsettextcompanyname(){
            return $this->settextcompanyname;
        }
        public function getsettextnumberofemployees(){
            return $this->settextcompanyname;
        }
        public function getsettextyear(){
            return $this->settextyear;
        }
        public function getsettextmonth(){
            return $this->settextmonth;
        }
        public function getsettextday(){
            return $this->settextday;
        }
    }


?>