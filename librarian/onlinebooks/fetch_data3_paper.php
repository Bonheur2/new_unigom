<?php
include('../../meet/con.php');

// Retrieve the current page number from the AJAX request
$page = $_GET['page'];

// Calculate the offset to determine the starting row of the current page
$offset = ($page - 1) * 4; // Assuming you want to display 3 cards per page

// Fetch the cards for the current page
$query = $conn->prepare("SELECT books_online.*,tbl_department.dept_full_name FROM books_online
                            INNER JOIN tbl_department ON books_online.department=tbl_department.dept_id WHERE books_online.status = 1 AND books_online.type = 2 LIMIT $offset, 4");
$query->execute();

// Build the HTML content for the cards
$html = "";
if ($query->rowCount() > 0) {
    while ($row = $query->fetch()) {
        $html .= "<div class='col-lg-3 col-md-3 col-sm-12'>";
            $html .= "<div class='card card-primary'>";
            $html .= "<a href='onlinebooks/files/" . $row['book_file'] . "' target='_blank' style='text-decoration: none;'>";
            $html .= "<div class=\"card-body\" style=\"background-image: url('onlinebooks/" . $row['book_image'] . "'); height: 215px; width: 167px; overflow: auto;\">";
            $html .= "<div>";
            $html .= "<p style=\"margin: 0;\"><strong>Title:</strong> " . $row['title'] . "</p>";
            $html .= "<p style=\"margin: 0;\"><strong>Author:</strong> " . $row['author'] . "</p>";
            $html .= "<p style=\"margin: 0;\"><strong>Program:</strong> " . $row['dept_full_name'] . "</p>";
            $html .= "</div>";
            $html .= "</div>";
            $html .= "</a>";
            $html .= "<div class='card-footer' style='color:move'>";
            $html .= "<div class='dropdown d-inline mr-2'>";
            $html .= "<button class='btn btn-primary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Actions</button>";
            $html .= "<div class='dropdown-menu'>";
            $html .= "<a class='dropdown-item' href='' id='edit_book4' value='" . $row['book_id'] . "'>Edit</a>";
            $html .= "<a class='dropdown-item' href='' id='publish_book4' value='" . $row['book_id'] . "'>Publish</a>";
            $html .= "<a class='dropdown-item' href='' id='delete_book4' value='" . $row['book_id'] . "'>Delete</a>";
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
