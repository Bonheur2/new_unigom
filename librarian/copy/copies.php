<html>

<head>
    <title>Crop Image Before Upload using CropperJS with PHP</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone/dist/dropzone.css" />
    <link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet" />
    <script src="https://unpkg.com/dropzone"></script>
    <script src="https://unpkg.com/cropperjs"></script>
    <style>
        img {
            display: block;
            max-width: 100%;
        }

        .image-container {
            width: 90px;
            /* Adjust the width and height to your desired size */
            height: 90px;
        }

        .image-container img {
            width: 90;
            height: 90%;
            object-fit: cover;

        }
    </style>
</head>

<body>
    <input type="hidden" id="input2">
<div class="modal fade" tabindex="-1" role="dialog" id="oldcode">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Add Old Code</h5>
                                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>                 
                                           </div>
                                                <div class="modal-body">
                                             <div class="row">
                                            <div class="col-12 col-md-12 col-lg-12 col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <form action="" id="editBookOld" method="POST">
                                                   <input type="hidden" name="old_id_copy" id="old_id_copy">
                                                     <input type="text" class="form-control" name="old_code_book" id="old_code_book" placeholder="Type Book Old Code">   
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12 col-lg-4"></div>
                                                        <div class="col-12 col-lg-8">
                                                            <button type="submit" class="btn btn-primary" style="width:80px;"><span id="EspinnerOld"></span>Save</button>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                  
                                                </form>
                                            </div>
                                        </div> 
                                  </div>
                               </div>
                          </div>
                       </div>
                    </div>
<!--edit Modal-->

<div class="modal fade" tabindex="-1" role="dialog" id="viewModal">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Book Edit</h5>
                                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>                 
                                           </div>
                                                <div class="modal-body">
                                             <div class="row">
                                            <div class="col-12 col-md-12 col-lg-12 col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <form action="" id="editBook" method="POST">
                                                        <input type="hidden" name="e_bookId" id="e_bookId">
                                                        <input type="hidden" name="action" value="confirm">
                                                  <div id="accordion">
                                                        <div class="accordion">
                                                            <a href="#" class="btn  font-weight-600 dropdown-toggle active" data-toggle="collapse" data-target="#panel-body-1" aria-expanded="true" style="width:100%; color:black;">Book info</a>
                                                            <div class="col-12 col-md-12 col-lg-12 accordion-body collapse show" id="panel-body-1" data-parent="#accordion">
                                                                <div class="form-group col-12 col-sm-12 col-lg-12">
                                                    <label>Book title</label>
                                                    <input type="text" class="form-control" name="e_title" id="e_title">
                                                    </div>
                                                     <div class="form-group col-12 col-sm-12 col-lg-12">
                                                    <label>Class</label>
                                                    <input type="text" class="form-control" name="e_class" id="e_class">
                                                    </div>
                                                     <div class="form-group col-12 col-sm-12 col-lg-12">
                                                    <label>ISBN</label>
                                                    <input type="text" class="form-control" name="e_isbn" id="e_isbn" >
                                                    </div>
                                                    <div class="form-group col-12 col-sm-12 col-lg-12">
                                                    <label>price</label>
                                                    <input type="text" class="form-control" name="e_price" id="e_price">
                                                    </div>
                                                    <div class="form-group col-12 col-sm-12 col-lg-12">
                                                    <label> other_info</label>
                                                    <input type="text" class="form-control" name="e_other_info" id="e_other_info">
                                                    </div>
                                                    <div class="form-group col-12 col-sm-12 col-lg-12">
                                                    <label>Section</label>
                                                    <div class="input-group">
                                                        <select class="form-control select2" style="width:100%;" name="e_section" id="e_section">
                                                        <option value="General">General</option>
                                                        <option value="Cartoon Adults">Cartoon Adults</option>
                                                        <option value="Cartoon kids">Cartoon kids</option>
                                                        <option value="Youth Comics">Youth Comics</option>
                                                        <option value="Children's documentaries">Children's documentaries</option>
                                                        <option value="Youth documentaries">Youth documentaries</option>
                                                        <option value="FR (Regional Fund)">FR (Regional Fund)</option>
                                                        <option value="H (Local History)">H (Local History)</option>
                                                        <option value="Newspapers">Newspapers</option>
                                                        <option value="Novels & Foreig Novels">Novels & Foreig Novels</option>
                                                        <option value="Children's Novels">Children's Novels</option>
                                                        <option value="Young Novels">Young Novels</option>
                                                        <option value="Wide View Novels">Wide View Novels</option>
                                                        <option value="Detective Novels">Detective Novels</option>
                                                    </select>
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-12 col-lg-12">
                                                    <label>Material type</label>
                                                    <div class="input-group">
                                                        <select class="form-control select2" style="width:100%;" name="e_material_type" id="e_material_type">
                                                        <option value="Books">Books</option>
                                                        <option value="Video tape">Video tape</option>
                                                        <option value="Audio CD">Audio CD</option>
                                                        <option value="CD-ROMs">CD-ROMs</option>
                                                        <option value="Artwork">Artwork</option>
                                                        <option value="Periodic">Periodic</option>
                                                        
                                                    </select>
                                                    </div>
                                                </div>
                                                   
                                                            </div>
                                                        </div>
                                                        <div class="accordion">
                                                            <a href="#" class="btn font-weight-600 dropdown-toggle" data-toggle="collapse" data-target="#panel-body-2" aria-expanded="true" style="width:100%; color:black;">Authors & Publishers</a>
                                                            <div class="col-12 col-md-12 col-lg-12 accordion-body collapse" id="panel-body-2" data-parent="#accordion">
                                                                <div class="form-group col-12 col-sm-12 col-lg-12">
                                                    <label>First Author</label>
                                                    <input type="text" class="form-control" name="e_author1" id="e_author1" >
                                                    </div> 
                                                    <div class="form-group col-12 col-sm-12 col-lg-12">
                                                    <label>Second Author</label>
                                                    <input type="text" class="form-control" name="e_author2" id="e_author2" >
                                                    </div>
                                                        <div class="form-group col-12 col-sm-12 col-lg-12">
                                                    <label>First Publisher</label>
                                                    <input type="text" class="form-control" name="e_pubisher1" id="e_pubisher1" >
                                                    </div> 
                                                    <div class="form-group col-12 col-sm-12 col-lg-12">
                                                    <label>Second Publisher</label>
                                                    <input type="text" class="form-control" name="e_pubisher2" id="e_pubisher2" >
                                                    </div>
                                                    
                                                            </div>
                                                        </div>
                                                        <div class="accordion">
                                                            <a href="#" class="btn  font-weight-600 dropdown-toggle" data-toggle="collapse" data-target="#panel-body-3" aria-expanded="true" style="width:100%; color:black;">Library Info</a>
                                                            <div class="col-12 col-md-12 col-lg-12 accordion-body collapse" id="panel-body-3" data-parent="#accordion">
                                                             <div class="form-group col-12 col-sm-12 col-lg-12">
                                                                    <label>Program (*)</label>
                                                                    <div class="input-group">
                                                                   
                                                                    <select class="form-control select2" style="width:100%;"name="e_dep" id="e_dep">
                                                                         <?php 
                                                             $stmt = $conn->prepare("SELECT * FROM  tbl_books_depart WHERE status=1");
                                                                $stmt->execute();
                                                                if($row=$stmt->rowCount()>0){
                                                                    while($row=$stmt->fetch()){
                                                                    ?>
                                                              <option value="<?php echo $row['book_id']; ?>"><?php echo $row['book_dep_name']; ?> </option>
                                                             <?php
                                                                        
                                                                }
                                                                } else{
                                                                  ?>
                                                                <option value='-1'>No program found</option>
                                                             <?php }
                                                            ?>
                                                                         
                                                                    </select>
                                                                </div>
                                                                </div>   
                                                                <div class="form-group col-12 col-sm-12 col-lg-12">
                                                                <label>Date of publication</label>
                                                                <input type="date" class="form-control" name="e_date_publish" id="e_date_publish" >
                                                                </div> 
                                                                <div class="form-group col-12 col-sm-12 col-lg-12">
                                                                <label>Version (or date of version)</label>
                                                                <input type="date" class="form-control" name="e_vers_publish" id="e_vers_publish" >
                                                                </div> 
                                                                <div class="form-group col-12 col-sm-12 col-lg-12">
                                                                <label>Language OF publication</label>
                                                              <input type="text" class="form-control" name="e_lang_publish" id="e_lang_publish">
                                                                </div>
                                                                <div class="form-group col-12 col-sm-12 col-lg-12">
                                                                <label>Orginal language</label>
                                                              <input type="text" class="form-control" name="e_org_lang" id="e_org_lang">
                                                                </div>
                                                             <div class="form-group col-12 col-sm-12 col-lg-12">
                                                                <label>Status</label>
                                                                <div class="input-group">
                                                                     <select class="form-control select2" style="width:100%;"name="e_status" id="e_status">
                                                            
                                                                    <option value="On-site consultation">On-site consultation</option>
                                                                    <option value="Deteriorated">Deteriorated</option>
                                                                    <option value="Document in good condtion">Document in good condtion</option>
                                                                    <option value="in process of import/entry">in process of import/entry</option>
                                                                    <option value="Deposit">Deposit</option>
                                                                    <option value="Lost">Lost</option>
                                                                </select>
                                                                    
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-12 col-sm-12 col-lg-12">
                                                                <label>Lease</label>
                                                                <div class="input-group">
                                                                    <select class="form-control select2" style="width:100%;" name="e_lease" id="e_lease">
                                                                    <option value="Library shelves">Library shelves</option>
                                                                    <option value="Library store">Library store</option>
                                                                </select>
                                                                </div>
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12 col-lg-4"></div>
                                                        <div class="col-12 col-lg-8">
                                                            <button type="submit" class="btn btn-primary" style="width:80px;"><span id="Espinner"></span>Save</button>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                  
                                                </form>
                                            </div>
                                        </div> 
                                  </div>
                               </div>
                          </div>
                       </div>
                    </div>
<!--end of edit modal-->
    <!-- Start app main Content -->
    <div class="main-content" id="books">
        <section class="section">
            <div class="section-header">
                <h1>Copies</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Available Copies</a></div>
                </div>
            </div>

        </section>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-2"></div>
                <div class="form-group col-12 col-md-8 col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="input-group">
                                <input type="text" class="form-control"
                                    placeholder="search by scan barcode" id="input">
                                <div class="input-group-append">
                                    <div class="input-group-text" id="spinner7">
                                        <i class="fas fa-search"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-4"></div>
                <div class="col-12 col-md-4" id="spinnerE"></div>
            </div>
        </div>
        <div id="card_data" style="display:none">
            <div class="card-body">



                <div id="seacrhedbook">



                </div>
                <div class="row" id="statistics" hidden>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="far fa-user"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Copies</h4>
                                </div>
                                <div class="card-body">
                                    <span id="total_copies"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-danger">
                                <i class="far fa-newspaper"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Losted</h4>
                                </div>
                                <div class="card-body">
                                    <span id="lost_copies"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                                <i class="far fa-file"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Borrowed</h4>
                                </div>
                                <div class="card-body">
                                    <span id="borrowed_copies"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="fas fa-circle"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Available</h4>
                                </div>
                                <div class="card-body">
                                    <span id="available_copies"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="col-lg-4 col-md-6 col-sm-6 col-12">-->
                    <!--    <div class="card card-statistic-1">-->
                    <!--        <div class="card-icon bg-success">-->
                    <!--        <span id="cover"></span>-->
                    <!--        </div>-->
                    <!--        <div class="card-wrap">-->
                    <!--            <div class="card-header">-->
                    <!--                <h4><span id="titlebk"></span> | </h4>-->
                    <!--            </div>-->
                    <!--            <div class="card-body">-->
                    <!--                <span id="ac_name"></span>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->

                </div>


                <form action="update_form1" method="POST" id="update_form1" hidden>


                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab1" role="tablist">
                            <li class="nav-item"><a class="nav-link active" id="contact-tab1" data-toggle="tab"
                                    href="#contact1" role="tab" aria-controls="contact1"
                                    aria-selected="false">&nbsp;Book info</a></li>
                            <li class="nav-item"><a class="nav-link" id="home-tab1" data-toggle="tab" href="#home1"
                                    role="tab" aria-controls="home1" aria-selected="true">&nbsp;Copies</a></li>
                            <!--<li class="nav-item"><a class="nav-link" id="home-tab" data-toggle="tab" href="#home"-->
                            <!--        role="tab" aria-controls="home" aria-selected="true"><i class="fa fa-plus"-->
                            <!--            aria-hidden="true"></i>&nbsp;New copy</a></li>-->
                        </ul>

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="contact1" role="tabpanel"
                                aria-labelledby="contact-tab1">
                                <div class="modal-header">

                                </div>
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" id="e_id" name="e_id">

                                <label>Book title</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            &nbsp;<i class="fas fa-info"></i>&nbsp;
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="b_title1" id="b_title" placeholder=""
                                        required>
                                </div>


                                <label>Department</label>
                                <div class="input-group">

                                    <select class="form-control select2" style="width:100%;" name="b_dep1" id="p_type1"
                                        required>
                                        <?php 
                                             $stmt = $conn->prepare("SELECT * FROM tbl_books_depart WHERE status=1");
                                                $stmt->execute();
                                                if($row=$stmt->rowCount()>0){
                                                    while($row=$stmt->fetch()){
                                                    ?>
                                        <option value="<?php echo $row['book_id']; ?>">
                                            <?php echo $row['book_dep_name']; ?>
                                        </option>
                                        <?php
                                                        
                                                }
                                                } else{
                                                  ?>
                                        <option value='-1'>No department found</option>
                                        <?php }
                                            ?>
                                    </select>
                                </div>


                                <label>Author</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text" id="authorsearcher">
                                            &nbsp;<i class="fa fa-user-circle" aria-hidden="true"></i>&nbsp;
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="author1" id="author1" placeholder=""
                                        required>
                                </div>

                                <label>Secondary Author(s)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            &nbsp;<i class="fas fa-info"></i>&nbsp;
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="bsecondauthor" id="bsecondauthor">
                                </div>

                                <label>Publisher</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            &nbsp;<i class="fas fa-info"></i>&nbsp;
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="publisher1" id="publisher1"
                                        placeholder="" required>
                                </div>
                                <label>Secondary Publisher (s)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            &nbsp;<i class="fas fa-info"></i>&nbsp;
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="secondpublisher" id="secondpublisher">
                                </div>

                                <label>ISBN</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            &nbsp;<i class="fas fa-info"></i>&nbsp;
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="isbn1" id="isbn1" placeholder=""
                                        required>
                                </div>


                                <label>Select Book Location</label>
                                <div class="input-group">
                                    <select class="form-control select2" style="width:100%;" name="b_location1"
                                        id="b_location1">

                                        <?php 
                                             $stmt = $conn->prepare("SELECT * FROM `books_location`");
                                                $stmt->execute();
                                                if($row=$stmt->rowCount()>0){
                                                    while($row=$stmt->fetch()){
                                                    ?>
                                        <option value="<?php echo $row['id']; ?>">
                                            <?php echo $row['location_Code']; ?> |
                                            <?php echo $row['location_name']; ?>
                                        </option>
                                        <?php
                                                        
                                                }
                                                } else{
                                                  ?>
                                        <option value='-1'>No Location found</option>
                                        <?php }
                                            ?>
                                    </select>
                                </div>


                                <label>Price</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            &nbsp;<i class="fa fa-usd" aria-hidden="true"></i>&nbsp;
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="bprice" id="bprice" placeholder="">
                                </div>

                                <label>Other information</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            &nbsp;<i class="fas fa-info"></i>&nbsp;
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="bother_info" id="bother_info"
                                        placeholder="">
                                </div>

                                <label>Notes on contents</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            &nbsp;<i class="fa fa-file-text" aria-hidden="true"></i>&nbsp;
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="bnote_on_contents"
                                        id="bnote_on_contents" placeholder="">
                                </div>

                                <label>Summary</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            &nbsp;<i class="fa fa-file-text" aria-hidden="true"></i>&nbsp;
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="bSummary" id="bSummary"
                                        placeholder="">
                                </div>

                                <label>Date of publication</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            &nbsp;<i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;
                                        </div>
                                    </div>
                                    <input type="date" class="form-control" name="bdate_of_publication"
                                        id="bdate_of_publication" placeholder="">
                                </div>

                                <label>Version (or date of version)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            &nbsp;<i class="fa fa-address-book" aria-hidden="true"></i>&nbsp;
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="bversion" id="bversion"
                                        placeholder="">
                                </div>

                                <label>Language OF publication</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            &nbsp;<i class="fa fa-language" aria-hidden="true"></i>&nbsp;
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="blanguage_publication"
                                        id="blanguage_publication" placeholder="">
                                </div>

                                <label>Orginal language</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            &nbsp;<i class="fa fa-language" aria-hidden="true"></i>&nbsp;
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="borginal_language"
                                        id="borginal_language" placeholder="">
                                </div>

                                <label>Link (electronic resources)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            &nbsp;<i class="fa fa-link" aria-hidden="true"></i>&nbsp;
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="bassociatedURL" id="bassociatedURL"
                                        placeholder="associatedURL">
                                </div>

                                <label>Status</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            &nbsp;<i class="fa fa-certificate" aria-hidden="true"></i>&nbsp;
                                        </div>
                                    </div>
                                    <select class="form-control" name="bstatus" id="bstatus">

                                        <option value="On-site consultation">On-site consultation</option>
                                        <option value="Deteriorated">Deteriorated</option>
                                        <option value="Document in good condtion">Document in good condtion</option>
                                        <option value="in process of import/entry">in process of import/entry</option>
                                        <option value="Deposit">Deposit</option>
                                        <option value="Lost">Lost</option>
                                    </select>

                                </div>

                                <label>Lease</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            &nbsp;<i class="fa fa-building" aria-hidden="true"></i>&nbsp;
                                        </div>
                                    </div>
                                    <select class="form-control" name="blease" id="blease">
                                        <option value="Library shelves">Library shelves</option>
                                        <option value="Library store">Library store</option>
                                    </select>
                                </div>

                                <label>Section</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            &nbsp;<i class="fa fa-users" aria-hidden="true"></i>&nbsp;
                                        </div>
                                    </div>
                                    <select class="form-control" name="bsection" id="bsection">
                                        <option value="Cartoon Adults">Cartoon Adults</option>
                                        <option value="Cartoon kids">Cartoon kids</option>
                                        <option value="Youth Comics">Youth Comics</option>
                                        <option value="Children's documentaries">Children's documentaries</option>
                                        <option value="Youth documentaries">Youth documentaries</option>
                                        <option value="FR (Regional Fund)">FR (Regional Fund)</option>
                                        <option value="H (Local History)">H (Local History)</option>
                                        <option value="Newspapers">Newspapers</option>
                                        <option value="Novels & Foreig Novels">Novels & Foreig Novels</option>
                                        <option value="Children's Novels">Children's Novels</option>
                                        <option value="Young Novels">Young Novels</option>
                                        <option value="Wide View Novels">Wide View Novels</option>
                                        <option value="Detective Novels">Detective Novels</option>
                                    </select>

                                </div>

                                <div class="modal-footer  br">
                                    <button type="button" class="btn btn-secondary btn-sm exit">Close</button>
                                    <button type="submit" class="btn btn-primary btn-sm"><span
                                            id="spinner2"></span>&nbsp;<span id="indicator2">Save
                                            changes</span></button>
                                </div>
                </form>

            </div>
            <div class="tab-pane fade" id="home1" role="tabpanel" aria-labelledby="home-tab1">
                <!--<button type="button" onclick="generatePDF()" class="btn btn-primary btn-sm">Print QR-Codes</button><br>-->
                &nbsp;&nbsp;
                <div class="row dark " id="qrcodes">
                </div>

            </div>

            <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">

                <!--Add copy-->
                <div class="card">
                    <div class="card-body row">
                        <form id="save_copy" action="save_copy" method="POST">
                            <input type="hidden" name="action" value="savecopy">
                            <input type="hidden" name="b_tp" id="b_tp">
                            <div class="card-body pb-0 row">
                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                    <div class="row">
                                        <div class="col-12 col-lg-12" Id="bookname">
                                        </div>
                                        <div class="col-12 col-lg-12" id="BookTitle">

                                        </div>
                                        <div class="col-12 col-lg-12" id="BookDep">

                                        </div>
                                    </div>
                                </div>
                                <div clas="col-12 col-lg-8">
                                    <div class="row">

                                        <div class="form-group col-12 col-sm-6 col-lg-6">
                                            <label>Number of copies</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        &nbsp;<i class="fa fa-clone" aria-hidden="true"></i>&nbsp;
                                                    </div>
                                                </div>
                                                <input type="number" class="form-control" name="book_codes"
                                                    id="book_codes" placeholder="Enter No of Copies" required>
                                            </div>
                                        </div>
                                        <div class="form-group col-12 col-sm-6 col-lg-6">
                                            <label>Select Block</label>
                                            <div class="input-group">
                                                <select class="form-control select2" style="width:100%;"
                                                    name="b_location" id="b_location" required>
                                                    <?php 
                                             $stmt = $conn->prepare("SELECT * FROM `books_location`");
                                                $stmt->execute();
                                                if($row=$stmt->rowCount()>0){
                                                    while($row=$stmt->fetch()){
                                                    ?>
                                                    <option value="<?php echo $row['id']; ?>">
                                                        <?php echo $row['location_Code']; ?> |
                                                        <?php echo $row['location_name']; ?>
                                                    </option>
                                                    <?php
                                                        
                                                }
                                                } else{
                                                  ?>
                                                    <option value='-1'>No block found</option>
                                                    <?php }
                                            ?>
                                                </select>
                                            </div>
                                        </div>
                                         <div class="col-12 col-sm-4 col-lg-4">
                                            </div>
                                        <div class="form-group col-12 col-sm-8 col-lg-8">
                                            <label>&nbsp;</label>
                                            <div class="input-group">
                                                <button type="submit" class="btn btn-primary" style="width:80px"><span id="indicatorsave"></span>Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


            </div>
        </div>
    </div>



    <div class="card-body">
        <div class="row" id="products-carousel">

        </div>

    </div>
    </div>
    </div>
    </div>










    </div>
    </div>
    </div>

    <!--javascript-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cropperjs/dist/cropper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function () {
            $('[data-toggle="popover"]').popover();

            $('#author1').keyup(function (e) {
                var formData = {
                    author: $(this).val(),
                    action: 'searchAutho'
                };
                $('#authorsearcher').html("&nbsp;<img src='/img/ajax_loader.gif' width='15'>&nbsp;").fadeIn('fast');
                $('#secondaryauthor').attr("hidden", true);

                $.ajax({
                    url: "books/book_controller.php",
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    success: function (data) {
                        $('#authorsearcher').html('&nbsp;<i class="fa fa-user-circle" aria-hidden="true"></i>&nbsp;');
                        if (data.length > 0) {
                            var i = 1;
                            var html = '';
                            data.forEach(function (value) {
                                var names = value.author_name;
                                var id = value.id;
                                html += '<tr class="stu" id="stu" data-id=' + id + ' style="cursor: pointer;">';
                                html += '<td>&nbsp;<i class="fa fa-user-circle" aria-hidden="true"></i>&nbsp;</td>';
                                html += '<td>' + names + '</td>';
                                html += '</tr>';
                                i++;
                            });
                        } else {
                            html = '<tr><td colspan="2"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> oops,Author not found</td></tr>';
                            html += '<tr><td colspan="2"><button id="saveAuthorBtn" class="btn btn-primary">Save Author</button></td></tr>';
                        }

                        var popoverContent = $('<table class="table table-sm">').html(html).prop('outerHTML');

                        $('#author1').popover('dispose').popover({
                            content: popoverContent,
                            html: true,
                            trigger: 'manual'
                        });

                        $('#author1').popover('show');


                        $('.stu').click(function () {
                            var selectedName = $(this).find('td:nth-child(2)').text();
                            $('#author1').val(selectedName);
                            $('#author1').popover('hide');
                            $('#authorsearcher').html("&nbsp;<i class='fa fa-check' aria-hidden='true'></i>&nbsp;");
                            $('#secondaryauthor').attr("hidden", false);

                        });

                        $('#saveAuthorBtn').click(function () {
                            var formData = {
                                author_name: $('#author1').val(),
                                action: 'save_author'
                            };
                            $('#authorsearcher').html("&nbsp;<img src='/img/ajax_loader.gif' width='15'>&nbsp;").fadeIn('fast');
                            $.ajax({
                                url: "books/book_controller.php",
                                type: "POST",
                                data: formData,
                                dataType: "json",
                                success: function (data) {
                                    if (data.status == 200) {
                                        var selectedName = $('#author1').val();
                                        $('#author1').popover('hide');
                                        $('#authorsearcher').html("&nbsp;<i class='fa fa-check' aria-hidden='true'></i>&nbsp;");
                                        $('#secondaryauthor').attr("hidden", false);
                                    } else if (data.status == 401) {
                                        pop_wrong(data.message);
                                    } else if (data.status == 500) {
                                        pop_wrong(data.message);
                                    }
                                },
                                error: function () {
                                    $('#spinner').fadeOut('fast');
                                    $('#indicator').html("Save");
                                    $('#secondaryauthor').attr("hidden", true);
                                    pop_wrong("Something went wrong!");
                                }
                            });
                        });

                    },
                    error: function () {
                        $('#author1').popover('dispose').popover({
                            content: 'Something went wrong',
                            trigger: 'manual'
                        });

                        $('#author1').popover('show');
                        $('#secondaryauthor').attr("hidden", true);
                    }
                });


            });

        });
    </script>

    <script>
        function generatePDF() {
            var qrcodesElement = document.getElementById('qrcodes');

            html2canvas(qrcodesElement)
                .then(function (canvas) {
                    var imageData = canvas.toDataURL('image/png');
                    var doc = new jsPDF('p', 'mm', 'a4');
                    var imageHeight = canvas.height * 210 / canvas.width;
                    doc.addImage(imageData, 'PNG', 10, 10, 190, imageHeight);
                    // var name = document.getElementById('titlebk');
                    doc.save('qrcodes.pdf');
                });
        }

        $(document).ready(function () {


            $("#input").keyup(function (e) {
                var formData = {
                    keyword: $(this).val(),
                    action: 'search'
                }
                $('#update_form1').attr("hidden", true);
                $('#statistics').attr("hidden", true);
                $('#seacrhedbook').attr("hidden", false);
                $('#spinner7').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $.ajax({
                    url: "copy/copy_controller.php",
                    type: "POST",
                    data: formData,
                    dataType: "JSON",
                    success: function (data) {
                        $('#spinner7').html("<i class='fas fa-search'></i>")
                        if (data.length > 0) {
                            var i = 1;
                            var html = '';
                            var html = '<div class="row">';
                            data.forEach(function (value) {
                                var images = value.image;
                                var titles = value.title;
                                var departments = value.book_dep_name;
                                var id = value.book_id;
                                var author = value.author;
                                var book_code = value.book_code;
                                var second_author=value.secondauthor;
                                var mainBarcode=value.bar_code;
                                html += '<div class="col-12 col-md-6 col-lg-3" >';
                                html += '<div class="card card-primary" >';
                                html += '<div data-id=' + id + ' class="edit bg-light" style="cursor: pointer;" class="card-body">';
                                html += '<p><img src="/librarian/books/' + images + '" alt="Book Image" ">';
                                html += '<span> <b>' + 'Title : </b>' + titles + '</span>';
                                html +='<br>';
                                html += '<span><b>' + 'Program : </b>' + departments + '</span>';
                                html +='<br>';
                                html += '<span> <b>' + 'Author : </b>' + author + '</span>';
                                html +='<br>';
                                html += '<span> <b>' + 'Second Author : </b>' + second_author + '</span>';
                                html +='<br>';
                                html += '<span> <b>' + 'Main Book : </b> <span id="mainBarcode">' + mainBarcode + '</span></span>';
                                html+='</div>';
                                // html +='<div class="card-footer" style="background:#7c56bd;">'
                                // html +='<div class="row">';
                                // html +='<div class="col-12 col-lg-6 col-sm-12 col-md-6">';
                                // html +='<i class="fa-solid fa-trash" style="color:#fff;font-size:22px;cursor: pointer;" value="' + id + '" title="Delete Book"></i>'; 
                                // html +='<span id="Fspinner"></span>';
                                // html +='</div>';
                                // html +='<div class="col-12 col-lg-6 col-sm-12 col-md-6">';
                                // html +='<i class="fa-solid fa-pen-to-square"  style="color:#fff;font-size:22px;cursor: pointer;" value="' + id + '" title="Edit Book"></i>';
                                // html +='<span id="Dspinner"></span>';
                                // html +='</div>';
                                // html +='</div>';
                                // html += '</div>';
                                html += '</div>';
                                html += '</div>';
                            });
                            html += '</div>';

                            $('#seacrhedbook').html(html);
                            $('#card_data').css({
                                display: 'block'
                            });
                        } else {
                            $('#spinner7').html('<i class="fa fa-exclamation-circle" aria-hidden="true"></i> oops, no data found');
                            $('#card_data').css({
                                display: 'none'
                            })
                        }
                    }, error: function () {
                        $('#spinner').html("<i class='fas fa-search'></i>")
                        pop_wrong("Something went wrong!");
                    }
                });
            });
            //pre-update View
            $(document).on('click', '.edit', function () {
                var data_id = $(this).data('id');
                var mainBarcode=$("#mainBarcode").text();
                var title = $(this).find('.card-header h4').text();
                var getData = {
                    id: data_id,
                    action: 'view'
                };
                $("#input").val(mainBarcode);
                $("#input2").val(data_id);
                $('#spinnerE').html("<img src='/img/ajax_loader.gif' width='24'>").fadeIn('fast');
                $.ajax({
                    type: "POST",
                    url: "books/book_controller.php",
                    data: getData,
                    dataType: "json",
                    success: function (data) {
                        $('#spinnerE').fadeOut('fast');
                        $('#spinner7').html("<i class='fas fa-search'></i>")
                        $("#e_id").val(data_id);
                        $("#ac_name").html(data[0].book_code);
                        $("#titlebk").html(data[0].title);

                        $("#b_title").val(data[0].title);
                        $("#p_type1").val(data[0].department);
                        $("#bsecondauthor").val(data[0].secondauthor);
                        $("#author1").val(data[0].author);
                        $("#publisher1").val(data[0].publisher);
                        $("#secondpublisher").val(data[0].secondpublisher);
                        $("#isbn1").val(data[0].isbn);
                        $("#b_location1").val(data[0].location);
                        $('#bprice').val(data[0].price);
                        $('#bother_info').val(data[0].other_info);
                        $('#bnote_on_contents').val(data[0].note_on_contents);
                        $('#bSummary').val(data[0].Summary);
                        $('#bdate_of_publication').val(data[0].date_of_publication);
                        $('#bversion').val(data[0].version);
                        $('#blanguage_publication').val(data[0].language_publication);
                        $('#borginal_language').val(data[0].orginal_language);
                        $('#bassociatedURL').val(data[0].associatedURL);
                        $('#bstatus').val(data[0].status);
                        $('#blease').val(data[0].lease);
                        $('#bsection').val(data[0].section);
                        $("#b_tp").val(data[0].book_id);
                        $("#bookname").html('<img src="/librarian/books/' + data[0].image + '" alt="Book cover" style="width:150px;height:150px;">');
                        $("#BookTitle").html('<b>Title </b>:' + data[0].title);
                        $("#BookDep").html('<b>Department :</b>' + data[0].dept_full_name);
                        var html3 = '';
                        var cover = data[0].image;
                        html3 += ' <div class="image-container">';
                        html3 += '<img src="/librarian/books/' + cover + '" alt="Book cover">';
                        html3 += '</div>';

                        $("#cover").html(html3);

                        $('#total_copies').html(data[1].total_copies);
                        $('#lost_copies').html(data[2].lost_copies);
                        $('#borrowed_copies').html(data[3].borrowed_copies);
                        $('#available_copies').html(data[4].available_copies);
                        var html2 = '';
                        var html2 = '<div class="row">';
                        if (data[1].total_copies>0) {
                            data[5].forEach(function (value) {
                                var qrcode = value.qr_code_file;
                                var copycode = value.book_code_number;
                                var 	old_copy_code=value.old_copy_code;
                                if(data[1].total_copies==1 ){
                                 html2 += '<div  class="col-12 col-md-6 col-lg-10">';   
                                }
                               else if( data[1].total_copies==2){
                                    html2 += '<div  class="col-12 col-md-6 col-lg-6">';      
                                }
                                
                                else{
                                    html2 += '<div  class="col-12 col-md-6 col-lg-6">';
                                }
                                
                                html2 += '<div class="card card-primary">';
                                html2 += '<div class="card-header">';
                                html2 += '<h4>' + copycode + '[ ' + data[0].title + ' ]' + '</h4>';
                               
                                html2 += '</div>';
                                html2 += '<div class="card-body">';
                               
                                html2 += '<p><img src="/librarian/books/' + data[0].image + '" alt="Book Image" style="width:220px;height:115px;"><img src="/librarian/books/' + qrcode + '" alt="Book Image" style="width:45px;height:100%;margin-left:50px;margin-top:-60px"></p>';
                                 if(old_copy_code){
                                  
                                }
                                else{
                                 html2 +='<button class="btn btn-primary ol_code"  value="' + copycode + '" title="Add Old Code">+ </button>';     
                                }
                               html2 +='<button class="btn btn-sm dete_copy" style="background-color:red;margin-left:15px"  value="' + copycode + '" title="Delete Copy Book">Delete</button>'; 

                                html2 += '</div>';
                                html2 += '</div>';
                                html2 += '</div>';

                            });
                            html2 += '</div>';
                            $('#qrcodes').html(html2);
                        } else {
                            $('#qrcodes').html('<tr><td colspan="3" align="center"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Oops, no data found</td></tr>');
                        }
                        $('#update_form1').attr("hidden", false);
                        $('#statistics').attr("hidden", false);
                        $('#seacrhedbook').attr("hidden", true);
                    },
                    error: function (error) {
                        $('#spinner4_' + data_id).fadeOut('fast');
                        pop_wrong("Something went wrong!");
                    }
                });
            });

            //update 
            $("#update_form1").submit(function (e) {
                e.preventDefault();

                var formData = new FormData(this);
                $('#spinner2').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $('#indicator2').html("Saving...");
                $.ajax({
                    url: "books/book_controller.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: "JSON",
                    success: function (data) {
                        $('#spinner2').fadeOut('fast');
                        $('#indicator2').html("Save change");
                        if (data.status == 200) {
                            $('#save_book')[0].reset();

                            pop_up_success(data.message);
                        }
                        if (data.status == 401) {
                            pop_wrong(data.message);
                        }
                        if (data.status == 500) {
                            pop_wrong(data.message);
                        }
                    }, error: function () {
                        $('#spinner2').fadeOut('fast');
                        $('#indicator2').html("Save change");
                        pop_wrong("Something went wrong!");

                    }
                });
            });
            $(document).on('click', '.exit', function () {
                $('#update_form1').attr("hidden", true);
                $('#statistics').attr("hidden", true);
                $('#seacrhedbook').attr("hidden", false);
            });


            //save Copyies
            $("#save_copy").submit(function (e) {
                e.preventDefault();
                $("#indicatorsave").html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                var book_codes = $("#book_codes").val();
                var styledText = 'Do you want to Add? <span style="font-size: 20px; color: #6D071A;">' + book_codes + '</span>' + ' copies ';
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
                        // User clicked "Yes," proceed with form submission
                var formData = new FormData(this);
                    $('#spinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
                    $('#indicator').html("Saving...");
                    $.ajax({
                        url: "books/book_controller.php",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(data) {
                            $("#indicatorsave").fadeOut('fast');
                            if (data.status === "200") {
                                
                                $('#save_copy')[0].reset();
                                pop_up_success(data.message);
                                afterdelete();
                            } else if (data.status === "500") {
                                pop_wrong(data.message);
                            }
                        },
                        error: function() {
                            $('#spinner').fadeOut('fast');
                            $('#indicator').html("Save");
                            pop_wrong("Something went wrong!");
                        }
                    });
                    }
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
                title: 'info',
                message: feedback,
                position: 'topCenter'
            });
        }

//     $(document).on('change', '.custom-switch-input', function() {
//   var checkboxValue = $(this).val();
//   Swal.fire({
//     title: "Are you sure?",
//     text: "You want to remove it",
//     icon: "question",
//     showCancelButton: true,
//     confirmButtonText: "Yes",
//     cancelButtonText: "No"
//   }).then((result) => {
//     if (result.isConfirmed) {
        
//         $.ajax({
//         url: "books/delete_book.php",
//         type: "POST",
//         data: {id:checkboxValue},
//         dataType: "json",
//         success: function(data) {
          
//           if (data.status === "200") {
//             pop_up_success(data.message);
//             getData();
//           } 
//           if (data.status === "500") {
//             pop_wrong(data.message);
//           }
//           if (data.status === "404") {
//             pop_wrong(data.message);
//               var checkbox = $('input[name="changestatus"]');
//               checkbox.prop('checked', true);
//           }
//         },
//         error: function() {
//           $('#spinner').fadeOut('fast');
//           $('#indicator').html("Save");
//           pop_wrong("Something went wrong!");
//         }
//       });  
//     }
//   });
//   });
  
  function getData(){
      var keyword=$("#input").val();
                var formData = {
                    keyword: keyword,
                    action: 'search'
                }
                $('#update_form1').attr("hidden", true);
                $('#statistics').attr("hidden", true);
                $('#seacrhedbook').attr("hidden", false);
                $('#spinner7').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
                $.ajax({
                    url: "books/book_controller.php",
                    type: "POST",
                    data: formData,
                    dataType: "JSON",
                    success: function (data) {
                        $('#spinner7').html("<i class='fas fa-search'></i>")
                        if (data.length > 0) {
                            var i = 1;
                            var html = '';
                            var html = '<div class="row">';
                            data.forEach(function (value) {
                                var images = value.image;
                                var titles = value.title;
                                var departments = value.book_dep_name;
                                var id = value.book_id;
                                var author = value.author;
                                var book_code = value.book_code;
                                var second_author=value.secondauthor;
                                html += '<div class="col-12 col-md-6 col-lg-3" >';
                                html += '<div class="card card-primary"  >';
                                html += '<div data-id=' + id + ' class="edit bg-light" style="cursor: pointer;" class="card-body">';
                                html += '<p><img src="/librarian/books/' + images + '" alt="Book Image" ">';
                                html += '<span> <b>' + 'Title : </b>' + titles + '</span>';
                                html +='<br>';
                                html += '<span><b>' + 'Program : </b>' + departments + '</span>';
                                html +='<br>';
                                html += '<span> <b>' + 'Author : </b>' + author + '</span>';
                                html +='<br>';
                                html += '<span> <b>' + 'Second Author : </b>' + second_author + '</span>';
                                html+='</div>';
                                html +='<div class="card-footer" style="background:#7c56bd;">'
                                html +='<div class="row">';
                                html +='<div class="col-12 col-lg-6 col-sm-12 col-md-6">';
                                html +='<i class="fa-solid fa-trash" style="color:#fff;font-size:22px;cursor: pointer;" value="' + id + '" title="Delete Book"></i>'; 
                                html +='<span id="Fspinner"></span>';
                                html +='</div>';
                                html +='<div class="col-12 col-lg-6 col-sm-12 col-md-6">';
                                html +='<i class="fa-solid fa-pen-to-square"  style="color:#fff;font-size:22px;cursor: pointer;" value="' + id + '" title="Edit Book"></i>';
                                html +='<span id="Dspinner"></span>';
                                html +='</div>';
                                html +='</div>';
                                html += '</div>';
                                html += '</div>';
                                html += '</div>';
                            });
                            html += '</div>';

                            $('#seacrhedbook').html(html);
                            $('#card_data').css({
                                display: 'block'
                            });
                        } else {
                            $('#spinner7').html('<i class="fa fa-exclamation-circle" aria-hidden="true"></i> oops, no data found');
                            $('#card_data').css({
                                display: 'none'
                            })
                        }
                    }, error: function () {
                        $('#spinner').html("<i class='fas fa-search'></i>")
                        pop_wrong("Something went wrong!");
                    }
                });
            
  }
</script>

<script>
    $(document).ready(function() {
  // Attach a click event handler to the icon with the specified class
   $(document).on('click', '.fa-trash', function() {
        $('#Fspinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
          var checkboxValue = $(this).attr('value');
   Swal.fire({
    title: "Are you sure?",
    text: "You want to remove it",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Yes",
    cancelButtonText: "No"
  }).then((result) => {
       $('#Fspinner').fadeOut('fast');
    if (result.isConfirmed) {
        
        $.ajax({
        url: "books/delete_book.php",
        type: "POST",
        data: {id:checkboxValue},
        dataType: "json",
        success: function(data) {
         
          if (data.status === "200") {
               
            pop_up_success(data.message);
            getData();
          } 
          if (data.status === "500") {
            pop_wrong(data.message);
          }
          if (data.status === "404") {
            pop_wrong(data.message);
              var checkbox = $('input[name="changestatus"]');
              checkbox.prop('checked', true);
          }
        },
        error: function() {
          $('#spinner').fadeOut('fast');
          $('#indicator').html("Save");
          pop_wrong("Something went wrong!");
        }
      });  
    }
  });
    
  
  });
  
   $(document).on('click', '.fa-pen-to-square', function() {
       $('#Dspinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
     var checkboxValue = $(this).attr('value');
     var formData={
       book_id: checkboxValue,
       action:"getData"
     };
         $.ajax({
        url: "books/edit_book.php",
        type: "POST",
        data: formData,
        dataType: "json",
        success: function(data) {
            $('#Dspinner').fadeOut('fast');
            $("#e_bookId").val(data.book_id);
            $("#e_title").val(data.title);
            $("#e_class").val(data.book_class);
            $("#e_isbn").val(data.isbn);
            $("#e_price").val(data.price);
            $("#e_other_info").val(data.other_info);
          var e_section= data.section;
          var selectElement = $("#e_section");
          selectElement.val(e_section);
          selectElement.prepend(selectElement.find("option[value='" + e_section + "']"));
            
           var e_material_type= data.material_type;
           var selectElement = $("#e_material_type");
           selectElement.val(e_material_type);
           selectElement.prepend(selectElement.find("option[value='" + e_material_type + "']"));
           
           $("#e_author1").val(data.author);
           $("#e_author2").val(data.secondauthor);
           $("#e_pubisher1").val(data.publisher);
           $("#e_pubisher2").val(data.secondpublisher);
            
            var e_dep= data.department;
           var selectElement = $("#e_dep");
           selectElement.val(e_dep);
           selectElement.prepend(selectElement.find("option[value='" + e_dep + "']"));
           
           $("#e_date_publish").val(data.publication_date);
           $("#e_vers_publish").val(data.version);
           $("#e_lang_publish").val(data.language_publication);
           $("#e_org_lang").val(data.orginal_language);
           
           var e_status= data.status;
           var selectElement = $("#e_status");
           selectElement.val(e_status);
           selectElement.prepend(selectElement.find("option[value='" + e_status + "']"));
           
           var e_lease= data.lease;
           var selectElement = $("#e_lease");
           selectElement.val(e_lease);
           selectElement.prepend(selectElement.find("option[value='" + e_lease + "']"));
           
            $("#viewModal").modal('show');
        },
        error: function() {
          $('#spinner').fadeOut('fast');
          $('#indicator').html("Save");
          pop_wrong("Something went wrong!");
        }
      });  
          
   });
   $("#editBook").submit(function(e){
       e.preventDefault();
       var formData = new FormData(this);
       $('#Espinner').html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $.ajax({
                url: "books/edit_book.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType:"JSON",
                success: function(data){
                    $('#Espinner').fadeOut('fast');
                    if(data.status==200){
                        $("#viewModal").modal('hide');
                        pop_up_success(data.message);
                        
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
   
   $(document).on('click', '.ol_code', function() {
       var code=$(this).val();
       $("#old_id_copy").val(code);
    $("#oldcode").modal('show');   
   })
   $("#editBookOld").submit(function(e){
       e.preventDefault();
     $("#EspinnerOld").html("<img src='../../img/ajax_loader.gif' width='15'>").fadeIn('fast');
     var sys_id=$("#old_id_copy").val();
     var old_code=$("#old_code_book").val();
     var formData={
         new_code:sys_id,
         old_code:old_code
     };
      $.ajax({
                url: "books/add_old_code.php",
                type: "POST",
                data: formData,
                dataType:"JSON",
                success: function(data){
                    $("#EspinnerOld").fadeOut('fast');
                  if(data.status==200){
                      $("#oldcode").modal('hide');
                        pop_up_success(data.message);
                      $('#card_data').css({
                                display: 'none'
                            });   
                            $("#editBookOld")[0].reset();
                    }
                    if(data.status==500){
                        pop_wrong(data.message);  
                    } 
                    if(data.status==400){
                        pop_wrong(data.message);  
                    }
                    
                },error: function(){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Save");
                    pop_wrong("Something went wrong!");
                    
                }
             });
   });
   
     $(document).on('click', '.dete_copy', function() {
       var code=$(this).val();

   Swal.fire({
    title: "Are you sure?",
    text: "You want to remove it",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Yes",
    cancelButtonText: "No"
  }).then((result) => {
      
    if (result.isConfirmed) {
        
        $.ajax({
        url: "books/delete_copy.php",
        type: "POST",
        data: {id:code},
        dataType: "json",
        success: function(data) {
          if (data.status === "200") {
               
            pop_up_success(data.message);
          afterdelete();
          } 
          if (data.status === "500") {
            pop_wrong(data.message);
          }
          if (data.status === "400") {
            pop_wrong(data.message);
             
          }
        },
        error: function() {
          $('#spinner').fadeOut('fast');
          $('#indicator').html("Save");
          pop_wrong("Something went wrong!");
        }
      });  
    }
  });
    
  
  
       
      
   })
});
function afterdelete(){
    
                var data_id = $("#input2").val();
                var getData = {
                    id: data_id,
                    action: 'view'
                };
                $("#input2").val(data_id);
                $('#spinnerE').html("<img src='/img/ajax_loader.gif' width='24'>").fadeIn('fast');
                $.ajax({
                    type: "POST",
                    url: "books/book_controller.php",
                    data: getData,
                    dataType: "json",
                    success: function (data) {
                        $('#spinnerE').fadeOut('fast');
                        $('#spinner7').html("<i class='fas fa-search'></i>")
                        $("#e_id").val(data_id);
                        $("#ac_name").html(data[0].book_code);
                        $("#titlebk").html(data[0].title);

                        $("#b_title").val(data[0].title);
                        $("#p_type1").val(data[0].department);
                        $("#bsecondauthor").val(data[0].secondauthor);
                        $("#author1").val(data[0].author);
                        $("#publisher1").val(data[0].publisher);
                        $("#secondpublisher").val(data[0].secondpublisher);
                        $("#isbn1").val(data[0].isbn);
                        $("#b_location1").val(data[0].location);
                        $('#bprice').val(data[0].price);
                        $('#bother_info').val(data[0].other_info);
                        $('#bnote_on_contents').val(data[0].note_on_contents);
                        $('#bSummary').val(data[0].Summary);
                        $('#bdate_of_publication').val(data[0].date_of_publication);
                        $('#bversion').val(data[0].version);
                        $('#blanguage_publication').val(data[0].language_publication);
                        $('#borginal_language').val(data[0].orginal_language);
                        $('#bassociatedURL').val(data[0].associatedURL);
                        $('#bstatus').val(data[0].status);
                        $('#blease').val(data[0].lease);
                        $('#bsection').val(data[0].section);
                        $("#b_tp").val(data[0].book_id);
                        $("#bookname").html('<img src="/librarian/books/' + data[0].image + '" alt="Book cover" style="width:150px;height:150px;">');
                        $("#BookTitle").html('<b>Title </b>:' + data[0].title);
                        $("#BookDep").html('<b>Department :</b>' + data[0].dept_full_name);
                        var html3 = '';
                        var cover = data[0].image;
                        html3 += ' <div class="image-container">';
                        html3 += '<img src="/librarian/books/' + cover + '" alt="Book cover">';
                        html3 += '</div>';

                        $("#cover").html(html3);

                        $('#total_copies').html(data[1].total_copies);
                        $('#lost_copies').html(data[2].lost_copies);
                        $('#borrowed_copies').html(data[3].borrowed_copies);
                        $('#available_copies').html(data[4].available_copies);
                        var html2 = '';
                        var html2 = '<div class="row">';
                        if (data[1].total_copies>0) {
                            data[5].forEach(function (value) {
                                var qrcode = value.qr_code_file;
                                var copycode = value.book_code_number;
                                var 	old_copy_code=value.old_copy_code;
                               if(data[1].total_copies==1 ){
                                 html2 += '<div  class="col-12 col-md-6 col-lg-10">';   
                                }
                                else if( data[1].total_copies==2){
                                    html2 += '<div  class="col-12 col-md-6 col-lg-6">';      
                                }
                                else{
                                    html2 += '<div  class="col-12 col-md-6 col-lg-3">';
                                }
                                
                                html2 += '<div class="card card-primary">';
                                html2 += '<div class="card-header">';
                                html2 += '<h4>' + copycode + '[ ' + data[0].title + ' ]' + '</h4>';
                               
                                html2 += '</div>';
                                html2 += '<div class="card-body">';
                               
                                html2 += '<p><img src="/librarian/books/' + data[0].image + '" alt="Book Image" style="width:220px;height:115px;"><img src="/librarian/books/' + qrcode + '" alt="Book Image" style="width:45px;height:45px;margin-left:50px;margin-top:-60px"></p>';
                                 if(old_copy_code){
                                  
                                }
                                else{
                                 html2 +='<button class="btn btn-primary ol_code"  value="' + copycode + '" title="Add Old Code">+ </button>';     
                                }
                               html2 +='<button class="btn btn-sm dete_copy" style="background-color:red;margin-left:15px"  value="' + copycode + '" title="Delete Copy Book"><span id="spanCopy"></span>Delete</button>'; 

                                html2 += '</div>';
                                html2 += '</div>';
                                html2 += '</div>';

                            });
                            html2 += '</div>';
                            $('#qrcodes').html(html2);
                        } else {
                            $('#qrcodes').html('<tr><td colspan="3" align="center"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Oops, no data found</td></tr>');
                        }
                        $('#update_form1').attr("hidden", false);
                        $('#statistics').attr("hidden", false);
                        $('#seacrhedbook').attr("hidden", true);
                    },
                    error: function (error) {
                        $('#spinner4_' + data_id).fadeOut('fast');
                        pop_wrong("Something went wrong!");
                    }
                });
            
}
</script>
 </div>