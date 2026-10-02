<?php
/**
 * 
 * Author @macintosh
 */
if(isset($_REQUEST['deleteClass'])){
    
    $record =$conn->prepare("delete from tbl_t_schedule where program_type = :prgType AND program_mode = :prgMode AND sub_class_id = :sub_class AND level_id = :level AND term_id = :term");
    
    $record->bindParam(':sub_class', $_REQUEST['deleteClass'], PDO::PARAM_INT);
    $record->bindParam(':prgType', $_REQUEST['prgType'], PDO::PARAM_INT);
    $record->bindParam(':prgMode', $_REQUEST['prgMode'], PDO::PARAM_INT);
    $record->bindParam(':level', $_REQUEST['level'], PDO::PARAM_INT);
    $record->bindParam(':term', $_REQUEST['term'], PDO::PARAM_INT);
    $record->execute();
    if($record){
        ?>
        <script>
                jQuery(function validation(){
                    swal("Great ", "Timetable Deleted Successfully !", "success", {
                        button: "Ok",
                    });
            });
            setTimeout(function() {
                window.location.href = 'edu?mis=timetable';
            }, 3000);
        </script>
        <?php
    }
    else{
         ?>
        <script>
                jQuery(function validation(){
                    swal("Error ", "An Error Occured!", "error", {
                        button: "Ok",
                    });
            });
            setTimeout(function() {
                window.location.href = 'edu?mis=timetable';
            }, 3000);
        </script>
        <?php       
    }
}
?>

</script>
<style>
    /* Add this CSS for the spinner */
    #spinnerid {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;
    }
    
        .spinner {
			margin: 100px auto;
			width: 50px;
			height: 50px;
			border-radius: 50%;
			border: 5px solid #ccc;
			border-top-color: #666;
			animation: spin 1s ease-in-out infinite;
		}    
    	@keyframes spin {
			to {transform: rotate(360deg);}
		}
    
    /* Update this CSS to hide the spinner by default */
    .spinner-border {
        display: none;
    }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
<!-- Start app main Content -->
        <div class="main-content">
                    <section class="section">
                        <div class="section-header">
                            <h3>Time tables</h3>
                            <div class="section-header-breadcrumb">
                                <!--<div class="breadcrumb-item active"><a href="#">Dashboard</a></div>-->
                                <!--<div class="breadcrumb-item"><a href="#">Marks</a></div>-->
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>TimeTable Details</h4>
                                            <div class="card-header-action">
                                                <!--<a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class=""></i></a>-->
                                            </div>
                                        </div>
                                        <div class="collapse show" id="mycard-collapse">
                                                <!--<div class="btn btn-primary float-right">-->
                                                <button type="button" class="btn btn-primary float-right" id="whole" hidden><i class="fa fa-table"></i> All Classes</button>
                                                <button class="btn btn-primary float-right" style="visibility: hidden;"></button>
                                                <button type="button" class="btn btn-primary float-right" id="one" hidden><i class="fa fa-table"></i> This Class</button>
                                                <!--</div>-->
                                        </div>
                                        <div class="card" style='padding: 20px;border-radius: 8px;border: 2px solid #B59820;width:100%;margin-top:1%;margin-bottom:1%;'>
                                            <div class="card-body row">
                                                <div class="form-group col-12 col-sm-4 col-lg-4" id="category">
                                                    <label>Program type</label>
                                                    <select class="form-control select2" name="categ" id="categ" style="width: 100%;">
                                                        <option disabled selected>--choose a mode--</option>
                                                        <?php
                                                        $spec=$conn->prepare("SELECT prg_type_id, prg_type_full_name from tbl_program_type");
                                                        $spec->execute();
                                                        $i=1;
                                                        while($theSpec=$spec->fetch()){
                                                            ?>
                                                            <option value="<?php echo $theSpec['prg_type_id']; ?>"><?php echo $theSpec['prg_type_full_name'];?> </option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <span id="spinner10"></span>
                                                </div>                                                            
                                                        <div class="form-group col-12 col-sm-4 col-lg-4" id="class-div">
                                                            <label>Learning mode</label>
                                                            <select class="form-control select2" select2 name="learning-mode" id="learning-mode" style="width: 100%;">
                                                            </select>
                                                            <span id="spinner0"></span>
                                                        </div>                                                        
                                                        
                                                        <div class="form-group col-12 col-sm-4 col-lg-4" id="class-id" hidden>
                                                            <label>Class</label>
                                                            <select class="form-control select2" name="classid" id="classid" style="width: 100%;">
                                                            </select>
                                                            <span id="spinner1"></span>
                                                        </div>
                                                        
                                                        <div class="form-group col-12 col-sm-4 col-lg-4" id="spec-levels"hidden>
                                                            <label>Level</label>
                                                            <select class="form-control select2" name="level_id" id="level_id" style="width: 100%;">
                                                            </select>
                                                            <span id="spinner2"></span>
                                                        </div>
                                                       
                                                        <div class="form-group col-12 col-sm-4 col-lg-4" id="spec-terms" hidden>
                                                            <label>Term </label>
                                                            <select class="form-control select2" name="term_id" id="term_id" style="width: 100%;">
                                                                <option disabled selected>--choose a mode--</option>
                                                                <?php
                                                                    $sem=$conn->prepare("SELECT * FROM tbl_semester");
                                                                    $sem->execute();
                                                                    while($sems=$sem->fetch()){
                                                                ?>
                                                            <option value="<?php echo $sems['sem_id']; ?>"><?php echo $sems['semester'];?> </option>
                                                            <?php } ?>
                                                            </select>
                                                            <span id="spinner3"></span>
                                                        </div>
                                                        <div class="table-responsive" style="background-color:RGB(255, 255, 255)">
                                                        <table class="table table-striped v_center" id="table-1" border="1">
                                                    	<center>
                                                        	<thead>
                                                                <tr> 
                                                                    <th>Serial</th>
                                                                	<th>Program Type</th>
                                                                	<th>Program Mode</th>
                                                                	<th>Specialization</th>
                                                                	<th>Level</th>
                                                                	<th>Term</th>
                                                                	<th >Action</th>
                                                                </tr>
                                                            </thead>									        
                                                        </center>
                                                        <tbody>
                                                            <?php
                                                            
                                                            // bloc name
                                                            $counter =0;
                                            				$timetable =$conn->prepare("select *from tbl_t_schedule group by sub_class_id, level_id, term_id");
                                            				$timetable->execute();
                                            				while($theTimetable =$timetable->fetch()){
                                            				    
                                            				    // prgtype
                                            				    $counter +=1;
                                            				    $prgType =$conn->prepare("select *from tbl_program_type where prg_type_id = :prgType");
                                            				    
                                            				    $prgType ->bindParam(':prgType',$theTimetable['program_type'], PDO::PARAM_INT);
                                            				    $prgType->execute();
                                            				    $thePrgType =$prgType->fetch();
                                            				    
                                            				    $prgMode =$conn->prepare("select *from tbl_program_mode where prg_mode_id = :prgMode");
                                            				    $prgMode ->bindParam(':prgMode',$theTimetable['program_mode'], PDO::PARAM_INT);
                                            				    $prgMode->execute();
                                            				    $theMode =$prgMode->fetch();
                                            				    
                                            				    $spec =$conn->prepare("select *from tbl_specialization where splz_id = :splz_id");
                                            				    $spec ->bindParam(':splz_id',$theTimetable['sub_class_id'], PDO::PARAM_INT);
                                            				    $spec->execute();
                                            				    $theSpec =$spec->fetch();
                                            				    
                                            				    $level =$conn->prepare("select * from tbl_level where level_id = :lev");
                                            				    $level->bindParam(':lev',$theTimetable['level_id'], PDO::PARAM_INT);
                                            				    $level->execute();
                                            				    $level = $level->fetch();
                                            				    
                                            				    $sem =$conn->prepare("select *from tbl_semester where sem_id = :sem_id");
                                            				    $sem ->bindParam(':sem_id',$theTimetable['term_id'], PDO::PARAM_INT);
                                            				    $sem->execute();
                                            				    $sem =$sem->fetch();
                                            				    
                                            				    ?>
                                            				    <tr> 
                                            				        <td><?php echo $counter;?></td>
                                            				        <td><?php echo $thePrgType['prg_type_full_name'];?></td>
                                            						<td><?php echo $theMode['prg_mode_full_name'];?></td>
                                            						<td><?php echo $theSpec['splz_full_name'];?></td>
                                            						<td><?php echo $level['level_full_name'];?></td>
                                            						<td><?php echo $sem['semester'];?></td>
                                            						<td>
                                            						    <a href="edu?mis=timetable&deleteClass=<?php echo $theTimetable['sub_class_id'];?>&prgType=<?php echo $theTimetable['program_type'];?>&prgMode=<?php echo $theTimetable['program_mode'];?>&level=<?php echo $theTimetable['level_id'];?>&term=<?php echo $theTimetable['term_id'];?>" class="btn btn-primary btn-sm" title="Delete Timetable"><i class="fa fa-trash"></i></a>
                                            						    <a href="edu?mis=view_single_table&viewClass=<?php echo $theTimetable['sub_class_id'];?>&prgType=<?php echo $theTimetable['program_type'];?>&prgMode=<?php echo $theTimetable['program_mode'];?>&level=<?php echo $theTimetable['level_id'];?>&term=<?php echo $theTimetable['term_id'];?>" class="btn btn-success btn-sm" title="View Timetable"><i class="fa fa-eye"></i></a>
                                            						</td>
                                            					</tr>
                                            					<?php
                                            				}
                                            				?>
                                                        </tbody>
                                                    </table> 
                                                </div>
                                                    </div>
                                                    <div id="mydiv"></div>
                                                    <!--<div >-->
                                                        
                                                    <!--</div>-->
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-lg-12" id="spinnerid"></div>
                                    </div>
                                    
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <?php
        include('swapp_hours.php');
        ?>
    <script src=" https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready( function () {
            $('#table-1').DataTable();
        } );
    </script>
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
    
//load levels
        $('#categ').change(function () {
            var getData= {
                        type:$(this).val(),
                        action:'load_levels'
                    };
            $('#spec-levels').attr('hidden',true);
            $('#spec-terms').attr('hidden',true);
            $('#spinner1').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "../../files/Programs/program_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $("#spec-levels").attr('hidden',false);
                    $("#level_id").empty();
                    $('#spinner1').fadeOut('fast');
                    if(data.length>0){
                        $.each(data, function (index, value) {
                                $("#level_id").append("<option value='" + value.level_id + "'>" + value.level_full_name +"</option>");
                            });
                    }
				},
				error:function(error){
				    $('#spinner1').fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        });
// learning mode

        $('#categ').change(function () {
            var getData= {
                        type:$(this).val(),
                        action:'availableModes'
                    };
            $('#class-id').attr('hidden',true);
            $('#spec-levels').attr('hidden',true);
            $('#spec-terms').attr('hidden',true);
            $('#spinner10').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            $.ajax({
                type: "POST",
                url: "../../files/Programs/program_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $("#learning-mode").empty();
                    $('#spinner10').fadeOut('fast');
                    if(data.length>0){
                        $("#learning-mode").append("<option disabled selected>--choose a mode--</option>");
                        $.each(data, function (index, value) {
                                $("#learning-mode").append("<option value='" + value.prg_mode_id + "'>" + value.prg_mode_full_name +"</option>");
                            });
                    }
				},
				error:function(error){
				    $('#spinner10').fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        });        

// learning mode

        $('#learning-mode').change(function () {
            var getData= {
                        mode:$(this).val(),
                        type :$('#categ').val(),
                        action:'load-specs-per-mode'
                    };
            $('#class-id').attr('hidden',true);
            $('#spec-terms').attr('hidden',true);
            $('#spinner0').html("<img src='../../img/ajax_loader.gif' width='30'>").fadeIn('fast');
            var counter =0;
            $.ajax({
                type: "POST",
                url: "../../files/Programs/program_controller.php",
                data: getData,
                dataType:"json",
                success:function(data){
                    $("#class-id").attr('hidden',false);
                    $("#classid").empty();
                    $('#spinner0').fadeOut('fast');
                    if(data.length>0){
                        $.each(data, function (index, value) {
                            counter +=1;
                                $("#classid").append("<option value='" + value.splz_id + "'>"+ value.splz_full_name +"</option>");
                            });
                    }
                    $("#spec-terms").removeAttr('hidden');
				},
				error:function(error){
				    $('#spinner0').fadeOut('fast');
                    pop_wrong("Something went wrong!")
				}
            });
        });
        
        
        // time table
        $("#term_id").change(function () {
            $("#whole").attr('hidden',false);
            $("#one").attr('hidden',false);
        });
        
        $("#one").click(function () {
            var levelId =$('#level_id').val();
            var classId = $('#classid').val();
            var type = $('#categ').val();
            var mode = $('#learning-mode').val();
            var term =$('#term_id').val();
            
			
			$('#spinnerid').html('<div class="spinner-border" role="status"><span class="visually-hidden"><div class="spinner"></div></span></div>');
            $('.spinner-border').show();
            
            $.get('tables/time_table?term_id=' + term + '&classId=' + classId +'&levelId='+levelId +'&type='+type +'&mode='+mode, function (data) {
            
            pop_up_request12('Time Table Added');
            
            setTimeout(function() {
                location.reload();
                }, 3000); // Reload the page after 3 seconds (3000 milliseconds)
            $("#mydiv").html(data);
            
            $('.spinner-border').fadeOut(910);
            });
        });
        
        $("#whole").click(function () {
            $('#single_time').hide();
            var mode = $('#learning-mode').val();
            var term = $('#term_id').val();
            var categ = $('#categ').val();
            
            $("#spec-terms").attr('hidden',true);
            $("#class-div").attr('hidden',true);
            $("#spec-levels").attr('hidden',true);
            $("#class-id").attr('hidden',true);
            
            // Show the spinner
            $('#spinnerid').html('<div class="spinner-border" role="status"><span class="visually-hidden"><div class="spinner"></div></span></div>');
            $('.spinner-border').show();
            ;
            $.get('tables/time_table_as_whole?mode=' + mode +'&categ=' +categ +'&term=' +term, function (data) {
                            
                pop_up_request12('Time Table Added');
            
                setTimeout(function() {
                    location.reload();
                }, 3000);
            $("#mydiv").html(data);
            $('.spinner-border').fadeOut(910);
            });
        });
        $("#view").click(function () {
            $('#single_time').hide();
            var mode = $('#learning-mode').val();
            var term = $('#term_id').val();
            var categ = $('#categ').val();
            
            $("#spec-terms").attr('hidden',true);
            $("#class-div").attr('hidden',true);
            $("#spec-levels").attr('hidden',true);
            $("#class-id").attr('hidden',true);
            
            // Show the spinner
            $('#spinnerid').html('<div class="spinner-border" role="status"><span class="visually-hidden"><div class="spinner"></div></span></div>');
            $('.spinner-border').show();
            ;
            $.get('tables/time_table_as_whole?mode=' + mode +'&categ=' +categ +'&term=' +term, function (data) {
                            
                pop_up_request12('Time Table Added');
            
                setTimeout(function() {
                    location.reload();
                }, 3000);
            $("#mydiv").html(data);
            $('.spinner-border').fadeOut(910);
            });
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
            title: 'Message',
            message: feedback,
            position: 'center'
        });
    }
    
    // Notification  
    function pop_up_request12(feedback) {
        jQuery(function validation(){
            swal("Done ", "Time Table Added!", "success", {
                button: "Ok",
            });
        });
    }
</script>