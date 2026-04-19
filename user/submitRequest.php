<?php
define('TITLE', 'Submit Request');
define('PAGE', 'submitRequest');
include('includes/header.php'); 
include('../dbConnection.php');
include('../emailConfig.php');
session_start();

// ─── Login Check ───────────────────────────────────────────────────────────────
if(isset($_SESSION['is_login'])){
    $rEmail = $_SESSION['rEmail'];

    // Fetch the logged-in user's name to pre-fill the form
    $sql    = "SELECT name FROM userlogin_tb WHERE email = '$rEmail'";
    $result = $conn->query($sql);
    if($result->num_rows == 1){
        $row   = $result->fetch_assoc();
        $rName = $row['name'];
    }
} else {
    echo "<script> location.href='userLogin.php'; </script>";
    exit();
}

// ─── Handle Form Submission ────────────────────────────────────────────────────
if(isset($_REQUEST['submitrequest'])){

    // Check all required fields are filled
    if(($_REQUEST['requestinfo']    == "") || ($_REQUEST['requestdesc']     == "") ||
       ($_REQUEST['requestername']  == "") || ($_REQUEST['requesteradd1']   == "") ||
       ($_REQUEST['requesteremail'] == "") || ($_REQUEST['requestermobile'] == "") ||
       ($_REQUEST['requestdate']    == "")){
        $msg = '<div class="alert alert-warning col-sm-6 mt-2" role="alert"> Fill All Fields </div>';

    } else {

        // Save form values to variables
        $rinfo   = $_REQUEST['requestinfo'];
        $rdesc   = $_REQUEST['requestdesc'];
        $rname   = $_REQUEST['requestername'];
        $radd1   = $_REQUEST['requesteradd1'];
        $remail  = $_REQUEST['requesteremail'];
        $rmobile = $_REQUEST['requestermobile'];
        $rdate   = $_REQUEST['requestdate'];
        $rpriority = $_REQUEST['priority'];
        $rprice    = $_REQUEST['final_price'];

        // Insert new request into submitrequest_tb
        $sql = "INSERT INTO submitrequest_tb
                    (request_info, request_desc, requester_name, requester_add1,
                     requester_email, requester_mobile, request_date, priority, service_price) 
                VALUES
                    ('$rinfo', '$rdesc', '$rname', '$radd1',
                     '$remail', '$rmobile', '$rdate', '$rpriority', $rprice)";

        if($conn->query($sql) == TRUE){
            // Get the auto-generated request ID of the newly inserted row
            $genid = mysqli_insert_id($conn);



            // Send email notification to user
            sendRequestReceivedNotification($rName, $rEmail, $genid, $rinfo);
            
            $msg = '<div class="alert alert-success col-sm-6 mt-2" role="alert"> Request Submitted Successfully Your Request ID: ' . $genid .' </div>';




            

            // Save the new request ID in session for the success page
            $_SESSION['myid'] = $genid;

            // Redirect to success page
            // echo "<script> location.href='submitRequestSuccess.php'; </script>";
            echo "<script>setTimeout(function(){ location.href='submitRequestSuccess.php'; }, 3000);</script>";


        } else {
            $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert"> Unable to Submit Your Request </div>';
        }
    }
}
?>

<div class="col-sm-9 col-md-10">

    <!-- Welcome Banner -->
    

    <form action="" method="POST" enctype="multipart/form-data">
        <h3 class="title text-center font-weight-bold text-dark mb-4 mt-5"
            style="font-family: Arial, Helvetica, sans-serif;">
            <i class="far fa-share-square"></i> REQUEST FORM
        </h3>

        <!-- ── Service Selection Dropdowns ── -->
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="category"><i class="fas fa-list"></i> Category<span class="text-danger"> *</span></label>
                <select class="form-control" id="category" name="category" required>
                    <option value="">Select Category</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="subcategory"><i class="fas fa-list-alt"></i> Subcategory<span class="text-danger"> *</span></label>
                <select class="form-control" id="subcategory" name="subcategory" required disabled>
                    <option value="">Select Subcategory</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="model"><i class="fas fa-mobile"></i> Model<span class="text-danger"> *</span></label>
                <select class="form-control" id="model" name="model" required disabled>
                    <option value="">Select Model</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="service"><i class="fas fa-cog"></i> Service<span class="text-danger"> *</span></label>
                <select class="form-control" id="service" name="service" required disabled>
                    <option value="">Select Service</option>
                </select>
            </div>
        </div>

        <!-- ── Pricing & Priority ── -->
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="price"><i class="fas fa-rupee-sign"></i> Base Price<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" id="price" name="price" required readonly>
            </div>
            <div class="form-group col-md-4">
                <label for="priority"><i class="fas fa-exclamation-circle"></i> Priority<span class="text-danger"> *</span></label>
                <select class="form-control" id="priority" name="priority" required>
                    <option value="Normal" selected>Normal</option>
                    <option value="High">High (+10%)</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="final_price"><i class="fas fa-money-bill-wave"></i> Final Price<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" id="final_price" name="final_price" required readonly>
            </div>
        </div>

        <!-- Priority surcharge info message (hidden by default) -->
        <div id="priority_info" class="alert alert-info" style="display:none;"></div>

        <!-- ── Request Info (auto-filled by JS) ── -->
        <div class="form-group">
            <label for="inputRequestInfo"><i class="fas fa-info-circle"></i> Request Info<span class="text-danger"> *</span></label>
            <input type="text" class="form-control" id="inputRequestInfo"
                   placeholder="Auto-filled when service type is selected"
                   name="requestinfo" required readonly>
        </div>

        <!-- ── Description ── -->
        <div class="form-group">
            <label for="inputRequestDescription"><i class="fas fa-pen-nib"></i> Description<span class="text-danger"> *</span></label>
            <textarea class="form-control" id="inputRequestDescription"
                      placeholder="Describe your problem in detail" name="requestdesc" rows="3" required></textarea>
        </div>

        <!-- ── Name & Address ── -->
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputName"><i class="fas fa-users"></i> Name<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" id="inputName"
                       name="requestername" value="<?php echo htmlspecialchars($rName); ?>" required readonly>
            </div>
            <div class="form-group col-md-6">
                <label for="inputAddress"><i class="fas fa-map-marker-alt"></i> Address<span class="text-danger"> *</span></label>
                <input type="text" class="form-control" id="inputAddress"
                       placeholder="Enter Address" name="requesteradd1" required>
            </div>
        </div>

        <!-- ── Contact & Date ── -->
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputEmail"><i class="far fa-envelope"></i> Email<span class="text-danger"> *</span></label>
                <input type="email" class="form-control" id="inputEmail"
                       name="requesteremail" value="<?php echo htmlspecialchars($rEmail); ?>" required readonly>
            </div>
            <div class="form-group col-md-3">
                <label for="inputMobile"><i class="fas fa-mobile"></i> Mobile<span class="text-danger"> *</span></label>

                
                <input type="text" class="form-control" id="inputMobile"
                       placeholder="Enter Mobile Number" pattern="^07\d{9}$" title="e.g. 07123456789" name="requestermobile" required>
            </div>
            <div class="form-group col-md-3">
                <label for="inputDate"><i class="fas fa-calendar-alt"></i> Requested Date<span class="text-danger"> *</span></label>
                <input type="date" class="form-control" id="inputDate"
                       name="requestdate" min="<?php echo date('Y-m-d'); ?>" required>
                <!-- Restrict date picker to today only -->
                <script>
                    const todayDate  = new Date().toISOString().split('T')[0];
                    const reqDateInput = document.getElementById('inputDate');
                    reqDateInput.min = todayDate;
                    reqDateInput.max = todayDate;
                </script>
            </div>
            <!-- <p class="text-danger">All fields are required *</p> -->
        </div>

        <!-- ── Buttons ── -->
        <button type="submit" class="btn btn-success mt-2" name="submitrequest">
            <i class="fas fa-paper-plane"></i> Submit
        </button>
        <button type="reset" class="btn btn-secondary mt-2">
            <i class="fas fa-cut"></i> Reset
        </button>

    </form>

    <!-- Success / Warning / Error message -->
    <?php if(isset($msg)) { echo $msg; } ?>

</div>

<?php include('includes/footer.php'); ?>

<!-- ──────────────────────────────────────────────────────────────────────────── -->
<!--  JavaScript: Dynamic Dropdowns + Price Calculation                          -->
<!-- ──────────────────────────────────────────────────────────────────────────── -->
<script>
$(document).ready(function(){

    // ── Helper: Reset a dropdown back to its default empty option ──────────────
    function resetDropdown(selector, label){
        $(selector).html('<option value="">' + label + '</option>').prop('disabled', true);
    }

    // ── 1. Load Categories on page load ───────────────────────────────────────
    $.ajax({
        url: "get_catalog.php",
        method: "GET",
        data: { action: "get_categories" },
        dataType: "json",
        success: function(data){
            $.each(data, function(key, value){
                $('#category').append(
                    '<option value="' + value.category_id + '">' + value.category_name + '</option>'
                );
            });
        },
        error: function(xhr, status, error){
            console.error("Category load error: " + status + " - " + error);
        }
    });

    // ── 2. Category changes → Load Subcategories ──────────────────────────────
    $('#category').change(function(){
        var cat_id = $(this).val();

        // Reset all child dropdowns
        resetDropdown('#subcategory', 'Select Subcategory');
        resetDropdown('#model',       'Select Model');
        resetDropdown('#service',     'Select Service');
        $('#price').val('');
        $('#final_price').val('');
        $('#inputRequestInfo').val('');
        $('#priority_info').hide();

        if(cat_id !== ''){
            $('#subcategory').prop('disabled', false);
            $.ajax({
                url: "get_catalog.php",
                method: "GET",
                data: { action: "get_subcategories", category_id: cat_id },
                dataType: "json",
                success: function(data){
                    $.each(data, function(key, value){
                        $('#subcategory').append(
                            '<option value="' + value.sub_id + '">' + value.sub_name + '</option>'
                        );
                    });
                }
            });
        }
    });

    // ── 3. Subcategory changes → Load Models ──────────────────────────────────
    $('#subcategory').change(function(){
        var sub_id = $(this).val();

        resetDropdown('#model',   'Select Model');
        resetDropdown('#service', 'Select Service');
        $('#price').val('');
        $('#final_price').val('');
        $('#inputRequestInfo').val('');

        if(sub_id !== ''){
            $('#model').prop('disabled', false);
            $.ajax({
                url: "get_catalog.php",
                method: "GET",
                data: { action: "get_models", sub_id: sub_id },
                dataType: "json",
                success: function(data){
                    $.each(data, function(key, value){
                        $('#model').append(
                            '<option value="' + value.model_id + '">' + value.model_name + '</option>'
                        );
                    });
                }
            });
        }
    });

    // ── 4. Model changes → Load Services ─────────────────────────────────────
    $('#model').change(function(){
        var model_id = $(this).val();

        resetDropdown('#service', 'Select Service');
        $('#price').val('');
        $('#final_price').val('');
        $('#inputRequestInfo').val('');

        if(model_id !== ''){
            $('#service').prop('disabled', false);
            $.ajax({
                url: "get_catalog.php",
                method: "GET",
                data: { action: "get_services", model_id: model_id },
                dataType: "json",
                success: function(data){
                    $.each(data, function(key, value){
                        $('#service').append(
                            '<option value="' + value.service_id + '">' + value.service_name + '</option>'
                        );
                    });
                }
            });
        }
    });

    // ── 5. Service changes → Fetch Price ──────────────────────────────────────
    $('#service').change(function(){
        var service_id = $(this).val();

        if(service_id !== ''){
            $.ajax({
                url: "get_catalog.php",
                method: "GET",
                data: { action: "get_price", service_id: service_id },
                dataType: "json",
                success: function(data){
                    $('#price').val(data.service_price);
                    calculateFinalPrice();
                    updateRequestInfo();
                }
            });
        } else {
            $('#price').val('');
            $('#final_price').val('');
            $('#inputRequestInfo').val('');
            $('#priority_info').hide();
        }
    });

    // ── 6. Priority changes → Recalculate ────────────────────────────────────
    $('#priority').change(function(){
        calculateFinalPrice();
        updateRequestInfo();
    });

    // ── Calculate Final Price ─────────────────────────────────────────────────
    function calculateFinalPrice(){
        var basePrice  = parseFloat($('#price').val()) || 0;
        var priority   = $('#priority').val();
        var finalPrice = basePrice;

        if(priority === 'High'){
            finalPrice = basePrice * 1.10;
            var surcharge = (finalPrice - basePrice).toFixed(2);
            $('#priority_info').html(
                '<i class="fas fa-info-circle"></i> <strong>High Priority:</strong> +10% added — ' +
                'Rs. ' + basePrice.toFixed(2) + ' + Rs. ' + surcharge +
                ' = Rs. ' + finalPrice.toFixed(2)
            ).show();
        } else {
            $('#priority_info').hide();
        }

        $('#final_price').val(finalPrice.toFixed(2));
    }

    // ── Build the Request Info string ─────────────────────────────────────────
    function updateRequestInfo(){
        var category    = $('#category option:selected').text();
        var subcategory = $('#subcategory option:selected').text();
        var model       = $('#model option:selected').text();
        var service     = $('#service option:selected').text();
        var priority    = $('#priority').val();
        var finalPrice  = $('#final_price').val();

        if(category && subcategory && model && service &&
           category !== 'Select Category'){
            $('#inputRequestInfo').val(
                category + ' - ' + subcategory + ' - ' + model + ' - ' + service +
                ' [' + priority + ' Priority] (Final Price: Rs. ' + finalPrice + ')'
            );
        }
    }

});
</script>