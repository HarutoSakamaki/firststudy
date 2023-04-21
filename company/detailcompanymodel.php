<?php
    require_once '../link.php';
    class Model{
        protected $id;
        protected $detailsuccess;
        protected $company;
        protected $establishyear;
        protected $establishmonth;
        protected $establishday;
        protected $businessdetailsarray = array();
        protected $president;
        protected $prefectures;
        protected $location;
        protected $numberofemployees;
        protected $homepage;
        

        public function value($postarray){
            $database = database('staff');
            $detailsuccess = false;


            if(isset($postarray['detail'])){
                $id = $postarray['id'];
                try{
                    $query = "SELECT * FROM company WHERE del = false AND id = ".$id;
                    $result = $database -> query($query);
                    $row = mysqli_fetch_assoc($result);
                    $detailsuccess = true;
                    $establishdatearray = explode('-', $row['establishdate']);
                    $establishyear = $establishdatearray[0];
                    $establishmonth = $establishdatearray[1];
                    $establishday = $establishdatearray[2];
                    $businessdetailsarray = json_decode($row['businessdetails'],true);
                    $company = $row['company'];
                    $president = $row['president'];
                    $prefectures = $row['prefectures'];
                    $location = $row['location'];
                    $numberofemployees = $row['numberofemployees'];
                    $homepage = $row['homepage'];
                }catch(Exception $e){
                    echo "エラー発生:" . $e->getMessage().'<br>';
                    $detailsuccess = false;
                }
                $this->id = $id;
                $this->detailsuccess = $detailsuccess;
                $this->company = $company;
                $this->establishyear = $establishyear;
                $this->establishmonth = $establishmonth;
                $this->establishday = $establishday;
                $this->businessdetailsarray = $businessdetailsarray;
                $this->president = $president;
                $this->prefectures = $prefectures;
                $this->location = $location;
                $this->numberofemployees = $numberofemployees;
                $this->homepage = $homepage;
            }else{
                $this->id = '';
                $this->detailsuccess = $detailsuccess;
                $this->company = '';
                $this->establishyear = '';
                $this->establishmonth = '';
                $this->establishday = '';
                $this->businessdetailsarray = array();
                $this->president = '';
                $this->prefectures = '';
                $this->location = '';
                $this->numberofemployees = '';
                $this->homepage = '';
            }
        }
        public function getid(){
            return $this->id;
        }
        public function getcompany(){
            return $this->company;
        }
        public function detailsuccess(){
            return $this->detailsuccess;
        }
        public function getestablishyear(){
            return $this->establishyear;
        }
        public function getestablishmonth(){
            return $this->establishmonth;
        }
        public function getestablishday(){
            return $this->establishday;
        }
        public function getbusinessdetailsarray(){
            return $this->businessdetailsarray;
        }
        public function getpresident(){
            return $this->president;
        }
        public function getprefectures(){
            return $this->prefectures;
        }
        public function getlocation(){
            return $this->location;
        }
        public function getnumberofemployees(){
            return $this->numberofemployees;
        }
        public function gethomepage(){
            return $this->homepage;
        }
    }

?>