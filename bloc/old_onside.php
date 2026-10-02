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
                    
                    <?php if($role_id==18){ ?>
                    <li class="dropdown active">
                        <a href="edu?mis=on" class="nav-link"><i class="fas fa-home"></i><span>Dashboard</span></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-cog"></i> <span>General Management</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=uni">University</a></li> 
                            <li><a class="nav-link" href="edu?mis=ups">Campus</a></li>
                            <li><a class="nav-link" href="edu?mis=post">Posts</a></li> 
                        </ul>
                    </li>
                    <li>
                        <a href="edu?mis=user" class="nav-link"><i class="fas fa-user-check"></i> <span>User Accounts</span></a>
                    </li>
                   
                   <?php } ?>
                    <?php if($role_id==1){ ?>
                    <li class="dropdown active">
                        <a href="edu?mis=1" class="nav-link"><i class="fas fa-home"></i><span>Dashboard</span></a>
                       
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-user-check"></i> <span>User Accounts</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=user">Users</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=empList">Users</a></li>-->
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-box-archive"></i> <span>Voting</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=setg">Settings</a></li>
                            <li><a class="nav-link" href="edu?mis=vopos">Positions</a></li>
                            <li><a class="nav-link" href="edu?mis=cand">Candidates</a></li>
                            <li><a class="nav-link" href="edu?mis=vstats">Results</a></li>
                        </ul>
                    </li>
                   
                    <?php } ?>
                    <?php if($role_id==25){ ?>
                    <li class="dropdown active">
                        <a href="edu?mis=1" class="nav-link"><i class="fas fa-home"></i><span>Dashboard </span></a>
                    </li>
                     <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fa fa-cog"></i> <span>General Settings</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=ptype"> Program type</a></li>
                            <li><a class="nav-link" href="edu?mis=pfac"> School</a></li>
                            <li><a class="nav-link" href="edu?mis=pdept"> Department</a></li>
                            <li><a class="nav-link" href="edu?mis=splz"> Specialization</a></li>
                            <li><a class="nav-link" href="edu?mis=partn"> Partners</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="far fa-address-book"></i> <span>Student Management</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=stuInfo">Student info</a></li>
                            <li><a class="nav-link" href="edu?mis=stuspo"> Student sponsors</a></li>
                              
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-clock"></i> <span>Session Management</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=addbloc">Blocks</a></li>
                            <li><a class="nav-link" href="edu?mis=addroom">Rooms</a></li>
                            <li><a class="nav-link" href="edu?mis=tablehours">Teaching hours</a></li>
                            <li><a class="nav-link" href="edu?mis=timetable">Timetables</a></li>
                            <li><a class="nav-link" href="edu?mis=gentables">Generated Tables</a></li>
                            <li><a class="nav-link" href="edu?mis=attlist">Attendance List</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=exmat">Exam Attendance</a></li>-->
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-id-card"></i> <span>Student Card</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=stucard">Print card</a></li>
                            <li><a class="nav-link" href="edu?mis=cardreg">Card registration</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Module Management</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=mod">Modules</a></li>
                            <li><a class="nav-link" href="edu?mis=mod_assign">Module to Year</a></li>
                            <li><a class="nav-link" href="edu?mis=mod_lead_assign">Module leaders</a></li>
                            <li><a class="nav-link" href="edu?mis=mod_stu">Student - Modules</a></li>
                            <li><a class="nav-link" href="edu?mis=assess_mod">Assessments</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-clipboard-check"></i> <span>Manage Marks </span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=msing">Student Marks</a></li>
                            <li><a class="nav-link" href="edu?mis=regmksOne">Register Marks / One</a></li>
                            <li><a class="nav-link" href="edu?mis=regmks">Register Marks / Mult</a></li>
                            <li><a class="nav-link" href="edu?mis=regmks2">Push marks</a></li>
                            <li><a class="nav-link" href="edu?mis=cmrks">Check Marks</a></li>
                            <li><a class="nav-link" href="edu?mis=transOne">Transcript / One</a></li>
                            <li><a class="nav-link" href="edu?mis=transLev">Transcript / Level</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-book-open"></i> <span>Degree</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=graduant">Manage Graduants</a></li>
                            <li><a class="nav-link" href="edu?mis=graduantList">Graduation List</a></li>
                            <li><a class="nav-link" href="edu?mis=graduantstats">Graduation Statistics</a></li>
                            <li><a class="nav-link" href="edu?mis=pdgr">Print Degree</a></li>
                            <li><a class="nav-link" href="edu?mis=pdgr1">Print Degree / One</a></li>
                        </ul>
                    </li>

                     <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-file-export"></i> <span>Student Report</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=stugen">General Report</a></li>
                            <li><a class="nav-link" href="edu?mis=bysts">By Student status</a></li>
                            <li><a class="nav-link" href="edu?mis=byspn">By all Sponsors</a></li>
                            <li><a class="nav-link" href="edu?mis=bynation">By Nationality</a></li>
                            <li><a class="nav-link" href="edu?mis=bygnd">By Gender</a></li>
                            <li><a class="nav-link" href="edu?mis=byage">By Age</a></li>
                            <li><a class="nav-link" href="edu?mis=bypro">By Province</a></li>
                            <li><a class="nav-link" href="edu?mis=bydist">By District</a></li>
                            <li><a class="nav-link" href="edu?mis=hcrep">Govt. Body Report</a></li>
                            <li><a class="nav-link" href="edu?mis=hcrepage">Govt. Body Age Report</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=bystuAcces">By Student Access</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=bycontrol">Stu access control (60D)</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=byctrl7d">Stu  accss control (7D)</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=byAllstu">All student</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=byAllSTS">All students  by status</a></li>-->
                        </ul>
                    </li>
                    

                    
                    <?php } ?>
                    <?php if($role_id==3){ ?>
                    <li class="dropdown active">
                        <a href="edu?mis=1" class="nav-link"><i class="fas fa-home"></i><span>Dashboard</span></a>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fa fa-cog"></i> <span>General settings</span></a>
                        <ul class="dropdown-menu">
                            <!--<li><a class="nav-link" href="edu?mis=feescateg"> Fee Categories</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=prces"> Prices</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=bills_trial"> Bills Trial</a>-->
                            <!--<li><a class="nav-link" href="edu?mis=uplfees"> Upload setting prices file</a></li>-->
                            <li><a class="nav-link" href="edu?mis=sponcateg"> Sponsor Categories</a></li>
                            <li><a class="nav-link" href="edu?mis=spnlist">Sponsors</a></li>
                            <li><a class="nav-link" href="edu?mis=bank">Bank Accounts</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-clipboard"></i> <span>Fee Management</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=feem">Fee Categories</a></li>
                            <li><a class="nav-link" href="edu?mis=slinvc">Special Fee Categories</a></li>
                            
                            <!--<li><a class="nav-link" href="edu?mis=feemsp">Specific Fee Categories</a></li>-->
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="far fa-arrow-alt-circle-up"></i> <span>Invoicing</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=indInvo"> Individual Invoice </a></li>
                            <li><a class="nav-link" href="edu?mis=multInvo"> Multiple Invoice</a></li>
                            <li><a class="nav-link" href="edu?mis=mnginv"> Manage invoice</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=invMng"> Invoice Manager</a></li>-->
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-briefcase"></i> <span>Student Payments</span></a>
                        <ul class="dropdown-menu">
                            <!--<li><a class="nav-link" href="edu?mis=stuspo"> Student sponsors  </a></li>-->
                            <li><a class="nav-link" href="edu?mis=indpayt"> Individual payment  </a></li>
                            <li><a class="nav-link" href="edu?mis=apppayt">Applicant Payment</a></li>
                            <li><a class="nav-link" href="edu?mis=pytNeg">Payment negotiation</a></li>
                        </ul>
                    </li>
                    
                    <!--<li class="dropdown">-->
                    <!--    <a href="#" class="nav-link has-dropdown"><i class="fas fa-arrows-alt"></i> <span>Balance transfer and Refunds</span></a>-->
                    <!--    <ul class="dropdown-menu">-->
                    <!--        <li><a class="nav-link" href="edu?mis=balTrans">Balance transfer</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=rednd">Refunds</a></li>-->
                    <!--    </ul>-->
                    <!--</li>-->
                    
                    <!--<li class="dropdown">-->
                    <!--    <a href="#" class="nav-link has-dropdown"><i class="fab fa-cc-amazon-pay"></i> <span>Bank Transfer</span></a>-->
                    <!--    <ul class="dropdown-menu">-->
                    <!--        <li><a class="nav-link" href="edu?mis=bkrpt"> Books Reports</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=distrpt">Dissertation Reports</a></li>-->
                    <!--    </ul>-->
                    <!--</li>-->
                  
                    <!--<li class="dropdown">-->
                    <!--    <a href="#" class="nav-link has-dropdown"><i class="fas fa-door-open"></i> <span>Clearence Req.</span></a>-->
                    <!--    <ul class="dropdown-menu">-->
                    <!--        <li><a class="nav-link" href="edu?mis=clerareq"> Requests</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=allreqcl">All Request(s)</a></li>-->
                    <!--    </ul>-->
                    <!--</li>-->
                    
                    <!--<li class="dropdown">-->
                    <!--    <a href="#" class="nav-link has-dropdown"><i class="fas fa-city"></i> <span>Industrial Attach.</span></a>-->
                    <!--    <ul class="dropdown-menu">-->
                    <!--        <li><a class="nav-link" href="edu?mis=indsreq"> Requests</a></li>-->
                    <!--    </ul>-->
                    <!--</li>-->
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-sitemap"></i> <span>Finance Statistics</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=gnlststcs">General statistics</a></li>
                            <li><a class="nav-link" href="edu?mis=allinvc">All invoices</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=cancelled">Cancelled Invoices</a></li>-->
                            <li><a class="nav-link" href="edu?mis=spyt">All payments</a></li>
                            <li><a class="nav-link" href="edu?mis=sbal">Balances</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=invocps">Invoices by Campus</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=pytcps">Payments by Campus</a></li>-->
                            <li><a class="nav-link" href="edu?mis=sfee">By Fee Category</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=sintk">By Intake</a></li>-->
                        </ul>
                    </li>
                    <!--<li class="dropdown">-->
                    <!--<a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-sitemap"></i> <span>Student Requests</span></a>-->
                    <!--    <ul class="dropdown-menu">-->
                    <!--        <li><a class="nav-link" href="edu?mis=bal_transfer">Balance Transfer</a></li>-->
                      
                    <!--    </ul>-->
                    <!--</li>-->
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-file-export"></i> <span>Finance Report</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=indreport">Individual Report</a></li>
                            <li><a class="nav-link" href="edu?mis=indrptpyt">Individual Payments</a></li>
                            <li><a class="nav-link" href="edu?mis=ptyByclassRpt"> Class Payments</a></li>
                        </ul>
                    </li>
                    
                    <!--<li class="dropdown">-->
                    <!--    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-balance-scale"></i> <span>Student Balance</span></a>-->
                    <!--    <ul class="dropdown-menu">-->
                    <!--        <li><a class="nav-link" href="edu?mis=invopytBlc">By Invoice & Payment</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=bySpon">By All Sponsors</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=stsInvoPay">By Statistics (Inv. & Pay.)</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=invo-stscs">By Statistics (Invoices)</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=pyt-stscs">By Statistics (Payments)</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=byselct">By Selections</a></li>-->
                    <!--    </ul>-->
                    <!--</li>-->
                    
                    <!--<li class="dropdown">-->
                    <!--    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-clipboard"></i> <span>Exams</span></a>-->
                    <!--    <ul class="dropdown-menu">-->
                    <!--        <li><a class="nav-link" href="edu?mis=exmat">Attendance</a></li>-->
                    <!--    </ul>-->
                    <!--</li>-->
                    
                    
                    <?php } ?>
                   
                    <?php if($role_id==4){ ?>
                    <li class="dropdown active">
                        <a href="edu?mis=1" class="nav-link"><i class="fas fa-home"></i><span>Dashboard</span></a>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-user-check"></i> <span>Personal Info</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=mypro">My Profile</a></li>
                            <li><a class="nav-link" href="edu?mis=infoacad">Academic info</a></li>
                            <li><a class="nav-link" href="edu?mis=mydoc">My Document</a></li>
                            <li><a class="nav-link" href="edu?mis=myphoto">My Photo </a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-book"></i> <span>Manage Modules</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=regm"> Register on Module</a></li>
                            <li><a class="nav-link" href="edu?mis=reoMo">Repeated Module </a></li>
                            <li><a class="nav-link" href="edu?mis=mmod">Module Evaluation </a></li>
                            
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-chalkboard-teacher"></i> <span>Marks and Sessions</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=mySchedule">Timetable</a></li>
                             <li><a class="nav-link" href="edu?mis=mmarks">My Marks</a></li>
                             <li><a class="nav-link" href="edu?mis=regonsem">Register On Semester</a></li>
                            
                            
                        </ul>
                    </li>
                     
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-laptop-house"></i> <span>E-Learning</span></a>
                        <ul class="dropdown-menu">
                            <!--<li><a class="nav-link" href="https://online.act.ac.rw/">Online courses </a></li>-->
                            <li><a class="nav-link" href="edu?mis=eblst"> E-book</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="edu?mis=request" class="nav-link"><i class="fas fa-angle-double-right"></i> <span>Academic Requests</span></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-clipboard"></i> <span>Finance</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=mstats">My Statistics</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="edu?mis=sbox" class="nav-link"><i class="fas fa-box-archive"></i><span>Suggestion box</span></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-laptop-house"></i> <span>Elections</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=msvt">Vote </a></li>
                        </ul>
                    </li>
                    <!--<li class="dropdown">-->
                    <!--    <a href="edu?mis=hec" class="nav-link"><i class="fas fa-podcast"></i><span>HEC-Student Satisfaction</span></a>-->
                    <!--</li>-->
                    
                   
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
                            <li><a class="nav-link" href="edu?mis=perInfo">Personal Info</a></li>
                            <li><a class="nav-link" href="edu?mis=prevEdu">Previous Educaction</a></li>
                            <li><a class="nav-link" href="edu?mis=lanPro">Language Proficiency</a></li>
                            <li><a class="nav-link" href="edu?mis=fasp">Family & Sponor</a></li>
                            <li><a class="nav-link" href="edu?mis=apcrs">Programme Sought</a></li>
                            <li><a class="nav-link" href="edu?mis=apDocs">Application documents</a></li>
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
                   
                    <?php if($role_id==6){ ?>
                    <li class="dropdown active">
                        <a href="edu?mis=1" class="nav-link"><i class="fas fa-home"></i><span>Dashboard</span></a>
                       
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fab fa-adn"></i> <span>Manage Applications</span></a>
                        <ul class="dropdown-menu">
                            <!--<li><a class="nav-link" href="edu?mis=new_applicant">New Application</a></li>-->
                            <li><a class="nav-link" href="edu?mis=docset">Application setup</a></li>
                            <li><a class="nav-link" href="edu?mis=pver">Pending Verifications</a></li>
                            <li><a class="nav-link" href="edu?mis=padm">Pending Applications</a></li>
                            <li><a class="nav-link" href="edu?mis=admreq">Submitted Applications</a></li>
                            <li><a class="nav-link" href="edu?mis=admresult">Admitted List</a></li>
                            
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fab fa-adn"></i> <span>Applications Support</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=admpaysta">Payment Statistics</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=admrecf">Record failed Payment</a></li>-->
                            <li><a class="nav-link" href="edu?mis=coeiss">Code & Email Issue </a></li>
                            <li><a class="nav-link" href="edu?mis=appres">Password Reset</a></li>
                            <li><a class="nav-link" href="edu?mis=appunl">Unlock Application</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=appwcd">Submitted without Doc</a></li>-->
                            <li><a class="nav-link" href="edu?mis=vercre">Verify & create Account</a></li>
                            <li><a class="nav-link" href="edu?mis=creacc">create Account</a></li>
                            <li><a class="nav-link" href="edu?mis=apppro">Applicant problems</a></li>
                            
                            <!--<li><a class="nav-link" href="edu?mis=subndo">Submitted without Document</a></li>-->
                            
                            <!--<li><a class="nav-link" href="edu?mis=checkduplicate">Check</a></li>-->
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="far fa-address-book"></i> <span>Student Management</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=newstu">Registration</a></li>
                            
                            <li><a class="nav-link" href="edu?mis=stuInfo">Student info</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=spsor"> Sponsor</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=attlist">Attendance List</a></li>-->
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-file-export"></i> <span>Student Report</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=stugen">General Report</a></li>
                            <li><a class="nav-link" href="edu?mis=bysts">By Student status</a></li>
                            <li><a class="nav-link" href="edu?mis=byspn">By Sponsor</a></li>
                            <li><a class="nav-link" href="edu?mis=bynation">By Nationality</a></li>
                            <li><a class="nav-link" href="edu?mis=bygnd">By Gender</a></li>
                            <li><a class="nav-link" href="edu?mis=byage">By Age</a></li>
                            <li><a class="nav-link" href="edu?mis=bypro">By Province</a></li>
                            <li><a class="nav-link" href="edu?mis=bydist">By District</a></li>
                            <li><a class="nav-link" href="edu?mis=bysec">By Sector</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=bystuAcces">By Student Access</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=bycontrol">Stu access control (60D)</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=byctrl7d">Stu  accss control (7D)</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=byAllstu">All student</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=byAllSTS">All students  by status</a></li>-->
                        </ul>
                    </li>
                    
                     <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-sitemap"></i> <span>Statistics</span></a>
                        <ul class="dropdown-menu">
                            <!--<li><a class="nav-link" href="edu?mis=statgen">General Statistics</a></li>-->
                            <li><a class="nav-link" href="edu?mis=statype">By Program Type</a></li>
                            <li><a class="nav-link" href="edu?mis=sttfcult">By School</a></li>
                            <li><a class="nav-link" href="edu?mis=sttDepart">By Department</a></li>
                            <li><a class="nav-link" href="edu?mis=stslevel">By Level</a></li>
                            <li><a class="nav-link" href="edu?mis=stsnation">By Nationality</a></li>
                            <li><a class="nav-link" href="edu?mis=stsGender">By Gender</a></li>
                            <li><a class="nav-link" href="edu?mis=stsacad">By Academic Year</a></li>
                        </ul>
                    </li>
                
                   
                   <?php } ?>
                   
                   <?php if($role_id==7){ ?>
                    <li class="dropdown active">
                        <a href="edu?mis=1" class="nav-link"><i class="fas fa-home"></i><span>Dashboard</span></a>
                       
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fa fa-cog"></i> <span>General Settings</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=acaRank"> Academic Rank</a></li>
                            <li><a class="nav-link" href="edu?mis=contype"> Contract type</a></li>
                            <li><a class="nav-link" href="edu?mis=staPlac"> Staff placement</a></li>
                            <li><a class="nav-link" href="edu?mis=staType">Staff promotion type</a></li>
                            <li><a class="nav-link" href="edu?mis=stafType">Staff type</a></li>
                            <li><a class="nav-link" href="edu?mis=faceW"> Face to Face WorkLoad</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=stf_dept">Staff department</a></li>-->
                            <li><a class="nav-link" href="edu?mis=stfPost">Staff Position</a></li>
                            <li><a class="nav-link" href="edu?mis=stfprb">Staff probation result</a></li>
                            <li><a class="nav-link" href="edu?mis=Leavetype"> Leave types</a></li>
                            <li><a class="nav-link" href="edu?mis=Leaveform"> Leave Form</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="far fa-building"></i> <span>Administration</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=servcode">Service Codes</a></li>
                            <li><a class="nav-link" href="edu?mis=genstr">General Fee Structure</a></li>
                            <li><a class="nav-link" href="edu?mis=paypln">Payment Plans</a></li>
                            <li><a class="nav-link" href="edu?mis=payco">Payment Contracts</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fab fa-cc-amazon-pay"></i> <span>Payments</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=pendpay">Pending Payments</a></li>
                            <li><a class="nav-link" href="edu?mis=payadvance">Payment Advance</a></li>
                            <li><a class="nav-link" href="edu?mis=bkpay">Bank Payments</a></li>
                            <li><a class="nav-link" href="edu?mis=debpay">Debit Payments</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-people-arrows"></i> <span>Manage Employees</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=empList">Employees List</a></li>
                            <li><a class="nav-link" href="edu?mis=stfHour">staff Working Hours</a></li>
                            <li><a class="nav-link" href="edu?mis=stfEV">Staff evaluation</a></li>
                              
                            
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-money-check-alt"></i> <span>Payroll</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=paylist">Payroll List</a></li>
                            <li><a class="nav-link" href="edu?mis=stfgross">Staff gross Salary</a></li>
                            <li><a class="nav-link" href="edu?mis=slycutb">Salary Cutback</a></li>
                        </ul>
                    </li>
                      
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-sort-amount-down"></i> <span>Rollback & WorkLoad</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=accNumber">Account Number</a></li>
                            <li><a class="nav-link" href="edu?mis=teaLoad">Teaching Load</a></li>
                            <li><a class="nav-link" href="edu?mis=cutrep">Cut Reports</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-receipt"></i> <span>Payroll Reports</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=allStfpay">All Staff</a></li>
                            <li><a class="nav-link" href="edu?mis=allstfBank">All By Bank</a></li>
                            <li><a class="nav-link" href="edu?mis=indpayl">Individual</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-sticky-note"></i> <span>Staff Reports</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=stfGenr">General Report</a></li>
                            <li><a class="nav-link" href="edu?mis=allStfinfor">All Staff</a></li>
                            <li><a class="nav-link" href="edu?mis=stafgenderr"> Reports by Gender</a></li>
                            <li><a class="nav-link" href="edu?mis=stapostr">Reports by Position</a></li>
                            <li><a class="nav-link" href="edu?mis=stadepr">Reports by Department</a></li>
                            <li><a class="nav-link" href="edu?mis=stfbysts"> Reports by Status</a></li>
                            <li><a class="nav-link" href="edu?mis=stf_timet">Reports by Time</a></li>
                            <li><a class="nav-link" href="edu?mis=stf_age">Reports by Age</a></li>
                            <li><a class="nav-link" href="edu?mis=rptSchool">By School</a></li>
                            <li><a class="nav-link" href="edu?mis=rptCampus">By Campus</a></li>
                        </ul>
                    </li>
                
                   
                    <?php } ?>
                    <?php if($role_id==26){ ?>
                    <li class="dropdown active">
                        <a href="edu?mis=1" class="nav-link"><i class="fas fa-home"></i><span>Dashboard</span></a>
                       
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fa fa-cog"></i> <span>General Settings</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=acaRank"> Academic Rank</a></li>
                            <li><a class="nav-link" href="edu?mis=contype"> Contract type</a></li>
                            <li><a class="nav-link" href="edu?mis=staPlac"> Staff placement</a></li>
                            <li><a class="nav-link" href="edu?mis=staType">Staff promotion type</a></li>
                            <li><a class="nav-link" href="edu?mis=stafType">Staff Category</a></li>
                            <li><a class="nav-link" href="edu?mis=faceW"> Face to Face WorkLoad</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=stf_dept">Staff department</a></li>-->
                            <li><a class="nav-link" href="edu?mis=stfPost">Staff Position</a></li>
                            <li><a class="nav-link" href="edu?mis=stfprb">Staff probation result</a></li>
                            <li><a class="nav-link" href="edu?mis=Leavetype"> Leave types</a></li>
                            <li><a class="nav-link" href="edu?mis=Leaveform"> Leave Form</a></li>
                        </ul>
                    </li>
                    
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-people-arrows"></i> <span>Manage Employees</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=empList">Employees List</a></li>
                            <li><a class="nav-link" href="edu?mis=stfHour">staff Working Hours</a></li>
                            <li><a class="nav-link" href="edu?mis=stfEV">Staff evaluation</a></li>
                              
                            
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-sticky-note"></i> <span>Staff Reports</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=stfGenr">General Report</a></li>
                            <li><a class="nav-link" href="edu?mis=allStfinfor">All Staff</a></li>
                            <li><a class="nav-link" href="edu?mis=stafgenderr"> Reports by Gender</a></li>
                            <li><a class="nav-link" href="edu?mis=stapostr">Reports by Position</a></li>
                            <li><a class="nav-link" href="edu?mis=stadepr">Reports by Department</a></li>
                            <li><a class="nav-link" href="edu?mis=stfbysts"> Reports by Status</a></li>
                            <li><a class="nav-link" href="edu?mis=stf_timet">Reports by Time</a></li>
                            <li><a class="nav-link" href="edu?mis=stf_age">Reports by Age</a></li>
                            <li><a class="nav-link" href="edu?mis=rptSchool">By School</a></li>
                            <li><a class="nav-link" href="edu?mis=rptCampus">By Campus</a></li>
                        </ul>
                    </li>
                
                   
                    <?php } ?>
                    <?php if($role_id==8){ ?>
                    <li class="dropdown active">
                        <a href="edu?mis=1" class="nav-link"><i class="fas fa-home"></i><span>Dashboard</span></a>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fa fa-cog"></i> <span>Settings</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=setf">Fines</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=barcode">BarCodes</a></li> -->
                             <li><a class="nav-link" href="edu?mis=bookDep">Books Department</a></li> 
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="far fa-address-book"></i> <span>Books</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=bookList">Record</a></li> 
                            <li><a class="nav-link" href="edu?mis=revbook">Review</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fa fa-history"></i> <span>Circulation</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=issb">Issue Book</a></li>
                            <li><a class="nav-link" href="edu?mis=retbook">Return Book</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-tasks"></i> <span>Clearance</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=rmvfine">Clear Fine</a></li>
                            <li><a class="nav-link" href="edu?mis=bkslstd">losted book(s)</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fa fa-book" aria-hidden="true"></i> <span>E-Books</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=addEbook">Record</a></li>
                            <li><a class="nav-link" href="edu?mis=listEbook">Review</a></li>
                        </ul>  
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fa fa-book" aria-hidden="true"></i> <span>E-Papers</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=addEpap">Record</a></li>
                            <li><a class="nav-link" href="edu?mis=listpapon">Review</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-file-export"></i> <span>Reports</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=rptbookdep">Department</a></li>
                            <li><a class="nav-link" href="edu?mis=rptbookauth">Author</a></li>
                            <li><a class="nav-link" href="edu?mis=lcatin">Location</a></li>
                            <li><a class="nav-link" href="edu?mis=byclass">Class</a></li>
                            <li><a class="nav-link" href="edu?mis=rptbookdel">Delayed</a></li>
                            <li><a class="nav-link" href="edu?mis=rptbooklost">Losted</a></li>
                            <li><a class="nav-link" href="edu?mis=brwdbook">Borrowed</a></li>
                            <li><a class="nav-link" href="edu?mis=unclrbook">Uncleared</a></li>  
                        </ul>
                    </li>
                   
                    <?php } ?>
                   
                    <?php if($_SESSION['role_id'] ==9){ ?>
                    <li class="dropdown active">
                        <a href="edu?mis=1" class="nav-link"><i class="fas fa-home"></i><span>Dashboard</span></a>
                    </li>
                    
                    <li class="dropdown">
                        <a href="edu?mis=stuAcs" class="nav-link"><i class="fas fa-wifi"></i> <span>Students Access</span></a>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-id-card"></i> <span>Student Card</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=stucard">Print card</a></li>
                            <li><a class="nav-link" href="edu?mis=cardreg">Card registration</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-id-card"></i> <span>Staff Card</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=staffcard">Print card</a></li>
                            <li><a class="nav-link" href="edu?mis=staffcardreg">Card registration</a></li>
                            <li><a class="nav-link" href="edu?mis=staffcardregrep">Card Report</a></li>
                        </ul>
                    </li>
                   
                    <?php } ?>
                   
                    <?php if($_SESSION['role_id'] ==10){ ?>
                    <li class="dropdown active">
                        <a href="edu?mis=1" class="nav-link"><i class="fas fa-home"></i><span>Dashboard </span></a>
                    </li>
                     <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fab fa-adn"></i> <span>Manage Applicants</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=admreq"> Submitted Applications</a></li>
                            <li><a class="nav-link" href="edu?mis=admresult">Admitted List</a></li>
                            <li><a class="nav-link" href="edu?mis=hfdtlst">Foundation List</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="far fa-address-book"></i> <span>Student Management</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=stuInfo">Student info</a></li>
                            <li><a class="nav-link" href="edu?mis=hdstl">Student List</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Module Management</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=mod">Modules</a></li>
                            <li><a class="nav-link" href="edu?mis=mod_assign">Module to Year</a></li>
                            <li><a class="nav-link" href="edu?mis=mod_lead_assign">Module leaders</a></li>
                            <li><a class="nav-link" href="edu?mis=mod_stu">Student - Modules</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-clipboard-check"></i> <span>Manage Marks </span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=msing">Student Marks</a></li>
                            <li><a class="nav-link" href="edu?mis=cmrks">Check Marks</a></li>
                            <li><a class="nav-link" href="edu?mis=regmks">Register Marks / Mult</a></li>
                            <li><a class="nav-link" href="edu?mis=transOne">Progress Report</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-book-open"></i> <span>Degree</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=graduantList">Graduation List</a></li>
                            <li><a class="nav-link" href="edu?mis=graduantstats">Graduation Statistics</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-sitemap"></i> <span>Statistics</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=stslevel">By Level</a></li>
                            <li><a class="nav-link" href="edu?mis=stsnation">By Nationality</a></li>
                            <li><a class="nav-link" href="edu?mis=stsGender">By Gender</a></li>
                            <li><a class="nav-link" href="edu?mis=stsacad">By Academic Year</a></li>
                            <li><a class="nav-link" href="edu?mis=stsacasem">By Semester </a></li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if($_SESSION['role_id'] ==28){ ?>
                    <li class="dropdown active">
                        <a href="edu?mis=1" class="nav-link"><i class="fas fa-home"></i><span>Dashboard </span></a>
                    </li>
                     <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fab fa-adn"></i> <span>Student Applicants</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=mbast"> Submitted Applications</a></li>
                            
                        </ul>
                    </li>
                    
                    <?php } ?>
                   
                    <?php if($_SESSION['role_id'] ==11){ ?>
                    <li class="dropdown active">
                        <a href="edu?mis=1" class="nav-link"><i class="fas fa-home"></i><span>Dashboard </span></a>
                    </li>
                     <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fab fa-adn"></i> <span>Manage Applicants</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=admreq"> Submitted Applications</a></li>
                            <li><a class="nav-link" href="edu?mis=admresult">Admitted List</a></li>
                            <li><a class="nav-link" href="edu?mis=dnfdtlst">Foundation List</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="far fa-address-book"></i> <span>Student Management</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=stuInfo">Student info</a></li>
                            <li><a class="nav-link" href="edu?mis=dnsdl">Student list</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Module Management</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=mod">Modules</a></li>
                            <li><a class="nav-link" href="edu?mis=mod_assign">Module to Year</a></li>
                            <li><a class="nav-link" href="edu?mis=mod_lead_assign">Module leaders</a></li>
                            <li><a class="nav-link" href="edu?mis=mod_stu">Student - Modules</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-clipboard-check"></i> <span>Manage Marks </span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=msing">Student Marks</a></li>
                            <li><a class="nav-link" href="edu?mis=cmrks">Check Marks</a></li>
                            <li><a class="nav-link" href="edu?mis=regmks">Register Marks / Mult</a></li>
                            <li><a class="nav-link" href="edu?mis=transOne">Progress Report</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-book-open"></i> <span>Degree</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=graduantList">Graduation List</a></li>
                            <li><a class="nav-link" href="edu?mis=graduantstats">Graduation Statistics</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-sitemap"></i> <span>Statistics</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=stssplz">By Department</a></li>
                            <li><a class="nav-link" href="edu?mis=stslevel">By Level</a></li>
                            <li><a class="nav-link" href="edu?mis=stsnation">By Nationality</a></li>
                            <li><a class="nav-link" href="edu?mis=stsGender">By Gender</a></li>
                            <li><a class="nav-link" href="edu?mis=stsacad">By Academic Year</a></li>
                            <li><a class="nav-link" href="edu?mis=stsacadsem">By Semester </a></li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if($_SESSION['role_id'] ==12){ ?>
                    <li class="dropdown active">
                        <a href="edu?mis=1" class="nav-link"><i class="fas fa-home"></i><span>Dashboard</span></a>
                    </li>
                   
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-clipboard-check"></i> <span>Marks and Sessions</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=regm">Register Marks</a></li>
                            <li><a class="nav-link" href="edu?mis=myAssess">My Assessments</a></li>
                            <li><a class="nav-link" href="edu?mis=tmtable">Timetable</a></li>
                            <li><a class="nav-link" href="edu?mis=attlist">Attendance List</a></li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if($role_id==13){ ?>
                    <li class="dropdown active">
                        <a href="edu?mis=1" class="nav-link"><i class="fas fa-home"></i><span>Dashboard</span></a>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fa fa-cog"></i> <span>General settings</span></a>
                        <ul class="dropdown-menu">
                            <!--<li><a class="nav-link" href="edu?mis=feescateg" >Fee Categories</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=prces"> Prices</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=bills_trial"> Bills Trial</a>-->
                            <!--<li><a class="nav-link" href="edu?mis=uplfees"> Upload setting prices file</a></li>-->
                            <li><a class="nav-link" href="edu?mis=sponcateg"> Sponsor Categories</a></li>
                            <li><a class="nav-link" href="edu?mis=spnlist">Sponsors</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="far fa-arrow-alt-circle-up"></i> <span>Invoicing</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=indInvo"> Individual Invoice </a></li>
                            <li><a class="nav-link" href="edu?mis=multInvo"> Multiple Invoice</a></li>
                            <li><a class="nav-link" href="edu?mis=CustInvo"> Custom Invoice</a></li>
                            <li><a class="nav-link" href="edu?mis=invMng"> Invoice Manager</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-briefcase"></i> <span>Student Payments</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=stuspo"> Student sponsors  </a></li>
                            <li><a class="nav-link" href="edu?mis=indpayt"> Individual payment  </a></li>
                            <li><a class="nav-link" href="edu?mis=apppayt">Applicant Payment</a></li>
                            <li><a class="nav-link" href="edu?mis=pytNeg">Payment negotiation</a></li>
                        </ul>
                    </li>
                    
                    <!--<li class="dropdown">-->
                    <!--    <a href="#" class="nav-link has-dropdown"><i class="fas fa-arrows-alt"></i> <span>Balance transfer and Refunds</span></a>-->
                    <!--    <ul class="dropdown-menu">-->
                    <!--        <li><a class="nav-link" href="edu?mis=balTrans">Balance transfer</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=rednd">Refunds</a></li>-->
                              
                            
                    <!--    </ul>-->
                    <!--</li>-->
                    
                    <!--<li class="dropdown">-->
                    <!--    <a href="#" class="nav-link has-dropdown"><i class="fab fa-cc-amazon-pay"></i> <span>Bank Transfer</span></a>-->
                    <!--    <ul class="dropdown-menu">-->
                    <!--        <li><a class="nav-link" href="edu?mis=bkrpt"> Books Reports</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=distrpt">Dissertation Reports</a></li>-->
                    <!--    </ul>-->
                    <!--</li>-->
                  
                    <!--<li class="dropdown">-->
                    <!--    <a href="#" class="nav-link has-dropdown"><i class="fas fa-door-open"></i> <span>Clearence Req.</span></a>-->
                    <!--    <ul class="dropdown-menu">-->
                    <!--        <li><a class="nav-link" href="edu?mis=clerareq"> Requests</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=allreqcl">All Request(s)</a></li>-->
                    <!--    </ul>-->
                    <!--</li>-->
                    
                    <!--<li class="dropdown">-->
                    <!--    <a href="#" class="nav-link has-dropdown"><i class="fas fa-city"></i> <span>Industrial Attach.</span></a>-->
                    <!--    <ul class="dropdown-menu">-->
                    <!--        <li><a class="nav-link" href="edu?mis=indsreq"> Requests</a></li>-->
                    <!--    </ul>-->
                    <!--</li>-->
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-sitemap"></i> <span>Finance Statistics</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=gnlststcs">General statistics</a></li>
                            <li><a class="nav-link" href="edu?mis=allinvc">All invoices</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=cancelled">Cancelled Invoices</a></li>-->
                            <li><a class="nav-link" href="edu?mis=spyt">All payments</a></li>
                            <li><a class="nav-link" href="edu?mis=sbal">Balances</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=invocps">Invoices by Campus</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=pytcps">Payments by Campus</a></li>-->
                            <li><a class="nav-link" href="edu?mis=sfee">By Fee Category</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=sintk">By Intake</a></li>-->
                        </ul>
                    </li>
                    <li class="dropdown">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-sitemap"></i> <span>Pending Requests</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=cancelled_cnvoices_req">Cancelled Invoices</a></li>
                            <li><a class="nav-link" href="edu?mis=re_Activated_nvoices_req">Re Activated Invoices</a></li>
                      
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-file-export"></i> <span>Finance Report</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=byspn">Filter By Sponsors</a></li>
                            <li><a class="nav-link" href="edu?mis=stubyspo"> Students Sponshorship </a></li>
                            <li><a class="nav-link" href="edu?mis=indreport">Individual Report</a></li>
                            <li><a class="nav-link" href="edu?mis=indrptpyt">Individual Payments</a></li>
                            <li><a class="nav-link" href="edu?mis=ptyByclassRpt"> Class Payments</a></li>
                        </ul>
                    </li>
                    
                    <!--<li class="dropdown">-->
                    <!--    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-balance-scale"></i> <span>Student Balance</span></a>-->
                    <!--    <ul class="dropdown-menu">-->
                    <!--        <li><a class="nav-link" href="edu?mis=invopytBlc">By Invoice & Payment</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=bySpon">By All Sponsors</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=stsInvoPay">By Statistics (Inv. & Pay.)</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=invo-stscs">By Statistics (Invoices)</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=pyt-stscs">By Statistics (Payments)</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=byselct">By Selections</a></li>-->
                    <!--    </ul>-->
                    <!--</li>-->
                    
                    <!--<li class="dropdown">-->
                    <!--    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-clipboard"></i> <span>Exams</span></a>-->
                    <!--    <ul class="dropdown-menu">-->
                    <!--        <li><a class="nav-link" href="edu?mis=exmat">Attendance</a></li>-->
                    <!--    </ul>-->
                    <!--</li>-->
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-clipboard"></i> <span>Fee Management</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=feem">Fee Categories </a></li>
                            <li><a class="nav-link" href="edu?mis=feemsp">Specific Fee Categories</a></li>
                        </ul>
                    </li>
                   
                   <?php } ?>
                      <?php if($role_id==14) { ?>
                    <li class="dropdown active">
                        <a href="edu?mis=1" class="nav-link"><i class="fas fa-home"></i><span>Dashboard</span></a>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fa fa-cog"></i> <span>General settings</span></a>
                        <ul class="dropdown-menu">
                            <!--<li><a class="nav-link" href="edu?mis=feescateg"> Fee Categories</a></li>-->
                            <li><a class="nav-link" href="edu?mis=prces"> Prices</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=uplfees"> Upload setting prices file</a></li>-->
                            <li><a class="nav-link" href="edu?mis=sponcateg"> Sponsor Categories</a></li>
                            <li><a class="nav-link" href="edu?mis=spnlist">Sponsors</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="far fa-arrow-alt-circle-up"></i> <span>Invoicing</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=indInvo"> Individual Invoice </a></li>
                            <li><a class="nav-link" href="edu?mis=multInvo"> Invoice by class</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=CustInvo"> Custom Invoice</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=AutoInvo"> Auto Invoice</a></li>-->
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-city"></i> <span>Hostels</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=hblock">Blocks</a></li>
                            <li><a class="nav-link" href="edu?mis=hrclass">Room classification</a></li>
                            <li><a class="nav-link" href="edu?mis=hroom">Rooms</a></li>
                            <li><a class="nav-link" href="edu?mis=hrac"> Accomodation</a></li>
                        </ul>
                    </li>
                    
                    <!--<li class="dropdown">-->
                    <!--    <a href="#" class="nav-link has-dropdown"><i class="fas fa-utensils"></i> <span>Restaurants</span></a>-->
                    <!--    <ul class="dropdown-menu">-->
                    <!--        <li><a class="nav-link" href="edu?mis=restClass">Class</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=rest">Restaurants</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=restu">Registered students</a></li>-->
                    <!--    </ul>-->
                    <!--</li>-->
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-briefcase"></i> <span>Student Payments</span></a>
                        <ul class="dropdown-menu">
                            <!--<li><a class="nav-link" href="edu?mis=stuspo"> Student sponsors  </a></li>-->
                            <li><a class="nav-link" href="edu?mis=indpayt"> Individual payment  </a></li>
                            <li><a class="nav-link" href="edu?mis=apppayt">Applicant Payment</a></li>
                            <li><a class="nav-link" href="edu?mis=pytNeg">Payment negotiation</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=balTrans">Balance transfer</a></li>-->
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-arrows-alt"></i> <span>Balance transfer and Refunds</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=balTrans">Balance transfer</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=rednd">Refunds</a></li>-->
                              
                            
                        </ul>
                    </li>
                    
                    <!--<li class="dropdown">-->
                    <!--    <a href="#" class="nav-link has-dropdown"><i class="fab fa-cc-amazon-pay"></i> <span>Bank Transfer</span></a>-->
                    <!--    <ul class="dropdown-menu">-->
                    <!--        <li><a class="nav-link" href="edu?mis=bkrpt"> Books Reports</a></li>-->
                    <!--         <li><a class="nav-link" href="edu?mis=distrpt">Dissertation Reports</a></li>-->
                              
                            
                    <!--    </ul>-->
                    <!--</li>-->
                  
                    <!--<li class="dropdown">-->
                    <!--    <a href="#" class="nav-link has-dropdown"><i class="fas fa-door-open"></i> <span>Clearence Req.</span></a>-->
                    <!--    <ul class="dropdown-menu">-->
                    <!--        <li><a class="nav-link" href="edu?mis=clerareq"> Requests</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=allreqcl">All Request(s)</a></li>-->
                    <!--    </ul>-->
                    <!--</li>-->
                    
                    <!--<li class="dropdown">-->
                    <!--    <a href="#" class="nav-link has-dropdown"><i class="fas fa-city"></i> <span>Industrial Attach.</span></a>-->
                    <!--    <ul class="dropdown-menu">-->
                    <!--        <li><a class="nav-link" href="edu?mis=indsreq"> Requests</a></li>-->
                    <!--    </ul>-->
                    <!--</li>-->
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-sitemap"></i> <span>Finance Statistics</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=gnlststcs">General Statistics</a></li>
                            <li><a class="nav-link" href="edu?mis=allinvc">All Invoices</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=cancelled">Cancelled Invoices</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=invocps">Invoices by Campus</a></li>-->
                            <li><a class="nav-link" href="edu?mis=opnblc">Opening Balances</a></li>
                            <li><a class="nav-link" href="edu?mis=spyt">Payments</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=pytcps">Payments by Campus</a></li>-->
                            <li><a class="nav-link" href="edu?mis=feesCateg">Invoices by Fee Category</a></li>
                            <li><a class="nav-link" href="edu?mis=pytFcateg">Payments by Fee Category</a></li>
                            <li><a class="nav-link" href="edu?mis=banksts">Bank Status</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-sitemap"></i> <span>Pending Requests</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=view_cancelled_cnvoices_req">Cancelled Invoices</a></li>
                            <li><a class="nav-link" href="edu?mis=view_re_Activated_nvoices_req">Re Activated Invoices</a></li>
                      
                    </ul>
                    </li>
                    
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-file-export"></i> <span>Finance Report</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=indreport">Individual Report</a></li>
                            <li><a class="nav-link" href="edu?mis=ptyByclassRpt">Class Report</a></li> 
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-clipboard"></i> <span>Fee Management</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=feem">Fee Categories </a></li>
                            <li><a class="nav-link" href="edu?mis=feemsp">Specific Fee Categories</a></li>
                        </ul>
                    </li>
                    
                    <!--<li class="dropdown">-->
                    <!--    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-balance-scale"></i> <span>Student Balance</span></a>-->
                    <!--    <ul class="dropdown-menu">-->
                    <!--        <li><a class="nav-link" href="edu?mis=invopytBlc">By Invoice & Payment</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=bySpon">By Sponsor</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=stsInvoPay">By Statistics (Inv. & Pay.)</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=invo-stscs">By Statistics (Invoices)</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=pyt-stscs">By Statistics (Payments)</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=byselct">By Selections</a></li>-->
                    <!--    </ul>-->
                    <!--</li>-->
                    
                    <?php } ?>
                   
                    <?php if($role_id==15){ ?>
                    <li class="dropdown active">
                        <a href="edu?mis=1" class="nav-link"><i class="fas fa-home"></i><span>Dashboard</span></a>
                    </li>
                      
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="far fa-arrow-alt-circle-up"></i> <span>Invoicing</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=indInvo"> Individual Invoice </a></li>
                            <li><a class="nav-link" href="edu?mis=multInvo"> Multiple Invoice</a></li>
                            <li><a class="nav-link" href="edu?mis=CustInvo"> Custom Invoice</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-briefcase"></i> <span>Student Payments</span></a>
                        <ul class="dropdown-menu">
                            <!--<li><a class="nav-link" href="edu?mis=stuspo"> Student sponsors  </a></li>-->
                            <li><a class="nav-link" href="edu?mis=indpayt"> Individual payment  </a></li>
                            <li><a class="nav-link" href="edu?mis=apppayt">Applicant Payment</a></li>
                            <li><a class="nav-link" href="edu?mis=pytNeg">Payment negotiation</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=balTrans">Balance transfer</a></li>-->
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-sitemap"></i> <span>Finance Statistics</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=gnlststcs">General Statistics</a></li>
                            <li><a class="nav-link" href="edu?mis=allinvc">All Invoices</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=cancelled">Cancelled Invoices</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=invocps">Invoices by Campus</a></li>-->
                            <li><a class="nav-link" href="edu?mis=opnblc">Opening Balances</a></li>
                            <li><a class="nav-link" href="edu?mis=pyt">Payments</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=pytcps">Payments by Campus</a></li>-->
                            <li><a class="nav-link" href="edu?mis=feesCateg">Invoices by Fee Category</a></li>
                            <li><a class="nav-link" href="edu?mis=pytFcateg">Payments by Fee Category</a></li>
                            <li><a class="nav-link" href="edu?mis=banksts">Bank Status</a></li>
                        </ul>
                    </li>
                    
                   <li class="dropdown">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-sitemap"></i> <span>Pending Requests</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=view_cancelled_cnvoices_req">Cancelled Invoices</a></li>
                            <li><a class="nav-link" href="edu?mis=view_re_Activated_nvoices_req">Re Activated Invoices</a></li>
                      
                    </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-file-export"></i> <span>Finance Report</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=gnlststcs">General statistics</a></li>
                            <li><a class="nav-link" href="edu?mis=spyt">All payments</a></li>
                            <li><a class="nav-link" href="edu?mis=sbal">Balances</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=invocps">Invoices by Campus</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=pytcps">Payments by Campus</a></li>-->
                            <li><a class="nav-link" href="edu?mis=sfee">By Fee Category</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=sintk">By Intake</a></li>-->
                        </ul>
                    </li>
                    
                    <!--<li class="dropdown">-->
                    <!--    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-balance-scale"></i> <span>Student Balance</span></a>-->
                    <!--    <ul class="dropdown-menu">-->
                    <!--        <li><a class="nav-link" href="edu?mis=invopytBlc">By Invoice & Payment</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=bySpon">By Sponsor</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=stsInvoPay">By Statistics (Inv. & Pay.)</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=invo-stscs">By Statistics (Invoices)</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=pyt-stscs">By Statistics (Payments)</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=byselct">By Selections</a></li>-->
                    <!--    </ul>-->
                    <!--</li>-->
                    
                    <?php } ?>
                    <?php if($role_id==16){ ?>
                    <li class="dropdown active">
                        <a href="edu?mis=1" class="nav-link"><i class="fas fa-home"></i><span>Dashboard </span></a>
                    </li>
                     <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-file-export"></i> <span>Student Report</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=stugen">General Report</a></li>
                            <li><a class="nav-link" href="edu?mis=bysts">By Student status</a></li>
                            <li><a class="nav-link" href="edu?mis=bynation">By Nationality</a></li>
                            <li><a class="nav-link" href="edu?mis=bygnd">By Gender</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=byage">By Age</a></li>-->
                            <li><a class="nav-link" href="edu?mis=bypro">By Province</a></li>
                            <li><a class="nav-link" href="edu?mis=bydist">By District</a></li>
                            <li><a class="nav-link" href="edu?mis=hcrep">Govt. Body Report</a></li>
                            <li><a class="nav-link" href="edu?mis=hcrepage">Govt. Body Age Report</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-sitemap"></i> <span>Statistics</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=statgen">General Statistics</a></li>
                            <li><a class="nav-link" href="edu?mis=statype">By Program Type</a></li>
                            <li><a class="nav-link" href="edu?mis=sttfcult">By School</a></li>
                            <li><a class="nav-link" href="edu?mis=sttDepart">By Department</a></li>
                            <li><a class="nav-link" href="edu?mis=stslevel">By Level</a></li>
                            <li><a class="nav-link" href="edu?mis=stsnation">By Nationality</a></li>
                            <li><a class="nav-link" href="edu?mis=stsGender">By Gender</a></li>
                            <li><a class="nav-link" href="edu?mis=stsintake">By Intake | Academic </a></li>
                            <li><a class="nav-link" href="edu?mis=stsacad">By Academic Year</a></li>
                            <li><a class="nav-link" href="edu?mis=graduantstats">Graduation Statistics</a></li>
                            <li><a class="nav-link" href="edu?mis=evres">Module Evaluation</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown d-none">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-podcast"></i> <span>Student Satisfaction</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=stathecgen">General report</a></li>
                            <li><a class="nav-link" href="edu?mis=stahectype">By Program Type</a></li>
                            <li><a class="nav-link" href="edu?mis=stthecfcult">By School</a></li>
                            <li><a class="nav-link" href="edu?mis=stthecsplz">By Specialization</a></li>
                            <li><a class="nav-link" href="edu?mis=stsheclevel">By Level</a></li>
                            <li><a class="nav-link" href="edu?mis=stshecgender">By Gender</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="edu?mis=sbox" class="nav-link"><i class="fas fa-box-archive"></i><span>Suggestion box</span></a>
                    </li>
                    <?php } ?>
                   
                    <?php if($role_id==17){ ?>
                    
                    <li class="dropdown active">
                        <a href="edu?mis=1" class="nav-link"><i class="fas fa-home"></i><span>Dashboard </span></a>
                    </li>
                    
                    <!--<li class="dropdown">-->
                    <!--    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-angle-double-right"></i> <span>Academic Requests</span></a>-->
                    <!--    <ul class="dropdown-menu">-->
                    <!--        <li><a class="nav-link" href="edu?mis=transReq">Transcript doc request</a></li>-->
                    <!--        <li><a class="nav-link" href="edu?mis=whomReq">Towhom doc request</a></li>-->
                            
                    <!--    </ul>-->
                    <!--</li>-->
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-clock"></i> <span>Session Management</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=addbloc">Blocks</a></li>
                            <li><a class="nav-link" href="edu?mis=addroom">Rooms</a></li>
                            <li><a class="nav-link" href="edu?mis=tablehours">Teaching hours</a></li>
                            <li><a class="nav-link" href="edu?mis=timetable">Timetables</a></li>
                            <li><a class="nav-link" href="edu?mis=gentables">Generated Tables</a></li>
                            <li><a class="nav-link" href="edu?mis=attlist">Attendance List</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=exmat">Exam Attendance</a></li>-->
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-clipboard-check"></i> <span>Manage Marks </span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=regmks">Register Marks</a></li>
                            <li><a class="nav-link" href="edu?mis=transOne">Transcript by One</a></li>
                            <li><a class="nav-link" href="edu?mis=transLev">Transcript by Level</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-book-open"></i> <span>Degree</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=mnggradua">Manage Graduants</a></li>
                            <li><a class="nav-link" href="edu?mis=repraceDeg">Replacement Request</a></li>
                            <li><a class="nav-link" href="edu?mis=printDegree">Print Degree</a></li> 
                            <li><a class="nav-link" href="edu?mis=printDegree2">Print Degree Copy </a></li>
                             <li><a class="nav-link" href="edu?mis=printRep">Print Replacement</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-id-card-alt"></i> <span>Certificate</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=mncltficate">Manage Certificate</a></li>
                            <li><a class="nav-link" href="edu?mis=prtceltif">Print Certificate</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-file-export"></i> <span>Student Report</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=stugen">General Report</a></li>
                            <li><a class="nav-link" href="edu?mis=bysts">By Student status</a></li>
                            <li><a class="nav-link" href="edu?mis=byspn">By Sponsor</a></li>
                            <li><a class="nav-link" href="edu?mis=bynation">By Nationality</a></li>
                            <li><a class="nav-link" href="edu?mis=bygnd">By Gender</a></li>
                            <li><a class="nav-link" href="edu?mis=byage">By Age</a></li>
                            <li><a class="nav-link" href="edu?mis=bypro">By Province</a></li>
                            <li><a class="nav-link" href="edu?mis=bydist">By District</a></li>
                            <li><a class="nav-link" href="edu?mis=bysec">By Sector</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=bystuAcces">By Student Access</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=bycontrol">Stu access control (60D)</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=byctrl7d">Stu  accss control (7D)</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=byAllstu">All student</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=byAllSTS">All students  by status</a></li>-->
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-sitemap"></i> <span>Statistics</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=statgen">General Statistics</a></li>
                            <li><a class="nav-link" href="edu?mis=statype">By Program Type</a></li>
                            <li><a class="nav-link" href="edu?mis=sttfcult">By School</a></li>
                            <li><a class="nav-link" href="edu?mis=sttDepart">By Department</a></li>
                            <li><a class="nav-link" href="edu?mis=stslevel">By Level</a></li>
                            <li><a class="nav-link" href="edu?mis=stsnation">By Nationality</a></li>
                            <li><a class="nav-link" href="edu?mis=stsGender">By Gender</a></li>
                            <li><a class="nav-link" href="edu?mis=stsacad">By Academic Year</a></li>
                            <li><a class="nav-link" href="edu?mis=evres">Module Evaluation</a></li>
                        </ul>
                    </li>
                    
                    <?php } ?>
                   
                    <?php if($role_id==19){ ?>
                    <li class="dropdown ">
                        <a href="edu?mis=0" class="nav-link"><i class="fa fa-home"></i><span>Dashboard </span></a>
                    </li>
                    
                    <li class="dropdown ">
                        <a href="edu?mis=1" class="nav-link"><i class="fa fa-tv"></i><span>ICT </span></a>
                    </li>
                    
                    <li class="dropdown">
                        <a href="edu?mis=2" class="nav-link"><i class="fas fa-file-signature"></i><span>Registrar </span></a>
                    </li>
                    
                    <li class="dropdown">
                        <a href="edu?mis=3" class="nav-link"><i class="	far fa-money-bill-alt"></i><span>Finance</span></a>
                    </li>
                    
                    <li class="dropdown ">
                        <a href="edu?mis=8" class="nav-link"><i class="fa fa-user-md"></i><span>Applicant  </span></a>
                    </li>
                    
                    <li class="dropdown ">
                        <a href="edu?mis=5" class="nav-link"><i class="fas fa-users-cog"></i><span>Admission officer </span></a>
                    </li>
                    
                    <li class="dropdown">
                        <a href="edu?mis=4" class="nav-link"><i class="fas fa-user-graduate"></i><span>Student </span></a>
                    </li>
                    <li class="dropdown ">
                        <a href="edu?mis=6" class="nav-link"><i class="fab fa-firstdraft"></i><span>HR </span></a>
                    </li>
                    
                    <li class="dropdown ">
                        <a href="edu?mis=7" class="nav-link"><i class="fa fa-book"></i><span>Librarian </span></a>
                    </li>

                    <li class="dropdown ">
                        <a href="edu?mis=9" class="nav-link"><i class="	fas fa-book-reader"></i><span>HoD </span></a>
                    </li>
                    
                    <li class="dropdown">
                        <a href="edu?mis=10" class="nav-link"><i class="fas fa-people-arrows"></i><span>Dean Of Student </span></a>
                    </li>
                    
                    <li class="dropdown ">
                        <a href="edu?mis=11" class="nav-link"><i class="fas fa-fill"></i><span>Lecturer </span></a>
                    </li>
                    
                    <li class="dropdown ">
                        <a href="edu?mis=12" class="nav-link"><i class="fas fa-comments-dollar"></i><span>DAF </span></a>
                    </li>
                    
                    <li class="dropdown ">
                        <a href="edu?mis=13" class="nav-link"><i class="fas fa-donate"></i><span>ACCOUNTANT </span></a>
                    </li>
                    
                    <li class="dropdown ">
                        <a href="edu?mis=14" class="nav-link"><i class="fab fa-elementor"></i><span>RECOVERY </span></a>
                    </li>
                    
                    <li class="dropdown ">
                        <a href="edu?mis=15" class="nav-link"><i class="fas fa-id-card-alt"></i><span>QUALITY OPERATOR </span></a>
                    </li>
                    
                    <li class="dropdown ">
                        <a href="edu?mis=6" class="nav-link"><i class="fab fa-ioxhost"></i><span>EXAMINATION OFFICER </span></a>
                    </li>
                    
                   <?php } ?>

                    <?php if($role_id==20 || $role_id==21){  ?>
                    
                    <li class="dropdown active">
                        <a href="edu?mis=1" class="nav-link"><i class="fas fa-home"></i><span>Dashboard</span></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-sitemap"></i> <span>Statistics</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=statgen">General Statistics</a></li>
                            <li><a class="nav-link" href="edu?mis=statype">By Program Type</a></li>
                            <li><a class="nav-link" href="edu?mis=sttfcult">By School</a></li>
                            <li><a class="nav-link" href="edu?mis=sttDepart">By Department</a></li>
                            <li><a class="nav-link" href="edu?mis=stslevel">By Level</a></li>
                            <li><a class="nav-link" href="edu?mis=stsnation">By Nationality</a></li>
                            <li><a class="nav-link" href="edu?mis=stsGender">By Gender</a></li>
                            <li><a class="nav-link" href="edu?mis=stsacad">By Academic Year</a></li>
                            <li><a class="nav-link" href="edu?mis=evres">Module Evaluation</a></li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if($role_id==22){  ?>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-podcast"></i> <span>Student Satisfaction</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=stathecgen">General report</a></li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if($role_id==23){ ?>
                    
                    <li class="dropdown active">
                        <a href="edu?mis=1" class="nav-link"><i class="fas fa-home"></i><span>Dashboard </span></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fa fa-cog"></i> <span>General Settings</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=iclass">Item Class</a></li>
                            <li><a class="nav-link" href="edu?mis=brds">Brands</a></li>
                            <li><a class="nav-link" href="edu?mis=itmz">Items</a></li>
                            <li><a class="nav-link" href="edu?mis=ctgz">Categories</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-folder-open"></i> <span>Management</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=areg">Registration</a></li>
                            <li><a class="nav-link" href="edu?mis=aver">Verification</a></li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php
                        $stmt = $conn->prepare("SELECT * FROM tbl_module_leader WHERE staff_id = ? AND status = 1"); 
                        $stmt->execute([$identification]);
                        if($stmt->rowCount() > 0 && $_SESSION['role_id'] != 12){
                    ?>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-clipboard-check"></i> <span>Teaching</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=regmt">Register Marks (As.)</a></li>
                            <li><a class="nav-link" href="edu?mis=myAssesst">My Assessments</a></li>
                            <li><a class="nav-link" href="edu?mis=tmtablet">Time Table</a></li>
                            <li><a class="nav-link" href="edu?mis=attlistt">Attendance List</a></li>
                            <li><a class="nav-link" href="edu?mis=regmks">Register Marks</a></li>
                        </ul>
                    </li>
                   <?php } ?>
                    <?php if($role_id==2){ ?>
                    
                    <li class="dropdown active">
                        <a href="edu?mis=1" class="nav-link"><i class="fas fa-home"></i><span>Dashboard </span></a>
                    </li>
                     <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fa fa-cog"></i> <span>General Settings</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=ptype"> Program type</a></li>
                            <li><a class="nav-link" href="edu?mis=pfac"> School</a></li>
                            <li><a class="nav-link" href="edu?mis=pdept"> Department</a></li>
                            <li><a class="nav-link" href="edu?mis=splz"> Specialization</a></li>
                            <li><a class="nav-link" href="edu?mis=partn"> Partners</a></li>
                            <li><a class="nav-link" href="edu?mis=bkk"> Book</a></li>
                            <li><a class="nav-link" href="edu?mis=apcrs"> Programme </a></li>
                        </ul>
                    </li>
                      <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-cogs"></i> <span>Academic Settings</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=lvlyear">Level</a></li>
                            <li><a class="nav-link" href="edu?mis=acady"> Academic Year</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=acaintake">Intake</a></li>-->
                            <li><a class="nav-link" href="edu?mis=sem">Semester</a></li>
                            <li><a class="nav-link" href="edu?mis=prg-mode">Program Modes</a></li>
                            <li><a class="nav-link" href="edu?mis=gradate"> Graduation Cycle</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fab fa-adn"></i> <span>Manage Applicants</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=new_applicant">Special Application</a></li>
                            <li><a class="nav-link" href="edu?mis=apper"> Application Period</a></li>
                            <li><a class="nav-link" href="edu?mis=pver"> Pending Verifications</a></li>
                            <li><a class="nav-link" href="edu?mis=padm"> Pending Applications</a></li>
                            <li><a class="nav-link" href="edu?mis=admreq"> Submitted Applications</a></li>
                            <li><a class="nav-link" href="edu?mis=amdaccept">Offer Acceptance</a></li>
                            <li><a class="nav-link" href="edu?mis=admresult">Admitted List</a></li>
                            <li><a class="nav-link" href="edu?mis=applst">Applicant List</a></li>
                            <li><a class="nav-link" href="edu?mis=fdtlst">Foundation List</a></li>
                            <li><a class="nav-link" href="edu?mis=admpaysta">Payment Statistics</a></li>
                            <li><a class="nav-link" href="edu?mis=apppro">Applicant problems</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="far fa-address-book"></i> <span>Student Management</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=newstu">Registration</a></li>
                            <li><a class="nav-link" href="edu?mis=newstudep">Registration Department</a></li>
                            <li><a class="nav-link" href="edu?mis=existstu">Re-Registration</a></li>
                            <li><a class="nav-link" href="edu?mis=stuInfo">Student info</a></li>
                            <li><a class="nav-link" href="edu?mis=rgsrslt">Student List</a></li>
                            <li><a class="nav-link" href="edu?mis=clsrep">Module Repair</a></li>
                            <li><a class="nav-link" href="edu?mis=stuspo"> Student sponsors</a></li>
                              
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-clock"></i> <span>Session Management</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=addbloc">Blocks</a></li>
                            <li><a class="nav-link" href="edu?mis=addroom">Rooms</a></li>
                            <li><a class="nav-link" href="edu?mis=tablehours">Teaching hours</a></li>
                            <li><a class="nav-link" href="edu?mis=timetable">Timetables</a></li>
                            <li><a class="nav-link" href="edu?mis=gentables">Generated Tables</a></li>
                            <li><a class="nav-link" href="edu?mis=attlist">Attendance List</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=exmat">Exam Attendance</a></li>-->
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-id-card"></i> <span>Student Card</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=stucard">Print card</a></li>
                            <li><a class="nav-link" href="edu?mis=cardreg">Card registration</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Module Management</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=mod">Modules</a></li>
                            <li><a class="nav-link" href="edu?mis=mod_assign">Module to Year</a></li>
                            <li><a class="nav-link" href="edu?mis=mod_lead_assign">Module leaders</a></li>
                            <li><a class="nav-link" href="edu?mis=mod_stu">Student - Modules</a></li>
                            <li><a class="nav-link" href="edu?mis=assess_mod">Assessments</a></li>
                        </ul>
                    </li>
                    <!--<li class="dropdown">-->
                    <!--    <a href="edu?mis=reqs" class="nav-link"><i class="fas fa-angle-double-right"></i> <span>Academic Requests</span></a>-->
                    <!--</li>-->
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-clipboard-check"></i> <span>Manage Marks </span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=msing">Student Marks</a></li>
                            <li><a class="nav-link" href="edu?mis=regmksOne">Register Marks / One</a></li>
                            <li><a class="nav-link" href="edu?mis=regmks">Register Marks / Mult</a></li>
                            <li><a class="nav-link" href="edu?mis=regmks2">Push marks</a></li>
                            <li><a class="nav-link" href="edu?mis=cmrks">Check Marks</a></li>
                            <li><a class="nav-link" href="edu?mis=transOne">Transcript / One</a></li>
                            <li><a class="nav-link" href="edu?mis=transLev">Transcript / Level</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-id-card-alt"></i> <span>Transcript & Report</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=transOne">Progress report</a></li>
                            <li><a class="nav-link" href="edu?mis=transAll">Transcript</a></li>
                            
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa fa-repeat"></i> <span>Decision On Student</span></a>
                        <ul class="dropdown-menu">
                            <!--<li><a class="nav-link" href="edu?mis=prmone">Promote by One</a></li>-->
                            <li><a class="nav-link" href="edu?mis=promclass">Promotion</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=decideclass">Decide By Class</a></li>-->
                             <li><a class="nav-link" href="edu?mis=graduant">Graduants</a></li>
                             
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-book-open"></i> <span>Degree</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=graduant">Manage Graduants</a></li>
                            <li><a class="nav-link" href="edu?mis=graduantList">Graduation List</a></li>
                            <li><a class="nav-link" href="edu?mis=graduantstats">Graduation Statistics</a></li>
                            <li><a class="nav-link" href="edu?mis=pdgr">Print Degree</a></li>
                            <li><a class="nav-link" href="edu?mis=pdgr1">Print Degree / One</a></li>
                        </ul>
                    </li>
                    
                    
                     <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-file-export"></i> <span>Student Report</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=stugen">General Report</a></li>
                            <li><a class="nav-link" href="edu?mis=bysts">By Student status</a></li>
                            <li><a class="nav-link" href="edu?mis=byspn">By all Sponsors</a></li>
                            <li><a class="nav-link" href="edu?mis=bynation">By Nationality</a></li>
                            <li><a class="nav-link" href="edu?mis=bygnd">By Gender</a></li>
                            <li><a class="nav-link" href="edu?mis=byage">By Age</a></li>
                            <li><a class="nav-link" href="edu?mis=bypro">By Province</a></li>
                            <li><a class="nav-link" href="edu?mis=bydist">By District</a></li>
                            <li><a class="nav-link" href="edu?mis=bysec">By Sector</a></li>
                            <li><a class="nav-link" href="edu?mis=hcrep">Govt. Body Report</a></li>
                            <li><a class="nav-link" href="edu?mis=hcrepage">Govt. Body Age Report</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=bystuAcces">By Student Access</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=bycontrol">Stu access control (60D)</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=byctrl7d">Stu  accss control (7D)</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=byAllstu">All student</a></li>-->
                            <!--<li><a class="nav-link" href="edu?mis=byAllSTS">All students  by status</a></li>-->
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-sitemap"></i> <span>Statistics</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=statgen">General Statistics</a></li>
                            <li><a class="nav-link" href="edu?mis=statype">By Program Type</a></li>
                            <li><a class="nav-link" href="edu?mis=sttfcult">By School</a></li>
                            <li><a class="nav-link" href="edu?mis=sttDepart">By Department</a></li>
                            <li><a class="nav-link" href="edu?mis=stslevel">By Level</a></li>
                            <li><a class="nav-link" href="edu?mis=stsnation">By Nationality</a></li>
                            <li><a class="nav-link" href="edu?mis=stsGender">By Gender</a></li>
                            <!--<li><a class="nav-link" href="edu?mis=stsintake">By Intake | Academic </a></li>-->
                            <li><a class="nav-link" href="edu?mis=stsacad">By Academic Year</a></li>
                            <li><a class="nav-link" href="edu?mis=stsacasem">By Semester </a></li>
                            <li><a class="nav-link" href="edu?mis=evres">Module Evaluation</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-podcast"></i> <span>Student Satisfaction</span></a>
                        <ul class="dropdown-menu">
                            <li><a class="nav-link" href="edu?mis=stathecgen">General report</a></li>
                            <li><a class="nav-link" href="edu?mis=stahectype">By Program Type</a></li>
                            <li><a class="nav-link" href="edu?mis=stthecfcult">By School</a></li>
                            <li><a class="nav-link" href="edu?mis=stthecsplz">By Specialization</a></li>
                            <li><a class="nav-link" href="edu?mis=stsheclevel">By Level</a></li>
                            <li><a class="nav-link" href="edu?mis=stshecgender">By Gender</a></li>
                        </ul>
                    </li>
                    
                    <?php } ?>
                    <?php if($role_id!=19 && $role_id!=22 && $role_id!=28){  ?>
                    <li class="dropdown">
                        <a href="edu?mis=ann" class="nav-link"><i class="fas fa-bell"></i><span>Announcements</span></a>
                    </li>
                    
                    <?php } ?>
                
                </ul>
                
                <?php if($role_id!=19 && $role_id!=28){  ?>
                <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
                    <a href="https://itec.rw/" class="btn btn-primary btn-lg btn-block btn-icon-split"><i class="fa fa-book"></i> Get Catalog</a>
                </div>
                <?php } ?>
                    
                <?php if($role_id==19){  ?>
                <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
                    <a href="https://itec.rw/" class="btn btn-primary btn-lg btn-block btn-icon-split"><i class="fa fa-book"></i> For More Info</a>
                </div>
                <?php } ?>
            </aside>  
        </div>

<script>
    $(document).ready(function () {
        $("#changeRole").change(function () {
            var dual_date = $('#dual_date').val();
            var acc_id=<?php  echo $acc_id; ?>;
            $(this).after('<div id="loader"><img src="../img/ajax_loader.gif" alt="loading...." width="30" height="30" /></div>');           
            $.get('../changeRole?role_id=' + $(this).val() +'&acc_id=' + acc_id , function (data) {
                changed_success(data);
                $('#loader').slideUp(910, function () {
                    $(this).remove();
                });
                setTimeout(function(){// wait for 5 secs(2)
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

     a:hover 
     {
            color: #ff6600;

     }

/* Element selector for all <li> elements */
li.dropdown  {
    color: black; 
    padding: 3px; /* Add padding inside each list item */
    background-color: #f5f5f5;
    margin: 5px 0; /* Add vertical margin */
    transform: scaleX(1);
    border-radius: 8px; /* Round the corners */
    box-shadow: 0 10px 10px rgba(169, 169, 169, 0.4);
    font-weight: 400;
    font-weight: bold;
    font-size: 13pt;
}
li.nav-link  {
    color: black; 
    padding: 3px; /* Add padding inside each list item */
    background-color: #f5f5f5;
    margin: 5px 0; /* Add vertical margin */
    transform: scaleX(1);
    border-radius: 8px; /* Round the corners */
    box-shadow: 0 10px 10px rgba(169, 169, 169, 0.4);
    font-weight: 400;
    font-weight: bold;
    font-size: 13pt;
}

/* Element selector for all <a> elements */
a  {
    text-decoration: none; /* Remove underline from links */
   /* Set text color */
}

</style>

