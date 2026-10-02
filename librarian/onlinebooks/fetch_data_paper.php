<?php
include ('../../meet/con.php');
$cardsPerPage = 4;
$currentpage = isset($_GET['page']) ? $_GET['page'] : 1;

// Fetch all data
$query = $conn->prepare("SELECT books_online.*,tbl_department.dept_full_name FROM books_online
                            INNER JOIN tbl_department ON books_online.department=tbl_department.dept_id WHERE books_online.status = 1 AND books_online.type = 2");
$query->execute();
$rows = $query->fetchAll();

$totalCards = count($rows);
$totalPages = ceil($totalCards / $cardsPerPage);

// Determine the range of cards to display based on the current page
$startIndex = ($currentpage - 1) * $cardsPerPage;
$endIndex = $startIndex + $cardsPerPage;
$displayedCards = array_slice($rows, $startIndex, $cardsPerPage);


echo "<div class='tab-pane fade show active' id='home' role='tabpanel' aria-labelledby='home-tab'>";
echo "<div class='row' id='cardContainer2'>";
if (!empty($displayedCards)) {
    foreach ($displayedCards as $row) {
        echo "<div class='col-lg-3 col-md-3 col-sm-12'>";
        echo "<div class='card card-primary'>";
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
        echo "<button class='btn btn-primary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>";
        echo "Actions";
        echo "</button>";
        echo "<div class='dropdown-menu'>";
        echo "<a class='dropdown-item' href='' id='edit_book4' value='".$row['book_id']."'>Edit</a>";
        echo "<a class='dropdown-item' href='' id='publish_book4' value='".$row['book_id']."'>Publish</a>";
        echo "<a class='dropdown-item' href='' id='delete_book4' value='".$row['book_id']."'>Delete</a>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
} else {
     echo  "<div class='col-12 col-md-4 col-lg-4'></div>";        
     echo "<div class='col-12 col-md-12 col-lg-6'>";
     echo "<span class='badge badge-secondary'>Oops! no paper found.</span>";
     echo "</div>";
}

echo "</div>";
echo "<div id='paginationContainer2'>";
echo "<div class='card-body'>";
echo "<nav aria-label='Page navigation example'>";
echo "<ul class='pagination'>";
if ($currentpage >= 1) {
    echo "<li class='page-item'><a class='page-link' href='?page=" . ($currentpage - 1) . "' id='prevLink2'>Previous</a></li>";
}

for ($i = 1; $i <= $totalPages; $i++) {
     echo "<li class='page-item'><a class='page-link disabled' href='javascript:void(0);'>$i</a></li>";
}

if ($currentpage < $totalPages) {
    echo "<li class='page-item'><a class='page-link' href='?page=" . ($currentpage + 1) . "' id='nextLink2'>Next</a></li>";
} else {
    echo "<li class='page-item disabled'><a class='page-link'>Next</a></li>";
}

echo "</ul>";
echo "</nav>";
echo "</div>";
echo "</div>";
?>
<script>
$(document).ready(function () {
    var currentPage = <?php echo $currentpage; ?>;
    var totalPages = <?php echo $totalPages; ?>;

    // Handle next button click event
    $('#nextLink2').on('click', function (e) {
        e.preventDefault(); // Prevent the default behavior

        // Check if the current page is the last page
        if (currentPage >= totalPages) {
            return; // Exit the function if it's the last page
        }

        var nextPage = currentPage + 1;

        // Fetch the next page using AJAX
        $.ajax({
            url: 'onlinebooks/fetch_data3_paper.php',
            type: 'GET',
            data: { page: nextPage },
            success: function (response) {
                // Replace the previous cards with the new cards
                 $('#cardContainer2 .card').slice(0, 4).remove();
                $('#cardContainer2').append(response);

                currentPage = nextPage;
            },
            error: function () {
                console.log('An error occurred while fetching the next page.');
            }
        });
    });

    // Handle previous button click event
    $('#prevLink2').on('click', function (e) {
        e.preventDefault(); // Prevent the default behavior

        // Check if the current page is the first page
        if (currentPage <= 1) {
            return; // Exit the function if it's the first page
        }

        var prevPage = currentPage - 1;

        // Fetch the previous page using AJAX
        $.ajax({
            url: 'onlinebooks/fetch_data3_paper.php',
            type: 'GET',
            data: { page: prevPage },
            success: function (response) {
                // Replace the next cards with the new cards
               $('#cardContainer2 .card').slice(-4).remove();
                $('#cardContainer2').prepend(response);

                currentPage = prevPage;
            },
            error: function () {
                console.log('An error occurred while fetching the previous page.');
            }
        });
    });
});
</script>