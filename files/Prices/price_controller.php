<?php
include ('../../meet/con.php');
$connection=$conn;
class Price{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }
    function save_price(){
        $camp_id = $_POST['camp_id'];
        $level_id = $_POST['level_id'];
        $prg_type_Id = $_POST['prg_type_Id'];
        $splz_id = $_POST['splz_id'];
        $intake_id = $_POST['intake_id'];
        $amount = $_POST['amount'];
        $tolerance_balance = $_POST['tolerance_balance'];
        $tolerance_expiration_date = $_POST['tolerance_expiration_date'];

        $stmt = $this->connect->prepare("SELECT * FROM tbl_amount_to_pay WHERE 
                                                                            camp_id='".$camp_id."' AND 
                                                                            prg_type_Id='".$prg_type_Id."' AND 
                                                                            splz_id='".$splz_id."' AND 
                                                                            intake_id='".$intake_id."' AND 
                                                                            level_id='".$level_id."' AND 
                                                                            amount='".$amount."' AND 
                                                                            tolerance_balance='".$tolerance_balance."' AND 
                                                                            tolerance_expiration_date='".$tolerance_expiration_date."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            $stmt = $this->connect->prepare("INSERT INTO tbl_amount_to_pay(camp_id,level_id,prg_type_Id,splz_id,intake_id,amount,tolerance_balance,tolerance_expiration_date) 
                  	VALUES('".$camp_id."','".$level_id."','".$prg_type_Id."','".$splz_id."','".$intake_id."','".$amount."','".$tolerance_balance."','".$tolerance_expiration_date."')");
            if($stmt->execute()){
                $data = array("status"=>"200","message" => "Data saved successfully!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            } else {
                $data = array("status"=>"500","message" => "Failed to save data!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
        }

	}
	
    
    function view_price(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_amount_to_pay WHERE m_to_pay_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
       
        $stmt2 = $this->connect->prepare("SELECT * FROM tbl_program_type WHERE campus_id='".$data['camp_id']."'");
        $stmt2->execute();
        $data2=$stmt2->fetchAll();

        $stmt3 = $this->connect->prepare("SELECT * FROM tbl_specialization WHERE prg_type='".$data['prg_type_Id']."'");
        $stmt3->execute();
        $data3=$stmt3->fetchAll();
        
        $stmt4 = $this->connect->prepare("SELECT * FROM tbl_level WHERE prg_type='".$data['prg_type_Id']."'");
        $stmt4->execute();
        $data4=$stmt4->fetchAll();
        $info=array();
        array_push($info,$data,$data2,$data3,$data4);
        $jsonData = json_encode($info);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
    
    function view_price_detailed(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT
                                            tbl_campus.camp_full_name,
                                            tbl_program_type.prg_type_full_name,
                                            tbl_specialization.splz_full_name,
                                            tbl_level.level_full_name,
                                            tbl_intake.intake_month,
                                            tbl_amount_to_pay.*
                                        FROM tbl_amount_to_pay
                                             INNER JOIN tbl_campus ON tbl_amount_to_pay.camp_id = tbl_campus.camp_id
                                             INNER JOIN tbl_program_type ON tbl_amount_to_pay.prg_type_Id = tbl_program_type.prg_type_id
                                             INNER JOIN tbl_specialization ON tbl_amount_to_pay.splz_id = tbl_specialization.splz_id
                                             INNER JOIN tbl_level ON tbl_amount_to_pay.level_id = tbl_level.level_id
                                             INNER JOIN tbl_intake ON tbl_amount_to_pay.intake_id = tbl_intake.intake_id WHERE m_to_pay_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
    
    function update_price(){
        $id = $_POST['m_to_pay_id'];
        $camp_id = $_POST['camp_id'];
        $level_id = $_POST['level_id'];
        $prg_type_Id = $_POST['prg_type_Id'];
        $splz_id = $_POST['splz_id'];
        $intake_id = $_POST['intake_id'];
        $amount = $_POST['amount'];
        $tolerance_balance = $_POST['tolerance_balance'];
        $tolerance_expiration_date = $_POST['tolerance_expiration_date'];

        $stmt = $this->connect->prepare("SELECT * FROM tbl_amount_to_pay WHERE 
                                                                            camp_id='".$camp_id."' AND 
                                                                            prg_type_Id='".$prg_type_Id."' AND 
                                                                            splz_id='".$splz_id."' AND 
                                                                            intake_id='".$intake_id."' AND 
                                                                            level_id='".$level_id."' AND 
                                                                            amount='".$amount."' AND 
                                                                            tolerance_balance='".$tolerance_balance."' AND 
                                                                            tolerance_expiration_date='".$tolerance_expiration_date."' AND m_to_pay_id!='".$id."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            $stmt = $this->connect->prepare("UPDATE tbl_amount_to_pay SET camp_id='".$camp_id."',
                                                                          level_id='".$level_id."',
                                                                          prg_type_Id='".$prg_type_Id."',
                                                                          splz_id='".$splz_id."',
                                                                          intake_id='".$intake_id."',
                                                                          amount='".$amount."',
                                                                          tolerance_balance='".$tolerance_balance."',
                                                                          tolerance_expiration_date='".$tolerance_expiration_date."'
                                                                    WHERE m_to_pay_id='".$id."'");
            if($stmt->execute()){
                $data = array("status"=>"200","message" => "Data updated successfully!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            } else {
                $data = array("status"=>"500","message" => "Failed to update data!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
        }
	}
	
	function set_campus_tolerance(){
        $camp_id = $_POST['camp_id'];
        $tolerance_balance = $_POST['tolerance_balance'];
        $tolerance_expiration_date = $_POST['tolerance_expiration_date'];

        $stmt = $this->connect->prepare("SELECT * FROM tbl_amount_to_pay WHERE camp_id='".$camp_id."'");
        $stmt->execute();
        if($stmt->rowCount()==0){
            $data = array("status"=>"401","message" => "No data found for the campus!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            $stmt = $this->connect->prepare("UPDATE tbl_amount_to_pay SET tolerance_balance='".$tolerance_balance."',
                                                                          tolerance_expiration_date='".$tolerance_expiration_date."'
                                                                    WHERE camp_id='".$camp_id."'");
            if($stmt->execute()){
                $data = array("status"=>"200","message" => "Data updated successfully!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            } else {
                $data = array("status"=>"500","message" => "Failed to update data!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
        }
	}
	
	function set_sponsor_tolerance(){
        $sponsor_id = $_POST['spon_id'];
        $tolerance_balance = $_POST['tolerance_balance'];
        $tolerance_expiration_date = $_POST['tolerance_expiration_date'];

        $stmt = $this->connect->prepare("SELECT * FROM tbl_sponsor_tolerance WHERE 
                                                                                spon_id='".$sponsor_id."' AND
                                                                                tolerance_balance='".$sponsor_id."' AND
                                                                                tolerance_expiration_date='".$tolerance_expiration_date."'
                                                                                ");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            $stmt = $this->connect->prepare("INSERT INTO tbl_sponsor_tolerance(spon_id,tolerance_balance,tolerance_expiration_date) VALUES ('".$sponsor_id."','".$tolerance_balance."','".$tolerance_expiration_date."')");
            if($stmt->execute()){
                $data = array("status"=>"200","message" => "Data saved successfully!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            } else {
                $data = array("status"=>"500","message" => "Failed to save data!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
        }
	}
	
    function view_sponsor_tolerance(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_sponsor_tolerance WHERE spon_tol_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
    
    function update_sponsor_tolerance(){
        $id = $_POST['spon_tol_id'];
        $spon_id = $_POST['spon_id'];
        $tolerance_balance = $_POST['tolerance_balance'];
        $tolerance_expiration_date = $_POST['tolerance_expiration_date'];

        $stmt = $this->connect->prepare("SELECT * FROM tbl_sponsor_tolerance WHERE 
                                                                            spon_id='".$spon_id."' AND 
                                                                            tolerance_balance='".$tolerance_balance."' AND 
                                                                            tolerance_expiration_date='".$tolerance_expiration_date."' AND spon_tol_id!='".$id."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            $stmt = $this->connect->prepare("UPDATE tbl_sponsor_tolerance SET spon_id='".$spon_id."',
                                                                          tolerance_balance='".$tolerance_balance."',
                                                                          tolerance_expiration_date='".$tolerance_expiration_date."'
                                                                    WHERE spon_tol_id='".$id."'");
            if($stmt->execute()){
                $data = array("status"=>"200","message" => "Data updated successfully!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            } else {
                $data = array("status"=>"500","message" => "Failed to update data!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
        }
    }
}	
		$price=new Price();
	    $action = $_POST['action'];
		switch($action){
		    case 'register_prices':
		        $price->save_price();
		        break;
		    case 'view_price':
		        $price->view_price();
		        break;
		    case 'view_price_detailed':
		        $price->view_price_detailed();
		        break;
		    case 'update_price':
		        $price->update_price();
		        break;
		    case 'save_campus_tolerance':
		        $price->set_campus_tolerance();
		        break;
		    case 'save_sponsor_tolerance':
		        $price->set_sponsor_tolerance();
		        break;
		    case 'view_sponsor_tolerance':
		        $price->view_sponsor_tolerance();
		        break;
		    case 'update_s_tolerance':
		        $price->update_sponsor_tolerance();
		        break;
		        
		}

	?>

