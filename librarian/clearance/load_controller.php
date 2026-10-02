

<?php
include ('../../meet/con.php');
$action=$_POST['action'];
if($action=="load_fines"){
    $i=0;
    $regNo=$_POST['stu'];
    $Query=$conn->prepare("SELECT * FROM borrowdetails WHERE borrow_id='".$regNo."' AND borrow_status!='lost' AND actual_returned>date_return ");
    $Query->execute();
    $data_query=$Query->rowCount();
    if($data_query>0){
      while($student=$Query->fetch()){
        $i++;
        $date1 = strtotime($student['actual_returned']);
        $date2 = strtotime($student['date_return']);
        $days = floor(($date1  - $date2) / (60 * 60 * 24));
        $sql2=$conn->prepare("SELECT fine FROM books_fine WHERE status=1");
        $sql2->execute();
        $fine=$sql2->fetch();
        $fineAmount=$fine['fine'];
        $fines_formula=$days*$fineAmount;
        $amountpaid=$student['amount'];
        $remain=$fines_formula-$amountpaid;
        $bc=$student['book_id'];
        
        $Query1=$conn->prepare("SELECT bk.title,bc.bar_code_copy FROM books bk INNER JOIN book_copies bc ON 
        bk.book_id=bc.book_id WHERE bc.id='".$bc."'");
        $Query1->execute();
        $book=$Query1->fetch();
        
        $Quer=$conn->prepare("SELECT DISTINCT fname,lname FROM tbl_admission WHERE reg_no='".$student['borrow_id']."' ");
        $Quer->execute();
        $data_stu=$Quer->fetch();
        
        ?>
      <tr>
          <td><?php echo $i++; ?></td>
          <td><?php echo $data_stu['fname'];?></td>
          <td><?php echo $data_stu['lname']; ?></td>
          <td><?php echo $student['borrow_id']; ?></td>
          <td><?php echo $book['title'].' [ '.$book['bar_code_copy'] .' ]'; ?></td>
          <td class="amount"><?php echo $remain; ?> </td>
          <td> <span  class=""  id="fineclear" data-id="<?php echo $student['borrow_details_id'] ?>"><span id="spinner_fine"></span><i class="fa fa-eraser" aria-hidden="true"></i></span></td>
          
      </tr>  
    <?php }   
    }
   else{
       ?>
       <tr>
        <td colspan="7" style="text-align:center">No data found for this reg no : <?php echo $regNo ?></td>
        </tr> 
   <?php }
}
else if($action=="load_losted"){
    $i=0;
    $regNo=$_POST['stu'];
    $Query=$conn->prepare("SELECT * FROM borrowdetails WHERE borrow_id='".$regNo."' AND borrow_status='lost'");
    $Query->execute();
    $row=$Query->rowCount();
    if($row>0){
     while($student=$Query->fetch()){
        $i++;
        $amountpaid=$student['amount'];
        $bc=$student['book_id'];
        $Query1=$conn->prepare("SELECT bk.title,bk.price,bc.bar_code_copy FROM books bk INNER JOIN book_copies bc ON 
        bk.book_id=bc.book_id WHERE bc.id='".$bc."'");
        $Query1->execute();
        $book=$Query1->fetch();
        $Quer=$conn->prepare("SELECT DISTINCT fname,lname FROM tbl_admission WHERE reg_no='".$student['borrow_id']."' ");
        $Quer->execute();
        $data_stu=$Quer->fetch();
        ?>
      <tr>
          <td><?php echo $i++; ?></td>
          <td><?php echo $data_stu['fname'];?></td>
          <td><?php echo $data_stu['lname']; ?></td>
          <td><?php echo $student['borrow_id']; ?></td>
          <td><?php echo $book['title'].' [ '.$book['bar_code_copy'] .' ]'; ?></td>
          <td class="amount"><?php echo $book['price']; ?> </td>
          <td style="display:inline"> 
          <?php
          if($amountpaid>=$book['price']){
              ?>
           <span class="badge badge-success">Cleared</span>
          <?php }
          else{
              ?>
              <div class="row col-12">
              <div class="col-lg-6 col-md-6">
              <p><span  class="badge badge-primary retun"  id="return" data-id="<?php echo $student['borrow_details_id'] ?>"><span id="spinner_return"></span>Return</span></p>    
              </div>&nbsp;
              <div class="col-lg-6 col-md-6">
                  <p><span  class="badge badge-warning fineclear"  id="fineclear" data-id="<?php echo $student['borrow_details_id'] ?>"><span id="spinner_fine"></span><i class="fa fa-eraser" aria-hidden="true"></i></span></p>
              </div>
          </div>
            
         <?php }
          ?>
          
          
          </td>
         </tr>  
         
    <?php }    
    }
    else{
        ?>
        <tr>
            <td colspan="7" style="text-align:center"> Oops no data found for this reg no: <?php echo $regNo ?> </td>
        </tr>
  <?php  }
     
}
?>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
     $('body').on('click', '#fineclear', function () {
         var data_id = $(this).data('id');
         var paid=parseInt($(".amount").text());
         $("#amunt_clreared").val(paid);
         $("#book_borrowd_id").val(data_id);
         $("#book_price").val(paid);
         $("#add_info").modal('show');
      });
      
      $('body').on('click', '#return', function () {
         var data_id = $(this).data('id');
         var styledText = 'The book has been returned' ;
  // Display confirmation dialog
  Swal.fire({
    title: "Are you sure?",
    html: styledText,
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Yes",
    cancelButtonText: "No"
  }).then((result) => {
    if (result.isConfirmed) {
     var formDataR={
         borroWId:data_id,
         action:"return_losted"
     }
      $('#spinner_return').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                url: "clearance/get_amount.php",
                type: "POST",
                data: formDataR,
                dataType: "JSON",
                success: function(data){
               $('#spinner_return').fadeOut('fast');
                  if(data.status==200){
                    pop_up_success(data.message);  
                  }
                  if(data.status==500){
                   pop_wrong(data.message);    
                  }
                  },error: function(){
                    $('#spinner_return').fadeOut('fast');
                    pop_wrong("Something went wrong!");
                    
                }
             });
    }
       
      });
      });
      
      function pop_wrong(feedback) {
        iziToast.warning({
        title: 'Error',
        message: feedback,
        position: 'topCenter'
      });
    }
    
   function pop_up_success(feedback) {
    iziToast.success({
    title: 'info',
    message: feedback,
    position: 'topCenter'
  });
    }
</script>