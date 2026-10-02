<?php
include ('../../meet/con.php');
$action = $_POST['action'];
if($action=="loadbook_by_location"){
    $i=0;
    $sumC=0;
    $location=$_POST['location'];
    $query1=$conn->prepare("SELECT i.*	FROM 
     books i INNER JOIN books_location b ON i.location=b.id 
     WHERE i.location='".$location."' ");
    $query1->execute();
    $row1=$query1->rowCount();
   while($data1=$query1->fetch()){
       $i++;
       $book_id=$data1['book_id'];
       $query2=$conn->prepare("SELECT COUNT(id) AS total_copies FROM book_copies WHERE book_id='".$book_id."'");
       $query2->execute();
       $data2=$query2->fetch();
       $copies=$data2['total_copies'];
       $sumC+=$copies;
       ?>
       <tr>
           <td><?php echo $i; ?></td>
           <td><?php echo $data1['title']; ?></td>
           <td><?php echo $data1['author']; ?></td>
           <td><?php echo $data1['isbn']; ?></td>
           <td><?php echo $data1['language_publication']; ?></td>
           <td><?php echo $data1['publisher']; ?></td>
           <td><?php echo $data1['price']; ?></td>
           <td><?php echo $data1['bar_code']; ?></td>
           <td><?php if($copies>0) echo $copies; ?></td>
           <td><?php echo $data1['book_class']; ?></td>
       </tr>
  <?php } ?>
   
    <tr style="background-color:gray;color:white">
    <td></td>
    <td>Total Books </td>
    <td><?php echo $row1; ?></td>
    <td></td>
    <td></td>
    <td>Total Copies</td>
    <td></td>
    <td></td>
    <td><?php echo $sumC ?></td>
    <td></td>
    </tr>
  <?php  }

?>