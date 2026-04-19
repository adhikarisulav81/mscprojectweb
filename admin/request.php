<?php
define('TITLE', 'Requests');
define('PAGE', 'request');
include('includes/header.php'); 
include('../dbConnection.php');
include('../emailConfig.php');
session_start();

//  Check is the user logged in or not
if(isset($_SESSION['is_adminlogin'])){
    $aEmail = $_SESSION['aEmail'];
} else {
    echo "<script> location.href='adminLogin.php'; </script>";
    exit();
}

// Runs only when the Reject button is clicked
if(isset($_REQUEST['reject'])){

    // Convert ID to integer for safety — this helps prevent SQL injection
    $requestId = intval($_REQUEST['id']);
    $rejection_reason = isset($_REQUEST['rejection_reason']) ? trim($_REQUEST['rejection_reason']) : '';

    // Fetch the original request details from submitrequest_tb
    // (Rejected requests don't exist in assignwork_tb yet)
    $sql_sub = "SELECT * FROM submitrequest_tb WHERE request_id = $requestId";
    $res_sub = $conn->query($sql_sub);

    if($res_sub->num_rows == 1){
        $row      = $res_sub->fetch_assoc();
        $rinfo    = $row['request_info'];
        $rdesc    = $row['request_desc'];
        $rname    = $row['requester_name'];
        $radd1    = $row['requester_add1'];
        $remail   = $row['requester_email'];
        $rmobile  = $row['requester_mobile'];
        $rpriority = $row['priority'];
        $rprice   = $row['service_price'];
        $rdate    = date('Y-m-d'); // Today's date becomes the rejection date

        // Insert into assignwork_tb with Rejected status
        // assign_tech = '-' (no technician), deliveryDate = NULL (no delivery)
        $insert_sql = "INSERT INTO assignwork_tb 
                        (request_id, request_info, request_desc, requester_name, requester_add1,
                         requester_email, requester_mobile, assign_tech, assign_date, status,
                         priority, service_price) 
                       VALUES 
                        ('$requestId', '$rinfo', '$rdesc', '$rname', '$radd1',
                         '$remail', '$rmobile', 'Request Rejected - No Technician Assigned', '$rdate', 'Rejected',
                         '$rpriority', '$rprice')";

        if($conn->query($insert_sql) === TRUE){
            // Send rejection email with reason
            sendRequestRejectionToUser($row['requester_name'], $row['requester_email'], $requestId, $row['request_info'], $rejection_reason);
            // Redirect back to refresh the pending list
            header("Location: request.php");
            exit;
        } else {
            $errorMsg = "Unable to Reject. Error: " . $conn->error;
        }

    } else {
        $errorMsg = "Request not found.";
    }
}

// Auto-Assign Logic 
if(isset($_REQUEST['auto_assign'])){

  $requestId = intval($_REQUEST['id']);

  // Fetch the original request details from submitrequest_tb
  $req_sql    = "SELECT * FROM submitrequest_tb WHERE request_id = $requestId";
  $req_result = $conn->query($req_sql);

  if($req_result->num_rows == 1){
      $req_row   = $req_result->fetch_assoc();
      $rinfo     = $req_row['request_info'];
      $rdesc     = $req_row['request_desc'];
      $rname     = $req_row['requester_name'];
      $radd1     = $req_row['requester_add1'];
      $remail    = $req_row['requester_email'];
      $rmobile   = $req_row['requester_mobile'];
      $rpriority = $req_row['priority'];
      $rprice    = $req_row['service_price'];
      $rdate     = date('Y-m-d');

      // Find technician with Lowest active job count
      // techEmail is now included so we can notify them directly
      $tech_sql = "SELECT t.techName, t.techEmail,
                          COUNT(CASE WHEN a.status NOT IN ('Completed', 'Rejected') THEN 1 END) AS active_count
                   FROM technician_tb t
                   LEFT JOIN assignwork_tb a ON t.techName = a.assign_tech
                   GROUP BY t.techName, t.techEmail
                   ORDER BY active_count ASC
                   LIMIT 1";

      $tech_result = $conn->query($tech_sql);

      if($tech_result->num_rows == 1){
          $tech_row      = $tech_result->fetch_assoc();
          $assigned_tech = $tech_row['techName'];
          $tech_email    = $tech_row['techEmail']; 
          $tech_active   = $tech_row['active_count'];

          // Insert the assignment into assignwork_tb
          $insert_sql = "INSERT INTO assignwork_tb 
                          (request_id, request_info, request_desc, requester_name, requester_add1,
                           requester_email, requester_mobile, assign_tech, assign_date, status,
                           priority, service_price) 
                         VALUES 
                          ('$requestId', '$rinfo', '$rdesc', '$rname', '$radd1',
                           '$remail', '$rmobile', '$assigned_tech', '$rdate', 'Assigned',
                           '$rpriority', '$rprice')";

          if($conn->query($insert_sql) === TRUE){

              $successMsg = "Request #$requestId has been automatically assigned to 
                             <strong>$assigned_tech</strong> 
                             (Active Jobs: $tech_active). <a href='work.php'>Click Here to View</a>";

              //Notify technician of their new assigned job
              sendWorkAssignedToTechnician(
                  $assigned_tech,
                  $tech_email,
                  $rname,
                  $requestId,
                  $rinfo,
                  $rdate
              );

              // Check technician workload EXCLUDING the job just inserted
              // If they have other active jobs already, notify the customer
              $workload_sql = "SELECT COUNT(*) as active_jobs 
                               FROM assignwork_tb 
                               WHERE assign_tech = '$assigned_tech' 
                               AND status NOT IN ('Completed', 'Rejected') 
                               AND request_id != '$requestId'";
              $workload_res = $conn->query($workload_sql);
              $workload_row = $workload_res->fetch_assoc();

              if($workload_row['active_jobs'] > 0){
                  // Technician has other active jobs, send busy notification to customer
                  sendTechnicianBusyNotification(
                      $rname,
                      $remail,
                      $assigned_tech,
                      $requestId
                  );
              } else {
                  // Technician is available, send assignment notification to customer
                  sendWorkAssignedToUser(
                      $rname,
                      $remail,
                      $assigned_tech,
                      $requestId,
                      $rdesc
                  );
              }

          } else {
              $errorMsg = "Assignment failed. Error: " . $conn->error;
          }

      } else {
          $errorMsg = "No technicians available. Please add technicians first.";
      }

  } else {
      $errorMsg = "Request not found.";
  }
}

// Priority Filter
// Reads ?priority=High or ?priority=Normal from the URL. Defaults to 'All'.
$priority_filter = isset($_GET['priority']) ? $_GET['priority'] : 'All';
?>

<div class="col-sm-9 col-md-10 mt-5">

    <h3 class="title text-center font-weight-bold text-dark mb-3"
        style="font-family: Arial, Helvetica, sans-serif;">
        <i class="fas fa-people-carry"></i> PENDING <span>REQUESTS</span>
    </h3>

   

    <!-- Error or Success Messages -->
    <?php if(isset($errorMsg)):   echo '<div class="alert alert-danger mx-3">'  . $errorMsg   . '</div>'; endif; ?>
    <?php if(isset($successMsg)): echo '<div class="alert alert-success mx-3">' . $successMsg . '</div>'; endif; ?>

    <!-- Priority Filter Buttons -->
    <div class="mt-3 mb-3 mx-3">
        <div class="btn-group" role="group" aria-label="Priority Filter">
            <a href="request.php?priority=All"
               class="btn <?php echo ($priority_filter == 'All')    ? 'btn-primary'        : 'btn-outline-primary'; ?>">
                <i class="fas fa-list"></i> All Priorities
            </a>
            <a href="request.php?priority=High"
               class="btn <?php echo ($priority_filter == 'High')   ? 'btn-danger'         : 'btn-outline-danger'; ?>">
                <i class="fas fa-arrow-up"></i> High
            </a>
            <a href="request.php?priority=Normal"
               class="btn <?php echo ($priority_filter == 'Normal') ? 'btn-success'        : 'btn-outline-success'; ?>">
                <i class="fas fa-minus"></i> Normal
            </a>
        </div>
    </div>

    <!-- Pending Requests Table -->
    <?php
    // Build query — show only requests NOT yet in assignwork_tb (truly pending)
    // LEFT JOIN with assignwork_tb; WHERE a.status IS NULL means no matching assigned record
    if($priority_filter != 'All'){
        $sql = "SELECT s.request_id, s.request_info, s.request_desc, s.request_date,
                       s.priority, s.requester_name, s.requester_email
                FROM submitrequest_tb AS s
                LEFT JOIN assignwork_tb AS a ON s.request_id = a.request_id
                WHERE s.priority = '$priority_filter' AND a.status IS NULL
                ORDER BY s.priority DESC, s.request_date ASC";
        // High priority requests float to the top within each filter
    } else {
        $sql = "SELECT s.request_id, s.request_info, s.request_desc, s.request_date,
                       s.priority, s.requester_name, s.requester_email
                FROM submitrequest_tb AS s
                LEFT JOIN assignwork_tb AS a ON s.request_id = a.request_id
                WHERE a.status IS NULL
                ORDER BY s.priority DESC, s.request_date ASC";
        // High priority first, then by oldest request date first
    }

    $result = $conn->query($sql);

    if($result->num_rows > 0):
        echo '<div class="table-responsive-sm mx-3">';
        echo '<table id="dataTableID" class="table table-hover">
              <thead class="thead-light">
                <tr>
                  <th>Req ID</th>
                  <th style="max-width: 175px;">Request Info</th>
                  <th>Customer</th>
                  <th>Request Date</th>
                  <th>Priority</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>';

        while($row = $result->fetch_assoc()):

            // Priority badge colour
            $priority_badge = ($row['priority'] == 'High')
                ? '<span class="badge badge-danger p-2">High</span>'
                : '<span class="badge badge-success p-2">Normal</span>';

            echo '<tr>
                  <th scope="row">' . $row["request_id"] . '</th>
                  <td>' . htmlspecialchars($row["request_info"]) . '</td>
                  <td>' . htmlspecialchars($row["requester_name"]) . '</td>
                  <td>' . $row["request_date"] . '</td>
                  <td>' . $priority_badge . '</td>
                  <td><span class="badge badge-warning p-2">Not Assigned</span></td>
                  <td>';

            // View Button
            echo '<form action="viewassignwork.php" method="POST" class="d-inline">
                    <input type="hidden" name="id" value="' . $row["request_id"] . '">
                    <button type="submit" class="btn btn-info btn-sm mr-1 mb-1" title="View Details">
                        <i class="far fa-eye"></i>
                    </button>
                  </form>';

            // Auto-Assign Button+
            // Clicking this triggers the auto_assign logic at the top of this file
            // No manual technician selection needed — system picks the best available technician
            echo '<form action="" method="POST" class="d-inline"
                        onsubmit="return confirm(\'Auto-assign Request #' . $row["request_id"] . ' to the most available technician?\')">
                    <input type="hidden" name="id" value="' . $row["request_id"] . '">
                    <button type="submit" name="auto_assign" class="btn btn-primary btn-sm mr-1 mb-1"
                            title="Auto-Assign to Best Available Technician">
                        <i class="fas fa-user-plus"></i> Assign
                    </button>
                  </form>';

            // Reject Button
            echo '<form action="" method="POST" class="d-inline">
                    <input type="hidden" name="id" value="' . $row["request_id"] . '">

                    <button type="button" class="btn btn-danger btn-sm mb-1 ml-1" data-toggle="modal" data-target="#rejectModal'.$row["request_id"].'" title="Reject Request">
                  <i class="fas fa-times-circle"></i> Reject
                </button>
                    <!-- <button type="submit" name="reject" class="btn btn-danger btn-sm mb-1">
                        <i class="fas fa-times-circle"></i> Reject
                    </button> -->
                    
                  </form>';

            echo '</td></tr>';

        endwhile;

        echo '</tbody></table>';
        echo '</div>';

    else:
        // No pending requests — show a friendly message
        echo '<div class="alert alert-info mt-4 mx-3 shadow-sm" role="alert">
                <h4 class="alert-heading"><i class="fas fa-check-circle"></i> Well done!</h4>
                <p>All requests have been assigned or handled. No pending requests remaining.</p>
                <hr>
                <p class="mb-0">New requests from users will appear here automatically.</p>
              </div>';
    endif;
    ?>




<?php 
  // Generate rejection modals for each request
  $result2 = $conn->query($sql);
  if($result2->num_rows > 0){
    while($row2 = $result2->fetch_assoc()){
  ?>
  <!-- Rejection Modal for Request #<?php echo $row2["request_id"]; ?> -->
  <div class="modal fade" id="rejectModal<?php echo $row2["request_id"]; ?>" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel<?php echo $row2["request_id"]; ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content border-0 shadow">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="rejectModalLabel<?php echo $row2["request_id"]; ?>">
            <i class="fas fa-times-circle"></i> Reject Request #<?php echo $row2["request_id"]; ?>
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" method="POST">
          <div class="modal-body">
            <input type="hidden" name="id" value="<?php echo $row2["request_id"]; ?>">
            
            <div class="card mb-3 border-left-primary" style="border-left: 4px solid #4e73df !important;">
              <div class="card-body py-2">
                <p class="mb-1"><strong><i class="fas fa-info-circle text-primary"></i> Request Info:</strong></p>
                <p class="mb-1 text-muted"><?php echo htmlspecialchars($row2["request_info"]); ?></p>
                <hr class="my-2">
                <p class="mb-1"><strong><i class="fas fa-user text-primary"></i> Customer:</strong> <?php echo htmlspecialchars($row2["requester_name"]); ?></p>
                <p class="mb-0"><strong><i class="fas fa-envelope text-primary"></i> Email:</strong> <?php echo htmlspecialchars($row2["requester_email"]); ?></p>
              </div>
            </div>
            
            <div class="alert alert-warning py-2 mb-3">
              <i class="fas fa-exclamation-triangle"></i> <strong>Warning:</strong> This action will mark the request as rejected and send an email notification to the customer with your rejection reason.
            </div>
            
            <div class="form-group">
              <label for="rejection_reason_<?php echo $row2["request_id"]; ?>" class="font-weight-bold">
                <i class="fas fa-comment-alt"></i> Rejection Reason <span class="text-danger">*</span>
              </label>
              <textarea class="form-control border-danger" id="rejection_reason_<?php echo $row2["request_id"]; ?>" name="rejection_reason" rows="4" placeholder="Please provide a clear reason for rejecting this request. For example:&#10;- Incomplete information provided&#10;- Service not available for this model&#10;- Duplicate request" required style="resize: vertical;"></textarea>
              <small class="form-text text-muted"><i class="fas fa-envelope"></i> This reason will be emailed to the customer.</small>
            </div>
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-arrow-left"></i> Cancel</button>
            <button type="submit" class="btn btn-danger" name="reject"><i class="fas fa-times-circle"></i> Reject & Notify User</button>
          </div>
        </form>
      </div>
    </div>


  </div>
  <?php
    }
  }
  ?>


</div>

<?php
include('includes/footer.php');
$conn->close();
?>