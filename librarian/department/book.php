                <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h1><?php echo $title ?></h1>
                            <div class="section-header-breadcrumb">
                                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                                <div class="breadcrumb-item"><a href="#">Books Department</a></div>
                            </div>
                        </div>
                       <div class="section-body">
                           <div class="row">
                                <div class="col-12 col-md-12 col-lg-12">
                                    <form id="create_dep" action="" method="POST">
                                        <input type="hidden" name="campus" value="<?php echo $camp_id; ?>">
                                        <input type="hidden" name="action" value="create_depBk">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>New department</h4>
                                                <div class="card-header-action">
                                                    <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                                </div>
                                            </div>
                                            <div class="collapse hide" id="mycard-collapse">
                                                <div class="card-body row">
                                                    <div class="form-group col-12 col-sm-6 col-lg-4">
                                                        <label>Department name</label>
                                                        <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                            <i class="fas fa-user"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="dname" placeholder="e.g. Agriculture General" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-6 col-lg-4">
                                                        <label>Level</label>
                                                        <select class="form-control select2" style="width:100%;" name="level" id="level" required>
                                                            <?php
                                                                $sql=$conn->prepare("SELECT prgtype.prg_type_full_name,lv.* 
                                                                FROM tbl_level lv INNER JOIN  tbl_program_type prgtype ON lv.prg_type=prgtype.prg_type_id WHERE 
                                                                prgtype.campus_id='".$camp_id."'");
                                                                $sql->execute();
                                                                while($role=$sql->fetch()){
                                                            ?>
                                                            <option value="<?php echo $role['level_id']; ?>"><?php echo $role['level_full_name'].' | '.$role['prg_type_full_name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                     <div class="form-group col-12 col-sm-6 col-lg-4 mt-4">
                                                        <button type="submit" class="btn btn-primary btn-sm" id="cBtn"><span id="spinner"></span>&nbsp; <span id="indicator">Submit</span></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-12 col-md-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Registered Books Department</h4>
                                        </div>
                                        <div class="card-body row">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-sm" id="books_dep_table">
                                                    <thead>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Action</th>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $query=$conn->prepare("
                                                        SELECT bd.*, lev.level_full_name,prgtype.prg_type_full_name FROM 
                                                        tbl_books_depart bd INNER JOIN tbl_level lev ON bd.level=lev.level_id 
                                                        INNER JOIN tbl_program_type prgtype ON prgtype.prg_type_id=lev.prg_type
                                                        ");
                                                        $query->execute();
                                                        $i=1;
                                                        while($bkdep=$query->fetch()){
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $i++; ?></td>
                                                            <td><?php echo $bkdep['book_dep_name']." ".' [ '.$bkdep['level_full_name'].' | '.$bkdep['prg_type_full_name'].' ]'; ?></td>
                                                            
                                                            <td>
                                                                <div class="buttons row">
                                                                   <button class="btn btn-primary btn-sm edit" data-id="<?php echo $bkdep['book_id']; ?>"><span id="spinner1_<?php echo $bkdep['book_id']; ?>"></span>&nbsp;<i class="fas fa-edit"></i>edit</button>
                                                                </div>
                                                            </td>
                                                          </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
        <!--edit -->
             <div class="modal fade" tabindex="-1" role="dialog" id="exampleModal2">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                         <form action="" id="edit_book_search" method="POST">
                        <div class="modal-header">
                             <h5 class="modal-title">Edit</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                            <label>Department Name</label>
                                 <input type="text" class="form-control" id="bk_title_search" name="bk_title_search" required>
                            </div>
                           <div class="form-group col-12 col-sm-6 col-lg-12">
                                <label>Level</label>
                                <select class="form-control select2" style="width:100%;" name="level_edit" id="level_edit" required>
                                    <?php
                                        $sql=$conn->prepare("SELECT prgtype.prg_type_full_name,lv.* 
                                        FROM tbl_level lv INNER JOIN  tbl_program_type prgtype ON lv.prg_type=prgtype.prg_type_id WHERE 
                                        prgtype.campus_id='".$camp_id."'");
                                        $sql->execute();
                                        while($role=$sql->fetch()){
                                    ?>
                                    <option value="<?php echo $role['level_id']; ?>"><?php echo $role['level_full_name'].' | '.$role['prg_type_full_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            
                        </div>
                        <input type="hidden" id="book_id_serch" name="book_id_serch">
                        <input type="hidden" name="action" value="edit_dep_book">
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary edit_btn"><span id="spinner24"></span>Save changes</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
                <!--end update modal-->
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/cropperjs/dist/cropper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"></script>
<!-- Add the SweetAlert library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function(){
    $('#users').DataTable({ 
      "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "iDisplayLength": 10
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
     //save location
    $("#create_dep").submit(function(e){
            e.preventDefault();
    
            var formData = new FormData(this);
            $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Saving...");
            $.ajax({
                url: "department/controller.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType:"JSON",
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    if(data.status==200){
                        pop_up_success(data.message);
                    }
                    if(data.status==403){
                        pop_wrong(data.message); 
                    }
                    if(data.status==500){
                        pop_wrong(data.message);  
                    }
                },error: function(){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    pop_wrong("Something went wrong!");
                    
                }
             });
          });

 $(document).on('click', '.edit', function(e) {
        e.preventDefault();
         var bookId = $(this).data('id');
         var formData={
            bookId:bookId,
            action:"edit_book"
         };
      $.ajax({
            url: 'department/controller.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(data) {
                $("#book_id_serch").val(bookId);
                $("#bk_title_search").val(data.book_dep_name);  
                var selectLevel = $("#level_edit");
                selectLevel.val(data.level);
                selectLevel.prepend(selectLevel.find("option[value='" + data.level + "']"));
                $("#exampleModal2").modal('show'); 
            },
            error: function() {
                console.log('An error occurred while processing the AJAX request.');
            }
        });
    });
    
        $("#edit_book_search").submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $('#spinner24').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $("#edit_book_search").find('input, select, button').prop('disabled', true);
            $.ajax({
                url: "department/controller.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType:"JSON",
                success: function(data){
                   $("#edit_book_search").find('input, select, button').prop('disabled', false); 
                    $('#spinner24').fadeOut('fast');
                    if(data.status==200){
                        pop_up_success(data.message);
                        $("#exampleModal2").modal('hide'); 
                        $('#books_dep_table').load(location.href + " #books_dep_table");
                    }
                    if(data.status==403){
                        pop_wrong(data.message); 
                    }
                    if(data.status==500){
                        pop_wrong(data.message);  
                    }
                },error: function(){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    pop_wrong("Something went wrong!");
                    
                }
             });
          });
    
});
    </script>