<?php 
include ('../../meet/con.php');

class BookDepartments
{
    private $connect;

    public function __construct($conn){
        $this->connect = $conn;
    }

    public function save_book_department(){
        if(isset($_POST['dname']) && isset($_POST['level'])){
            $dname = $_POST['dname'];
            $level = $_POST['level'];
            
            $check = $this->connect->prepare("SELECT * FROM tbl_books_depart WHERE book_dep_name=? AND level=?");
            $check->execute([$dname, $level]);
            $row = $check->rowCount();

            if($row > 0){
                $this->response(403, "Data exists!");
            } else {
                $insert = $this->connect->prepare("INSERT INTO tbl_books_depart(book_dep_name, level) VALUES(?, ?)");
                $result = $insert->execute([$dname, $level]);

                if($result){
                    $this->response(200, "Data inserted!");
                } else {
                    $this->response(500, "Sorry, failed!");
                }
            }
        } else {
            $this->response(400, "Invalid input!");
        }
    }
    
    public function get_edit(){
        $bookId=$_POST['bookId'];
        $stmt=$this->connect->prepare("SELECT * FROM tbl_books_depart WHERE book_id=?");
        $stmt->execute([$bookId]);
        $data=$stmt->fetch();
        echo json_encode($data);
    }
    public function update_details(){
        $book_id_serch=$_POST['book_id_serch'];
        $bk_title_search=$_POST['bk_title_search'];
        $level_edit=$_POST['level_edit'];
        $editQuery=$this->connect->prepare("UPDATE tbl_books_depart SET book_dep_name=? , level=? WHERE book_id =?");
        $result=$editQuery->execute([$bk_title_search,$level_edit,$book_id_serch]);
         if($result){
            $this->response(200, "Data updated!");
        } else {
            $this->response(500, "Sorry, failed!");
        }
    }
    public function response($status, $message){
        $data = array("status" => $status, "message" => $message);
        echo json_encode($data);
    }
}

if(isset($_POST['action'])){
    $department = new BookDepartments($conn);
    $action = $_POST['action'];

    switch($action){
        case 'create_depBk':
            $department->save_book_department();
            break;
        case 'edit_book':
            $department->get_edit();
            break;
        case 'edit_dep_book':
            $department->update_details();
            break;
        default:
            $department->response(400, "Invalid action!");
            break;
    }
} else {
    echo json_encode(array("status" => 400, "message" => "Action not set!"));
}
?>
