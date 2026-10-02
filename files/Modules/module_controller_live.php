<?php

include ('../../meet/con.php');
$action=$_POST['action'];

if($action=="save_data_live"){
$mod_id=$_POST['mod_id'];
$prg_type=$_POST['prg_type'];
$fac_id=$_POST['fac_id'];
$dept_id=$_POST['dept_id'];
$splz_id=$_POST['splz_id'];
$level_id=$_POST['level_id'];

$check=$conn->prepare("SELECT * FROM tbl_modules_live WHERE level_id_live='".$level_id."' AND mod_id_live='".$mod_id."' AND splz_id_live='".$splz_id."'");
$check->execute();
if($check->rowCount()==0){
    $Insert=$conn->prepare("INSERT INTO tbl_modules_live (prg_type_live,fac_id_live,dept_id_live,level_id_live,mod_id_live,splz_id_live)
      VALUES('".$prg_type."','".$fac_id."','".$dept_id."','".$level_id."','".$mod_id."','".$splz_id."')");
    $Insert->execute();
}
$data=array("status"=>200,"message"=>"Inserted In live");

echo json_encode($data);
}
else if($action=="get_data_live"){
    $getData=$conn->prepare("SELECT * FROM  tbl_modules_live GROUP BY mod_id_live");
    $getData->execute();
    while($row=$getData->fetch()){
        $mod_id_live=$row['mod_id_live'];
        $getMod=$conn->prepare("SELECT * FROM modules WHERE module_id='".$mod_id_live."' ");
        $getMod->execute();
        $modData=$getMod->fetch();
    echo "<tr>";
    echo "<td>" . $modData['module_name'] . " [" . $modData['module_code'] . "]</td>";
    
    echo "<td>";
    echo "<select class='select2' name='credited_module_$mod_id_live' id='credited_module_$mod_id_live'>";
    echo "<option value='1'>Yes</option>";
    echo "<option value='0'>No</option>";
    echo "</select>";
    echo "</td>";
    echo "<td>";
    
    echo "<input type='text' class=form-control form-control-sm' name='module_credits_$mod_id_live' id='module_credits_$mod_id_live'>";
    echo "</td>";
    
    echo "<td>";
    echo " <input type='text' class='form-control form-control-sm' name='credit_price_$mod_id_live' id='credit_price_$mod_id_live'>";
    echo "</td>";
    
    echo "<td>";
    echo "  <input type='text' class='form-control form-control-sm' name='cat_$mod_id_live'  required>";
    echo "</td>";
    
    echo "<td>";
    echo " <input type='text' class='form-control form-control-sm' name='exam_$mod_id_live' required>";
    echo "</td>";
   
   
    echo "<td>";
    echo "<select class='select2' name='is_project_$mod_id_live' id='is_project_$mod_id_live'>";
    echo "<option value='1'>Yes</option>";
    echo "<option value='0'>No</option>";
    echo "</select>";
    echo "</td>";
   
  echo "</tr>";
    }
    
}

if($action=="assign2"){
        $program=$_POST['prg_type'];
        $department=$_POST['dept_id'];
        $faculty=$_POST['fac_id'];
        $level=$_POST['level_id'];
        $spec=$_POST['splz_id'];
        $term=$_POST['term_id'];
    if(!empty($_POST['mod_id'])){
      foreach($_POST['mod_id'] as $value){
        $stmt =$conn->prepare("SELECT * FROM tbl_modules WHERE prg_type='".$program."' AND fac_id='".$faculty."' AND dept_id='".$department."' AND level_id='".$level."' AND mod_id='".$value."' AND term_id='".$term."' AND splz_id='".$spec."'");
        $stmt->execute();
        $r=$stmt->rowCount();
        if($r>0){
            $data = array("status"=>"401","message" => "Data already exists!");
              
        } 
            
      else {
            $stmt =$conn->prepare("INSERT INTO tbl_modules(prg_type,fac_id,dept_id,level_id,mod_id,term_id,module_credits,credited_module,splz_id,credit_price,is_project,cat,exam) 
                  	VALUES('".$program."','".$faculty."','".$department."','".$level."','".$value."','".$term."','".$_POST['module_credits_'.$value]."',
                  	'".$_POST['credited_module_'.$value]."','".$spec."','".$_POST['credit_price_'.$value]."','".$_POST['is_project_'.$value]."','".$_POST['cat_'.$value]."','".$_POST['exam_'.$value]."')");
            if($stmt->execute()){
             $data = array("status"=>"200","message" => "Data saved successfully!");
               
            } else {
                $data = array("status"=>"500","message" => "Failed to save data!");
                
            }
        }
       }
     $jsonData = json_encode($data);
            header('Content-Type: application/json');
            echo $jsonData;  
    }
    $conn->exec("TRUNCATE TABLE  tbl_modules_live");
   
}
?>