<?php
define('TITLE', 'Check Status');
define('PAGE', 'checkStatus');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();

if($_SESSION['is_login']){
 $rEmail = $_SESSION['rEmail'];
} else {
 echo "<script> location.href='userLogin.php'; </script>";
 exit;
}
?>
  
<div class="col-sm-6">
  <form action="" method="GET" class="d-print-none">
    <h3 class="title text-center font-weight-bold text-dark mb-4 mt-5" style="font-family: Arial, Helvetica, sans-serif;">
    <i class="fas fa-truck-moving"></i> CHECK <span>REQUEST STATUS</span></h3>
    <div class="form-group mr-3">
      <i class="fas fa-portrait"></i>
      <label for="checkid">Enter Request ID: </label>
      <input type="text" class="form-control" pattern="\d*" title="Enter Numbers only" placeholder="Enter Request ID" id="checkid" name="checkid" value="<?php if(isset($_GET['checkid'])) { echo htmlspecialchars($_GET['checkid']); } ?>" required>
    </div>
    <button type="submit" name="search" class="btn btn-info"><i class="fas fa-search"></i> Search</button>
  </form>
  <?php if(isset($msg)) {echo $msg; } ?>
  
  <?php
  if(isset($_GET['checkid']) && $_GET['checkid'] != ''){
    $checkid = intval($_GET['checkid']);
    
    // Joint query to check existence in submitrequest and status in assignwork
    $sql = "SELECT s.*, a.status AS assign_status, a.assign_tech, a.assign_date 
            FROM submitrequest_tb AS s 
            LEFT JOIN assignwork_tb AS a ON s.request_id = a.request_id 
            WHERE s.request_id = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $checkid);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0){
      $row = $result->fetch_assoc();
      
      // Ownership check
      if($row['requester_email'] !== $rEmail){
        echo '<div class="alert alert-danger mt-2" role="alert">
                <i class="fas fa-exclamation-triangle"></i> Request ID does not match to that user.
              </div>';
      } else {
        $status = !empty($row['assign_status']) ? $row['assign_status'] : "Not Assigned";
        
        // Define progress percentage and stepper states
        $progress = 0;
        $progress_color = "bg-info"; // Default green
        $steps = ['Submitted' => 'pending', 'Assigned' => 'pending', 'Work Started' => 'pending', 'Completed' => 'pending'];
        
        if($status == "Not Assigned"){
          $progress = 25;
          $progress_color       = "bg-warning";
          $steps['Submitted'] = 'active';
        } 
        // elseif($status == "Assigned" || $status == "Pending"){

        elseif($status == "Assigned"){
          $progress = 50;
          $progress_color       = "bg-dark";
          $steps['Submitted'] = 'active';
          $steps['Assigned'] = 'active';
        } elseif($status == "Work Started"){
          $progress = 75;
          $progress_color       = "bg-secondary";
          $steps['Submitted'] = 'active';
          $steps['Assigned'] = 'active';
          $steps['Work Started'] = 'active';
        } elseif($status == "Completed"){
          $progress = 100;
          $progress_color       = "bg-success";
          $steps['Submitted'] = 'active';
          $steps['Assigned'] = 'active';
          $steps['Work Started'] = 'active';
          $steps['Completed'] = 'active';
        } 
        elseif($status == "Rejected"){
            $progress = 25; // Reset to submitted
            $progress_color       = "bg-danger";
            $steps['Submitted'] = 'rejected';
            // $status = "Not Assigned (Rejected)";
        }
    ?>

    <!-- Modern Status Card -->
    <div class="card mt-2 shadow-lg border-0 rounded-lg">
      <div class="card-header text-white bg-info p-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-clipboard-list mr-2"></i> Request #<?php echo htmlspecialchars($checkid); ?></h5>
        <?php
// Pick badge colour based on current status
if($status == "Completed")       { $badge_class = "badge-success";   }  
elseif($status == "Rejected")    { $badge_class = "badge-danger";    }  
elseif($status == "Work Started"){ $badge_class = "badge-secondary"; }  
elseif($status == "Assigned")    { $badge_class = "badge-dark";   }  
elseif($status == "Not Assigned")    { $badge_class = "badge-warning";   }
else                             { $badge_class = "badge-primary";   }  
?>
<span class="badge <?php echo $badge_class; ?> p-2 px-3">
    <?php echo htmlspecialchars($status); ?>
</span>
      </div>
      <div class="card-body p-5">
        
        <!-- Status Stepper -->
        <div class="status-stepper mb">
          <div class="progress" style="height: 15px;">
<!-- Dynamic $progress_color variable for dynamically changing the progress bar color according to the status -->
<div class="progress-bar <?php echo $progress_color; ?>" role="progressbar" style="width: <?php echo $progress; ?>%;" aria-valuenow="<?php echo $progress; ?>" aria-valuemin="0" aria-valuemax="100"></div>          </div>
          <div class="d-flex justify-content-between mt-1 text-center step-labels">
            <div class="step-item <?php echo $steps['Submitted']; ?>">
              <div class="step-icon"><i class="fas fa-file-invoice"></i></div>
              <small class="font-weight-bold">Submitted</small>
            </div>
            <div class="step-item <?php echo $steps['Assigned']; ?>">
              <div class="step-icon"><i class="fas fa-user-check"></i></div>
              <small class="font-weight-bold">Assigned</small>
            </div>
            <div class="step-item <?php echo $steps['Work Started']; ?>">
              <div class="step-icon"><i class="fas fa-briefcase"></i></div>
              <small class="font-weight-bold">Work Started</small>
            </div>
            <div class="step-item <?php echo $steps['Completed']; ?>">
              <div class="step-icon"><i class="fas fa-check-double"></i></div>
              <small class="font-weight-bold">Completed</small>
            </div>
          </div>
        </div>

        <?php if($status == "Not Assigned"): ?>
<div class="alert alert-warning text-center mt-3 mb-0" role="alert">
    <i class="fas fa-file-invoice fa-lg mr-2"></i>
    <strong>This request has been submitted to the admin.</strong>
    <br>
    Frequently check the status to know the technician assignment.
</div>
<?php endif; ?>

<?php if($status == "Assigned"): ?>
<div class="alert alert-dark text-center mt-3 mb-0" role="alert">
    <i class="fas fa-user-check fa-lg mr-2"></i>
    <strong>This request has been assigned to the Technician.</strong>
    <br>
    Find the details listed below.
</div>
<?php endif; ?>

<?php if($status == "Work Started"): ?>
<div class="alert alert-secondary text-center mt-3 mb-0" role="alert">
    <i class="fas fa-briefcase fa-lg mr-2"></i>
    <strong>Request is in progress. Technician has started working on this.</strong>
    <br>
    Check status regularly to know the completion of this request.
</div>
<?php endif; ?>

<?php if($status == "Completed"): ?>
<div class="alert alert-success text-center mt-3 mb-0" role="alert">
    <i class="fas fa-check-double fa-lg mr-2"></i>
    <strong>Your Request is completed. Thank you for choosing us.</strong>
    <br><br>
    <a href="submitRequest.php" class="btn btn-sm btn-success shadow-sm mb-2"><i class="fas fa-plus"></i> Create New</a>
    <a href="myRequests.php" class="btn btn-sm btn-success shadow-sm mb-2"><i class="fas fa-file-invoice"></i> View Invoice</a>
</div>
<?php endif; ?>

        <?php if($status == "Rejected"): ?>
<div class="alert alert-danger text-center mt-3 mb-0" role="alert">
    <i class="fas fa-times-circle fa-lg mr-2"></i>
    <strong>This request has been submitted but rejected by admin.</strong>
    <br>
    You might have received reasons for rejection in your email.
    <br><br>
    <a href="submitRequest.php" class="btn btn-sm btn-success shadow-sm mb-2"><i class="fas fa-plus"></i> Create New</a>
    
</div>
<?php endif; ?>

        <!-- Details Table -->
        <div class="table-responsive">
          <table class="table table-hover border">
            <tbody>
              <tr>
                <th class="bg-light w-25">Customer Name</th>
                <td><?php echo htmlspecialchars($row['requester_name']); ?></td>
              </tr>
              <tr>
                <th class="bg-light">Service Info</th>
                <td><?php echo htmlspecialchars($row['request_info']); ?></td>
              </tr>
              <tr>
                <th class="bg-light">Description</th>
                <td><?php echo nl2br(htmlspecialchars($row['request_desc'])); ?></td>
              </tr>
              <?php if(!empty($row['assign_tech'])) { ?>
              <tr>
                <th class="bg-light text-primary font-weight-bold">Assigned Technician</th>
                <td class="text-primary font-weight-bold"><?php echo htmlspecialchars($row['assign_tech']); ?></td>
              </tr>
              <tr>
                <th class="bg-light">Assigned Date</th>
                <td><?php echo htmlspecialchars($row['assign_date']); ?></td>
              </tr>
              <?php } ?>
              <?php if(!empty($row['deliveryDate'])) { ?>
              <tr>
                <th class="bg-light text-success font-weight-bold">Delivery Date</th>
                <td class="text-success font-weight-bold"><?php echo htmlspecialchars($row['deliveryDate']); ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>

        <div class="mt-4 text-center d-print-none">
          <button type="button" class="btn btn-dark btn-md mr-3 shadow-sm" onclick="window.print()"><i class="fas fa-print"></i> Print Receipt</button>
          <a href="checkStatus.php" class="btn btn-outline-secondary btn-md shadow-sm">Check Another</a>
        </div>
      </div>
    </div>

    <?php 
      }
      $stmt->close();
    } else {
      echo '<div class="alert alert-danger mt-2" role="alert">
              <i class="fas fa-exclamation-circle"></i> No request submitted with this ID. Provide the valid Request ID.
            </div>';
      $stmt->close();
    }
  }
  ?>

  <style>
    .step-item                     { color: #ccc; flex: 1; transition: all 0.3s ease; }
    .step-item.active              { color: #000; }
    .step-item.active .step-icon   { transform: scale(1.2); }

    /* Rejected step styling */
    .step-item.rejected            { color: #dc3545; }
    .step-item.rejected .step-icon { transform: scale(1.2); color: #dc3545; }

    .step-icon                     { font-size: 1.5rem; margin-bottom: 5px; }
    .progress-bar                  { transition: width 1s ease-in-out; }

    @media print {
        .card      { border: 1px solid #ddd !important; box-shadow: none !important; }
        .bg-dark   { background-color: #343a40 !important; }
    }
</style>

</div>

<?php
include('includes/footer.php'); 
?>