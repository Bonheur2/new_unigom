<?php include'infrom.php'; ?>
<?php include'bar.php'; ?>
<?php  include'subbar.php'; ?>
<style>
    textarea {
        width: 100%;
        height: 150px;
        padding: 10px;
        border-radius: 4px;
        font-size: 16px;
        margin-bottom: 20px;
        border: 1px solid grey;
    }
    textarea:focus{
        border: 1px solid grey;
        box-shadow: none;
    }
</style>
<div class="main-content">
    <section class="section">
       <div class="section-body">
           <div class="row">
                <div class="col-12" style="margin:auto;">
                    <form id="send_suggestion" action="send_suggestion" method="POST">
                        <input type="hidden" name="action" value="send_suggestion">
                        <div class="card">
                            <div class="card-header">
                                <h3>Suggestion Box</h3>
                            </div>
                            <div class="card-body">
                                <p>We value your feedback and suggestions as they play a crucial role in helping us improve our services and better meet your needs. Please use this suggestion box to share your thoughts, ideas, and concerns. Whether it's a recommendation for a new feature, a way to enhance our current offerings, or constructive criticism, we want to hear from you. Your input is invaluable to us and will be carefully reviewed by our team. Rest assured, your identity will remain anonymous, allowing you to share your honest opinions freely. Thank you for taking the time to contribute to our continuous improvement.</p>
                                <div class="form-group">
                                    <textarea name="suggestion" id="suggestion" placeholder="Type your suggestion here..." minlength="20" maxlength="255"></textarea>
                                    <small id="characterCount" class="form-text text-muted">0/255 characters</small>
                                </div>
                                <div style="display:flex;flex-direction:row-reverse;" class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-sm" id="sBtn"><span id="spinner"></span>&nbsp; <span id="indicator">submit</span></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<?php  include'comb/orgin.php'; ?>       
<?php  include'comb/coda.php'; ?>  
        
<!--javascript-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const textarea = document.getElementById('suggestion');
        const characterCount = document.getElementById('characterCount');

        textarea.addEventListener('input', function () {
            const currentLength = textarea.value.length;
            characterCount.textContent = `${currentLength}/255 characters`;
        });
    });
</script>
<script>
    $(document).ready(function(){
        $("#send_suggestion").submit(function(e){
            e.preventDefault();
        
            var formData = new FormData(this)
            $('#spinner').html("<img src='/img/ajax_loader.gif' width='15'>").fadeIn('fast');
            $('#indicator').html("Sending...");
            $("#sBtn").attr('disabled',true);
            $.ajax({
                url: "/files/Suggestions/controller.php", 
                type: "POST",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function(data){
                    $('#spinner').fadeOut('fast');
                    $('#indicator').html("Submit");
                    $("#sBtn").attr('disabled', false);
                    if(data.status==200){
                        $("#send_suggestion")[0].reset();
                        pop_up_success(data.message);
                    }
                },error: function(){
                    $('#spinner').fadeOut('fast');
                    $("#sBtn").attr('disabled',false);
                    $('#indicator').html("Submit");
                    pop_wrong_("Something went wrong!");
                }
            });
        });
    });

   function pop_wrong_(feedback) {
        iziToast.warning({
            title: 'info',
            message: feedback,
            position: 'topCenter'
        });
    }
    
    function pop_up_success(feedback) {
        iziToast.success({
            title: 'Info:',
            message: feedback,
            position: 'topCenter'
        });
    }
</script>