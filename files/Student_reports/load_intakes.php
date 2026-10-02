<?php
include ('../../meet/con.php');
$connection=$conn;
class Intakes{
     private $connect;
     public function __construct() {
		global $connection;
		$this->connect=$connection;
	}
  public function Load_intakes(){
      $prg=$_POST['type'];
      $stmt=$this->connect->prepare("SELECT tbl_intake.*,tbl_acad_cycle.* FROM tbl_intake INNER JOIN tbl_acad_cycle ON tbl_acad_cycle.acad_cycle_id =tbl_intake.acad_cycle_id
       WHERE tbl_intake.prg_type='".$prg."'");
      $stmt->execute();
      $data=$stmt->fetchAll();
      $jsonData=json_encode($data);
      header('Content-Type:application/json');
      echo ($jsonData);
      
  }
}
$intake=new Intakes();
$action=$_POST['action'];
switch($action){
    case 'load_intakes':
        $intake->Load_intakes();
        break;
}
?>