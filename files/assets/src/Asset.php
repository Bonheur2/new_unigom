<?php
    include '../../../meet/con.php';
    include './functions.php';
    include('../phpqrcode/qrlib.php');

    $connection=$conn;
    class Asset{
        private $connect;
        public function __construct() {
    		global $connection;
    		$this->connect=$connection;
        }
        
        public function getUniCode(){
            $stmt = $this->connect->prepare("SELECT short_name FROM tbl_university LIMIT 1");
            $stmt->execute();
            return ($stmt->fetch())['short_name'];
        }
         
        public function getClassCode($iclass_id){
            $stmt = $this->connect->prepare("SELECT iclass_code FROM tbl_item_class WHERE iclass_id = ?");
            $stmt->execute([$iclass_id]);
            return ($stmt->fetch())['iclass_code'];
        }
    	
    	public function verifyAssetGenerationRequest($catg_id, $count,$locat){
            $stmt0 = $this->connect->prepare("SELECT * FROM tbl_categories WHERE catg_id = ? AND status = 1");
            $stmt0->execute([$catg_id]);
            if($stmt0->rowCount() == 0){
                return false;
            }
        
            if(is_int((int)$count)){
                return true;
            }
            return false;
    	}
    	
    	public function getAssetId(){
            $getLast = $this->connect->prepare('SELECT asset_id FROM tbl_assets ORDER BY asset_id DESC LIMIT 1');
            $getLast->execute();
            if($getLast->rowCount() > 0){
                $last = $getLast->fetch();
                return $last['asset_id'] + 1;
            } else{
                return 1;
            }
    	}
    	
    	public function getClass($catg_id){
            $getItemClass = $this->connect->prepare("SELECT i.iclass_id FROM tbl_categories c INNER JOIN tbl_items i ON c.item_id = i.item_id WHERE c.catg_id = ?");
            $getItemClass->execute([$catg_id]);
            return ($getItemClass->fetch())['iclass_id'];
    	}
    	public function getLastCode(){
            //$iclass_id = $this->getClass($catg_id);
            $getlastcode = $this->connect->prepare("SELECT asset_no FROM tbl_assets ORDER BY asset_id DESC LIMIT 1");
            $getlastcode->execute();
            if($getlastcode->rowCount() > 0){
                return ($getlastcode->fetch())['asset_no'];
            }else{
                return 0;
            }
    	}
    	
    	public function insertAsset($iclass_id, $catg_id, $prefix, $asset_no, $asset_key){
            $stmt = $this->connect->prepare("INSERT INTO tbl_assets(iclass_id, catg_id, prefix, asset_no, asset_key) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$iclass_id, $catg_id, $prefix, $asset_no, $asset_key]);
            return;
    	}
    	
    	public function generateAndSaveassets($catg_id, $count,$locat){
    	    $iclass_id = $this->getClass($catg_id);
    	    
    	    $unicode = $this->getUniCode();

    	    $classcode = $this->getClassCode($iclass_id);
            $prefix = strtolower($unicode.'/'.$locat);
            $lastId = $this->getAssetId();

            try{    
        	    $this->connect->beginTransaction();
        	    $i = 1;
                while($i <= $count){
                    $lastcode = $this->getLastCode($catg_id);

                    $prevSuffix = (int)substr($lastcode, -7); // Use 7-digit suffix
                    
                    if ($prevSuffix < 9) {
                        $prevSuffix += 1;
                        $suffix = '000000'.$prevSuffix; // Add one more zero
                    } elseif ($prevSuffix < 99) {
                        $prevSuffix += 1;
                        $suffix = '00000'.$prevSuffix;
                    } elseif ($prevSuffix < 999) {
                        $prevSuffix += 1;
                        $suffix = '0000'.$prevSuffix;
                    } elseif ($prevSuffix < 9999) {
                        $prevSuffix += 1;
                        $suffix = '000'.$prevSuffix;
                    } elseif ($prevSuffix < 99999) {
                        $prevSuffix += 1;
                        $suffix = '00'.$prevSuffix;
                    } elseif ($prevSuffix < 999999) { // New condition for 7 digits
                        $prevSuffix += 1;
                        $suffix = '0'.$prevSuffix;
                    } else {
                        $prevSuffix += 1;
                        $suffix = $prevSuffix;
                    }
                    
                    $asset_no = $suffix;

                
                    $qr_data = $prefix.$asset_no.'//-IT-24//'.$lastId.date('dnyhmi');
                    
                    $qr_token= hash('sha256', $qr_data);
                    $qr_file = "../QR/".$qr_token.".png";
                    QRcode::png($qr_token, $qr_file, QR_ECLEVEL_Q, 10, 1);
                    
                    $this->insertAsset($iclass_id, $catg_id, $prefix, $asset_no, $qr_token);
                        
                    $i += 1;
                    $lastId += 1;
                }
        	    if($this->connect->commit()){
                    sendFeedback(200, "Operation done successfully!");
        	    }
        	    else {
        	        sendFeedback(400, "Failed to save data!");
        	    }
    	    }catch (PDOException $e) {
                $this->connect->rollback();
                sendFeedback(400, "Something Went wrong, retry!"); 
            }
    	}
        
        public function generate_assets(){
            $catg_id = $_POST['catg_id'];
            $count = $_POST['count'];
            $locat=$_POST['locat'];
            $isValid = $this->verifyAssetGenerationRequest($catg_id, $count,$locat);
            if($isValid){
                $this->generateAndSaveAssets($catg_id, $count,$locat);
            } else{
                sendFeedback(400, "Invalid Request!");
            }
        }
    }
    
	$asset = new Asset();
    $action = $_POST['action'];
	switch($action){
	   case 'generate':
	        $asset->generate_assets();
	        break;
	}

?>