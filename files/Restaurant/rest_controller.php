<?php
include ('../../meet/con.php');
$connection=$conn;
class Restaurant{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }

//restautants
    function save_rest(){
        $class_id = $_POST['class_id'];
        $camp_id = $_POST['camp_id'];
        $rest_name = $_POST['rest_name'];
        $price = $_POST['price'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_restaurant WHERE rest_name='".$rest_name."' AND class_id='".$class_id."' AND camp_id='".$camp_id."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            $stmt = $this->connect->prepare("INSERT INTO tbl_restaurant(class_id,camp_id,rest_name,price) VALUES ('".$class_id."','".$camp_id."','".$rest_name."','".$price."')");
            
            if($stmt->execute()){
                $data = array("status"=>"200","message" => "Data saved successfully!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }else{
                $data = array("status"=>"500","message" => "Failed to save data!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
        }
	}

    function view_rest(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT r.*,
                                                c.class_name 
                                                FROM tbl_restaurant r 
                                                INNER JOIN tbl_rest_class c ON r.class_id=c.class_id 
                                                WHERE r.rest_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
            
    function update_rest(){
        $id = $_POST['rest_id'];    
        $class_id = $_POST['class_id'];
        $rest_name = $_POST['rest_name'];
        $price = $_POST['price'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_restaurant WHERE rest_name='".$rest_name."' AND class_id='".$class_id."' AND rest_id!='".$id."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
        $stmt = $this->connect->prepare("UPDATE tbl_restaurant set class_id='".$class_id."',rest_name='".$rest_name."',price='".$price."' WHERE rest_id='".$id."'");
        if($stmt->execute()){
                $data = array("status"=>"200","message" => "data updated successfully");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            } else {
                $data = array("status"=>"500","message" => "Failed to update data");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
        }
    }

    function delete_rest(){
        $id = $_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_restaurant WHERE class_id='".$id."'");
        $stmt->execute();
        $prevData=$stmt->fetch();
        if($prevData['status']==1){
            $status=2;
        }
        else{
            $status=1;
        }
        $stmt2 = $this->connect->prepare("UPDATE tbl_restaurant SET status='".$status."' WHERE rest_id='".$id."'");  
        if($stmt2->execute()){
            $data = array("status"=>"200","message" => "operation done successfully");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        } else {
            $data = array("status"=>"500","message" => "operation failed!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
    }
    
//register subscription
    function save_dinner(){
        $reg_no = $_POST['reg_no'];
        $rest_id = $_POST['rest_id'];
        $month_count = $_POST['month_count'];
        $from_date = $_POST['from_date'];
        $to_date = $_POST['to_date'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_rest_subscription
                                            WHERE reg_no = '".$reg_no."' AND rest_id = '".$rest_id."'
                                            AND (
                                                -- Case 1: Existing range is completely contained within the given range
                                                (from_date >= '".$from_date."' AND to_date <= '".$to_date."')
                                                -- Case 2: Given range is completely contained within the existing range
                                                OR (from_date <= '".$from_date."' AND to_date >= '".$to_date."')
                                                -- Case 3: Given range starts within the existing range
                                                OR ('".$from_date."' BETWEEN from_date AND to_date)
                                                -- Case 4: Given range ends within the existing range
                                                OR ('".$to_date."' BETWEEN from_date AND to_date)
                                            ) AND status=1");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "There is an active subscription!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            //fetch amount to be paid
            $stmt0 = $this->connect->prepare("SELECT price FROM tbl_restaurant WHERE rest_id='".$rest_id."'");
            $stmt0->execute();
            $priceData=$stmt0->fetch();
            $amount=$month_count*$priceData['price'];
            
            $stmt1 = $this->connect->prepare("SELECT acad_cycle_id FROM tbl_register_program_ug WHERE reg_no='".$reg_no."' AND reg_active=1");
            $stmt1->execute();
            $acad=$stmt1->fetch();
            $acad_cycle_id=$acad['acad_cycle_id'];
            $stmt = $this->connect->prepare("INSERT INTO tbl_rest_subscription(reg_no,acad_cycle_id,rest_id,month_count,from_date,to_date,amount) VALUES ('".$reg_no."','".$acad_cycle_id."','".$rest_id."','".$month_count."','".$from_date."','".$to_date."','".$amount."')");
            
            if($stmt->execute()){
                $data = array("status"=>"200","message" => "subscription saved successfully!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }else{
                $data = array("status"=>"500","message" => "Failed to save data!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
        }
	}
	//view subscription
    function view_dinner(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_rest_subscription WHERE restu_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
    
    
//update subscription
    function update_dinner(){
        $restu_id = $_POST['din_id'];
        $reg_no = $_POST['reg_no'];
        $rest_id = $_POST['rest_id'];
        $month_count = $_POST['month_count'];
        $from_date = $_POST['from_date'];
        $to_date = $_POST['to_date'];
        
        $stmt = $this->connect->prepare("SELECT * FROM tbl_rest_subscription
                                            WHERE reg_no = '".$reg_no."' AND rest_id = '".$rest_id."'
                                            AND (
                                                -- Case 1: Existing range is completely contained within the given range
                                                (from_date >= '".$from_date."' AND to_date <= '".$to_date."')
                                                -- Case 2: Given range is completely contained within the existing range
                                                OR (from_date <= '".$from_date."' AND to_date >= '".$to_date."')
                                                -- Case 3: Given range starts within the existing range
                                                OR ('".$from_date."' BETWEEN from_date AND to_date)
                                                -- Case 4: Given range ends within the existing range
                                                OR ('".$to_date."' BETWEEN from_date AND to_date)
                                            ) AND restu_id!='".$restu_id."' AND status=1");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "There is an active subscription!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            //fetch amount to be paid
            $stmt0 = $this->connect->prepare("SELECT price FROM tbl_restaurant WHERE rest_id='".$rest_id."'");
            $stmt0->execute();
            $priceData=$stmt0->fetch();
            $amount=$month_count*$priceData['price'];
            
            
            $stmt1 = $this->connect->prepare("SELECT acad_cycle_id FROM tbl_register_program_ug WHERE reg_no='".$reg_no."' AND reg_active=1");
            $stmt1->execute();
            $acad=$stmt1->fetch();
            $acad_cycle_id=$acad['acad_cycle_id'];
            $stmt = $this->connect->prepare("UPDATE tbl_rest_subscription SET reg_no='".$reg_no."',acad_cycle_id='".$acad_cycle_id."' ,rest_id='".$rest_id."', month_count='".$month_count."' , from_date='".$from_date."' , to_date='".$to_date."', amount='".$amount."' WHERE restu_id='".$restu_id."'");
            
            if($stmt->execute()){
                $data = array("status"=>"200","message" => "subscription saved successfully!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }else{
                $data = array("status"=>"500","message" => "Failed to save data!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData; 
            }
        }
	}

    function delete_dinner(){
        $id = $_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_rest_subscription WHERE restu_id='".$id."'");
        $stmt->execute();
        $prevData=$stmt->fetch();
        if($prevData['status']==1){
            $status=2;
        }
        else{
            $status=1;
        }
        $stmt2 = $this->connect->prepare("UPDATE tbl_rest_subscription SET status='".$status."' WHERE restu_id='".$id."'");  
        if($stmt2->execute()){
            $data = array("status"=>"200","message" => "operation done successfully");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        } else {
            $data = array("status"=>"500","message" => "operation failed!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
    }
    
    function new_class(){
        $class_name=$_POST['class_name'];
        $campus_id=$_POST['camp_id'];
        $stCheck=$this->connect->prepare("SELECT * FROM tbl_rest_class WHERE class_name='".$class_name."' AND campus_id='".$campus_id."'"); 
        $stCheck->execute();
        $rowCoountHere=$stCheck->rowCount();
        if($rowCoountHere>0){
         $data = array("status"=>"203","message" => "Class already exist!");
         $jsonData = json_encode($data);
         header('Content-Type: application/json');
         echo $jsonData;   
        }
        else{
            $insert=$this->connect->prepare("INSERT INTO tbl_rest_class(class_name,campus_id) VALUES('".$class_name."','".$campus_id."')");
            $result=$insert->execute();
            if($result){
             $data = array("status"=>"200","message" => "Class added!");
             $jsonData = json_encode($data);
             header('Content-Type: application/json');
             echo $jsonData;    
            }
            else{
             $data = array("status"=>"500","message" => "Failed!");
             $jsonData = json_encode($data);
             header('Content-Type: application/json');
             echo $jsonData;    
            }
        }
        
    }
     function delete_class_rest(){
         $class_id=$_POST['id'];
         $check=$this->connect->prepare("SELECT status FROM tbl_rest_class WHERE class_id='".$class_id."'");
         $check->execute();
         $dataSts=$check->fetch();
         $status=$dataSts['status'];
         if($status==1){
          $stQuery=$this->connect->prepare("UPDATE tbl_rest_class SET status=2 WHERE class_id='".$class_id."'");   
         }
         else {
            $stQuery=$this->connect->prepare("UPDATE tbl_rest_class SET status=1 WHERE class_id='".$class_id."'"); 
         }
         
         $result=$stQuery->execute();
         if($result){
             $data = array("status"=>"200","message" => "Status updated!");
             $jsonData = json_encode($data);
             header('Content-Type: application/json');
             echo $jsonData;    
         }
         else{
             $data = array("status"=>"500","message" => "Failed !");
             $jsonData = json_encode($data);
             header('Content-Type: application/json');
             echo $jsonData;
         }
     }
     function view_class_rest(){
         $class_id=$_POST['id'];
         $Query=$this->connect->prepare("SELECT * FROM tbl_rest_class WHERE class_id='".$class_id."' ");
         $Query->execute();
         $dataQuery=$Query->fetch();
         $jsonData = json_encode($dataQuery);
         header('Content-Type: application/json');
         echo $jsonData;
     }
    function update_class_rest(){
        $classId=$_POST['class_id'];
        $class_name=$_POST['class_name_edit'];
        $stq=$this->connect->prepare("UPDATE tbl_rest_class SET class_name='".$class_name."' WHERE class_id='".$classId."'");
        $result=$stq->execute();
        if($result){
             $data = array("status"=>"200","message" => "Class updated!");
             $jsonData = json_encode($data);
             header('Content-Type: application/json');
             echo $jsonData;    
         }
         else{
             $data = array("status"=>"500","message" => "Failed !");
             $jsonData = json_encode($data);
             header('Content-Type: application/json');
             echo $jsonData;
         }
    }
}	
		$rest=new Restaurant();
	    $action = $_POST['action'];
		switch($action){
		    case 'save_rest':
		        $rest->save_rest();
		        break;
		    case 'view_rest':
		        $rest->view_rest();
		        break;
		    case 'update_rest':
		        $rest->update_rest();
		        break;
		    case 'delete_rest':
		        $rest->delete_rest();
		        break;
		    case 'save_dinner':
		        $rest->save_dinner();
		        break;
		    case 'view_dinner':
		        $rest->view_dinner();
		        break;
		    case 'update_dinner':
		        $rest->update_dinner();
		        break;
		    case 'delete_dinner':
		        $rest->delete_dinner();
		        break;
		   case 'save_class_rest':
		       $rest->new_class();
		       break;
		   case 'delete_class_rest':
		       $rest->delete_class_rest();
		       break;
		  case 'view_class_rest':
		       $rest->view_class_rest();
		       break;
		  case 'update_class_rest':
		       $rest->update_class_rest();
		       break;
		}

	?>

