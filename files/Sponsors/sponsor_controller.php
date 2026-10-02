<?php

include ('../../meet/con.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$connection=$conn;
class Sponsor{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }
	//send feedback
	function sendFeedback($s,$m){
        $data = array("status"=>$s,"message" =>$m);
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
	}
	//sponsor category
    function save_sponsor_cat(){
        $name=$_POST['spon_cat_name'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_sponsor_category WHERE spon_cat_name='".$name."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $this->sendFeedback(401,"category already exists!");
        } else {
            $stmt = $this->connect->prepare("INSERT INTO tbl_sponsor_category(spon_cat_name) 
                  	VALUES('".$name."')");
            if($stmt->execute()){
                $this->sendFeedback(200,"Data saved successfully!");
            } else {
                $this->sendFeedback(500,"Failed to save data!");
            }
        }
	}
	
    function view_sponsor_cat(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_sponsor_category WHERE spon_cat_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
    
    function view_sponsorship(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT s.*,f.name as fee_name FROM tbl_stu_sponsor s INNER JOIN fee_category f ON s.fee_id=f.id WHERE s.stu_sponsor_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData;    
    }
    
    function update_sponsor_cat(){
        $id=$_POST['spon_cat_id'];
        $name=$_POST['spon_cat_name'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_sponsor_category WHERE spon_cat_name='".$name."' AND spon_cat_id!='".$id."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "category already exists!");
            $this->sendFeedback(401,"category already exists!");   
        } else {
        $stmt = $this->connect->prepare("UPDATE tbl_sponsor_category SET spon_cat_name='".$name."' WHERE spon_cat_id='".$id."'");
        if($stmt->execute()){
                $this->sendFeedback(200,"category updated successfully");    
            } else {
                $this->sendFeedback(500,"Failed to update category");   
            }
        }
    }
     	function delete_sponsor_cat(){
             $id = $_POST['id'];
             $stmt = $this->connect->prepare("SELECT * FROM tbl_sponsor_category WHERE spon_cat_id='".$id."'");
             $stmt->execute();
             $prevData=$stmt->fetch();
             if($prevData['status']==1){
                    $status=2;
                    }
             else{
                    $status=1;
             }
                $stmt2 = $this->connect->prepare("UPDATE tbl_sponsor_category SET status='".$status."' WHERE spon_cat_id='".$id."'");
             if($stmt2->execute()){
                $this->sendFeedback(500,"operation done successfully");   
             } else {
                    $this->sendFeedback(500,"operation failed");    
                 }
             }
	
	//sponsors
    function save_sponsor(){
        $full_name=$_POST['spon_full_name'];
        $short_name=$_POST['spon_short_name'];
        $spon_cat_id=$_POST['spon_cat_id'];
        $email=$_POST['sponsor_email'];
        $phone= str_replace(' ', '', $_POST['phone']);
        $address=$_POST['address'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_sponsor WHERE spon_full_name='".$full_name."' AND spon_cat_id='".$spon_cat_id."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $this->sendFeedback(401,"Sponsor already exists!");    
        } else {
            $stmt = $this->connect->prepare("INSERT INTO tbl_sponsor(spon_cat_id,spon_full_name,spon_short_name,address,spon_email,phone) 
                  	VALUES('".$spon_cat_id."','".$full_name."','".$short_name."','".$address."','".$email."','".$phone."')");
            if($stmt->execute()){
                $this->sendFeedback(200,"Data saved successfully!");    
            } else {
                $this->sendFeedback(500,"Failed to save data!");    
            }
        }
	}
	
    function view_sponsor(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_sponsor WHERE spon_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
    
    function update_sponsor(){
        $id=$_POST['s_id'];
        $full_name=$_POST['s_spon_full_name'];
        $short_name=$_POST['s_spon_short_name'];
        $spon_cat_id=$_POST['s_spon_cat_id'];
        $email=$_POST['sponsor_email'];
        $phone=$_POST['s_phone'];
        $address=$_POST['s_address'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_sponsor WHERE spon_full_name='".$full_name."' AND spon_cat_id='".$spon_cat_id."' AND spon_id!='".$id."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $this->sendFeedback(401,"sponsor already exists!");  
        } else {
        $stmt = $this->connect->prepare("UPDATE tbl_sponsor SET spon_full_name='".$full_name."',
                                                                spon_short_name='".$short_name."',
                                                                spon_cat_id='".$spon_cat_id."',
                                                                spon_email='".$email."',
                                                                phone='".$phone."',
                                                                address='".$address."'
                                                                WHERE spon_id='".$id."'");
        if($stmt->execute()){
            $this->sendFeedback(200,"sponsor updated successfully!");  
            } else {
                $this->sendFeedback(500,"Failed to update sponsor");  
            }
        }
    }
    	function delete_sponsor(){
            $id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_sponsor WHERE spon_id='".$id."'");
            $stmt->execute();
            $prevData=$stmt->fetch();
            if($prevData['status']==1){
                $stmt2 = $this->connect->prepare("UPDATE tbl_sponsor SET status=2 WHERE spon_id='".$id."'");
            }
            else{
                $stmt2 = $this->connect->prepare("UPDATE tbl_sponsor SET status=1 WHERE spon_id='".$id."'");  
            }
            
            if($stmt2->execute()){
                $this->sendFeedback(200,"operation done successfully!");  
            } else {
                $this->sendFeedback(500,"operation failed!");  
                }
        }
        
        function registerSponsor() {
            $reg_no = $_POST['stu'];
            $sponsor = $_POST['sponsor'];
            $sponsor_percentage = $_POST['sponsorship_percentage'];
            $changes = 0;
        
            try {
                foreach ($_POST['fee'] as $fee) {
                    $stmt = $this->connect->prepare("SELECT * FROM tbl_stu_sponsor WHERE reg_no = :reg_no AND fee_id = :fee_id AND status = 1");
                    $stmt->execute([':reg_no' => $reg_no, ':fee_id' => $fee]);
                    if ($stmt->rowCount() > 0) {
                        // Sponsor data already exists
                    } else {
                        $stmt0 = $this->connect->prepare("INSERT INTO tbl_stu_sponsor (reg_no, sponsor_id, sponsor_percentage, fee_id) VALUES (:reg_no, :sponsor_id, :sponsor_percentage, :fee_id)");
                        $stmt0->execute([
                            ':reg_no' => $reg_no,
                            ':sponsor_id' => $sponsor,
                            ':sponsor_percentage' => $sponsor_percentage,
                            ':fee_id' => $fee
                        ]);
                        $changes++;
                    }
                }
        
                if ($changes > 0) {
                    $this->sendFeedback(200, "Data saved successfully!");
                } else {
                    $this->sendFeedback(401, "There is active sponsor data!");
                }
            } catch (PDOException $e) {
                $this->sendFeedback(401, "Failed to save data! Error: " . $e->getMessage());
            }
        }
        
        function update_sponsorship(){
            $id=$_POST['stu_sponsor_id'];
            $sponsor=$_POST['sponsor'];
            $sponsor_percentage=$_POST['sponsor_percentage'];
            $stmt = $this->connect->prepare("UPDATE tbl_stu_sponsor SET sponsor_percentage='".$sponsor_percentage."' WHERE stu_sponsor_id='".$id."' ");
            if($stmt->execute()){
                $this->sendFeedback(200,"sponsor updated successfully!");
                } else {
                    $this->sendFeedback(401,"Failed to update sponsor");  
                }
        }
        
    	function manage_sponsorship(){
            $id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_stu_sponsor WHERE stu_sponsor_id='".$id."'");
            $stmt->execute();
            $prevData=$stmt->fetch();
            $status=$prevData['status']==1?2:1;
            $stmt2 = $this->connect->prepare("UPDATE tbl_stu_sponsor SET status='".$status."' WHERE stu_sponsor_id='".$id."'");
            if($stmt2->execute()){
                $this->sendFeedback(200,"operation done successfully!");  
            } else {
                $this->sendFeedback(500,"operation failed!");  
                }
        }
        function show_student_fee() {
    $reg_no = $_POST['reg_no'];

    $select_first = "SELECT * FROM tbl_register_program_ug WHERE reg_no = :reg_no";
    $cselect_first = $this->connect->prepare($select_first);
    $cselect_first->execute([':reg_no' => $reg_no]);
    $row_cselect_first = $cselect_first->fetch(PDO::FETCH_ASSOC);

    $camp_id = $row_cselect_first['camp_id'];
    $prg_type_id = $row_cselect_first['prg_type'];
    $fac_id = $row_cselect_first['fac_id'];
    $dept_id = $row_cselect_first['dept_id'];
    $splz_id = $row_cselect_first['splz_id'];
    $level_id = $row_cselect_first['level_id'];

    $select_fee = "SELECT * FROM tbl_fee_category WHERE camp_id = :camp_id AND prg_type_id = :prg_type_id AND fac_id = :fac_id AND dept_id = :dept_id AND splz_id = :splz_id AND level_id = :level_id";
    $cselect_fee = $this->connect->prepare($select_fee);
    $cselect_fee->execute([
        ':camp_id' => $camp_id,
        ':prg_type_id' => $prg_type_id,
        ':fac_id' => $fac_id,
        ':dept_id' => $dept_id,
        ':splz_id' => $splz_id,
        ':level_id' => $level_id
    ]);
    $row_cselect_fee = $cselect_fee->fetchAll(PDO::FETCH_ASSOC);
    $jsonData = json_encode($row_cselect_fee);
    header('Content-Type: application/json');
    echo $jsonData;
}
}	
		$sponsor=new Sponsor();
	    $action = $_POST['action'];
		switch($action){
		    case 'save_sponsor':
		        $sponsor->save_sponsor();
		        break;
		    case 'update':
		        $sponsor->update_sponsor();
		        break;
		    case 'view':
		        $sponsor->view_sponsor();
		        break;
		    case 'delete':
		        $sponsor->delete_sponsor();
		        break;
		    case 'register_cat':
		        $sponsor->save_sponsor_cat();
		        break;
		    case 'update_cat':
		        $sponsor->update_sponsor_cat();
		        break;
		    case 'view_cat':
		        $sponsor->view_sponsor_cat();
		        break;
		    case 'delete_cat':
		        $sponsor->delete_sponsor_cat();
		        break;
		    case 'assign_sponsor':
		        $sponsor->registerSponsor();
		        break;
		    case 'view_sponship':
		        $sponsor->view_sponsorship();
		        break;
		    case 'update_sponship':
		        $sponsor->update_sponsorship();
		        break;
		    case 'on-off-sponship':
		        $sponsor->manage_sponsorship();
		        break;
		    case 'load_student_fee':
		        $sponsor->show_student_fee();
		        break;
		        
		}

	?>

