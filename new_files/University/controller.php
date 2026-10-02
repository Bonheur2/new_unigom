<?php
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'].DS.'');
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'meet');

require_once(LIB_PATH.DS."session.php");
require_once(LIB_PATH.DS."con.php");
require_once(LIB_PATH.DS."bind.php");

$conn->exec("SET NAMES utf8mb4");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

header('Content-Type: application/json; charset=utf-8');

class University{
    private $connect;
    private $upload_dir = 'uploads/university/';

    public function __construct(){
        global $conn;
        $this->connect = $conn;
    }

    private function handle_logo_upload(){
        if(!isset($_FILES['logo']) || $_FILES['logo']['error'] !== UPLOAD_ERR_OK){
            return null;
        }

        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
        $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        if(!in_array($ext, $allowed)){
            return false;
        }

        $target_dir = SITE_ROOT.$this->upload_dir;
        if(!is_dir($target_dir)){
            mkdir($target_dir, 0755, true);
        }

        $filename = 'univ_'.uniqid().'.'.$ext;
        $target_path = $target_dir.$filename;

        if(move_uploaded_file($_FILES['logo']['tmp_name'], $target_path)){
            return '/'.$this->upload_dir.$filename;
        }
        return false;
    }

    public function save_university(){
        $full_name = trim($_POST['full_name']);
        $short_name = trim($_POST['short_name']);
        $country = trim($_POST['country']);
        $email = trim($_POST['email']);
        $website = trim($_POST['website']);
        $phone = trim($_POST['phone']);
        $location = trim($_POST['location']);
        $po_box = trim($_POST['po_box']);
        $reg_date = $_POST['reg_date'] != '' ? $_POST['reg_date'] : null;

        if($full_name == ''){
            echo json_encode(['status' => 401, 'message' => 'Full name is required']);
            return;
        }

        try{
            $chk = $this->connect->prepare("SELECT id FROM tbl_university WHERE full_name = :full_name");
            $chk->execute([':full_name' => $full_name]);
            if($chk->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'This university already exists']);
                return;
            }

            $logo = $this->handle_logo_upload();
            if($logo === false){
                echo json_encode(['status' => 401, 'message' => 'Invalid logo file type']);
                return;
            }

            $sql = $this->connect->prepare("INSERT INTO tbl_university (full_name, short_name, country, email, website, phone, location, po_box, logo, reg_date)
                                            VALUES (:full_name, :short_name, :country, :email, :website, :phone, :location, :po_box, :logo, :reg_date)");
            $sql->execute([
                ':full_name' => $full_name,
                ':short_name' => $short_name,
                ':country' => $country,
                ':email' => $email,
                ':website' => $website,
                ':phone' => $phone,
                ':location' => $location,
                ':po_box' => $po_box,
                ':logo' => $logo,
                ':reg_date' => $reg_date
            ]);
            echo json_encode(['status' => 200, 'message' => 'University saved successfully']);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error saving university: '.$e->getMessage()]);
        }
    }

    public function view_university(){
        $id = $_POST['id'];

        $sql = $this->connect->prepare("SELECT id, full_name, short_name, country, email, website, phone, location, po_box, logo, reg_date FROM tbl_university WHERE id = :id");
        $sql->execute([':id' => $id]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        if($row){
            echo json_encode($row);
        } else {
            echo json_encode(['status' => 500, 'message' => 'University not found']);
        }
    }

    public function update_university(){
        $id = $_POST['id'];
        $full_name = trim($_POST['full_name']);
        $short_name = trim($_POST['short_name']);
        $country = trim($_POST['country']);
        $email = trim($_POST['email']);
        $website = trim($_POST['website']);
        $phone = trim($_POST['phone']);
        $location = trim($_POST['location']);
        $po_box = trim($_POST['po_box']);
        $reg_date = $_POST['reg_date'] != '' ? $_POST['reg_date'] : null;

        if($full_name == ''){
            echo json_encode(['status' => 401, 'message' => 'Full name is required']);
            return;
        }

        try{
            $chk = $this->connect->prepare("SELECT id FROM tbl_university WHERE full_name = :full_name AND id != :id");
            $chk->execute([':full_name' => $full_name, ':id' => $id]);
            if($chk->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'This university already exists']);
                return;
            }

            $logo = $this->handle_logo_upload();
            if($logo === false){
                echo json_encode(['status' => 401, 'message' => 'Invalid logo file type']);
                return;
            }

            if($logo !== null){
                $sql = $this->connect->prepare("UPDATE tbl_university SET full_name=:full_name, short_name=:short_name, country=:country, email=:email, website=:website, phone=:phone, location=:location, po_box=:po_box, logo=:logo, reg_date=:reg_date WHERE id=:id");
                $sql->execute([
                    ':full_name' => $full_name,
                    ':short_name' => $short_name,
                    ':country' => $country,
                    ':email' => $email,
                    ':website' => $website,
                    ':phone' => $phone,
                    ':location' => $location,
                    ':po_box' => $po_box,
                    ':logo' => $logo,
                    ':reg_date' => $reg_date,
                    ':id' => $id
                ]);
            } else {
                $sql = $this->connect->prepare("UPDATE tbl_university SET full_name=:full_name, short_name=:short_name, country=:country, email=:email, website=:website, phone=:phone, location=:location, po_box=:po_box, reg_date=:reg_date WHERE id=:id");
                $sql->execute([
                    ':full_name' => $full_name,
                    ':short_name' => $short_name,
                    ':country' => $country,
                    ':email' => $email,
                    ':website' => $website,
                    ':phone' => $phone,
                    ':location' => $location,
                    ':po_box' => $po_box,
                    ':reg_date' => $reg_date,
                    ':id' => $id
                ]);
            }
            echo json_encode(['status' => 200, 'message' => 'University updated successfully']);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error updating university']);
        }
    }

    public function delete_university(){
        $id = $_POST['id'];

        try{
            $chk = $this->connect->prepare("SELECT camp_id FROM tbl_campus WHERE university_id = :id");
            $chk->execute([':id' => $id]);
            if($chk->rowCount() > 0){
                echo json_encode(['status' => 401, 'message' => 'Cannot delete: campuses are linked to this university']);
                return;
            }

            $sql = $this->connect->prepare("DELETE FROM tbl_university WHERE id = :id");
            $sql->execute([':id' => $id]);

            echo json_encode(['status' => 200, 'message' => 'University deleted successfully']);
        } catch(PDOException $e){
            echo json_encode(['status' => 500, 'message' => 'Error deleting university']);
        }
    }
}

$university = new University();
$action = $_POST['action'];

switch($action){
    case 'register':
        $university->save_university();
        break;
    case 'view':
        $university->view_university();
        break;
    case 'update':
        $university->update_university();
        break;
    case 'delete':
        $university->delete_university();
        break;
    default:
        echo json_encode(['status' => 401, 'message' => 'Invalid action']);
        break;
}
