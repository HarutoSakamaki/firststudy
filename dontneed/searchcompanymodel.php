
 
 
 <?php
    require_once '../link.php';
    class Model{
        public $search = false;
        public $delete = false;
        protected $deletesuccess;
        protected $searchsuccess;
        protected $searchquery;
        protected $settextcompany;
        protected $settextminemployees;
        protected $settextmaxemployees;
        protected $settextminyear;
        protected $settextminmonth;
        protected $settextminday;
        protected $settextmaxyear;
        protected $settextmaxmonth;
        protected $settextmaxday;
        public $searcharray=array();
        public $formsearchquery;
        protected $postflag;

        public function value($postarray){
            $deletesuccess = false;
            $searchsuccess = false;
            $database = database('staff');
            $companyid = '';
            $searcharray = array();
            $postflag = false;
            if(isset($postarray['search']) or isset($postarray['delete'])){
                $postflag = true;
                if(isset($postarray['delete'])){
                    $companyid = $postarray['id'];
                    $searchquery = $postarray['searchquery'];
                    try{
                        $query = 'UPDATE company SET del = true WHERE company.id = \''.$companyid.'\'';
                        $database -> query($query);
                        $deletesuccess = true;
                    }catch(Exception $e){
                        echo "エラー発生:" . $e->getMessage().'<br>';
                        echo "削除できませんでした";
                    }
                    try{
                        $searchquery = $postarray['searchquery'];
                        $searchquery = formbackquery($searchquery);
                        $searchresult = $database -> query($searchquery);
                        $searchsuccess = true;
                        $searcharray = array();
                        while($row = mysqli_fetch_assoc($searchresult)){
                            $searcharray[] = array('id'=>$row['id'],'company'=>$row['company'],'numberofemployees'=>$row['numberofemployees'],'establishdate'=>$row['establishdate']);
                        }
                    }catch (Exception $e){
                        $searchsuccess = false;
                        echo "エラー発生:" . $e->getMessage().'<br>';
                        echo "取得できませんでした";
                    }
                }else if(isset($postarray['search'])){
                    $company = $postarray['searchcompany'];
                    $minemployees = $postarray['minemployees'];
                    $maxemployees = $postarray['maxemployees'];
                    $minestablish = $postarray['minyear'].'-'.$postarray['minmonth'].'-'.$postarray['minday'];
                    $maxestablish = $postarray['maxyear'].'-'.$postarray['maxmonth'].'-'.$postarray['maxday'];
                    if($company != ""){
                        $companyterms = ' company LIKE \'%'.$company.'%\' ';
                    }else{
                        $companyterms = ' company LIKE \'%\' ';
                    }
                    if($maxemployees == ''){
                        $employeesterms = ' AND numberofemployees >= '.$minemployees.' ';
                    }else{
                        $employeesterms = ' AND numberofemployees BETWEEN '.$minemployees.' and '.$maxemployees;
                    }
                    $establishterms = ' AND establishdate BETWEEN DATE(\''.$minestablish.'\') and DATE(\''.$maxestablish.'\') ';
                    try{
                        $query = 'SELECT * FROM company WHERE '.$companyterms.$employeesterms.$establishterms.' AND del = false ORDER BY numberofemployees DESC';
                        /* echo $query; */
                        $searchresult = $database -> query($query);
                        $searchquery = $query;
                        $searchsuccess = true;
                        $searcharray = array();
                        while($row = mysqli_fetch_assoc($searchresult)){
                            $searcharray[] = array('id'=>$row['id'],'company'=>$row['company'],'numberofemployees'=>$row['numberofemployees'],'establishdate'=>$row['establishdate']);
                        }
                    }catch(Exception $e){
                        echo "エラー発生:" . $e->getMessage().'<br>';
                        echo "検索できませんでした";
                        $searchsuccess = false;
                        $searcharray = array();
                    }
                }
                $settextcompany = $postarray['searchcompany'];
                $settextminemployees = $postarray['minemployees'];
                $settextmaxemployees = $postarray['maxemployees'];
                $settextminyear = $postarray['minyear'];
                $settextminmonth = $postarray['minmonth'];
                $settextminday = $postarray['minday'];
                $settextmaxyear = $postarray['maxyear'];
                $settextmaxmonth = $postarray['maxmonth'];
                $settextmaxday = $postarray['maxday'];
            }else{
                $settextcompany = '';
                $settextminemployees = '0';
                $settextmaxemployees = '';
                $settextminyear = '0';
                $settextminmonth = '1';
                $settextminday = '1';
                $settextmaxyear = date('Y');
                $settextmaxmonth = '12';
                $settextmaxday = '12';
                $searchquery = '';
                $searcharray = ['空のarray'];
            }
            
            $this->search = isset($postarray['search']);
            $this->delete = isset($postarray['delete']);
            $this->searchsuccess = $searchsuccess;
            $this->deletesuccess = $deletesuccess;
            $this->searchquery = $searchquery;
            $this->settextcompany = $settextcompany;
            $this->settextminemployees = $settextminemployees;
            $this->settextmaxemployees = $settextmaxemployees;
            $this->settextminyear = $settextminyear;
            $this->settextminmonth = $settextminmonth;
            $this->settextminday = $settextminday;
            $this->settextmaxyear = $settextmaxyear;
            $this->settextmaxmonth = $settextmaxmonth;
            $this->settextmaxday = $settextmaxday;
            $this->searcharray = $searcharray;
            $this->formsearchquery = formquery($searchquery);
            $this->postflag = $postflag;
        }
        public function getdeletesuccess(){
            return $this->deletesuccess;
        }
        public function getsearchsuccess(){
            return $this->searchsuccess;
        }
        public function getsearchquery(){
            return $this->searchquery;
        }
        public function getsettextcompany(){
            return $this->settextcompany;
        }
        public function getsettextminemployees(){
            return $this->settextminemployees;
        }
        public function getsettextmaxemployees(){
            return $this->settextmaxemployees;
        }
        public function getsettextminyear(){
            return $this->settextminyear;
        }
        public function getsettextminmonth(){
            return $this->settextminmonth;
        }
        public function getsettextminday(){
            return $this->settextminday;
        }
        public function getsettextmaxyear(){
            return $this->settextmaxyear;
        }
        public function getsettextmaxmonth(){
            return $this->settextmaxmonth;
        }
        public function getsettextmaxday(){
            return $this->settextmaxday;
        }
        public function getsearch(){
            if($this->search==true){
                return true;
            }else{
                return false;
            }
        }
        public function getdelete(){
            if($this->delete==false){
                return true;
            }else{
                return false;
            }
        }
        public function getformsearchquery(){
            return $this->$formsearchquery;
        }
        public function getsearcharray(){
            return $this->$searcharray;
        }
        public function getpostflag(){
            return $this->postflag;
        }
    }
 
 ?>