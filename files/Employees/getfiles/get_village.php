<?php
include ('../../../meet/con.php');

if (isset($_POST['post_id']) && !empty($_POST['post_id'])) {
    $post_id = $_POST['post_id'];

    $stmt = $conn->prepare("SELECT * FROM villages WHERE codecell = ?");
    $stmt->execute([$post_id]);

    echo '<option value=""></option>';
    while ($row = $stmt->fetch()) {
        $fullName = $row['VillageName'];
        echo '<option value="' . $row['CodeVillage'] . '">' . htmlspecialchars($fullName) . '</option>';
    }
} else {
    echo '<option value=""></option>';
}
?>
