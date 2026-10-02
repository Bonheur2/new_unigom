<div class="modal fade" id="modalCart5" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
          </div>
      
         <div class="modal-body">
              <embed id="iframeContent"  width="100%" height="100%">
            </div>
        </div>
  </div>
</div>
<div class="main-content">
    <section class="section">
     
     <div class="card">
    <div class="card-header">
        
        <div class="row col-12">
            <div class="col-lg-9 col-sm-9">
          <h4>Uncleared Books Report</h4>      
            </div>
            <div class="col-lg-3 col-sm-3">
                  <button class="btn btn-primary" type="button" name="exceldownload" id="exceldownload"><i class='fas fa-file-excel'></i>&nbsp;&nbsp;&nbsp;Download Excell</button> 
            </div>
        </div>
            </div>
                <div class="card-body">
                    <div class="table-responsive">
                <table class="table" id="report_delayed">
                    <thead>
                  <th>#</th>  
                  <th>Title</th>  
                  <th>Book code</th> 
                  <th>Reg No</th> 
                  <th>Students</th> 
                  <th>Department</th>
                  <th>Paid (Frw)</th>
                  <th>Remain (Frw)</th>
                  <th></th>
                  </thead>
                  <tbody>
                   <?php
                   $i=0;
            $date = date('Y-m-d');
            $query = $conn->prepare("SELECT a.book_id, a.title,a.book_code, b.*, c.*
                                    FROM book_copies b
                                    INNER JOIN books a ON b.book_id = a.book_id
                                    INNER JOIN borrowdetails c ON b.id = c.book_id
                                    WHERE c.actual_returned > c.date_return AND c.borrow_status='returned' ");
         
            $query->execute();
            while($rows=$query->fetch()){
                $i++;
                $url=$rows['receipt_image'];
                $regNo=$rows['borrow_id'];
                $paid=$rows['amount'];
                $date1 = strtotime($rows['actual_returned']);
                $date2 = strtotime($rows['date_return']);
                $days = floor(($date1  - $date2) / (60 * 60 * 24));
                $f_st=$conn->prepare("SELECT fine FROM books_fine WHERE status=1");
                $f_st->execute();
                $st_fine=$f_st->fetch();
                $fine=$st_fine['fine'];
                $fines=$days*$fine;
                if($fines>$paid){
                    $remain=$fines-$paid;
                }
                else{
                    $remain=0;
                }
                $query2 = $conn->prepare("SELECT  DISTINCT a.fname, a.lname,a.reg_no,c.dept_full_name FROM tbl_register_program_ug b
                                         INNER JOIN tbl_admission a ON b.reg_no = a.reg_no 
                                         INNER JOIN  tbl_department c ON b.dept_id=c.dept_id WHERE a.reg_no = :student");
                $query2->bindParam(':student', $regNo);
                $query2->execute();
                $row = $query2->fetch();
                $row_count=$query2->rowCount();
                if($row_count>0 && $remain>0){
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $rows['title']; ?></td>
                        <td><?php echo $rows['bar_code_copy']; ?></td>
                        <td><?php echo $regNo; ?></td>
                        <td><?php echo $row['fname'].' '.$row['lname']; ?></td>
                        <td><?php echo $row['dept_full_name']; ?></td>
                        <td><?php echo $paid; ?></td>
                        <td><?php echo $remain ?></td>
                       <td>
                        <button onclick="openPrintWindow('circulation/fines/<?php echo $url; ?>')" type="button" class="btn btn-icon bt-sm border-0 waves-effect waves-light btn-success" data-toggle="tooltip" data-placement="top" title="View">
                                        <i class="fa fa-eye"></i>
                                    </button>  
                       </td> 
                    </tr>
                 <?php  } }    
                                                      
                   ?>
                  </tbody>
                 </table>
                </div>
                </div>
              </div>
              
             </section>
            </div>
            
            <script>
              $(document).ready(function () {
                   $("#exceldownload").click(function() {
                window.open("booksReport/Excell/uncleared_excell.php", "_blank");
            
              });
              });
            </script>
            <!--scripts end-->
             <script>
                                function openPrintWindow(url) {
                                    $("#iframeContent").attr("src", url);
                                    $("#modalCart5").modal('show');
                                }
                            </script>