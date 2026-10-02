<?php 
include ('../../meet/con.php');

$action = $_POST['action'];
if($action=="viewbooks"){
    $i=0;
    $dep_id=$_POST['value'];
    $query=$conn->prepare("SELECT * FROM books_online WHERE type=1 AND department='".$dep_id."' ");
    $query->execute();
    
    while($data=$query->fetch()){
        $i++;
        ?>
       <tr onclick="window.open('../../librarian/onlinebooks/files/<?php echo $data['book_file']; ?>', '_blank');" style="cursor: pointer;">
        <td><?php echo $i; ?></td>
        <td><?php echo $data['title']; ?></td>
        <td>PDF</td>
        <td><?php echo date('Y-m-d', strtotime($data['created_at'])); ?></td>

    </tr>
    <?php
        
    }
}

?>