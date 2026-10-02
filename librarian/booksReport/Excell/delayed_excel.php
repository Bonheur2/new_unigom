<?php
$action=$_REQUEST['action'];

header('Content-type: text/html; charset=utf-8');
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=delayed_books.xls");  
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
                                                  <p class="mt-0" style="font-size:24px;">Delayed Books report</p> 
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
                  <th scope="col">return date</th>
                  <th scope="col">Addition Days</th>
                 </thead>
            <tbody>
           <?php
           $i=0;
            $date = date('Y-m-d');
            $query = $conn->prepare("SELECT a.book_id, a.title,a.book_code, b.*, c.*
                                    FROM book_copies b
                                    INNER JOIN books a ON b.book_id = a.book_id
                                    INNER JOIN borrowdetails c ON b.id = c.book_id
                                    WHERE DATE_FORMAT(c.date_return, '%Y-%m-%d') < :date AND c.borrow_status = 'pending'");
            $query->bindParam(':date', $date);
            $query->execute();
            while($rows=$query->fetch()){
                $i++;
            $date1 = strtotime(date('Y-m-d'));
            $date2 = strtotime($rows['date_return']);
            $days = floor(($date1  - $date2) / (60 * 60 * 24));
            $f_st=$conn->prepare("SELECT fine FROM books_fine WHERE status=1");
            $f_st->execute();
            $st_fine=$f_st->fetch();
            $fine=$st_fine['fine'];
            $fines=$days*$fine;
            $student = $rows['borrow_id'];
            $query2 = $conn->prepare("SELECT a.fname, a.lname,a.reg_no,c.dept_full_name FROM tbl_register_program_ug b
                                     INNER JOIN tbl_admission a ON b.reg_no = a.reg_no 
                                     INNER JOIN  tbl_department c ON b.dept_id=c.dept_id WHERE a.reg_no = :student");
            $query2->bindParam(':student', $student);
            $query2->execute();
            $row = $query2->fetch();   
            ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $rows['title']; ?></td>
                <td><?php echo $rows['book_code_number']; ?></td>
                <td><?php echo $row['reg_no']; ?></td>
                <td><?php echo $row['fname'].' '.$row['lname']; ?></td>
                <td><?php echo $row['dept_full_name']; ?></td>
                <td><?php echo $rows['issue_date']; ?></td>
                <td><?php echo $rows['date_return']; ?></td>
                <td><?php echo $days; ?></td>
              </tr>
           <?php }
           
           ?>
            </tbody>
         </table>     



