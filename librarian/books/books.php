       <html>
	<head>
		<title>Crop Image Before Upload using CropperJS with PHP</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="https://unpkg.com/dropzone/dist/dropzone.css" />
		<link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet"/>
		<script src="https://unpkg.com/dropzone"></script>
		<script src="https://unpkg.com/cropperjs"></script>
		<style>

		.image_area {
		  position: relative;
		}

		img {
		  	display: block;
		  	max-width: 100%;
		}
		.preview {
  			overflow: hidden;
  			width: 160px;
  			height: 160px;
  			margin: 10px;
  			border: 1px solid red;
		}

		.modal-lg{
  			max-width: 1000px !important;
		}

		.overlay {
		  position: absolute;
		  bottom: 10px;
		  left: 0;
		  right: 0;
		  background-color: rgba(255, 255, 255, 0.5);
		  overflow: hidden;
		  height: 0;
		  transition: .5s ease;
		  width: 100%;
		}

		.image_area:hover .overlay {
		  height: 50%;
		  cursor: pointer;
		}

		.text {
		  color: #333;
		  font-size: 20px;
		  position: absolute;
		  top: 50%;
		  left: 50%;
		  -webkit-transform: translate(-50%, -50%);
		  -ms-transform: translate(-50%, -50%);
		  transform: translate(-50%, -50%);
		  text-align: center;
		}
.stu:hover {
    background-color: #6D071A;
    color:white;/* Set the desired background color on hover */
}
.stu2:hover {
  background-color: #6D071A;
    color:white;/* Set the desired background color on hover */   
}
.scrollable-popover {
    max-height: 100px; /* Set the maximum height for the popover content */
    overflow-y: auto; /* Enable vertical scrolling when content exceeds the maximum height */
}
		</style>
	</head>
	<body>
       
        <!-- Start app main Content -->
        <div class="main-content" id="books">
            <section class="section">
                <div class="section-header">
                    <h1>Book record</h1>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="#">Books</a></div>
                        <div class="breadcrumb-item">Book Record</div>
                    </div>
                </div>
             </section>  
                
                
                            <div class="card">
                               <div class="card-body">
                                     <ul class="nav nav-tabs" id="myTab1" role="tablist">
                                        <li class="nav-item"><a class="nav-link active" id="contact-tab1" data-toggle="tab" href="#contact1" role="tab" aria-controls="contact1" aria-selected="false"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;New item</a></li>
                                        <li class="nav-item"><a class="nav-link" id="home-tab1" data-toggle="tab" href="#home1" role="tab" aria-controls="home1" aria-selected="true"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; New Copy</a></li>
                                        <li class="nav-item"><a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Books Block</a></li>
                                        <li class="nav-item"><a class="nav-link" id="home-tab" data-toggle="tab" href="#home3" role="tab" aria-controls="home3" aria-selected="true"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Dissertation</a></li>
                                    </ul>
                                  
                                     <div class="tab-content" id="myTabContent">
                                    
                                    
                                         <!--add book-->
                            <div class="tab-pane fade show active" id="contact1" role="tabpanel" aria-labelledby="contact-tab1">
                            <div class="card">
                            <div class="card-body row">
                                
                                        <form id="save_book" action="save_book" method="POST">
                                            <input type="hidden" name="action" value="register" >
                                            <div class="card-body pb-0 row">
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Department (*)</label>
                                                    <div class="input-group">
                                                   
                                                    <select class="form-control select2" style="width:100%;"name="b_dep" id="p_type" >
                                                         <?php 
                                             $stmt = $conn->prepare("SELECT * FROM  tbl_books_depart WHERE status=1 ORDER BY book_dep_name ASC");
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
                                                 
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Book title (*)</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fa fa-font" aria-hidden="true"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="b_title" id="f_f_name" placeholder="" required>
                                                    </div>
                                                </div>
                                                    
                                                     <div class="form-group col-12 col-sm-4 col-lg-4">
                                                                <label>Book Code (*)</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fa fa-bookmark" aria-hidden="true"></i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" class="form-control" name="bar_code" id="bar_code" placeholder="" >
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-12 col-sm-4 col-lg-4">
                                                                <label>ISBN (*)</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fa fa-barcode" aria-hidden="true"></i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" class="form-control" name="isbn" id="isbn" placeholder="" required>
                                                                </div>
                                                            </div>
                                                             <div class="form-group col-12 col-sm-4 col-lg-4">
                                                                <label>Class(*)</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fa fa-bookmark" aria-hidden="true"></i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" class="form-control" name="class_book" id="class_book" placeholder="" >
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-12 col-sm-4 col-lg-4">
                                                                <label>Author (*)</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text" id="authorsearcher">
                                                                            &nbsp;<i class="fa fa-user-circle" aria-hidden="true"></i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" class="form-control" name="author" id="author" data-toggle="popover" data-placement="bottom">
                                                                </div>
                                                            </div>
                                                            
                                                        <div class="form-group col-12 col-sm-4 col-lg-4"  id="secondaryauthor">
                                                            <label>Secondary Author(s)</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-text" id="authorsearcherSecondary">
                                                                   &nbsp;<i class="fa fa-user-circle"></i>&nbsp;</div>
                                                                </div>
                                                                <input type="text" class="form-control" name="secondauthor" id="secondauthor" data-toggle="popover" data-placement="bottom">
                                                            </div>
                                                        </div>
                                                         <div class="form-group col-12 col-sm-4 col-lg-4" >
                                                                    <label>Select Block</label>
                                                                    <div class="input-group">
                                                                     <select class="form-control select2" style="width:100%;" name="b_locations" id="b_locations" >
                                                                         <?php 
                                                             $stmt = $conn->prepare("SELECT * FROM `books_location`");
                                                                $stmt->execute();
                                                                if($row=$stmt->rowCount()>0){
                                                                    while($row=$stmt->fetch()){
                                                                    ?>
                                                              <option value="<?php echo $row['id']; ?>"><?php echo $row['location_Code']; ?> | <?php echo $row['location_name']; ?></option>
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
                                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                                <label>Publisher (*)</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fa fa-bookmark" aria-hidden="true"></i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" class="form-control" name="publisher" id="publisher" placeholder="" >
                                                                </div>
                                                            </div>
                                                             <div class="form-group col-12 col-sm-4 col-lg-4">
                                                                <label>Secondary Publisher (s)</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fa fa-bookmark" aria-hidden="true"></i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" class="form-control" name="secondpublisher" id="secondpublisher" placeholder="" >
                                                                </div>
                                                            </div>
                                                             
                                                             <div class="form-group col-12 col-sm-4 col-lg-4 d-none">
                                                        <label>Select Book cover</label>  
                                                        <div class="custom-file">
                                                <input type="file" name="upload_image" class="custom-file-input image" id="upload_image" accept="image/jpeg, image/png">
                                                        <label class="custom-file-label" id="selected_book_image">Book Cover</label>
                                                        </div>
                                                    </div>
                                    
                                                 <div class="form-group col-sm-12 col-lg-4 col-md-4"
                                                        id="image_show" style="display:none">
                                                        <div class="custom-file">
                                                            <img src="" id="uploaded_image" name="uploaded_image"
                                                                class="img-responsive img-circle"
                                                                style="width:80px;height:80px;" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Price</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fa fa-usd" aria-hidden="true"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="price" id="price" placeholder="" >
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Other information</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fas fa-info"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="other_info" id="other_info" placeholder="" >
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Notes on contents</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fa fa-file-text" aria-hidden="true"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="note_on_contents" id="note_on_contents" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Summary</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fa fa-file-text" aria-hidden="true"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="Summary" id="Summary" placeholder="" >
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Date of publication</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="date" class="form-control" name="date_of_publication" id="date_of_publication" placeholder="" >
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Version (or date of version)</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fa fa-address-book" aria-hidden="true"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="input" class="form-control" name="version" id="version" placeholder="" >
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Language OF publication</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fa fa-language" aria-hidden="true"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="language_publication" id="language_publication" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Orginal language</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fa fa-language" aria-hidden="true"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="orginal_language" id="orginal_language" placeholder="" >
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Link (electronic resources)</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fa fa-link" aria-hidden="true"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="associatedURL" id="associatedURL" placeholder="associatedURL">
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Status</label>
                                                    <div class="input-group">
                                                         <select class="form-control select2" style="width:100%;"name="status" id="status">
                                                
                                                        <option value="On-site consultation">On-site consultation</option>
                                                        <option value="Deteriorated">Deteriorated</option>
                                                        <option value="Document in good condtion">Document in good condtion</option>
                                                        <option value="in process of import/entry">in process of import/entry</option>
                                                        <option value="Deposit">Deposit</option>
                                                        <option value="Lost">Lost</option>
                                                    </select>
                                                        
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Lease</label>
                                                    <div class="input-group">
                                                        <select class="form-control select2" style="width:100%;" name="lease" id="lease">
                                                        <option value="Library shelves">Library shelves</option>
                                                        <option value="Library store">Library store</option>
                                                    </select>
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Section</label>
                                                    <div class="input-group">
                                                        <select class="form-control select2" style="width:100%;" name="section" id="section">
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
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Material type</label>
                                                    <div class="input-group">
                                                        <select class="form-control select2" style="width:100%;" name="material_type" id="material_type">
                                                        <option value="Books">Books</option>
                                                        <option value="Video tape">Video tape</option>
                                                        <option value="Audio CD">Audio CD</option>
                                                        <option value="CD-ROMs">CD-ROMs</option>
                                                        <option value="Artwork">Artwork</option>
                                                        <option value="Periodic">Periodic</option>
                                                    </select>
                                                    </div>
                                                </div>
                                                
                                                 <div class="form-group col-12 col-sm-4 col-lg-4">
                                                  <label>&nbsp;</label>
                                                <div class="input-group">
                                                    <button type="submit" class="btn btn-primary" style="width:80px;"><span id="spinnersave1"></span>&nbsp;<span id="indicatorsave1">Save</span></button>
                                                </div>
                                            </div>                  
                                                
                                                
                                               
                                                
                                               
                                                
                                                
                                                   
                                                <!--<div class="custom-file">-->
                                                <!-- <input type="text" name="image" id="upload_image1" hidden>-->
                                                <!--<label class="custom-file-label">Choose File</label>-->
                                                <!--</div>-->
                                               
                                              
                                              
                                            <div class="card-body pb-0 row">
                                                
                                                
                                                
                                            </div>
                                       


                                    <div class="form-group col-12 col-sm-2 col-lg-2">
                                        </div>
                                
                            
                                    
                                            <div class="form-group col-12 col-sm-4 col-lg-4">
                                                
                                            </div>
                                            </div>
                                        </form>

                                    </div>
                                
                            </div>
                            </div>
                            <!--Add copy-->
                            <div class="tab-pane fade" id="home1" role="tabpanel" aria-labelledby="home-tab1">
                            <div class="card">
                                    <div class="card-body row">
                                       <form id="save_copy" action="save_copy" method="POST">
                                            <input type="hidden" name="action" value="savecopy">
                                            <div class="card-body pb-0 row">
                                                
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Select Book</label>
                                                    <div class="input-group">
                                                    
                                                      <select class="form-control select2" style="width:100%;"name="b_tp" id="b_tp" >
                                        <?php 
                                             $stmt = $conn->prepare("SELECT * FROM books");
                                                $stmt->execute();
                                                if($row=$stmt->rowCount()>0){
                                                    while($row=$stmt->fetch()){
                                                    ?>
                                              <option value="<?php echo $row['book_id']; ?>"><?php echo $row['book_id']; ?> | <?php echo $row['title']; ?> </option>
                                             <?php
                                                        
                                                }
                                                } else{
                                                  ?>
                                                <option value='-1'>No book found</option>
                                             <?php }
                                            ?>
                                           </select>
                                                    </div>
                                                </div>
                                                
                                               <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Select Block</label>
                                                    <div class="input-group">
                                                     <select class="form-control select2" style="width:100%;" name="b_location" id="b_location" required>
                                                         <?php 
                                             $stmt = $conn->prepare("SELECT * FROM `books_location`");
                                                $stmt->execute();
                                                if($row=$stmt->rowCount()>0){
                                                    while($row=$stmt->fetch()){
                                                    ?>
                                              <option value="<?php echo $row['id']; ?>"><?php echo $row['location_Code']; ?> | <?php echo $row['location_name']; ?></option>
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
                                                
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                                <label>Bar Code (*)</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fa fa-bookmark" aria-hidden="true"></i>&nbsp;
                                                                   </div>
                                                               </div>
                                                            <input type="text" class="form-control" name="bar_code_copy" id="bar_code_copy" placeholder="" >
                                                          </div>
                                                    </div>
                                            <div class="form-group col-12 col-sm-4 col-lg-6">
                                                <div class="input-group">
                                                    <button type="submit" class="btn btn-primary" ><span id="spinnerH"></span>Save</button>
                                                </div>
                                            </div>
                                            </div>
                                        </form>
                                    </div>
                                
                            </div>
                            </div>
                            
                            <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="card">
                                <div class="card-body row">
                                       <form id="save_location" action="save_location" method="POST">
                                            <input type="hidden" name="action" value="bklocation">
                                            <div class="card-body pb-0 row">
                                                
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Block Code</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fa fa-building" aria-hidden="true"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="Building" id="Building" placeholder="" >
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Block Name</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fa fa-location-arrow" aria-hidden="true"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="location_name" id="location_name" placeholder="" >
                                                    </div>
                                                </div>
                                                
                                                
                                            <div class="form-group col-12 col-sm-4 col-lg-4">
                                                <label>&nbsp;</label>
                                                <div class="input-group">
                                                    <button type="submit" class="btn btn-primary" style="width:80px;"><span id="spinner"></span>&nbsp;<span id="indicator">Save</span></button>
                                                </div>
                                            </div>
                                            </div>
                                        </form>


                                    </div>
                                
                            </div>
                            </div>
                            
                            <div class="tab-pane fade" id="home3" role="tabpanel" aria-labelledby="home-tab">
                            <div class="card">
                                <div class="card-body row">
                                       <form id="save_dissertation" action=" " method="POST">
                                            <input type="hidden" name="action" value="save_dist">
                                            <div class="card-body pb-0 row">
                                                
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Department (*)</label>
                                                    <div class="input-group">
                                                   
                                                    <select class="form-control select2" style="width:100%;"name="b_depD" id="b_depD" >
                                                         <?php 
                                             $stmt = $conn->prepare("SELECT * FROM  tbl_books_depart WHERE status=1 ORDER BY book_dep_name ASC");
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
                                                 
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Book title (*)</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fa fa-font" aria-hidden="true"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="b_titleD"  placeholder="" required>
                                                    </div>
                                                </div>
                                                
                                                   <div class="form-group col-12 col-sm-4 col-lg-4">
                                                        <label>Author (*)</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text" id="authorsearcher">
                                                                    &nbsp;<i class="fa fa-user-circle" aria-hidden="true"></i>&nbsp;
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" name="authorD" id="authorD" >
                                                        </div>
                                                    </div>   
                                                
                                                 <div class="form-group col-12 col-sm-4 col-lg-4"  id="secondaryauthor">
                                                        <label>Secondary Author(s)</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text" id="authorsearcherSecondaryD">
                                                               &nbsp;<i class="fa fa-user-circle"></i>&nbsp;</div>
                                                            </div>
                                                            <input type="text" class="form-control" name="secondauthorD" id="secondauthorD">
                                                        </div>
                                                    </div>
                                                    
                                                      <div class="form-group col-12 col-sm-4 col-lg-4" >
                                                                    <label>Select Block</label>
                                                                    <div class="input-group">
                                                                     <select class="form-control select2" style="width:100%;" name="b_locationsD" id="b_locationsD" >
                                                                         <?php 
                                                             $stmt = $conn->prepare("SELECT * FROM `books_location`");
                                                                $stmt->execute();
                                                                if($row=$stmt->rowCount()>0){
                                                                    while($row=$stmt->fetch()){
                                                                    ?>
                                                              <option value="<?php echo $row['id']; ?>"><?php echo $row['location_Code']; ?> | <?php echo $row['location_name']; ?></option>
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
                                                                 <div class="form-group col-12 col-sm-4 col-lg-4">
                                                                <label>Publisher (*)</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">
                                                                            &nbsp;<i class="fa fa-bookmark" aria-hidden="true"></i>&nbsp;
                                                                        </div>
                                                                    </div>
                                                                    <input type="text" class="form-control" name="publisherD" id="publisherD" placeholder="">
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Date of publication</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="date" class="form-control" name="date_of_publicationD" id="date_of_publicationD" placeholder="" >
                                                    </div>
                                                </div>
                                                 <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Language OF publication</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                &nbsp;<i class="fa fa-language" aria-hidden="true"></i>&nbsp;
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" name="language_publicationD" id="language_publicationD" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="form-group col-12 col-sm-4 col-lg-4">
                                                    <label>Section</label>
                                                    <div class="input-group">
                                                        <select class="form-control select2" style="width:100%;" name="sectionD" id="sectionD">
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
                                            <div class="form-group col-12 col-sm-4 col-lg-4">
                                                <label>&nbsp;</label>
                                                <div class="input-group">
                                                    <button type="submit" class="btn btn-primary" style="width:80px;"><span id="spinnersave2"></span>&nbsp;<span id="indicatorsave2">Save</span></button>
                                                </div>
                                            </div>
                                            </div>
                                        </form>


                                    </div>
                                <div class="tarckbarCode"></div>
                            </div>
                            </div>
                            </div>     
                        </div>
                    </div>
          <!--Image preciew-->
            <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
			  	<div class="modal-dialog modal-lg" role="document">
			    	<div class="modal-content">
			      		<div class="modal-header">
			        		<h5 class="modal-title">Crop Image Before Upload</h5>
			        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          			<span aria-hidden="true">×</span>
			        		</button>
			      		</div>
			      		<div class="modal-body">
			        		<div class="img-container">
			            		<div class="row">
			                		<div class="col-md-4">
			                    		<img src="" id="sample_image" />
			                		</div>
			                		<div class="col-md-4">
			                    		<div class="preview"></div>
			                		</div>
			            		</div>
			        		</div>
			      		</div>
			      		<div class="modal-footer">
			      			<button type="button" id="crop" class="btn btn-primary">Crop</button>
			        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
			      		</div>
			    	</div>
			  	</div>
			</div>
                               
<?php include 'bookscripts.php' ?>
        </div>