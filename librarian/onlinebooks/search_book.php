<?php


                                      include ('../../meet/con.php');
                                      $action=$_POST['action'];
                                      if($action=="search_book"){
                                        $name=$_POST['book_name'];
                                        
                                        echo "<div class='row'>";
                                           
                                            $query = $conn->prepare(" SELECT books_online.*,tbl_book_program.dept_full_name FROM books_online
                            INNER JOIN tbl_book_program ON books_online.department=tbl_book_program.dept_id WHERE (books_online.title like '".$name."%' OR tbl_book_program.dept_full_name like '".$name."%'
                                            OR books_online.isbn like '".$name."%' OR books_online.author like'" .$name."%') AND books_online.status=1  AND books_online.type = 1 ");
                                            $query->execute();
                                            if ($nrows = $query->rowCount() > 0) {
                                                while ($row = $query->fetch()) {
                                         echo "<div class='col-lg-3 col-md-3 col-sm-12'>"; 
                                        echo "<div class='card '>";
                                        echo "<div class='card-body'>";
                                        echo "<a href='onlinebooks/files/{$row['book_file']}' target='_blank'><img src='onlinebooks/files/{$row['book_image']}' alt='avat'></a>";
                                        echo "</div>";
                                        echo "<div class='card-footer' style='color:move'>";
                                        echo  "<div class='row'>";
                                        echo "<div class='col-lg-12' style='font-size:10px'>Title: " . $row['title'] . "</div>";
                                        echo "<div class='col-lg-12' style='font-size:10px'>Department: "  .$row['dept_full_name']. "</div>";
                                        echo "</div>";
                                        echo "<div class='dropdown d-inline mr-2'>";
                                        echo "<button class='btn btn-primary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                       Actions
                                                    </button>";
                                        echo "<div class='dropdown-menu'>";
                                        echo "<a class='dropdown-item' href='' id='edit_book2' value='" . $row['book_id'] . "'>Edit</a>";
                                        echo "<a class='dropdown-item' href='' id='publish_book2' value='" . $row['book_id'] . "'>Publish</a>";
                                        echo "<a class='dropdown-item' href='' id='delete_book2' value='" . $row['book_id'] . "'>Delete</a>";               
                                        echo "</div>";
                                        echo"</div>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</div>";
                                           }
                                            } 
                                            else {
                                        echo "<div class='col-12 col-md-4 col-lg-4'></div>";        
                                        echo "<div class='col-12 col-md-12 col-lg-6'>";
                                        echo "<span class='badge badge-secondary'>No book matched with <span style='margin-left:20px;'> $name</span> </span>";
                                        echo "</div>";
                                        echo "</div>";
                                        }
                                        echo "</div>";
                                          
                                      }





?>