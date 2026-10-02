<?php
include ('../../../meet/con.php');

if (isset($_POST['post_id']) && !empty($_POST['post_id'])) {
    $post_id = $_POST['post_id'];
    
    if ($post_id == 3) {
        $stmt = $conn->prepare("SELECT * FROM staff_post");
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("SELECT * FROM staff_post WHERE staff_type_id = ?");
        $stmt->execute([$post_id]);
    }
    

    echo '<option value=""></option>';
    while ($row = $stmt->fetch()) {
        $fullName = $row['staff_post_full_name'];
        echo '<option value="' . $row['staff_post_id'] . '">' . htmlspecialchars($fullName) . '</option>';
    }
} else {
    echo '<option value=""></option>';
}
?>
