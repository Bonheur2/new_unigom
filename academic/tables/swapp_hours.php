    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Table Contents Swapping Operation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="hours-tab" data-toggle="tab" href="#hours" role="tab" aria-controls="default" aria-selected="true"><b>Swapp Hours</b></a></li>
                        <li class="nav-item"><a class="nav-link" id="room-tab" data-toggle="tab" href="#rooms" role="tab" aria-controls="specific" aria-selected="false"><b>Swapp Rooms</b></a></li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                        <div class="tab-pane fade show active"  role="tabpanel" id="hours" aria-labelledby="hours-tab">
                            <form id="swapp-hours"action="table_info" method="POST" enctype="multipart/form-data"  method="POST" id="ModalForm">
                            <input type="hidden" id="editId" value="">
                            <div class="form-group">
                                <label for="spec">Specialization</label>
                                <select class="form-control" id="edit-spec" name="edit-spec">
                                    <option>choose a class</option>
                                    <?php
                                    $spec=$conn->prepare("select *from tbl_specialization where splz_id IN(select sub_class_id from tbl_t_schedule)");
                                    $spec->execute();
                                    while($theSpec =$spec->fetch()){
                                        ?>
                                        <option value="<?php echo $theSpec['splz_id'];?>"><?php echo $theSpec['splz_full_name'];?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <span id="spinner20"></span>
                            </div>
                            <div class="form-group">
                                <label for="edit-level">Level</label>
                                <select class="form-control" id="edit-level" name="edit-level">
                                </select>
                                <span id="spinner30"></span>
                            </div>
                            <div class="form-group">
                                <label for="edit-level">Term</label>
                                <select class="form-control" id="edit-term" name="edit-term">
                                </select>
                                <span id="spinner60"></span>
                            </div>                        
                            <div class="card-body pb-0 row" style='padding: 20px;border-radius: 8px; border: 2px solid #B59820; margin-top:1%;margin-bottom:1%;'>
                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                    <label for="edit-day">Day to Move From</label>
                                    <select class="form-control" id="edit-day" name="edit-day">
                                    </select> 
                                    <span id="spinner40"></span>
                                </div>
                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                    <label for="move-from" style="width:200px;">Hour to Move From</label>
                                    <select class="form-control" id="move-from" name="move-from">
                                    </select>
                                    <span id="spinner50"></span>
                                </div>
                            </div>
                            <div class="card-body pb-0 row" style='padding: 20px;border-radius: 8px; border: 2px solid #B59820; margin-top:1%;margin-bottom:1%;'>
                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                    <label for="edit-day">Day to Move To</label>
                                    <select class="form-control" id="edit-day-to" name="edit-day-to">
                                    </select>
                                    <span id="spinner70"></span>
                                </div>
                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                    <label for="move-to">Hour to Move To</label>
                                    <select class="form-control" id="move-to" name="move-to">
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a  class="btn btn-secondary" data-dismiss="modal">Close</a>
                                <button type="submit"  id="saveModalButton" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                        </div>
                        <div class="tab-pane fade" role="tabpanel"  id="rooms" aria-labelledby="room-tab">
                            <form id="swapp-hours"action="table_info" method="POST" enctype="multipart/form-data"  method="POST" id="ModalForm">
                            <input type="hidden" id="editId" value="">
                            <div class="form-group">
                                <label for="spec">Specialization</label>
                                <select class="form-control" id="edit-spec" name="edit-spec">
                                    <option>choose a class</option>
                                    <?php
                                    $spec=$conn->prepare("select *from tbl_specialization where splz_id IN(select sub_class_id from tbl_t_schedule)");
                                    $spec->execute();
                                    while($theSpec =$spec->fetch()){
                                        ?>
                                        <option value="<?php echo $theSpec['splz_id'];?>"><?php echo $theSpec['splz_full_name'];?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <span id="spinner20"></span>
                            </div>
                            <div class="form-group">
                                <label for="edit-level">Level</label>
                                <select class="form-control" id="edit-level" name="edit-level">
                                </select>
                                <span id="spinner30"></span>
                            </div>
                            <div class="form-group">
                                <label for="edit-level">Term</label>
                                <select class="form-control" id="edit-term" name="edit-term">
                                </select>
                                <span id="spinner60"></span>
                            </div>                        
                            <div class="card-body pb-0 row" style='padding: 20px;border-radius: 8px; border: 2px solid #B59820; margin-top:1%;margin-bottom:1%;'>
                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                    <label for="edit-day">Day to Move From</label>
                                    <select class="form-control" id="edit-day" name="edit-day">
                                    </select> 
                                    <span id="spinner40"></span>
                                </div>
                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                    <label for="move-from" style="width:200px;">Hour to Move From</label>
                                    <select class="form-control" id="move-from" name="move-from">
                                    </select>
                                    <span id="spinner50"></span>
                                </div>
                            </div>
                            <div class="card-body pb-0 row" style='padding: 20px;border-radius: 8px; border: 2px solid #B59820; margin-top:1%;margin-bottom:1%;'>
                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                    <label for="edit-day">Day to Move To</label>
                                    <select class="form-control" id="edit-day-to" name="edit-day-to">
                                    </select>
                                    <span id="spinner70"></span>
                                </div>
                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                    <label for="move-to">Hour to Move To</label>
                                    <select class="form-control" id="move-to" name="move-to">
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a  class="btn btn-secondary" data-dismiss="modal">Close</a>
                                <button type="submit"  id="saveModalButton" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-lg-12" id="bloc"></div>