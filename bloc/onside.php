   <div class="main-sidebar sidebar-style-2">
       <aside id="sidebar-wrapper">
           <div class="sidebar-brand">
               <?php   if($prvg==2){  ?>
               <select class="select2" style="width:70%" id="changeRole">
                   <option><?php echo $u_role ?></option>
                   <?php 
                            $sthChange = $db->query("SELECT * FROM tbl_user_roles WHERE role_id !='".$role_id."' and role_id NOT IN(4,5,19) ORDER BY role ASC");
                            while($resChange = $sthChange->fetch()) {
                        ?>
                   <option value="<?php  echo $resChange['role_id'];?>"><?php echo $resChange['role'] ?></option>
                   <?php } ?>
               </select>
               <?php } else{ ?>
               <a href="edu?mis=1"><?php echo $u_role ?></a>
               <?php } ?>
           </div>
           <ul class="sidebar-menu">
               <?php if($role_id==2){ ?>
               <li class="dropdown active">
                   <a href="edu?mis=on" class="nav-link"><i class="fas fa-home"></i><span>Dashboard</span></a>
               </li>
               <li class="dropdown">
                   <a href="#" class="nav-link has-dropdown"><i class="fas fa-cog"></i> <span>General
                           Settings</span></a>
                   <ul class="dropdown-menu">
                       <li><a class="nav-link" href="edu?mis=uni">University</a></li>
                       <li><a class="nav-link" href="edu?mis=ups">Campus</a></li>
                   </ul>
               </li>
               <li class="dropdown">
                   <a href="#" class="nav-link has-dropdown"><i class="fas fa-cog"></i> <span>Academic
                           Settings</span></a>
                   <ul class="dropdown-menu">
                       <li><a class="nav-link" href="edu?mis=acdmc">Academic Year</a></li>
                       <li><a class="nav-link" href="edu?mis=fac">Faculties</a></li>
                       <li><a class="nav-link" href="edu?mis=prgty">Program Type</a></li>
                       <li><a class="nav-link" href="edu?mis=Dprtm">Department</a></li>
                       <li><a class="nav-link" href="edu?mis=opts">Options</a></li>
                       <li><a class="nav-link" href="edu?mis=lvls">Level</a></li>
                       <!-- <li><a class="nav-link" href="edu?mis=feecat">Fee Category</a></li> -->

                   </ul>
               </li>
               <li class="dropdown">
                   <a href="#" class="nav-link has-dropdown"><i class="fab fa-adn"></i> <span>Applicants
                           Settings</span></a>
                   <ul class="dropdown-menu">
                       <li><a class="nav-link" href="edu?mis=appty">Application Types</a></li>
                       <li><a class="nav-link" href="edu?mis=appdc">Application Document</a></li>
                       <li><a class="nav-link" href="edu?mis=appd">Application Period</a></li>
                       <li><a class="nav-link" href="edu?mis=apstp">Application Steps</a></li>
                       <li><a class="nav-link" href="edu?mis=fcdt">Faculity Document</a></li>
                       <li><a class="nav-link" href="edu?mis=chstr">Choice Document</a></li>
                   </ul>
               </li>
               <li class="dropdown">
                   <a href="#" class="nav-link has-dropdown"><i class="fab fa-adn"></i> <span>Manage
                           Applicants</span></a>
                   <ul class="dropdown-menu">
                       <li><a class="nav-link" href="edu?mis=apprvw">Application Review</a></li>
                       <li><a class="nav-link" href="edu?mis=sbtdap">Submitted Application</a></li>
                   </ul>
               </li>
               <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Teaching Units</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=tchunt">Teaching Units</a></li>
                            <li><a class="nav-link" href="edu?mis=mod_assign">Module to Year</a></li>
                            <li><a class="nav-link" href="edu?mis=mod_lead_assign">Module leaders</a></li>
                            <li><a class="nav-link" href="edu?mis=mod_stu">Student - Modules</a></li>
                            <li><a class="nav-link" href="edu?mis=assess_mod">Assessments</a></li>
                        </ul>
                    </li>
               <?php } ?>
               <?php if($role_id==1){ ?>
               <li class="dropdown active">
                   <a href="edu?mis=on" class="nav-link"><i class="fas fa-home"></i><span>Dashboard</span></a>
               </li>
               <li class="dropdown">
                   <a href="#" class="nav-link has-dropdown"><i class="fas fa-cog"></i> <span>System Users</span></a>
                   <ul class="dropdown-menu">
                       <li><a class="nav-link" href="edu?mis=urat">User Account</a></li>
                   </ul>
               </li>
               <li class="dropdown">
                   <a href="#" class="nav-link has-dropdown"><i class="fab fa-adn"></i> <span>Manage
                           Applicants</span></a>
                   <ul class="dropdown-menu">
                       <li><a class="nav-link" href="edu?mis=apprvw">Application Review</a></li>
                   </ul>
               </li>

               <?php } ?>
               <?php if($role_id==18){ ?>
               <li class="dropdown active">
                   <a href="edu?mis=on" class="nav-link"><i class="fas fa-home"></i><span>Dashboard</span></a>
               </li>
               <!--<li class="dropdown">-->
               <!--    <a href="#" class="nav-link has-dropdown"><i class="fas fa-cog"></i> <span>System Users</span></a>-->
               <!--    <ul class="dropdown-menu">-->
               <!--        <li><a class="nav-link" href="edu?mis=urat">User Account</a></li>-->
               <!--    </ul>-->
               <!--</li>-->
               <li class="dropdown">
                   <a href="#" class="nav-link has-dropdown"><i class="fab fa-adn"></i> <span>Manage
                           Applicants</span></a>
                   <ul class="dropdown-menu">
                       <li><a class="nav-link" href="edu?mis=apprvw">Application Review</a></li>
                   </ul>
               </li>

               <?php } ?>
               <?php if($role_id==3){ ?>
               <li class="dropdown active">
                   <a href="edu?mis=on" class="nav-link"><i class="fas fa-home"></i><span>Dashboard</span></a>
               </li>
               <li class="dropdown">
                   <a href="#" class="nav-link has-dropdown"><i class="fas fa-cog"></i> <span>Fee management</span></a>
                   <ul class="dropdown-menu">
                       <li><a class="nav-link" href="edu?mis=urat">Fee category</a></li>
                   </ul>
               </li>
               <li class="dropdown">
                   <a href="#" class="nav-link has-dropdown"><i class="fab fa-adn"></i> <span>Manage
                           Applicants</span></a>
                   <ul class="dropdown-menu">
                       <li><a class="nav-link" href="edu?mis=apprvw">Application Review</a></li>
                   </ul>
               </li>

               <?php } ?>
               <?php if($_SESSION['role_id'] ==5){ ?>
                    <li class="dropdown active">
                        <a href="edu?mis=1" class="nav-link"><i class="fas fa-home"></i><span>Home</span></a>
                       
                    </li>
                    <?php
                        $stmt00 = $conn->prepare("SELECT * FROM tbl_applicants WHERE code='".$code."' AND submitted=2");
                        $stmt00->execute();
                        if($stmt00->rowCount()==0){
                    ?>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-clipboard"></i> <span>Application</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=review">Review Information</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=prevEdu">Previous Educaction</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=lanPro">Language Proficiency</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=fasp">Family & Sponor</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=apcrs">Programme Sought</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=apDocs">Application documents</a></li>-->
                            <?php
                            // $prTypeQ=$conn->prepare("SELECT prg_type FROM  tbl_admittedPRG WHERE Stu_code='".$code."'");
                            // $prTypeQ->execute();
                            // $dataTpyeQ=$prTypeQ->fetch();
                            // $pryT=$dataTpyeQ['prg_type'];
                            if($prg_ty==2 || $prg_ty==4 || $prg_ty==6 || $prg_ty==8){
                                ?>
                              <li><a class="nav-link" href="edu?mis=essay">Research proposal</a></li>
                            <?php
                            
                        }
                            ?>
                            
                            <!--<li><a class="nav-link" href="edu?mis=payinfo">Payment Info</a></li> -->
                            <li><a class="nav-link" href="edu?mis=sbt">Submit</a></li>
                        </ul>
                    </li>

                    <?php } ?>
                   
                    <?php } ?>
           </ul>

       </aside>
   </div>

   <script>
$(document).ready(function() {
    $("#changeRole").change(function() {
        var dual_date = $('#dual_date').val();
        var acc_id = <?php  echo $acc_id; ?>;
        $(this).after(
            '<div id="loader"><img src="../img/ajax_loader.gif" alt="loading...." width="30" height="30" /></div>'
            );
        $.get('../changeRole?role_id=' + $(this).val() + '&acc_id=' + acc_id, function(data) {
            changed_success(data);
            $('#loader').slideUp(910, function() {
                $(this).remove();
            });
            setTimeout(function() { // wait for 5 secs(2)
                window.location.href = "../lgout";
            }, 3000);
        });
    });

});

function changed_success(feedback) {
    iziToast.info({
        title: 'Well Done',
        message: 'System Role have been Changed successfully ',
        position: 'topCenter'
    });
}
   </script>



   <script>
function makeActive(submenu) {
    const dropdownParent = submenu.closest('.dropdown');
    document.querySelectorAll('.dropdown').forEach(item => {
        item.classList.remove('active');
        item.querySelector('a')?.removeAttribute('style');
    });
    document.querySelectorAll('.dropdown-menu a').forEach(item => {
        item.classList.remove('submenu-active');
        item.removeAttribute('style');
    });

    dropdownParent.classList.add('active');
    dropdownParent.querySelector('a').style.backgroundColor = '#f0f8ff';
    dropdownParent.querySelector('a').style.fontWeight = 'bold';

    submenu.classList.add('submenu-active');
    submenu.style.color = '#ffffff';
    submenu.style.backgroundColor = '#052df5';
    submenu.style.fontWeight = 'bold';
    submenu.style.borderRadius = '1px';
}

document.addEventListener('DOMContentLoaded', () => {
    const url = window.location.href;
    document.querySelectorAll('.dropdown-menu a').forEach(link => {
        if (url === link.href) {
            const parentDropdown = link.closest('.dropdown');
            parentDropdown.classList.add('active');
            parentDropdown.querySelector('a').style.backgroundColor = '#f0f8ff';
            parentDropdown.querySelector('a').style.fontWeight = 'bold';

            link.classList.add('submenu-active');
            link.style.color = '#052df5';
            link.style.fontWeight = 'bold';
            link.style.borderRadius = '5px';
            link.style.width = '99%';
        }
    });
});
   </script>


   <style>
/* Element selector for all <ul> elements */

a:hover {
    color: #ff6600;

}

/* Element selector for all <li> elements */
li.dropdown {
    color: black;
    padding: 3px;
    /* Add padding inside each list item */
    background-color: #f5f5f5;
    margin: 5px 0;
    /* Add vertical margin */
    transform: scaleX(1);
    border-radius: 8px;
    /* Round the corners */
    box-shadow: 0 10px 10px rgba(169, 169, 169, 0.4);
    font-weight: 400;
    font-weight: bold;
    font-size: 13pt;
}

li.nav-link {
    color: black;
    padding: 3px;
    /* Add padding inside each list item */
    background-color: #f5f5f5;
    margin: 5px 0;
    /* Add vertical margin */
    transform: scaleX(1);
    border-radius: 8px;
    /* Round the corners */
    box-shadow: 0 10px 10px rgba(169, 169, 169, 0.4);
    font-weight: 400;
    font-weight: bold;
    font-size: 13pt;
}

/* Element selector for all <a> elements */
a {
    text-decoration: none;
    /* Remove underline from links */
    /* Set text color */
}
   </style>