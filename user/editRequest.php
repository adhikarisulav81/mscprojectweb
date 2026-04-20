<?php
define('TITLE', 'Edit Request');
define('PAGE', 'submitRequest');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();

if($_SESSION['is_login']){
 $rEmail = $_SESSION['rEmail'];
} else {
 echo "<script> location.href='userLogin.php'; </script>";
 exit(); //exit() stops the rest of the code from running after the redirect. Without this, PHP would keep executing even after the redirect script is sent.
}

// Get request ID
if(!isset($_GET['id'])){
  //If no id is found in the URL, shows an alert and redirects back to the requests page
    echo "<script> alert('Invalid Request'); location.href='myRequests.php'; </script>";
    exit(); 
}else{
  //If found, saves the ID into $request_id
  $request_id = $_GET['id'];
}


// Searches the database for a request that matches both the ID from the URL and the logged-in user's email
$verify_sql = "SELECT * FROM submitrequest_tb WHERE request_id = '$request_id' AND requester_email = '$rEmail'";
$verify_result = $conn->query($verify_sql);

//If no match, it means either the request doesn't exist or someone is trying to edit someone else's request
if($verify_result->num_rows != 1){

  //Shows an alert and redirects.
    echo "<script> alert('Request not found or access denied'); location.href='myRequests.php'; </script>";
    exit();
}

// Check if request is already assigned and exists in assignwork_tb
$assigned_check = "SELECT * FROM assignwork_tb WHERE request_id = '$request_id'";
$assigned_result = $conn->query($assigned_check);
if($assigned_result->num_rows > 0){
  // If it does (num_rows > 0), it means a technician has already been assigned so editing is not allowed
    echo "<script> alert('The request is already assigned. Assigned request is unable to edit.'); location.href='myRequests.php'; </script>";
    exit();
}

// We already ran the verify query above and know it found exactly 1 row. This line fetches that row as an array so we can pre-fill the form with existing values
$request_data = $verify_result->fetch_assoc();

// Handle form submission
if(isset($_POST['updaterequest'])){
    // Checking for Empty Fields
    if(($_REQUEST['requestinfo'] == "") || ($_REQUEST['requestdesc'] == "") || ($_REQUEST['requestername'] == "") || 
    ($_REQUEST['requesteradd1'] == "") || ($_REQUEST['requesteremail'] == "") || 
    ($_REQUEST['requestermobile'] == "") || ($_REQUEST['requestdate'] == "")){
        $msg = '<div class="alert alert-warning col-sm-6 mt-2" role="alert"> Fill All Fields </div>';
    } else {
       
        $rinfo = $_REQUEST['requestinfo'];
        $rdesc = $_REQUEST['requestdesc'];
        $rname = $_REQUEST['requestername'];
        $radd1 = $_REQUEST['requesteradd1'];
        
        $remail = $_REQUEST['requesteremail'];
        $rmobile = $_REQUEST['requestermobile'];
        $rdate = $_REQUEST['requestdate'];
        $rpriority = $_REQUEST['priority'];

        $sql = "UPDATE submitrequest_tb SET 
                request_info = '$rinfo',
                request_desc = '$rdesc',
                requester_name = '$rname',
                requester_add1 = '$radd1',
                
                requester_email = '$remail',
                requester_mobile = '$rmobile',
                request_date = '$rdate',
                priority = '$rpriority'
                WHERE request_id = '$request_id'";
        
        if($conn->query($sql) == TRUE){
            echo "<script> alert('Request Updated Successfully'); location.href='myRequests.php'; </script>";
            exit();
        } else {
            $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert"> Unable to Update Request </div>';
        }
    }
}

// Extract category, subcategory, model, service from request_info for pre-selection
// This is a simplified approach - you might need to adjust based on your data structure
?>

<div class="col-sm-9 col-md-10">
  <form class="" action="" method="POST">
  <h4 class='title font-weight-bold text-center text-white bg-dark mb-3 mt-4' style='padding: 7px; border-radius: 5px;'>
 EDIT REQUEST   
 </h4> 

    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> You can only edit requests that have not been assigned to a technician.
    </div>

    <div class="form-row">
      <div class="form-group col-md-3">
        <label for="category" class="font-weight-bold"><i class="fas fa-folder"></i> Category<span class="text-danger"> *</span></label>
        <select class="form-control" id="category" name="category" required>
          <option value="">Select Category</option>
        </select>
      </div>
      <div class="form-group col-md-3">
        <label for="subcategory" class="font-weight-bold"><i class="fas fa-list-alt"></i> Subcategory<span class="text-danger"> *</span></label>
        <!-- This dropdown is disabled by default. It only becomes active after the user picks a category. Same applies to Model and Service.-->
        <select class="form-control" id="subcategory" name="subcategory" required disabled>
          <option value="">Select Subcategory</option>
        </select>
      </div>
      <div class="form-group col-md-3">
        <label for="model" class="font-weight-bold"><i class="fas fa-mobile"></i> Model<span class="text-danger"> *</span></label>
        <select class="form-control" id="model" name="model" required disabled>
          <option value="">Select Model</option>
        </select>
      </div>
      <div class="form-group col-md-3">
        <label for="service" class="font-weight-bold"><i class="fas fa-cog"></i> Service<span class="text-danger"> *</span></label>
        <select class="form-control" id="service" name="service" required disabled>
          <option value="">Select Service</option>
        </select>
      </div>
    </div>
    
    <div class="form-row">
      <div class="form-group col-md-4">
        <label for="price" class="font-weight-bold"><i class="fas fa-rupee-sign"></i> Base Price<span class="text-danger"> *</span></label>
        <input type="text" class="form-control" id="price" name="price" readonly>
      </div>
      <div class="form-group col-md-4">
        <label for="priority" class="font-weight-bold"><i class="fas fa-exclamation-circle"></i> Priority<span class="text-danger"> *</span></label>
        <select class="form-control" id="priority" name="priority" required>

        <!-- Pre-selects whichever priority was saved in the database. The ? 'selected' : '' adds the word selected to the matching option, which makes the browser show it as chosen.-->
          <option value="Normal" <?php echo ($request_data['priority'] == 'Normal') ? 'selected' : ''; ?>>Normal</option>
          <option value="High" <?php echo ($request_data['priority'] == 'High') ? 'selected' : ''; ?>>High (+10%)</option>
        </select>
      </div>
      <div class="form-group col-md-4">
        <label for="final_price" class="font-weight-bold"><i class="fas fa-money-bill-wave"></i> Final Price<span class="text-danger"> *</span></label>
        <!-- Read-only field automatically calculated by JavaScript (base price + priority surcharge).-->
        <input type="text" class="form-control" id="final_price" name="final_price" readonly>
      </div>
    </div>
    <div id="priority_info" class="alert alert-info" style="display:none;"></div>

    <div class="form-group">
      <label for="inputRequestInfo" class="font-weight-bold"><i class="fas fa-info-circle"></i> Request Info<span class="text-danger"> *</span></label>
      <input type="text" class="form-control" id="inputRequestInfo" placeholder="Request Info" name="requestinfo" value="<?php echo $request_data['request_info']; ?>" readonly>  <!-- This fills the input with the existing saved value from the database.-->
    </div>
    <div class="form-group">
      <label for="inputRequestDescription" class="font-weight-bold"><i class="fas fa-pen-nib"></i> Description<span class="text-danger"> *</span></label>
      <input type="text" class="form-control" id="inputRequestDescription" placeholder="Write Description" name="requestdesc" value="<?php echo $request_data['request_desc']; ?>">
    </div>
    <div class="form-group">
      <label for="inputName" class="font-weight-bold"><i class="fas fa-users"></i> Name<span class="text-danger"> *</span></label>
      <input type="text" class="form-control" id="inputName" placeholder="Enter Your Name" name="requestername" value="<?php echo $request_data['requester_name']; ?>" readonly>
    </div>
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="inputAddress" class="font-weight-bold"><i class="fas fa-map-marker-alt"></i> Address<span class="text-danger"> *</span></label>
        <input type="text" class="form-control" id="inputAddress" placeholder="Enter Address" name="requesteradd1" value="<?php echo $request_data['requester_add1']; ?>">
      </div>
      
    </div>
    
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="inputEmail" class="font-weight-bold"><i class="fas fa-envelope"></i> Email<span class="text-danger"> *</span></label>
        <input type="email" class="form-control" id="inputEmail" placeholder="Enter Email Address" name="requesteremail" value="<?php echo $request_data['requester_email']; ?>" readonly>
      </div>
      <div class="form-group col-md-2">
        <label for="inputMobile" class="font-weight-bold"><i class="fas fa-mobile"></i> Mobile<span class="text-danger"> *</span></label>
        <input type="text" class="form-control" id="inputMobile" placeholder="Enter Mobile Number" name="requestermobile" value="<?php echo $request_data['requester_mobile']; ?>">
      </div>
      <div class="form-group col-md-2">
        <label for="inputDate" class="font-weight-bold"><i class="fas fa-calendar-alt"></i> Requested Date<span class="text-danger"> *</span></label>
        <input type="date" class="form-control" id="inputDate" name="requestdate" value="<?php echo $request_data['request_date']; ?>">
      </div>

    </div>
    <button type="submit" class="btn btn-success mt-2" name="updaterequest"><i class="fas fa-save"></i> Update Request</button>
    <a href="myRequests.php" class="btn btn-secondary mt-2"><i class="fas fa-times"></i> Cancel</a>
  </form>
  <!-- below msg display if required fill missing or form submitted success or failed -->
  <?php if(isset($msg)) {echo $msg; } ?>
  </div>
</div>


<?php
include('includes/footer.php'); 
?>
<script>
$(document).ready(function(){
    // Fetch Categories
    $.ajax({ //$.ajax sends a background request to get_catalog.php without reloading the page

        url: "get_catalog.php",
        method: "GET",
        data: {action: "get_categories"}, //Asks for all categories 

        dataType: "json",
        success: function(data){
          // When the response comes back as JSON, $.each loops through every category and adds it as an option to the #category dropdown
            $.each(data, function(key, value){
                $('#category').append('<option value="'+value.category_id+'">'+value.category_name+'</option>');
            });
        }
    });

    // Fetch Subcategories
    $('#category').change(function(){ //Fires when the user picks a category

        var cat_id = $(this).val(); //$(this).val() gets the selected category's ID

        // Resets all the dropdowns below it back to empty defaults 
        $('#subcategory').html('<option value="">Select Subcategory</option>');
        $('#model').html('<option value="">Select Model</option>');
        $('#service').html('<option value="">Select Service</option>');
        $('#price').val('');
        $('#inputRequestInfo').val('');
        
        // If a real category was chosen it enables the subcategory dropdown and loads its options via Ajax
        if(cat_id != ''){
            $('#subcategory').prop('disabled', false);
            $.ajax({
                url: "get_catalog.php",
                method: "GET",
                data: {action: "get_subcategories", category_id: cat_id},
                dataType: "json",
                success: function(data){
                    $.each(data, function(key, value){
                        $('#subcategory').append('<option value="'+value.sub_id+'">'+value.sub_name+'</option>');
                    });
                }
            });
        } else {
          // If the blank "Select Category" was chosen then it disables all other dropdowns
             $('#subcategory').prop('disabled', true);
             $('#model').prop('disabled', true);
             $('#service').prop('disabled', true);
        }
    });

    // Fetch Models
    $('#subcategory').change(function(){
        var sub_id = $(this).val();
        $('#model').html('<option value="">Select Model</option>');
        $('#service').html('<option value="">Select Service</option>');
        $('#price').val('');
        $('#inputRequestInfo').val('');

        if(sub_id != ''){
            $('#model').prop('disabled', false);
            $.ajax({
                url: "get_catalog.php",
                method: "GET",
                data: {action: "get_models", sub_id: sub_id},
                dataType: "json",
                success: function(data){
                    $.each(data, function(key, value){
                        $('#model').append('<option value="'+value.model_id+'">'+value.model_name+'</option>');
                    });
                }
            });
        } else {
            $('#model').prop('disabled', true);
            $('#service').prop('disabled', true);
        }
    });

     // Fetch Services
    $('#model').change(function(){
        var model_id = $(this).val();
        $('#service').html('<option value="">Select Service</option>');
        $('#price').val('');
        $('#inputRequestInfo').val('');

        if(model_id != ''){
            $('#service').prop('disabled', false);
            $.ajax({
                url: "get_catalog.php",
                method: "GET",
                data: {action: "get_services", model_id: model_id},
                dataType: "json",
                success: function(data){
                    $.each(data, function(key, value){
                        $('#service').append('<option value="'+value.service_id+'">'+value.service_name+'</option>');
                    });
                }
            });
        } else {
             $('#service').prop('disabled', true);
        }
    });

    // Fetch Price and Update Request Info
    $('#service').change(function(){
      // When a service is selected, fetches its price from the database and fills the price field. Then immediately recalculates the final price and updates the request info text.
        var service_id = $(this).val();
        if(service_id != ''){
            var category = $('#category option:selected').text();
            var subcategory = $('#subcategory option:selected').text();
            var model = $('#model option:selected').text();
            var service = $('#service option:selected').text();

            $.ajax({
                url: "get_catalog.php",
                method: "GET",
                data: {action: "get_price", service_id: service_id},
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

    // Priority change handler
    $('#priority').change(function(){
        calculateFinalPrice();
        updateRequestInfo();
    });

    // Calculate final price based on priority
    function calculateFinalPrice(){
      // parseFloat() converts the price text to a decimal number. || 0 means "use 0 if the field is empty"
        var basePrice = parseFloat($('#price').val()) || 0;
        var priority = $('#priority').val();
        var finalPrice = basePrice;
        
        // If priority is High, multiplies price by 1.10 (adds 10%)
        if(priority == 'High'){
            finalPrice = basePrice * 1.10;
            $('#priority_info').html('<i class="fas fa-info-circle"></i> <strong>High Priority:</strong> +10% added to base price (Rs. ' + basePrice.toFixed(2) + ' + Rs. ' + (finalPrice - basePrice).toFixed(2) + ' = Rs. ' + finalPrice.toFixed(2) + ')').show();
        } else {
          // If priority is normal, hide the high priority extra charge (add on 10%) message box 
            $('#priority_info').hide();
        }
        
        $('#final_price').val(finalPrice.toFixed(2)); //toFixed(2) formats the number to 2 decimal places

    }

    // Update request info with all details
    function updateRequestInfo(){
      // Gets the text labels of all selected dropdown options
        var category = $('#category option:selected').text();
        var subcategory = $('#subcategory option:selected').text();
        var model = $('#model option:selected').text();
        var service = $('#service option:selected').text();
        var priority = $('#priority').val();
        var finalPrice = $('#final_price').val();
        
        if(category && subcategory && model && service){
        // Builds a single descriptive string i.e. Request Info
            $('#inputRequestInfo').val(category + ' - ' + subcategory + ' - ' + model + ' - ' + service + ' [' + priority + ' Priority] (Final Price: Rs. ' + finalPrice + ')');
        }
    }

    // Initialize final price calculation on page load
    calculateFinalPrice(); //Called once when the page loads to set up any default price calculations.
});
</script>
