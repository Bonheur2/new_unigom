<?php
include ('../../meet/con.php');
$connection=$conn;
class Hostel{
    private $connect;
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	  }

	//hostel blocks
    function save_block(){
    	$block_name = $_POST['block_name'];
    	$campus_id = $_POST['campus_id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_hostel_block WHERE block_name='".$block_name."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "building already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            $stmt = $this->connect->prepare("INSERT INTO tbl_hostel_block(campus_id,block_name) 
                  	VALUES('".$campus_id."','".$block_name."')");
            
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
    function view_block(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_hostel_block WHERE block_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
    
    function load_classes(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_room_class WHERE block_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetchAll();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
            
        function update_block(){
        $id = $_POST['block_id'];    
        $block_name = $_POST['block_name'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_hostel_block WHERE block_name='".$block_name."' AND block_id!='".$id."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
        $stmt = $this->connect->prepare("UPDATE tbl_hostel_block set block_name='".$block_name."' WHERE block_id='".$id."'");
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

    	function delete_block(){
            $id = $_POST['id'];
            $stmt = $this->connect->prepare("SELECT * FROM tbl_hostel_block WHERE block_id='".$id."'");
            $stmt->execute();
            $prevData=$stmt->fetch();
            if($prevData['status']==1){
                $status=2;
            }
            else{
                $status=1;
            }
            $stmt2 = $this->connect->prepare("UPDATE tbl_hostel_block SET status='".$status."' WHERE block_id='".$id."'");  
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

//room classification
    function save_class(){
    	$class_name = $_POST['class_name'];
    	$block_id = $_POST['block_id'];
    	$price = $_POST['price'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_room_class WHERE class_name='".$block_name."' AND block_id='".$block_id."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            $stmt = $this->connect->prepare("INSERT INTO tbl_room_class(block_id,class_name,price) 
                  	VALUES('".$block_id."','".$class_name."','".$price."')");
            
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

    function view_class(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_room_class WHERE class_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
            
    function update_class(){
        $id = $_POST['class_id'];    
        $block_id = $_POST['block_id'];
        $class_name = $_POST['class_name'];
        $price = $_POST['price'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_room_class WHERE class_name='".$class_name."' AND block_id='".$block_id."' AND class_id!='".$id."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
        $stmt = $this->connect->prepare("UPDATE tbl_room_class set block_id='".$block_id."',class_name='".$class_name."',price='".$price."' WHERE class_id='".$id."'");
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

    function delete_class(){
        $id = $_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_room_class WHERE class_id='".$id."'");
        $stmt->execute();
        $prevData=$stmt->fetch();
        if($prevData['status']==1){
            $status=2;
        }
        else{
            $status=1;
        }
        $stmt2 = $this->connect->prepare("UPDATE tbl_room_class SET status='".$status."' WHERE class_id='".$id."'");  
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
    
//rooms
    function save_room(){
    	$block_id = $_POST['block_id'];
    	$class_id = $_POST['class_id'];
    	$room_code = $_POST['room_code'];
    	$capacity = $_POST['capacity'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_hostel_room WHERE block_id='".$block_id."' AND class_id='".$class_id."' AND room_code='".$room_code."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
            $stmt = $this->connect->prepare("INSERT INTO tbl_hostel_room(room_code,block_id,class_id,capacity) 
                  	VALUES('".$room_code."','".$block_id."','".$class_id."','".$capacity."')");
            
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

    function view_room(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_hostel_room WHERE room_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        
        $stmt2 = $this->connect->prepare("SELECT * FROM tbl_room_class WHERE block_id='".$data['block_id']."'");
        $stmt2->execute();
        $data2=$stmt2->fetchAll();
        
        $info=array();
        array_push($info,$data,$data2);
        $jsonData = json_encode($info);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
    
    function update_room(){
        $id = $_POST['room_id'];
    	$block_id = $_POST['block_id'];
    	$class_id = $_POST['class_id'];
    	$room_code = $_POST['room_code'];
    	$capacity = $_POST['capacity'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_hostel_room WHERE block_id='".$block_id."' AND class_id='".$class_id."' AND room_code='".$room_code."' AND room_id!='".$id."'");
        $stmt->execute();
        if($stmt->rowCount()>0){
            $data = array("status"=>"401","message" => "Data already exists!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        } else {
        $stmt = $this->connect->prepare("UPDATE tbl_hostel_room set block_id='".$block_id."',class_id='".$class_id."',room_code='".$room_code."',capacity='".$capacity."' WHERE room_id='".$id."'");
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
    
    function delete_room(){
        $id = $_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_hostel_room WHERE room_id='".$id."'");
        $stmt->execute();
        $prevData=$stmt->fetch();
        if($prevData['status']==1){
            $status=2;
        }
        else{
            $status=1;
        }
        $stmt2 = $this->connect->prepare("UPDATE tbl_hostel_room SET status='".$status."' WHERE room_id='".$id."'");  
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
    
    //contracts
    function load_available_rooms(){
    	$class_id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT r.room_id, r.room_code, r.capacity, COUNT(t.room_id) AS num_tenants
                                        FROM tbl_hostel_room r
                                        LEFT JOIN tbl_tenants t ON r.room_id = t.room_id AND t.status = 1
                                        WHERE r.status = 1 AND r.class_id='".$class_id."'
                                        GROUP BY r.room_id, r.capacity
                                        HAVING COUNT(t.room_id) < r.capacity");
        $stmt->execute();
        $data=$stmt->fetchAll();
        $jsonData = json_encode($data);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
    
    //save tenant
    function save_tenant(){
    	$reg_no = $_POST['reg_no'];
    	$room_id = $_POST['room_id'];
    	
    	//check if the room is available
        $stmt = $this->connect->prepare("SELECT r.room_id, r.capacity, COUNT(t.room_id) AS num_tenants
                                        FROM tbl_hostel_room r
                                        LEFT JOIN tbl_tenants t ON r.room_id = t.room_id AND t.status = 1
                                        WHERE r.status = 1 AND r.room_id='".$room_id."'
                                        GROUP BY r.room_id, r.capacity
                                        HAVING COUNT(t.room_id) < r.capacity");
        $stmt->execute();
        if($stmt->rowCount()==0){
            $data = array("status"=>"401","message" => "This room is fully occupied");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;   
        }else {
            $stmt2 = $this->connect->prepare("SELECT intake_id FROM tbl_admission WHERE reg_no='".trim($reg_no)."' ");
            $stmt2->execute();
            $intake=$stmt2->fetch();
            $intake_id=$intake['intake_id'];
            
            $stmtAc = $this->connect->prepare("SELECT acad_cycle_id FROM tbl_register_program_ug WHERE reg_no='".trim($reg_no)."' AND reg_active=1");
            $stmtAc->execute();
            $acadData=$stmtAc->fetch();
            $acad_id=$acadData['acad_cycle_id'];
            //check if the tenant is already in hostel
            $stmt3 = $this->connect->prepare("SELECT reg_no FROM tbl_tenants WHERE reg_no='".$reg_no."' AND status=1");
            $stmt3->execute();
            if($stmt3->rowCount()>0){
                $data = array("status"=>"401","message" => "data already exists");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;   
            }
            else{
                $dt=date('Y-m-d');
                //save info
                $save = $this->connect->prepare("INSERT INTO tbl_tenants(reg_no,room_id,intake_id,acad_cycle_id,date) 
                      	VALUES('".trim($reg_no)."','".$room_id."','".$intake_id."','".$acad_id."','".$dt."')");
                
                if($save->execute()){
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
	}  
	//view tenant to update
    function view_tenant(){
    	$id=$_POST['id'];
        $stmt = $this->connect->prepare("SELECT t.*,
                                                r.room_code,r.block_id,r.class_id,
                                                c.class_name,
                                                b.block_name,
                                                i.intake_month
                                            FROM tbl_tenants t
                                                INNER JOIN tbl_intake i ON t.intake_id=i.intake_id
                                                INNER JOIN tbl_hostel_room r ON t.room_id=r.room_id
                                                INNER JOIN tbl_room_class c ON r.class_id=c.class_id
                                                INNER JOIN tbl_hostel_block b ON r.block_id=b.block_id
                                            WHERE t.tenant_id='".$id."'");
        $stmt->execute();
        $data=$stmt->fetch();
        
        $block_id=$data['block_id'];
        $class_id=$data['class_id'];
        $stmt2 = $this->connect->prepare("SELECT class_id,class_name FROM tbl_room_class WHERE block_id='".$block_id."' AND status=1");
        $stmt2->execute();
        $data2=$stmt2->fetchAll();
        
        $stmt3 = $this->connect->prepare("SELECT r.room_id, r.room_code, r.capacity, COUNT(t.room_id) AS num_tenants
                                        FROM tbl_hostel_room r
                                        LEFT JOIN tbl_tenants t ON r.room_id = t.room_id AND t.status = 1
                                        WHERE r.status = 1 AND r.class_id='".$class_id."'
                                        GROUP BY r.room_id, r.capacity
                                        HAVING COUNT(t.room_id) < r.capacity OR r.room_id='".$data['room_id']."'");
        $stmt3->execute();
        $data3=$stmt3->fetchAll();
        $info=array();
        array_push($info,$data,$data2,$data3);
        $jsonData = json_encode($info);
        header('Content-Type: application/json');
        echo $jsonData; 
    }
    
    //update tenant
    function update_tenant(){
        $id = $_POST['tenant_id'];
    	$reg_no = $_POST['reg_no'];
    	$room_id = $_POST['room_id'];
    	
    	//Get current stored room
        $stmt0 = $this->connect->prepare("SELECT room_id FROM tbl_tenants WHERE tenant_id='".$id."'");
        $stmt0->execute();
        $prevRoomData=$stmt0->fetch();
        $prev_room_id=$prevRoomData['room_id'];
        
    	//check if the room is available
    	if($prev_room_id!=$room_id){
            $stmt = $this->connect->prepare("SELECT r.room_id, r.capacity, COUNT(t.room_id) AS num_tenants
                                            FROM tbl_hostel_room r
                                            LEFT JOIN tbl_tenants t ON r.room_id = t.room_id AND t.status = 1
                                            WHERE r.status = 1 AND r.room_id='".$room_id."'
                                            GROUP BY r.room_id, r.capacity
                                            HAVING COUNT(t.room_id) < r.capacity");
            $stmt->execute();
            if($stmt->rowCount()==0){
                $data = array("status"=>"401","message" => "This room is fully occupied");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;   
                return;
            }
    	}
        $dt=date('Y-m-d');
        $save = $this->connect->prepare("UPDATE tbl_tenants SET room_id='".$room_id."', date='".$dt."' WHERE tenant_id='".$id."'");
                
        if($save->execute()){
            $data = array("status"=>"200","message" => "Data updated successfully!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }else{
            $data = array("status"=>"500","message" => "Failed to update data!");
            $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData; 
        }
	}
	
	//delete contract
    function delete_tenant(){
        $id = $_POST['id'];
        $stmt = $this->connect->prepare("SELECT * FROM tbl_tenants WHERE tenant_id='".$id."'");
        $stmt->execute();
        $prevData=$stmt->fetch();
        if($prevData['status']==1){
            $status=2;
        }
        else{
            $status=1;
        }
        $stmt2 = $this->connect->prepare("UPDATE tbl_tenants SET status='".$status."' WHERE tenant_id='".$id."'");  
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
}	
		$hostel=new Hostel();
	    $action = $_POST['action'];
		switch($action){
		    case 'save_block':
		        $hostel->save_block();
		        break;
		    case 'update_block':
		        $hostel->update_block();
		        break;
		    case 'view_block':
		        $hostel->view_block();
		        break;
		    case 'delete_block':
		        $hostel->delete_block();
		        break;
		    case 'load_classes':
		        $hostel->load_classes();
		        break;
		    case 'save_class':
		        $hostel->save_class();
		        break;
		    case 'update_class':
		        $hostel->update_class();
		        break;
		    case 'view_class':
		        $hostel->view_class();
		        break;
		    case 'delete_class':
		        $hostel->delete_class();
		        break;
		    case 'save_room':
		        $hostel->save_room();
		        break;
		    case 'update_room':
		        $hostel->update_room();
		        break;
		    case 'view_room':
		        $hostel->view_room();
		        break;
		    case 'delete_room':
		        $hostel->delete_room();
		        break;
		    case 'load_available_rooms':
		        $hostel->load_available_rooms();
		        break;
		    case 'save_tenant':
		        $hostel->save_tenant();
		        break;
		    case 'view_tenant':
		        $hostel->view_tenant();
		        break;
		    case 'update_tenant':
		        $hostel->update_tenant();
		        break;
		    case 'delete_tenant':
		        $hostel->delete_tenant();
		        break;
		}

	?>

