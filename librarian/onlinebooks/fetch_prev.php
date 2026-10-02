<?php
include('../../meet/con.php');

// Retrieve the current page number from the AJAX request
$page = $_GET['page'];

// Calculate the offset to determine the starting row of the current page
$offset = ($page - 1) * 4; // Assuming you want to display 3 cards per page

// Fetch the cards for the current page
$query = $conn->prepare("SELECT books_online.*,tbl_department.dept_full_name FROM books_online
                            INNER JOIN tbl_department ON books_online.department=tbl_department.dept_id WHERE books_online.status = 1  AND books_online.type = 1   LIMIT $offset, 4");
$query->execute();

// Build the HTML content for the cards
$html = "";



if ($query->rowCount() > 0) {
    while ($row = $query->fetch()) {
        $html .= "<div class='col-lg-3 col-md-3 col-sm-12'>";
        $html.="<div class='card'>";
        $html .= "<div class='card-body'>";
        $html .= "<a href='onlinebooks/files/{$row['book_file']}' target='_blank'><img src='onlinebooks/files/{$row['book_image']}' alt='avat'></a>";
        $html .= "<div class='card-footer' style='color:move'>";
        $html.= "<div class='row'>";
        $html .= "<div class='col-lg-12' style='font-size:10px'>Title: " . $row['title'] . "</div>";
        $html.="<div class='col-lg-12' style='font-size:10px'>Department: "  .$row['dept_full_name']. "</div>";
        $html.= "</div>";
        $html .= "<div class='dropdown d-inline mr-2'>";
        $html .= "<button class='btn btn-primary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Actions</button>";
        $html .= "<div class='dropdown-menu'>";
        $html .= "<a class='dropdown-item' href='' id='edit_book' value='" . $row['book_id'] . "'>Edit</a>";
        $html .= "<a class='dropdown-item' href='' id='publish_book' value='" . $row['book_id'] . "'>Publish</a>";
        $html .= "<a class='dropdown-item' href='' id='delete_book' value='" . $row['book_id'] . "'>Delete</a>";
        $html .= "</div>";
        $html .= "</div>";
        $html .= "</div>";
        $html .= "</div>";
        $html .= "</div>";
        $html .= "</div>";
    }
    
} 
echo $html;
?>
