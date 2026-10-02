<?php
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors to browser
ini_set('log_errors', 1);

ob_start();

// Check if includes exist
if (!file_exists('../../meet/con.php')) {
    ob_clean();
    echo json_encode(['status' => 'error', 'message' => 'Database connection file not found']);
    exit;
}

if (!file_exists('phpqrcode/qrlib.php')) {
    ob_clean();
    echo json_encode(['status' => 'error', 'message' => 'QR Code library not found']);
    exit;
}

include('../../meet/con.php');
include('phpqrcode/qrlib.php');

ob_clean();
header('Content-Type: application/json');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_book') {
    
    try {
        $acad_cycle_id = isset($_POST['acad_cycle_id']) ? $_POST['acad_cycle_id'] : 0;
        $document_name = isset($_POST['document_name']) ? trim($_POST['document_name']) : '';
        
        if ($acad_cycle_id == 0) {
            echo json_encode(['status' => 'error', 'message' => 'Please select an academic year']);
            exit;
        }
        
        if (empty($document_name)) {
            echo json_encode(['status' => 'error', 'message' => 'Please enter a document name']);
            exit;
        }
        
        if (!isset($_FILES['graduation_book'])) {
            echo json_encode(['status' => 'error', 'message' => 'No file uploaded']);
            exit;
        }
        
        $file = $_FILES['graduation_book'];
        
        // Check for upload errors - remove the size check here
        if ($file['error'] !== 0) {
            $error_message = 'Upload error: ';
            switch ($file['error']) {
                case UPLOAD_ERR_INI_SIZE:
                    $error_message .= 'File exceeds PHP upload_max_filesize directive';
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $error_message .= 'File exceeds MAX_FILE_SIZE directive';
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $error_message .= 'File was only partially uploaded';
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $error_message .= 'No file was uploaded';
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $error_message .= 'Missing temporary folder';
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $error_message .= 'Failed to write file to disk';
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $error_message .= 'File upload stopped by extension';
                    break;
                default:
                    $error_message .= 'Unknown error code: ' . $file['error'];
            }
            echo json_encode(['status' => 'error', 'message' => $error_message]);
            exit;
        }
        
        // Validate file type by extension
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['pdf', 'doc', 'docx'];
        
        if (!in_array($file_extension, $allowed_extensions)) {
            echo json_encode(['status' => 'error', 'message' => 'Only PDF and DOC files allowed']);
            exit;
        }
        
        // Create directories
        $upload_dir = '../../uploads/graduation_books/';
        if (!file_exists($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to create upload directory']);
                exit;
            }
        }
        
        $qr_dir = '../../uploads/qr_codes/';
        if (!file_exists($qr_dir)) {
            if (!mkdir($qr_dir, 0777, true)) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to create QR directory']);
                exit;
            }
        }
        
        // Generate filenames
        $file_name = 'book_' . time() . '_' . uniqid() . '.' . $file_extension;
        $file_path = $upload_dir . $file_name;
        
        // Move file
        if (!move_uploaded_file($file['tmp_name'], $file_path)) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save file']);
            exit;
        }
        
        // Generate QR code
        $qr_filename = 'qr_' . time() . '_' . uniqid() . '.png';
        $qr_path = $qr_dir . $qr_filename;
        $document_url = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $file_path;
        
        QRcode::png($document_url, $qr_path, QR_ECLEVEL_L, 10);
        
        // Insert into database
        $sql = $conn->prepare("INSERT INTO tbl_books (acad_cycle_id, document_name, file_name, file_path, qr_code, uploaded_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $result = $sql->execute([$acad_cycle_id, $document_name, $file_name, $file_path, $qr_path]);
        
        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Book uploaded successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database insert failed']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()]);
    }
    
    exit;
}

// Handle update/replace book
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_book') {
    
    try {
        $book_id = isset($_POST['book_id']) ? $_POST['book_id'] : 0;
        $acad_cycle_id = isset($_POST['acad_cycle_id']) ? $_POST['acad_cycle_id'] : 0;
        $document_name = isset($_POST['document_name']) ? trim($_POST['document_name']) : '';
        $keep_qr = isset($_POST['keep_qr']) ? $_POST['keep_qr'] : 0; // New option
        
        if ($book_id == 0) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid book ID']);
            exit;
        }
        
        if ($acad_cycle_id == 0) {
            echo json_encode(['status' => 'error', 'message' => 'Please select an academic year']);
            exit;
        }
        
        if (empty($document_name)) {
            echo json_encode(['status' => 'error', 'message' => 'Please enter a document name']);
            exit;
        }
        
        // Get existing book info
        $sql = $conn->prepare("SELECT * FROM tbl_books WHERE id = ?");
        $sql->execute([$book_id]);
        $existing_book = $sql->fetch(PDO::FETCH_ASSOC);
        
        if (!$existing_book) {
            echo json_encode(['status' => 'error', 'message' => 'Book not found']);
            exit;
        }
        
        // Check if new file is uploaded
        if (isset($_FILES['graduation_book']) && $_FILES['graduation_book']['error'] === 0) {
            
            $file = $_FILES['graduation_book'];
            
            // Validate file type
            $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed_extensions = ['pdf', 'doc', 'docx'];
            
            if (!in_array($file_extension, $allowed_extensions)) {
                echo json_encode(['status' => 'error', 'message' => 'Only PDF and DOC files allowed']);
                exit;
            }
            
            $upload_dir = '../../uploads/graduation_books/';
            $qr_dir = '../../uploads/qr_codes/';
            
            // Handle QR code based on user choice
            if ($keep_qr == 1) {
                // Keep existing QR code and filename - just replace file content
                $file_name = $existing_book['file_name'];
                $file_path = $existing_book['file_path'];
                
                // Delete old file first
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
                
                // Save new file with same name and path
                if (!move_uploaded_file($file['tmp_name'], $file_path)) {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to save file']);
                    exit;
                }
                
                // Update database - keep existing qr_code, file_name, and file_path
                $sql = $conn->prepare("UPDATE tbl_books SET acad_cycle_id = ?, document_name = ?, uploaded_at = NOW() WHERE id = ?");
                $result = $sql->execute([$acad_cycle_id, $document_name, $book_id]);
                
            } else {
                // Generate new filename and QR code
                
                // Delete old file
                if (file_exists($existing_book['file_path'])) {
                    unlink($existing_book['file_path']);
                }
                
                // Upload new file with new name
                $file_name = 'book_' . time() . '_' . uniqid() . '.' . $file_extension;
                $file_path = $upload_dir . $file_name;
                
                if (!move_uploaded_file($file['tmp_name'], $file_path)) {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to save file']);
                    exit;
                }
                
                // Delete old QR code
                if (file_exists($existing_book['qr_code'])) {
                    unlink($existing_book['qr_code']);
                }
                
                // Generate new QR code
                $qr_filename = 'qr_' . time() . '_' . uniqid() . '.png';
                $qr_path = $qr_dir . $qr_filename;
                $document_url = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $file_path;
                QRcode::png($document_url, $qr_path, QR_ECLEVEL_L, 10);
                
                // Update database with new file and QR
                $sql = $conn->prepare("UPDATE tbl_books SET acad_cycle_id = ?, document_name = ?, file_name = ?, file_path = ?, qr_code = ?, uploaded_at = NOW() WHERE id = ?");
                $result = $sql->execute([$acad_cycle_id, $document_name, $file_name, $file_path, $qr_path, $book_id]);
            }
            
        } else {
            // Update only academic year and document name (no new file)
            $sql = $conn->prepare("UPDATE tbl_books SET acad_cycle_id = ?, document_name = ? WHERE id = ?");
            $result = $sql->execute([$acad_cycle_id, $document_name, $book_id]);
        }
        
        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Book updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Update failed']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()]);
    }
    
    exit;
}

// Delete book
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_book') {
    
    try {
        $book_id = isset($_POST['book_id']) ? $_POST['book_id'] : 0;
        
        if ($book_id == 0) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid book ID']);
            exit;
        }
        
        // Get book info
        $sql = $conn->prepare("SELECT * FROM tbl_books WHERE id = ?");
        $sql->execute([$book_id]);
        $book = $sql->fetch(PDO::FETCH_ASSOC);
        
        if ($book) {
            // Delete files
            if (file_exists($book['file_path'])) {
                unlink($book['file_path']);
            }
            if (file_exists($book['qr_code'])) {
                unlink($book['qr_code']);
            }
            
            // Delete from database
            $sql = $conn->prepare("DELETE FROM tbl_books WHERE id = ?");
            $result = $sql->execute([$book_id]);
            
            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Book deleted successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Delete failed']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Book not found']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()]);
    }
    
    exit;
}

// Fetch all books
if (isset($_GET['action']) && $_GET['action'] === 'fetch_books') {
    try {
        $sql = $conn->prepare("SELECT b.*, ac.acad_year FROM tbl_books b 
                               LEFT JOIN tbl_acad_cycle ac ON b.acad_cycle_id = ac.acad_cycle_id 
                               ORDER BY b.uploaded_at DESC");
        $sql->execute();
        $books = $sql->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'data' => $books]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Fetch error: ' . $e->getMessage()]);
    }
    exit;
}

// If no action matched
echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
exit;
?>