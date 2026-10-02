<?php
include ('../../meet/con.php');
$month = $_POST['month'];
$sql = "SELECT s.*, i.first_name, i.family_name
        FROM tbl_staff_salary s
        LEFT JOIN tbl_staff_info i ON s.staff_id = i.staff_id
        WHERE s.ending_date IS NULL OR DATE_FORMAT(s.ending_date, '%Y-%m') >= '".$month."'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$total = 0;
$i=1;
while($data=$stmt->fetch()){
    $total += $data['gross_salary'];
?>
    <tr>
        <td><?php echo $i++ ?></td>
        <td><?php echo $data['first_name'].' '.$data['family_name'] ?></td>
        <td><?php echo number_format($data['gross_salary'],2) ?></td>
    </tr>
<?php } ?>

