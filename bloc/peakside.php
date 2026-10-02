<div id="app">
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
    
        <!-- Start app top navbar -->
        <nav class="navbar navbar-expand-lg main-navbar " >
            <input type="hidden" name="rder" id="rder" value='<?php echo $identification; ?>'>
            <form class="form-inline mr-auto">
              <ul class="navbar-nav mr-3">
                    <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
                    <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
                </ul>
                <div class="search-element">
                    <div class="search-backdrop"></div>
                </div>
            </form>
            <ul class="navbar-nav navbar-right" style="color:#fff">
                <?php
                    if($_SESSION['role_id']==4){
                        $sql0=$conn->prepare("SELECT * FROM tbl_announcement WHERE (readers NOT LIKE '%" . $identification . "%' OR NOT JSON_CONTAINS(readers, '\"" . $identification . "\"')) AND target!=3");  
                    }
                    else{
                        $sql0=$conn->prepare("SELECT * FROM tbl_announcement WHERE (readers NOT LIKE '%" . $identification . "%' OR NOT JSON_CONTAINS(readers, '\"" . $identification . "\"')) AND user!='".$identification."'");
                    }
                    $sql0->execute();
                    if($sql0->rowCount()>0){
                ?>
                <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg beep"><i class="far fa-bell"></i></a>
                    <div class="dropdown-menu dropdown-list dropdown-menu-right">
                        <div class="dropdown-header">Announcements
                            <div class="float-right">
                                <a id="all" href="#">Mark All As Read</a>
                            </div>
                        </div>
                        <div class="dropdown-list-content dropdown-list-icons">
                            <?php
                                if($role_id==4){
                                    $sql=$conn->prepare("SELECT * FROM tbl_announcement WHERE (readers NOT LIKE '%" . $identification . "%' OR NOT JSON_CONTAINS(readers, '\"" . $identification . "\"')) AND user!='".$identification."'  AND target!=3 ORDER BY date_published DESC");
                                }
                                else{
                                    $sql=$conn->prepare("SELECT * FROM tbl_announcement WHERE (readers NOT LIKE '%" . $identification . "%' OR NOT JSON_CONTAINS(readers, '\"" . $identification . "\"')) AND user!='".$identification."' ORDER BY date_published DESC"); 
                                }
                                
                                $sql->execute();
                                while($ann=$sql->fetch()){
                            ?>
                                <a href="<?php echo $ann['file']; ?>" target="_blank" class="dropdown-item dropdown-item-unread notf" data-id="<?php echo $ann['id']; ?>">
                                    <div class="dropdown-item-icon bg-primary text-white">
                                        <i class="fas fa-comment"></i>
                                    </div>
                                    <div class="dropdown-item-desc"><?php echo $ann['title']; ?>
                                        <div class="time text-primary">
                                            <?php 
                                                $dateTime = new DateTime($ann['date_published']);
                                                $formattedDate = $dateTime->format('Y-m-d');
                                                echo $formattedDate; 
                                            ?>
                                        </div>
                                    </div>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </li>
                <?php } ?>
                <li class="dropdown">
                    <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                    <img alt="image" src="../assets/img/avatar/avtUser.png" class="rounded-circle mr-1">
                    <!--<div id="profileImage"></div>-->
                    <div class="d-sm-none d-lg-inline-block">Hi, <?php echo $first_name  ?></div></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <?php if($role_id!=5){ ?>
                        <a href="edu?mis=profile" class="dropdown-item has-icon"><i class="far fa-user"></i> Profile</a>
                        <a href="edu?mis=pwd" class="dropdown-item has-icon"><i class="fa fa-lock"></i> Change password</a>
                        <?php } ?>
                        <a href="edu?mis=profile" class="dropdown-item has-icon"><i class="far fa-user"></i> Profile</a>
                        <div class="dropdown-divider"></div>
                     <a href="../preout" class="dropdown-item has-icon text-danger"><i class="fas fa-sign-out-alt"></i>Logout</a>
                    
                    </div>
                </li>
            </ul>
        </nav>
        
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){
    $(document).on('click','#all',function(){
            var formData = {
                action:'read_all',
                reader:$("#rder").val()
            }
            $.ajax({
                url: "/files/announcements/controller.php",
                type: "POST",
                data: formData,
                dataType:'JSON',
                success: function(data){
                    window.location.reload();
                },error: function(){
                }
             });
          });
    $(document).on('click','.notf',function(){
            var formData = {
                action:'read',
                not:$(this).data('id'),
                reader:$("#rder").val()
            }
            $.ajax({
                url: "/files/announcements/controller.php",
                type: "POST",
                data: formData,
                dataType:'JSON',
                success: function(data){
                    window.location.reload();
                },error: function(){
                }
             });
          });
          
          
    });
    
</script> 