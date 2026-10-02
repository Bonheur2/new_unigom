<link rel="stylesheet" href="https://unpkg.com/dropzone/dist/dropzone.css" />
<link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet" />
<style>
#paginationContainer {
    max-width: 100%; /* Adjust this value to control the maximum width */
    overflow: auto; /* Hide any overflowing content */
    margin: 0 auto; /* Center the pagination container */
}

#paginationContainer2 {
    max-width: 100%; /* Adjust this value to control the maximum width */
    overflow: auto; /* Hide any overflowing content */
    margin: 0 auto; /* Center the pagination container */
}
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
        margin-left: 100px;
        margin-top: 10px;
        margin-bottom: 10px;
        border: 1px solid red;
    }
     .preview11 {
        overflow: hidden;
        width: 160px;
        height: 160px;
        margin-left: 100px;
        margin-top: 10px;
        margin-bottom: 10px;
        border: 1px solid red;
    }
.preview12 {
        overflow: hidden;
        width: 160px;
        height: 160px;
        margin-left: 100px;
        margin-top: 10px;
        margin-bottom: 10px;
        border: 1px solid red;
    }
    .preview13 {
        overflow: hidden;
        width: 160px;
        height: 160px;
        margin-left: 100px;
        margin-top: 10px;
        margin-bottom: 10px;
        border: 1px solid red;
    }

    .modal-lg {
        max-width: 800px !important;
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
    
</style>
<!-- Start app main Content -->
<div class="main-content" id="books">
    
   <!--start of modal edit for search-->
<div class="modal fade" tabindex="-1" role="dialog" id="exampleModal2">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                         <form action="" id="edit_book_search" method="POST">
                        <div class="modal-header">
                             <h5 class="modal-title">E-paper Edit</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                            <label>Book Title</label>
                                 <input type="text" class="form-control" id="bk_title_search">
                            </div>
                            <div class="form-group">
                                                    <label>Author</label>
                                                    <select name="author3" id="author3"  class="form-control select2" style="width:100%">
                                                        <option value='' disabled selected>--Choose one--</option>
                                                        <?php
                                                            $sql_auth=$conn->prepare("SELECT * FROM authors");
                                                            $sql_auth->execute();
                                                            $i=1;
                                                            while($nat=$sql_auth->fetch()){
                                                        ?>
                                                         <option value="<?php echo $nat['author_name']; ?>"><?php echo $nat['author_name']; ?> </option>
                                                       <?php } ?>
                                                    <option value="0">Others</option>
                                                </select>
                                </div>  
                                <div class="form-group">
                                                    <label>Second Author</label>
                                                    <select name="secondauthor3" id="secondauthor3"  class="form-control select2" style="width:100%">
                                                        <option value='' disabled selected>--Choose one--</option>
                                                        <?php
                                                            $sql_sauth=$conn->prepare("SELECT * FROM  second_authors");
                                                            $sql_sauth->execute();
                                                            $i=1;
                                                            while($nat=$sql_sauth->fetch()){
                                                        ?>
                                                         <option value="<?php echo $nat['names']; ?>"><?php echo $nat['names']; ?> </option>
                                                       <?php } ?>
                                                    <option value="0">Others</option>
                                                </select>
                                         </div>
                                         <div class="form-group">
                                                        <label>publisher</label>
                                                        <input type="text" class="form-control" id="publisher3" name="publisher2">
                                        </div>
                                     <div class="form-group">
                                        <label>Program</label>
                                    <select class="custom-select" id="book_dep_id_search" name="book_dep_id_search">
                               </select>
                            </div>
                            <div class="form-group d-none">
                              <div class="custom-file">
                                  <input type="file" class="custom-file-input" id="image2_search" name="image2_search">
                                    <label class="custom-file-label" id="selected_book_image_edit1_search">change image</label>
                                  </div> 
                             </div>
                        </div>
                        <input type="hidden" id="book_id_serch" name="book_id_serch">
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"><span id="spinner24"></span>Save changes</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
<!--end of modal edit for search-->
<!--start  of edit crop image search-->
    <div class="modal fade" id="modal12" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
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
			                    		<img src="" id="sample_image12" />
			                		</div>
			                		<div class="col-md-4">
			                    		<div class="preview12"></div>
			                		</div>
			            		</div>
			        		</div>
			      		</div>
			      		<div class="modal-footer">
			      			<button type="button" id="crop12" class="btn btn-primary"><span id="spinner27"></span>&nbsp;Crop</button>
			        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
			      		</div>
			    	</div>
			  	</div>
			</div>
    <!--end of edit crop image search-->                      
<!--start edit after inserting book-->
<div class="modal fade" tabindex="-1" role="dialog" id="exampleModal4">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="" id="edit_book_after_insert" method="POST">
                        <div class="modal-header">
                             <h5 class="modal-title">E-paper Edit</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                            <label>Book Title</label>
                                 <input type="text" class="form-control" id="bk_title_edit">
                                     </div>
                             <div class="form-group">
                                                    <label>Author</label>
                                                    <select name="author2" id="author2"  class="form-control select2" style="width:100%">
                                                        <option value='' disabled selected>--Choose one--</option>
                                                        <?php
                                                            $sql_auth=$conn->prepare("SELECT * FROM authors");
                                                            $sql_auth->execute();
                                                            $i=1;
                                                            while($nat=$sql_auth->fetch()){
                                                        ?>
                                                         <option value="<?php echo $nat['author_name']; ?>"><?php echo $nat['author_name']; ?> </option>
                                                       <?php } ?>
                                                    <option value="0">Others</option>
                                                </select>
                                </div>  
                                <div class="form-group">
                                                    <label>Second Author</label>
                                                    <select name="secondauthor2" id="secondauthor2"  class="form-control select2" style="width:100%">
                                                        <option value='' disabled selected>--Choose one--</option>
                                                        <?php
                                                            $sql_sauth=$conn->prepare("SELECT * FROM  second_authors");
                                                            $sql_sauth->execute();
                                                            $i=1;
                                                            while($nat=$sql_sauth->fetch()){
                                                        ?>
                                                         <option value="<?php echo $nat['names']; ?>"><?php echo $nat['names']; ?> </option>
                                                       <?php } ?>
                                                    <option value="0">Others</option>
                                                </select>
                                         </div>
                                         <div class="form-group">
                                                        <label>publisher</label>
                                                        <input type="text" class="form-control" id="publisher2" name="publisher2">
                                        </div>
                                     <div class="form-group">
                                        <label>program</label>
                                    <select class="custom-select" id="book_dep_id_edit" name="book_dep_id_edit">
                               </select>
                            </div>
                            <div class="form-group d-none">
                              <div class="custom-file">
                                  <input type="file" class="custom-file-input" id="image2_insert" name="image2_insert">
                                    <label class="custom-file-label" id="selected_book_image_edit1_insert">change image</label>
                                  </div> 
                             </div>
                        </div>
                        <input type="hidden" id="book_id_edt" name="book_id_edt">
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"><span id="spinner25"></span>Save changes</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
<!--end of modal of edit after inserting-->
 <!--end of edit crop image after any action-->
    <div class="modal fade" id="modal13" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
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
			                    		<img src="" id="sample_image13" />
			                		</div>
			                		<div class="col-md-4">
			                    		<div class="preview13"></div>
			                		</div>
			            		</div>
			        		</div>
			      		</div>
			      		<div class="modal-footer">
			      			<button type="button" id="crop13" class="btn btn-primary"><span id="spinner28"></span>&nbsp;Crop</button>
			        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
			      		</div>
			    	</div>
			  	</div>
			</div>
    <!--end of edit crop image after any action-->
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Registered Online Papers</h4>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item"><a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                    role="tab" aria-controls="home" aria-selected="true">ALL</a></li>
                            <li class="nav-item"><a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile"
                                    role="tab" aria-controls="profile" aria-selected="false">Search Paper</a></li>

                        </ul>
                        
                        <div class="tab-content" id="myTabContent">

                            <?php
                            include ('../../meet/con.php');
                            
                            $cardsPerPage = 4;
                            $currentpage = isset($_GET['page']) ? $_GET['page'] : 1;
                            
                            // Fetch all data
                            $query = $conn->prepare("SELECT books_online.*,tbl_department.dept_full_name FROM books_online
                            INNER JOIN tbl_department ON books_online.department=tbl_department.dept_id WHERE books_online.status = 1 AND books_online.type=2");
                            $query->execute();
                            $rows = $query->fetchAll();
                            
                            $totalCards = count($rows);
                            $totalPages = ceil($totalCards / $cardsPerPage);
                            
                            // Determine the range of cards to display based on the current page
                            $startIndex = ($currentpage - 1) * $cardsPerPage;
                            $endIndex = $startIndex + $cardsPerPage;
                            $displayedCards = array_slice($rows, $startIndex, $cardsPerPage);
                            
                            ?>

                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="row" id="cardContainer">
                                    <?php
                                    if (!empty($displayedCards)) {
                                        foreach ($displayedCards as $row) {
                                    ?>
                                    <div class="col-lg-3 col-md-3 col-sm-12">
                                    <div class="card card-primary">
                                  <a href="onlinebooks/files/<?php echo $row['book_file']; ?>" target="_blank" style="text-decoration: none;">
                                    <div class="card-body" style="background-image: url('<?php echo 'onlinebooks/' . $row['book_image']; ?>'); height: 215px; width: 167px; overflow: auto;">
                                        <div>
                                            <p style="margin: 0;"><strong>Title:</strong> <?php echo $row['title']; ?></p>
                                            <p style="margin: 0;"><strong>Author:</strong> <?php echo $row['author']; ?></p>
                                            <p style="margin: 0;"><strong>Program:</strong> <?php echo $row['dept_full_name']; ?></p>
                                        </div>
                                    </div>
                                </a>
                                <div class="card-footer" style="color: move;">
                                            <div class="dropdown d-inline mr-2">
                                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="" id="edit_book" value="<?php echo $row['book_id']; ?>">Edit</a>
                                                    <a class="dropdown-item" href="" id="publish_book" value="<?php echo $row['book_id']; ?>">Publish</a>
                                                    <a class="dropdown-item" href="" id="delete_book" value="<?php echo $row['book_id']; ?>">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                    <?php
                                        }
                                    } else {
                                        ?>
                                <div class="col-12 col-md-4 col-lg-4"></div>        
                        <div class="col-12 col-md-12 col-lg-6">
                                    <span class="badge badge-secondary">Oops! no paper found.</span>
                                </div>
                                <?php }
                                    ?>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-lg-6"></div>
                                    <div class="col-12 col-lg-6">
                                          <span id="nextspan"></span>
                                    </div>
                                </div>
                                <div id="paginationContainer">
                                  
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-lg-12">
                                          <nav aria-label="Page navigation example">
                                        <ul class="pagination">
                                            <?php
                                        if ($currentpage >= 1) {
                                            echo "<li class='page-item'><a class='page-link' href='?page=" . ($currentpage - 1) . "' id='prevLink'>Previous</a></li>";
                                        }
                                        
                                        for ($i = 1; $i <= $totalPages; $i++) {
                                            echo "<li class='page-item'><a class='page-link disabled' href='javascript:void(0);'>$i</a></li>";
                                        }
                                        
                                        if ($currentpage < $totalPages) {
                                                echo "<li class='page-item'><a class='page-link' href='?page=" . ($currentpage + 1) . "' id='nextLink'>Next</a></li>";
                                            } else {
                                                echo "<li class='page-item disabled'><a class='page-link'>Next</a></li>";
                                            }
                                        ?>
                                        </ul>
                                    </nav>  
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                
                                <form action="" id="edit_book1" method="POST">
                                <div class="modal fade" tabindex="-1" role="dialog" id="exampleModal">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">E-paper Edit</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>paper Title</label>z
                                                        <input type="text" class="form-control" id="bk_title">
                                                    </div>
                                                    <div class="form-group">
                                                    <label>Author</label>
                                                    <select name="author1" id="author1"  class="form-control select2" style="width:100%">
                                                        <option>--Choose one--</option>
                                                        <?php
                                                            $sql_auth=$conn->prepare("SELECT * FROM authors");
                                                            $sql_auth->execute();
                                                            $i=1;
                                                            while($nat=$sql_auth->fetch()){
                                                        ?>
                                                         <option value="<?php echo $nat['author_name']; ?>"><?php echo $nat['author_name']; ?> </option>
                                                       <?php } ?>
                                                </select>
                                         </div>
                                         <div class="form-group">
                                                    <label>Second Author</label>
                                                    <select name="secondauthor1" id="secondauthor1"  class="form-control select2" style="width:100%">
                                                        <option value='' disabled selected>--Choose one--</option>
                                                        <?php
                                                            $sql_sauth=$conn->prepare("SELECT * FROM  second_authors");
                                                            $sql_sauth->execute();
                                                            $i=1;
                                                            while($nat=$sql_sauth->fetch()){
                                                        ?>
                                                         <option value="<?php echo $nat['names']; ?>"><?php echo $nat['names']; ?> </option>
                                                       <?php } ?>
                                                    <option value="0">Others</option>
                                                </select>
                                         </div>
                                         <div class="form-group">
                                                        <label>publisher</label>
                                                        <input type="text" class="form-control" id="publisher1" name="publisher1">
                                                    </div>
                                                    <div class="form-group">
                                                    <label>Program</label>
                                                    <select class="custom-select" id="book_dep_id" name="book_dep_id">
                                                    </select>
                                                </div>
                                                <div class="form-group d-none">
                                                      <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="image2" name="image2">
                                                        <label class="custom-file-label" id="selected_book_image_edit1">change image</label>
                                                    </div> 
                                                   </div>
                                                </div>
                                                <input type="hidden" id="book_id2" name="book_id2">
                                                <div class="modal-footer bg-whitesmoke br">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary"><span id="spinner23"></span>Save changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                           
                        </form>
                            <!--start  of edit crop image-->
    <div class="modal fade" id="modal11" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
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
			                    		<img src="" id="sample_image11" />
			                		</div>
			                		<div class="col-md-4">
			                    		<div class="preview11"></div>
			                		</div>
			            		</div>
			        		</div>
			      		</div>
			      		<div class="modal-footer">
			      			<button type="button" id="crop11" class="btn btn-primary"><span
                                                    id="spinner26"></span>&nbsp;Crop</button>
			        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
			      		</div>
			    	</div>
			  	</div>
			</div>
    <!--end of edit crop image-->
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="row">
                                    <div class="col-12 col-md-12 col-lg-2"></div>
                                        <div class="form-group col-12 col-md-8 col-lg-8">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="search_book"
                                            placeholder="Search Paper by Name,Program ,ISBN,Author">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text" id="spinner22">
                                                                <i class="fas fa-search"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <div id="searched_data" ></div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-12 col-md-4 d-none">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item"><a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                    role="tab" aria-controls="home" aria-selected="true"><i class="fa fa-plus"
                                        aria-hidden="true"></i>&nbsp;New book</a></li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">


                                <!--add book-->
                                <div class="card">
                                    <div class="card-header">
                                        <!--<h4><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;New Book</h4>-->
                                        <div class="card-header-action">
                                            <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info"
                                                href="#"><i class="fas fa-plus"></i></a>
                                        </div>
                                    </div>
                                    <div class="collapse hide" id="mycard-collapse">

                                        <!--Add book-->
                                        <div class="card-body row">
                                            <form id="save_online_book" action="" method="POST">
                                                <div class="card-body pb-0 row">

                                                    <div class="form-group col-sm-12 col-lg-12 col-md-12" id="depa">
                                                        <label>Department (*)</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                            </div>
                                                            <select class="form-control select2" style="width:100%;"
                                                                name="d_name" id="d_name" required>
                                                                <option>-----choose one-----</option>
                                                                <?php 
                                             $stmt = $conn->prepare("SELECT * FROM tbl_department WHERE status=1");
                                                $stmt->execute();
                                                if($row=$stmt->rowCount()>0){
                                                    while($row=$stmt->fetch()){
                                                    ?>
                                                                <option value=" <?php echo $row['dept_id']; ?>">
                                                                    <?php echo $row['dept_full_name']; ?>
                                                                </option>
                                                                <?php
                                                                        
                                                                    }
                                                                }
                                                                else{
                                                                  ?>
                                                                <option value='-1'>No department found</option>
                                                               <?php }
                                                            ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-sm-12 col-lg-12 col-md-12" id="title_book" style="display:none">
                                                        <label>Book title (*)</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    &nbsp;<i class="fa fa-font"
                                                                        aria-hidden="true"></i>&nbsp;
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" name="b_title"
                                                                id="b_title" placeholder="Enter Book Title">
                                                        </div>
                                                    </div>
                                                    <div class="form-group  col-sm-12 col-lg-12 col-md-12" id="bk_file" style="display:none">
                                                        <label>Select Book</label>
                                                        <div class="custom-file">
                                                            <input type="file" name="book_file"
                                                                class="custom-file-input image" id="book_file"
                                                                accept=".pdf" required>
                                                            <label class="custom-file-label"
                                                                id="selected_book">Book</label>
                                                        </div>
                                                    </div>
                                                     <div class="form-group col-sm-12 col-lg-12 col-md-12" id="isbn_book" style="display:none">
                                                    <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    &nbsp;<i class="fa fa-font"
                                                                        aria-hidden="true"></i>&nbsp;
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" name="isbn"
                                                                id="isbn" placeholder="Enter ISBN">
                                                        </div>
                                                      </div>    
                                                    <div class="form-group col-sm-12 col-lg-12 col-md-12" id="bk_image" style="display:none">
                                                        <label>Choose Book Image</label>
                                                        <div class="custom-file">
                                                            <input type="file" name="image_file"
                                                                class="custom-file-input image" id="image_file"
                                                                required>
                                                            <label class="custom-file-label"
                                                                id="selected_book_image">Book image</label>
                                                        </div>
                                                    </div>

                                                    <input type="hidden" name="action" id="action" value="save_book">
                                                    <div class="form-group col-sm-12 col-lg-12 col-md-12"
                                                        id="image_show" style="display:none">
                                                        <div class="custom-file">
                                                            <img src="" id="uploaded_image" name="uploaded_image"
                                                                class="img-responsive img-circle"
                                                                style="width:80px;height:80px;" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-12 col-sm-1 col-lg-1">
                                                        <label>&nbsp;</label>
                                                        <div class="input-group">
                                                            <button type="submit" class="btn btn-primary"><span
                                                                    id="spinner20"></span>&nbsp;<span
                                                                    id="indicatorsave1">Save</span></button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
                                aria-hidden="true">
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
                                            <button type="button" id="crop" class="btn btn-primary"><span
                                                    id="spinner21"></span>&nbsp;Crop</button>
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
   
<?php include 'epaperscript.php' ?>
</div>