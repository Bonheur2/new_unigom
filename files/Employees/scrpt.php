<script>
$(document).ready(function() {
    $('#countryy').change(function() {
        var selectedPost = $(this).val();
        console.log(selectedPost);
        if (selectedPost == 160) {
           
            $('#filProvince').show();
        } else {
             $('#filProvince').hide();
             $('#FMydistrict').hide();
             $('#FMysector').hide();
             $('#FMycell').hide();
             $('#FMyvillage').hide();
        }
    });
    
    
    
    $('#province').change(function() {
        var selectedPost = $(this).val();

        if (selectedPost != "") {
            $('#FMydistrict').show();
            $.ajax({
                url: '/files/Employees/getfiles/get_district.php',
                type: 'POST',
                data: { post_id: selectedPost },
                beforeSend: function() {
                    $('#spinner33').html("Loading...");
                },
                success: function(response) {
                    $('#Mydistrict').html(response);
                    $('#spinner33').html("");
                },
                error: function() {
                    $('#spinner33').html("Something went wrong!");
                }
            });
        } else {
            $('#FMydistrict').hide();
            $('#Mydistrict').html('<option value=""></option>');
        }
    });
    
    
    $('#Mydistrict').change(function() {
        var selectedPost = $(this).val();

        if (selectedPost != "") {
            $('#FMysector').show();
            $.ajax({
                url: '/files/Employees/getfiles/get_sector.php',
                type: 'POST',
                data: { post_id: selectedPost },
                beforeSend: function() {
                    $('#spinner33').html("Loading...");
                },
                success: function(response) {
                    $('#Mysector').html(response);
                    $('#spinner33').html("");
                },
                error: function() {
                    $('#spinner33').html("Something went wrong!");
                }
            });
        } else {
            $('#FMysector').hide();
            $('#Mydistrict').html('<option value=""></option>');
        }
    });
    
    
    
    $('#Mysector').change(function() {
        var selectedPost = $(this).val();

        if (selectedPost != "") {
            $('#FMycell').show();
            $.ajax({
                url: '/files/Employees/getfiles/get_cell.php',
                type: 'POST',
                data: { post_id: selectedPost },
                beforeSend: function() {
                    $('#spinner33').html("Loading...");
                },
                success: function(response) {
                    $('#Mycell').html(response);
                    $('#spinner33').html("");
                },
                error: function() {
                    $('#spinner33').html("Something went wrong!");
                }
            });
        } else {
            $('#FMycell').hide();
            $('#Mydistrict').html('<option value=""></option>');
        }
    });
    $('#Mycell').change(function() {
        var selectedPost = $(this).val();

        if (selectedPost != "") {
            $('#FMyvillage').show();
            $.ajax({
                url: '/files/Employees/getfiles/get_village.php',
                type: 'POST',
                data: { post_id: selectedPost },
                beforeSend: function() {
                    $('#spinner33').html("Loading...");
                },
                success: function(response) {
                    $('#Myvillage').html(response);
                    $('#spinner33').html("");
                },
                error: function() {
                    $('#spinner33').html("Something went wrong!");
                }
            });
        } else {
            $('#FMyvillage').hide();
            $('#Mydistrict').html('<option value=""></option>');
        }
    });
    
    
    
        $('#accademic').change(function() {
        var selectedPost = $(this).val();
        console.log("Selected academic type: " + selectedPost);
        if (selectedPost != "") {
            $('#dvfiledType').show();
            $.ajax({
                url: '/files/Employees/getfiles/get_postions.php',
                type: 'POST',
                data: { post_id: selectedPost },
                beforeSend: function() {
                    $('#spinner33').html("Loading...");
                },
                success: function(response) {
                    $('#filedType').html(response);
                    $('#spinner33').html("");
                },
                error: function() {
                    $('#spinner33').html("Something went wrong!");
                }
            });
        } else {
            $('#dvfiledType').hide();
            $('#filedType').html('<option value=""></option>');
        }
    });

});
</script>


<script>
    const probationRange = document.getElementById("probation_range");
    const probationDisplay = document.getElementById("probation_display");

    probationRange.oninput = function () {
        probationDisplay.textContent = this.value;
    }
</script>

