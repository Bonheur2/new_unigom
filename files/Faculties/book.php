<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h3>Book</h3>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="#">Book</a></div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>New Book</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i
                                        class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="collapse hide" id="mycard-collapse">
                            <div class="card-body row">
                                <form id="save_program" method="POST" enctype="multipart/form-data">
                                    <div class="card-body pb-0 row">
                                        <div class="form-group col-12 col-sm-4 col-lg-4">
                                            <label>Academic Year</label>
                                            <select class="form-control select2" style="width:100%" name="acad_cycle_id"
                                                id="acad_cycle_id">
                                                <option value="0">select Academic Year</option>
                                                <?php
                                                            $sql_camp=$conn->prepare("SELECT * FROM  tbl_acad_cycle ");
                                                            $sql_camp->execute();
                                                            $i=1;
                                                            while($campus=$sql_camp->fetch()){
                                                                ?>
                                                <option value="<?php echo $campus['acad_cycle_id']; ?>">
                                                    <?php echo $campus['acad_year'] ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-12 col-sm-4 col-lg-4">
                                            <label>Document Name</label>
                                            <input type="text" name="document_name" id="document_name"
                                                class="form-control" placeholder="Enter document name" required>
                                        </div>

                                        <span id="spinner_prg"></span>
                                        <div class="form-group col-12 col-sm-4 col-lg-4">
                                            <label>Book (PDF/DOC)</label>
                                            <input type="file" name="graduation_book" id="graduation_book"
                                                class="form-control" accept=".pdf,.doc,.docx" required>
                                        </div>



                                        <div class="form-group col-12 col-sm-1 col-lg-1">
                                            <label>&nbsp;</label>
                                            <div class="input-group">
                                                <button type="submit" class="btn btn-primary"><span
                                                        id="spinner"></span>&nbsp;<span
                                                        id="indicator">Save</span></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-lg-12">
                    <div class="card" id="sample-login">
                        <div class="card-header">
                            <h4>Registered Book</h4>
                        </div>
                        <div class="card-body pb-0">
                            <div class="table-responsive">
                                <table class="table table-striped" id="books_table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Academic Year</th>
                                            <th>Document Name</th>
                                            <th>File Name</th>
                                            <th>Uploaded Date</th>
                                            <th>QR Code</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="books_list">
                                        <tr>
                                            <td colspan="7" class="text-center">Loading...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Edit Book Modal -->
<div class="modal fade" id="editBookModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Book</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="edit_book_form" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="book_id" id="edit_book_id">

                    <div class="form-group">
                        <label>Academic Year</label>
                        <select class="form-control" name="acad_cycle_id" id="edit_acad_cycle_id">
                            <option value="0">Select Academic Year</option>
                            <?php
                                $sql_camp=$conn->prepare("SELECT * FROM tbl_acad_cycle");
                                $sql_camp->execute();
                                while($campus=$sql_camp->fetch()){
                            ?>
                            <option value="<?php echo $campus['acad_cycle_id']; ?>">
                                <?php echo $campus['acad_year'] ?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Document Name</label>
                        <input type="text" name="document_name" id="edit_document_name" class="form-control"
                            placeholder="Enter document name" required>
                    </div>

                    <div class="form-group">
                        <label>Replace Book (Optional - Leave empty to keep current file)</label>
                        <input type="file" name="graduation_book" id="edit_graduation_book" class="form-control"
                            accept=".pdf,.doc,.docx">
                        <small class="text-muted">Current file: <span id="current_file_name"></span></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <span id="edit_spinner"></span>
                        <span id="edit_indicator">Update</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
$(document).ready(function() {
    // Load books on page load
    loadBooks();

    // Handle form submission
    $('#save_program').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append('action', 'save_book');

        // Validate
        if ($('#acad_cycle_id').val() == 0) {
            pop_wrong('Please select an academic year');
            return;
        }

        if ($('#document_name').val().trim() === '') {
            pop_wrong('Please enter a document name');
            return;
        }

        if ($('#graduation_book')[0].files.length === 0) {
            pop_wrong('Please select a file');
            return;
        }

        // Remove client-side file size check - let server handle it

        // Show loading
        $('#spinner').html('<i class="fas fa-spinner fa-spin"></i>');
        $('#indicator').text('Uploading...');
        $('button[type="submit"]').prop('disabled', true);

        $.ajax({
            url: '../files/Faculties/book_controller.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            timeout: 300000, // 5 minutes timeout
            success: function(response) {
                console.log('Success response:', response);
                if (response.status === 'success') {
                    pop_up_success(response.message);
                    $('#save_program')[0].reset();
                    $('#mycard-collapse').collapse('hide');
                    loadBooks();
                } else {
                    pop_wrong(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log('Response Text:', xhr.responseText);
                console.log('Status:', status);
                console.log('Error:', error);

                var errorMsg = 'An error occurred. Please try again.';
                if (status === 'timeout') {
                    errorMsg = 'Upload timeout. File may be too large.';
                } else if (status === 'parsererror') {
                    errorMsg = 'Server response error. Check console for details.';
                    console.error('Parse error. Raw response:', xhr.responseText);
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                pop_wrong(errorMsg);
            },
            complete: function() {
                $('#spinner').html('');
                $('#indicator').text('Save');
                $('button[type="submit"]').prop('disabled', false);
            }
        });
    });

    // Handle edit book form submission
    $('#edit_book_form').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append('action', 'update_book');

        // Validate
        if ($('#edit_acad_cycle_id').val() == 0) {
            pop_wrong('Please select an academic year');
            return;
        }

        if ($('#edit_document_name').val().trim() === '') {
            pop_wrong('Please enter a document name');
            return;
        }

        // Show loading
        $('#edit_spinner').html('<i class="fas fa-spinner fa-spin"></i>');
        $('#edit_indicator').text('Updating...');
        $('#edit_book_form button[type="submit"]').prop('disabled', true);

        $.ajax({
            url: '../files/Faculties/book_controller.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            timeout: 300000,
            success: function(response) {
                if (response.status === 'success') {
                    pop_up_success(response.message);
                    $('#editBookModal').modal('hide');
                    loadBooks();
                } else {
                    pop_wrong(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log('Error:', xhr.responseText);
                pop_wrong('An error occurred. Please try again.');
            },
            complete: function() {
                $('#edit_spinner').html('');
                $('#edit_indicator').text('Update');
                $('#edit_book_form button[type="submit"]').prop('disabled', false);
            }
        });
    });

    // Display file size when selected
    $('#graduation_book').on('change', function() {
        var fileSize = this.files[0].size;
        var fileSizeMB = (fileSize / (1024 * 1024)).toFixed(2);
        console.log('File size: ' + fileSizeMB + ' MB');
    });
});

// Edit book function
function editBook(id, acad_cycle_id, file_name, document_name) {
    $('#edit_book_id').val(id);
    $('#edit_acad_cycle_id').val(acad_cycle_id);
    $('#edit_document_name').val(document_name);
    $('#current_file_name').text(file_name);
    $('#edit_graduation_book').val('');
    $('#editBookModal').modal('show');
}

// Delete book function
function deleteBook(id) {
    swal({
        title: 'Are you sure?',
        text: 'Do you want to delete this book? This action cannot be undone!',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url: '../files/Faculties/book_controller.php',
                type: 'POST',
                data: {
                    action: 'delete_book',
                    book_id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        pop_up_success(response.message);
                        loadBooks();
                    } else {
                        pop_wrong(response.message);
                    }
                },
                error: function() {
                    pop_wrong('An error occurred. Please try again.');
                }
            });
        }
    });
}

// Load books from database
function loadBooks() {
    $.ajax({
        url: '../files/Faculties/book_controller.php?action=fetch_books',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                var html = '';
                if (response.data.length > 0) {
                    $.each(response.data, function(index, book) {
                        html += '<tr>';
                        html += '<td>' + (index + 1) + '</td>';
                        html += '<td>' + (book.acad_year || 'N/A') + '</td>';
                        html += '<td>' + (book.document_name || 'N/A') + '</td>';
                        html += '<td>' + book.file_name + '</td>';
                        html += '<td>' + book.uploaded_at + '</td>';
                        html += '<td><img src="' + book.qr_code +
                            '" width="80" height="80" alt="QR Code"></td>';
                        html += '<td>';
                        html += '<a href="' + book.file_path +
                            '" target="_blank" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i> View</a> ';
                        html += '<a href="' + book.qr_code +
                            '" download class="btn btn-sm btn-info"><i class="fas fa-download"></i> QR</a> ';
                        html += '<button onclick="editBook(' + book.id + ', ' + book.acad_cycle_id +
                            ', \'' + book.file_name + '\', \'' + (book.document_name || '') +
                            '\')" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</button> ';
                        html += '<button onclick="deleteBook(' + book.id +
                            ')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>';
                        html += '</td>';
                        html += '</tr>';
                    });
                } else {
                    html = '<tr><td colspan="7" class="text-center">No books found</td></tr>';
                }
                $('#books_list').html(html);
            }
        }
    });
}

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
</script>