<?php
    include ('../../../meet/con.php');
    $connection=$conn;
    class Satisfaction{
        private $connect;
        public function __construct() {
    		global $connection;
    		$this->connect=$connection;
    	}

        function Rating(){
            $category = $_POST['category'];
            $splz = $_POST['splz_id'];
            $stu = $_POST['stu'];
            $changes = 0;
            
            //get academic year
            $getAcad = $this->connect->prepare("SELECT acad_cycle_id FROM tbl_acad_cycle WHERE status=1");
            $getAcad->execute();
            $acad=$getAcad->fetch();
            $acad_cycle_id=$acad['acad_cycle_id'];
            
            //get additional info
            $getMode = $this->connect->prepare("SELECT ug.prg_mode_id, 
                                                        ug.splz_id, 
                                                        ug.level_id, 
                                                        ad.intake_id 
                                                    FROM tbl_register_program_ug ug
                                                        INNER JOIN tbl_admission ad ON ug.reg_no = ad.reg_no
                                                    WHERE ug.reg_no='".$stu."' AND 
                                                        ug.reg_active=1 
                                                        ORDER BY ug.reg_prg_id DESC LIMIT 1
                                                    ");
            $getMode->execute();
            $mode=$getMode->fetch();
            $mode_id=$mode['prg_mode_id'];
            $splz_id=$mode['splz_id'];
            $level_id=$mode['level_id'];
            $intake_id=$mode['intake_id'];
            
            $getAns = $this->connect->prepare("SELECT * FROM tbl_student_satisfaction WHERE stu = ? AND category_id = ? AND splz_id = ? AND intake_id = ? AND level_id = ? ");
            $getAns->execute([$stu, $category, $splz_id, $intake_id, $level_id]);
            if($getAns->rowCount()>0){
                $data = array("status"=>"401","message" => "You have submitted your answers before!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
                return;
            }
            foreach($_POST['questions'] as $qn){
                try{
                    $answer = $_POST['answer_'.$qn];
                    $insert = $this->connect->prepare("INSERT INTO tbl_student_satisfaction(stu, splz_id, level_id, mode_id, acad_cycle_id, intake_id, category_id, question_id, answer_id) 
                              	VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");

                    if($insert->execute([$stu, $splz_id, $level_id, $mode_id, $acad_cycle_id, $intake_id, $category, $qn, $answer])){
                        $changes++;
                    }
                }catch(PDOException $e){
                    $data = array("status"=>"500","message" => "Something went wrong!");
                    $jsonData = json_encode($data);
                    header('Content-Type: application/json');
                    echo $jsonData;   
                }
            }
            if($changes>0){
                $data = array("status"=>"200");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
            else{
                $data = array("status"=>"401","message" => "Failed to save ratings!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
        }
        
    }	
	$satisfaction = new Satisfaction();
    $action = $_POST['action'];
	switch($action){
	    case 'rating':
	        $satisfaction->Rating();
	        break;
	}

	?>

