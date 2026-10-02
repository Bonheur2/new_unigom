<?php
include ('../../meet/con.php');

if (isset($_POST['post_id']) && !empty($_POST['post_id'])) {
    $post_id = $_POST['post_id'];

    $stmt = $conn->prepare("SELECT staff_id, family_name, first_name FROM tbl_staff_info WHERE Post = ?");
    $stmt->execute([$post_id]);

    echo '<option value=""></option>';
    while ($row = $stmt->fetch()) {
        $fullName = $row['first_name'] . ' ' . $row['family_name'];
        echo '<option value="' . $row['staff_id'] . '">' . htmlspecialchars($fullName) . '</option>';
    }
} else {
    echo '<option value="">No staff found</option>';
}
?>
