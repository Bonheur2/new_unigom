<?php
include ('../../../meet/con.php');
$connection=$conn;
class Document{
    private $connect;
    private $upload_dir = "../../../student_docs/";
    
    public function __construct() {
		global $connection;
		$this->connect=$connection;
	}
	
    private function ensureDirectoryExists() {
        if (!file_exists($this->upload_dir)) {
            mkdir($this->upload_dir, 0755, true);
        }
    }
    
	function upload_docs(){
        $this->ensureDirectoryExists();
        
        $code = $_POST['code'];
        $prg = $_POST['prg'];
        $files = array();
        $missing_required_docs = array();
        
        $stmt = $this->connect->prepare("SELECT *, requered_status FROM tbl_document_type WHERE (prg_type='".$prg."' OR prg_type=0) AND status=1");
        $stmt->execute();
        
        // First pass: Check for missing required documents
        while ($docData = $stmt->fetch()) {
            $default_file_name = $docData['file_name'];
            $file_name = $_FILES[$default_file_name]['name'];
            
            // Check if required file is missing (requered_status = 1)
            if(empty($file_name) && $docData['requered_status'] == 1){
                array_push($missing_required_docs, $docData['document_name']);
            }
        }
        
        // If there are missing required documents, return error
        if(!empty($missing_required_docs)){
            $missing_docs_list = implode(', ', $missing_required_docs);
            $data = array("status" => "error", "message" => "Missing required documents: " . $missing_docs_list);
            echo json_encode($data);
            return;
        }
        
        // Second pass: Process file uploads
        $stmt = $this->connect->prepare("SELECT *, requered_status FROM tbl_document_type WHERE (prg_type='".$prg."' OR prg_type=0) AND status=1");
        $stmt->execute();
        
        while ($docData = $stmt->fetch()) {
            $default_file_name = $docData['file_name'];
            $default_file_type = $docData['file_type'];
            
            $errors = array();
        	$file_name = $_FILES[$default_file_name]['name'];
        	$file_size = $_FILES[$default_file_name]['size'];
        	$file_tmp = $_FILES[$default_file_name]['tmp_name'];
        	$file_type = $_FILES[$default_file_name]['type'];
        	
        	// Skip if no file uploaded and it's not required (requered_status = 2)
        	if(empty($file_name) && $docData['requered_status'] == 2){
        	    continue;
        	}
        	
        	// Skip if no file (we already checked required files above)
        	if(empty($file_name)){
        	    continue;
        	}
        	
        	$tmp = explode('.', $_FILES[$default_file_name]['name']);   
        	$file_ext = strtolower(end($tmp));
        	
            $extensions_img = array("jpeg", "jpg", "png");
            $extensions_pdf = array("pdf");
            
            // Check file size first
            if ($file_size > 2097152) {
                array_push($errors, 'File size must be exactly 2 MB');
                $data = array("status" => "error", "message" => "File size must be at most 2MB for " . $docData['document_name']);
                echo json_encode($data);
                break;
            }
            
            // Validate file extension based on document type
            if (!empty($docData['file_type'])) {
                if($docData['file_type'] == "image"){
                    if (!in_array($file_ext, $extensions_img)) {
                        array_push($errors, 'extension not allowed.');
                        $data = array("status" => "error", "message" => "Image extension not allowed for " . $docData['document_name'] . ". Only jpg, jpeg, png allowed.");
                        echo json_encode($data);
                        break;
                    }  
                } else {
                    if (!in_array($file_ext, $extensions_pdf)) {
                        array_push($errors, 'extension not allowed.');
                        $data = array("status" => "error", "message" => "Document extension not allowed for " . $docData['document_name'] . ". Only PDF allowed.");
                        echo json_encode($data);
                        break;
                    }
                }
            }
            
            $file=array("default_file_name" => $default_file_name, "file_ext" => $file_ext,"file_tmp"=>$file_tmp);
            array_push($files,$file); 
        }
        $in=0;
        $ino=0;
        if (empty($errors) == true) {
            for($i=0;$i<count($files);$i++){
                $new_file_name = $files[$i]['default_file_name'] . "_" . $code . "." . $files[$i]['file_ext'];
                $new_file_name = str_replace('/', '-', $new_file_name);
                $checkExists=$this->connect->prepare("SELECT * FROM tbl_application_doc WHERE upload_doc='/student_docs/$new_file_name'");
                $checkExists->execute();
                if($checkExists->rowCount()==0){
                    move_uploaded_file($files[$i]['file_tmp'], "../../../student_docs/" . $new_file_name);
                    $doc = "/student_docs/" . $new_file_name;
                    $stmt = $this->connect->prepare("INSERT INTO tbl_application_doc(tracking_id,upload_doc) VALUES ('" . $code . "','" . $doc . "')");
                    if($stmt->execute()){
                        $in++;
                    }
                }
                else
                    {
                        move_uploaded_file($files[$i]['file_tmp'], "../../../student_docs/" . $new_file_name);
                        $ino++;
                    }
            }
            if ($in>0) {
                $data = array("status" => "200", "message" => "Documents uploaded!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;
            }
            elseif($ino>0){
                $data = array("status" => "200", "message" => "Some document already exists");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;
            }
            else{
                $data = array("status" => "500", "message" => "Failed to save data!");
                $jsonData = json_encode($data);
                header('Content-Type: application/json');
                echo $jsonData;  
        	}
        }
    }

    function upload_single_doc() {
        $this->ensureDirectoryExists();

        $code = $_POST['code'];
        $prg = $_POST['prg'];
        $default_file_name = $_POST['file_name'];

        if (empty($default_file_name) || empty($_FILES[$default_file_name]['name'])) {
            $data = array("status" => "error", "message" => "Please select a file to upload.");
            echo json_encode($data);
            return;
        }

        $stmt = $this->connect->prepare("SELECT *, requered_status FROM tbl_document_type WHERE file_name = ? AND (prg_type = ? OR prg_type = 0) AND status = 1");
        $stmt->execute([$default_file_name, $prg]);
        $docData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$docData) {
            $data = array("status" => "error", "message" => "Invalid document type.");
            echo json_encode($data);
            return;
        }

        $docPrefix = "/student_docs/" . $default_file_name . "_" . $code . ".";
        $checkExists = $this->connect->prepare("SELECT * FROM tbl_application_doc WHERE tracking_id = ? AND upload_doc LIKE ?");
        $checkExists->execute([$code, $docPrefix . '%']);
        $existingDoc = $checkExists->fetch(PDO::FETCH_ASSOC);
        if ($existingDoc) {
            $data = array(
                "status" => "401",
                "message" => $docData['document_name'] . " has already been uploaded.",
                "appl_doc_id" => $existingDoc['appl_doc_id'],
                "upload_doc" => $existingDoc['upload_doc']
            );
            echo json_encode($data);
            return;
        }

        $file_name = $_FILES[$default_file_name]['name'];
        $file_size = $_FILES[$default_file_name]['size'];
        $file_tmp = $_FILES[$default_file_name]['tmp_name'];
        $tmp = explode('.', $file_name);
        $file_ext = strtolower(end($tmp));

        $extensions_img = array("jpeg", "jpg", "png");
        $extensions_pdf = array("pdf");

        if ($file_size > 2097152) {
            $data = array("status" => "error", "message" => "File size must be at most 2MB for " . $docData['document_name']);
            echo json_encode($data);
            return;
        }

        if (!empty($docData['file_type'])) {
            if ($docData['file_type'] == "image") {
                if (!in_array($file_ext, $extensions_img)) {
                    $data = array("status" => "error", "message" => "Image extension not allowed for " . $docData['document_name'] . ". Only jpg, jpeg, png allowed.");
                    echo json_encode($data);
                    return;
                }
            } else {
                if (!in_array($file_ext, $extensions_pdf)) {
                    $data = array("status" => "error", "message" => "Document extension not allowed for " . $docData['document_name'] . ". Only PDF allowed.");
                    echo json_encode($data);
                    return;
                }
            }
        }

        $new_file_name = $default_file_name . "_" . $code . "." . $file_ext;
        $new_file_name = str_replace('/', '-', $new_file_name);
        $doc = "/student_docs/" . $new_file_name;

        if (!move_uploaded_file($file_tmp, $this->upload_dir . $new_file_name)) {
            $data = array("status" => "500", "message" => "Failed to save " . $docData['document_name'] . "!");
            echo json_encode($data);
            return;
        }

        $insert = $this->connect->prepare("INSERT INTO tbl_application_doc (tracking_id, upload_doc) VALUES (?, ?)");
        $insert->execute([$code, $doc]);

        $data = array(
            "status" => "200",
            "message" => $docData['document_name'] . " uploaded successfully!",
            "appl_doc_id" => $this->connect->lastInsertId(),
            "upload_doc" => $doc
        );
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    function delete_doc() {
        $appl_doc_id = $_POST['appl_doc_id'] ?? '';
        $code = $_POST['code'] ?? '';

        if (empty($appl_doc_id) || empty($code)) {
            $data = array("status" => "error", "message" => "Invalid delete request.");
            echo json_encode($data);
            return;
        }

        $stmt = $this->connect->prepare("SELECT * FROM tbl_application_doc WHERE appl_doc_id = ? AND tracking_id = ?");
        $stmt->execute([$appl_doc_id, $code]);
        $docRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$docRow) {
            $data = array("status" => "error", "message" => "Document not found.");
            echo json_encode($data);
            return;
        }

        $filePath = '../../..' . $docRow['upload_doc'];
        if (is_file($filePath)) {
            unlink($filePath);
        }

        $delete = $this->connect->prepare("DELETE FROM tbl_application_doc WHERE appl_doc_id = ? AND tracking_id = ?");
        if ($delete->execute([$appl_doc_id, $code])) {
            $data = array("status" => "200", "message" => "Document deleted. You can upload a new file.");
            header('Content-Type: application/json');
            echo json_encode($data);
        } else {
            $data = array("status" => "500", "message" => "Failed to delete document.");
            echo json_encode($data);
        }
    }

function upload_image() {
    $this->ensureDirectoryExists();
    
    $imageData = $_POST['image'];
    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));
    
    $code = $_POST['code'];
    $date = date('Y-m-d_H-i-s');
    $filename = 'photo_' . $code . '_' . $date . '.png';
    $filename = str_replace('/', '-', $filename);
    $destination_folder = "../../../student_docs/";
    $img = "/student_docs/" . $filename;
    
    $tempImagePath = $destination_folder . 'temp_' . $filename;
    file_put_contents($tempImagePath, $imageData);
    
    function removeBackground($imagePath) {
        $apiKey = 'iEEtM6XZtCoTkPxPpy8Nk5BP';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.remove.bg/v1.0/removebg');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'image_file' => curl_file_create($imagePath),
            'size' => 'auto'
        ]);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-Api-Key: ' . $apiKey,
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
    
        if ($response === false) {
            return false;
        }
    
        return $response;
    }
    
    $backgroundRemovedImageContent = removeBackground($tempImagePath);
    
    if ($backgroundRemovedImageContent === false) {
        echo "Error removing background";
        return;
    }
    
    file_put_contents($tempImagePath, $backgroundRemovedImageContent);

    if (file_exists($tempImagePath)) {
        $new_file_name = $destination_folder . $filename;
        if (rename($tempImagePath, $new_file_name)) {
            $getPrev = $this->connect->prepare("SELECT upload_doc FROM tbl_application_doc WHERE tracking_id = ? AND upload_doc LIKE '/student_docs/photo_%'");
            $getPrev->execute([$code]);
            if ($getPrev->rowCount() > 0) {
                $prevImage = $getPrev->fetch();
                $file_name_prefix = $prevImage['upload_doc'];
                $file_path_pattern = '../../..' . $file_name_prefix . '*';
                $files_to_delete = glob($file_path_pattern);
                foreach ($files_to_delete as $file_path) {
                    if (is_file($file_path)) {
                        unlink($file_path);
                    }
                }
            }
            
            $checkExists = $this->connect->prepare("SELECT * FROM tbl_application_doc WHERE tracking_id = ? AND upload_doc LIKE '/student_docs/photo_%'");
            $checkExists->execute([$code]);
            if ($checkExists->rowCount() == 0) {
                $stmt = $this->connect->prepare("INSERT INTO tbl_application_doc(tracking_id, upload_doc) VALUES (?, ?)");
                $stmt->execute([$code, $img]);
            } else {
                $stmt = $this->connect->prepare("UPDATE tbl_application_doc SET upload_doc = ? WHERE tracking_id = ? AND upload_doc LIKE '/student_docs/photo_%'");
                $stmt->execute([$img, $code]);
            }

            if ($stmt->execute()) {
                echo 200;
            } else {
                echo 500;
            }
        }
    }
}

    }	
		$doc=new Document();
	    $action = $_POST['action'];
		switch($action){
		    case 'upload_docs':
		        $doc->upload_docs();
		        break;
		    case 'upload_single_doc':
		        $doc->upload_single_doc();
		        break;
		    case 'delete_doc':
		        $doc->delete_doc();
		        break;
		    case 'upload_image':
		        $doc->upload_image();
		        break;
		}

	?>

