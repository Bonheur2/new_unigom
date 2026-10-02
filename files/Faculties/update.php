<?php
include ('../../meet/con.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM tbl_books WHERE id = ?");
    $stmt->execute([$id]);
    $book = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    
    if (!empty($_FILES['new_file']['name'])) {
        $file_name = $_FILES['new_file']['name'];
        $file_tmp = $_FILES['new_file']['tmp_name'];
        $file_path = "files/Faculties/" . $file_name;
        
        move_uploaded_file($file_tmp, $file_path);
        
        // Update database
        $update = $conn->prepare("UPDATE tbl_books SET file_path = ?, file_name = ? WHERE id = ?");
        $update->execute([$file_name, $file_name, $id]);
        
        echo "<script>alert('File Updated Successfully'); window.location.href='index.php';</script>";
    }
}
?>

<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $book['id'] ?>">
    <label>Current File:</label>
    <a href="/files/Faculties/<?= $book['file_path'] ?>" target="_blank"><?= $book['file_name'] ?></a><br><br>
    
    <label>Upload New File:</label>
    <input type="file" name="new_file" required><br><br>

    <button type="submit" class="btn btn-primary">Update File</button>
</form>
