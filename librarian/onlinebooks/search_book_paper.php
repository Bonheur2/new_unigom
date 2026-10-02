<?php


                                      include ('../../meet/con.php');
                                      $action=$_POST['action'];
                                      if($action=="search_book"){
                                        $name=$_POST['book_name'];
                                        
                                        echo "<div class='row'>";
                                           
                                            $query = $conn->prepare(" SELECT books_online.*,tbl_book_program.dept_full_name FROM books_online
                            INNER JOIN tbl_book_program ON books_online.department=tbl_book_program.dept_id WHERE (books_online.title like '" .$name."%' OR books_online.author like'".$name."%'  OR tbl_book_program.dept_full_name like '".$name."%'
                                            OR books_online.isbn like '".$name."%') AND books_online.status=1 AND books_online.type=2 ");
                                            $query->execute();
                                            if ($nrows = $query->rowCount() > 0) {
                                                while ($row = $query->fetch()) {
                                        echo "<div class='col-lg-3 col-md-3 col-sm-12'>"; 
                                        echo "<div class='card '>";
                                        echo '<a href="onlinebooks/files/' . $row['book_file'] . '" target="_blank" style="text-decoration: none;">';
                                        echo "<div class='card-body' style=\"background-image: url('onlinebooks/" . $row['book_image'] . "'); height: 215px; width: 167px; overflow: auto;\">";

                                        echo "<div>";
                                        echo "<p style=\"margin: 0;\"><strong>Title:</strong> " . $row['title'] . "</p>";
                                        echo "<p style=\"margin: 0;\"><strong>Author:</strong> " . $row['author'] . "</p>";
                                        echo "<p style=\"margin: 0;\"><strong>Program:</strong> " . $row['dept_full_name'] . "</p>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo  "</a>";
                                        echo "<div class='card-footer' style='color:move'>";
                                        
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
                                            } else {
                                        echo "<div class='col-12 col-md-4 col-lg-4'></div>";        
                                        echo "<div class='col-12 col-md-12 col-lg-6'>";
                                        echo "<span class='badge badge-secondary'>No paper matched with <span style='margin-left:20px;'> $name</span> </span>";
                                        echo "</div>";
                                                
                                            }
                                        echo "</div>";
                                      }
                                      
                                      
                                      else{
                                          
                                      }





?>