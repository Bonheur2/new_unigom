<?php  include'infrom.php'; ?>
<?php  include'bar.php'; ?>
<?php  include'subbar.php'; ?>
<?php include 'meet/con.php' ?>
        <!-- Start top menu -->
    <style>
        .bookss{
            max-height:max-content;
            overflow-y: auto;

        }
         @media (max-width: 400px) { 
    .target_level {
        margin-top: 40px !important; 
    }
}

    </style>   

        <!-- Start app main Content -->
        <div class="main-content">
            <section class="section">
                <div class="row  target_level">
                       <div class="buttons">
                      <?php 
                 $stmt = $conn->prepare("SELECT * FROM  tbl_books_depart WHERE status=1 ORDER BY book_dep_name ASC");
                    $stmt->execute();
                    if($row=$stmt->rowCount()>0){
                        while($row=$stmt->fetch()){
                        ?>
                           <a href="#" class="btn btn-primary car_leve" data-value="<?php echo $row['book_id'] ?> "><?php echo $row['book_dep_name'] ?></a>
                      <?php
                                            
                     }} ?>
                   </div> 
                </div>
            
 <div class="section-header bookss">
     
                 <div class="row col-12 getData">
                      <table class="table table-striped" id="listEbook">
                          <thead>
                                   <tr>
                                    <th>#</th>
                                    <th>Book Name</th>
                                    <th>Type</th>
                                    <th>Date Modified</th>
                                     </tr>
                                    </thead>
                           <tbody id="getresults"> 
                           </tbody>
                         </table>  
                    </div>
                 </div>
                </div>
              
            </section> 
        </div>
       <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

$(document).ready(function(){
  $('.car_leve').on('click', function() {
    // Retrieve the data-value attribute and alert it
      const dataValue = $(this).data('value');
      var formData={
               value:dataValue,
               action:"viewbooks"
           };
            $.ajax({
                        url: 'ListEbooks/view_by_dep.php',
                        type: 'POST',
                        data: formData,
                        success: function(data) {
                        $("#getresults").html(data);
                        },
                        error: function() {
                           alert('An error occurred.');
                        }
                    }); 
    });
});


</script>
  <?php  include'org.php'; ?>       
   <?php  include'comb/coda.php'; ?>     
     
  
