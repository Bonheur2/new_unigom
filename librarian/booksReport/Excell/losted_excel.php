<?php
$action=$_REQUEST['action'];

header('Content-type: text/html; charset=utf-8');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=losted_books.xls");  
require_once '../../../meet/con.php'; 
 ?>
   <div class="row">
      <div class="card col-lg-12">
                               <div class="card-body">
                                    <div class="media">
                                        <div class="media-body">
                                            <div class="row">
                                                <div class="col-lg-9">
                                                </div>
                                                <div class="col-lg-3">
                                                  <p class="mt-0" style="font-size:24px;">Losted Books report</p> 
                                                  <p class="mt-0" style="font-size:24px;">Date : <?php echo date('Y-m-d'); ?></p> 
                                                </div>
                                            </div>
                                       
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
  <table  class="table mb-none" border="1" style="width:100%">
            <thead>
                  <th scope="col">#</th>  
                  <th scope="col">Title</th>  
                  <th scope="col">Book code</th> 
                  <th scope="col">Reg No</th> 
                  <th scope="col">Students</th> 
                  <th scope="col">Department</th>
                  <th scope="col">Issue Date</th>
                  <th scope="col">Price</th>
                 </thead>
            <tbody>
           <?php
            $i=0;
            $query = $conn->prepare("SELECT a.book_id, a.title,a.book_code, a.price,b.*, c.*
                        FROM book_copies b
                        INNER JOIN books a ON b.book_id = a.book_id
                        INNER JOIN borrowdetails c ON b.id = c.book_id
                        WHERE  c.borrow_status = 'lost' ");
            $query->execute();
            while($rows=$query->fetch()){
                $i++;
            $student = $rows['borrow_id'];
            $query2 = $conn->prepare("SELECT a.fname, a.lname,a.reg_no,c.dept_full_name FROM tbl_register_program_ug b
                                     INNER JOIN tbl_admission a ON b.reg_no = a.reg_no 
                                     INNER JOIN  tbl_department c ON b.dept_id=c.dept_id WHERE a.reg_no ='". $student."'");
            $query2->execute();
            $row = $query2->fetch();
           
            ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $rows['title']; ?></td>
                <td><?php echo $rows['bar_code_copy']; ?></td>
                <td><?php echo $student; ?></td>
                <td><?php echo $row['fname'].' '.$row['lname']; ?></td>
                <td><?php echo $row['dept_full_name']; ?></td>
                <td><?php echo $rows['issue_date']; ?></td>
                <td><?php echo $rows['price']; ?></td>
              </tr>
           <?php }
           
           ?>
            </tbody>
         </table>     



