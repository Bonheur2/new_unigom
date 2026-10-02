<?php
header('Content-type: text/html; charset=utf-8');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=All users.xls"); 
include ('../../meet/con.php');
$camp_id=$_REQUEST['cId'];
$st=$conn->prepare("SELECT * FROM tbl_campus WHERE camp_id='".$camp_id."'");
$st->execute();
$dataC=$st->fetch();
$campus_name=$dataC['camp_full_name']
?>
<h1>
    NJALA UNIVERSITY
</h1>
<h2>
  <?php echo $campus_name; ?>  
</h2>
<table class="table table-hover table-sm" border="1" style="width:80%">
    <thead>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
    </thead>
    <tbody>
        <?php
        $query=$conn->prepare("SELECT 
                                tbl_users.*, 
                                tbl_user_roles.role 
                                    FROM tbl_users 
                                INNER JOIN tbl_user_roles ON tbl_users.role_id=tbl_user_roles.role_id 
                                    WHERE tbl_users.campus_id='".$camp_id."' AND tbl_users.role_id!=18 ORDER BY tbl_users.role_id ASC");
        $query->execute();
        $i=1;
        while($user=$query->fetch()){
        ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo $user['first_name']." ".$user['family_name']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td><?php echo $user['role']; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>