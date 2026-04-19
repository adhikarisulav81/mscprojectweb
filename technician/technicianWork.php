<?php
define('TITLE', 'Technician Assigned Work');
define('PAGE', 'technicianWork');
include('includes/header.php'); 
include('../dbConnection.php');
include('../emailConfig.php');
//way of remembering who is logged in across pages.
session_start();

 if(isset($_SESSION['is_techlogin'])){ //isset() checks if a variable exists.

//  If the technician is logged in, their email is saved in $tEmail.
  $tEmail = $_SESSION['tEmail'];
 } else {
  echo "<script> location.href='technicianLogin.php'; </script>";
 }

 // Get technician details whose email address matches 
 $sql = "SELECT techName FROM technician_tb WHERE techEmail='$tEmail'";
 $result = $conn->query($sql);
 if($result->num_rows == 1){ //num_rows == 1 means exactly one match was found.
  $row = $result->fetch_assoc(); //fetch_assoc() grabs the result as an array.

  //The technician's name is saved in $tName for later use.
  $tName = $row['techName'];
 }

 //Checks if the "Finalize Work" button was clicked 
  if(isset($_REQUEST['completework'])){
    //$rid stores the request ID of the request being completed.
  $rid = $_REQUEST['id'];
  // Fetch all the user details about this work order from the database
  $sql_user = "SELECT * FROM assignwork_tb WHERE request_id = '$rid'";
  $res_user = $conn->query($sql_user);
  if($res_user->num_rows == 1){
    $row_user = $res_user->fetch_assoc();

       //Saves the customer's name, email, job info, and the assigned technician's name into variables.
    $uName = $row_user['requester_name'];
    $uEmail = $row_user['requester_email'];
    $uInfo = $row_user['request_info'];
    $tech_name = $row_user['assign_tech'];

    // Update status to Completed 
    $sql_update = "UPDATE assignwork_tb SET status = 'Completed' WHERE request_id = '$rid'";
    if($conn->query($sql_update) == TRUE){ //Only runs the next steps if this update was successful.

      // Rewards the technician by adding 1 point to their rating score
      // $sql_update_tech = "UPDATE technician_tb SET techRating = techRating + 1 WHERE techName = '$tech_name'";
      
      // $conn->query($sql_update_tech);


      // Check if there is another assigned job for this technician and notify the user
      $next_job_sql = "SELECT * FROM assignwork_tb WHERE assign_tech = '$tech_name' AND status = 'Assigned' AND request_id != '$rid' ORDER BY assign_date ASC LIMIT 1";
      $next_job_res = $conn->query($next_job_sql);
      if($next_job_res->num_rows == 1){
        $next_job_row = $next_job_res->fetch_assoc();
        sendTechnicianAvailableNotification($next_job_row['requester_name'], $next_job_row['requester_email'], $tech_name, $next_job_row['request_id']);
      }

      // Send completion notification to current user
      sendWorkCompletionToUser($uName, $uEmail, $tName, $rid, $uInfo);
      $msg = '<div class="alert alert-success col-sm-6 mt-2" role="alert"> The Request has been Completed and notified to the customer! </div>';
      echo '<meta http-equiv="refresh" content= "2;URL=?completed" />';
    }
  }
}


//Checks if the "Start" button was clicked and get the request ID.
if(isset($_REQUEST['startwork'])){
  $rid = $_REQUEST['id'];


  // Fetch user details for email notification
  $sql_user = "SELECT * FROM assignwork_tb WHERE request_id = '$rid'";
  $res_user = $conn->query($sql_user);
  if($res_user->num_rows == 1){
    $row_user = $res_user->fetch_assoc();
    $uName = $row_user['requester_name'];
    $uEmail = $row_user['requester_email'];
    $uInfo = $row_user['request_info'];

    // Update status to Work Started
    $sql_update = "UPDATE assignwork_tb SET status = 'Work Started' WHERE request_id = '$rid'";
    if($conn->query($sql_update) == TRUE){
      // Send notification to user
      sendWorkStartedNotification($uName, $uEmail, $tName, $rid);
      $msg = '<div class="alert alert-success col-sm-6 mt-2" role="alert"> Work Started and Notified to the Customer! </div>';
      echo '<meta http-equiv="refresh" content= "2;URL=?started" />';
    }
  }
}

?>


<div class="col-sm-9 col-md-10">


  <div class="mx-5 mt-5">
    <!--Table-->
    <h3 class="title text-center font-weight-bold text-dark mb-4" style="font-family: Arial, Helvetica, sans-serif;"><i class="fas fa-list"></i> <span>ASSIGNED WORK</span> ORDERS</h3>
    
    <!-- Status Filter UI -->
    <!-- It checks the URL for a ?status= value (e.g. ?status=Completed). If nothing is in the URL, it defaults to 'All'.-->
    <?php $status_filter = isset($_GET['status']) ? $_GET['status'] : 'All'; ?>
    <div class="d-flex justify-content-center mb-4 flex-wrap align-items-center">
      <div class="btn-group mb-2 shadow-sm" role="group" aria-label="Status Filter">
         <a href="technicianWork.php?status=All" class="btn btn-all <?php echo ($status_filter == 'All') ? 'btn-primary' : 'btn-outline-primary'; ?>">
          <i class="fas fa-list"></i> All
        </a>
        
        <a href="technicianWork.php?status=Assigned" class="btn <?php echo ($status_filter == 'Assigned') ? 'btn-dark' : 'btn-outline-dark'; ?>">
          <i class="fas fa-clock"></i> Assigned
        </a>
        <a href="technicianWork.php?status=Work Started" class="btn <?php echo ($status_filter == 'Work Started') ? 'btn-secondary' : 'btn-outline-secondary'; ?>">
          <i class="fas fa-cog"></i> Started
        </a>
        <a href="technicianWork.php?status=Completed" class="btn <?php echo ($status_filter == 'Completed') ? 'btn-success' : 'btn-outline-success'; ?>">
          <i class="fas fa-check-circle"></i> Completed
        </a>
      </div>
    </div>

    <?php
      // Apply filters securely
      if ($status_filter == 'Assigned') {
          $status_condition = "AND status IN ('Assigned')";
      } elseif ($status_filter != 'All') {
          $status_condition = "AND status = '$status_filter'";
      } else {
          $status_condition = "";
      }


      //Get all work orders for this technician, apply the filter, and sort by newest first.
      $sql = "SELECT * FROM assignwork_tb WHERE assign_tech='$tName' $status_condition ORDER BY assign_date DESC";
      $result = $conn->query($sql);
      if($result->num_rows > 0){
        echo '<div class="table-responsive-sm">';

        //Only draws a table if at least one job was found.
        echo '<table id="dataTableID" class="table">
          <thead>
          <tr>
            <th scope="col">Request ID</th>
            <th scope="col">Request Info</th>
            <th scope="col">User</th>
            <th scope="col">Mobile</th>
            <th scope="col">Assigned Date</th>
            <th scope="col">Status</th>
            <!-- <th scope="col">Delivery Date</th> -->
            <th scope="col">Action</th>
          </tr>
          </thead>
          <tbody>';
          //Loops through every request in the results, one at a time.
          while($row = $result->fetch_assoc()){

            $status = $row["status"];
            $status_class = "badge-secondary"; // Default, will be overridden
  //Picks a colour for the status badge depending on the job's status
            if($status == "Completed"){
              $status_class = "badge-success";
            } elseif($status == "Work Started"){
              $status_class = "badge-secondary";
            } elseif($status == "Assigned"){
              $status_class = "badge-dark";
            } elseif($status == "Not Assigned"){
              $status_class = "badge-warning";
            } elseif($status == "Rejected"){
              $status_class = "badge-danger";
            }

            echo '<tr>';
              echo '<th scope="row">'.$row["request_id"].'</th>';
              echo '<td>'. $row["request_info"].'</td>';
              echo '<td>'.$row["requester_name"].'</td>';
              echo '<td>'.$row["requester_mobile"].'</td>';
              echo '<td>'.$row["assign_date"].'</td>';

              //Displays the status as a coloured badge in the table.
              echo '<td><span class="badge '.$status_class.' p-2">'.$status.'</span></td>';
              // echo '<td>'.$row["deliveryDate"].'</td>';
              echo '<td>
                <form action="technicianViewWork.php" method="POST" class="d-inline"> 
                  <input type="hidden" name="id" value='. $row["request_id"] .'>
                  <button type="submit" class="btn btn-sm btn-dark mr-2 mb-2" title="View Details" name="view" value="View"><i class="fas fa-eye"></i> View</button>
                </form>';
                //Only shows the Start button if the job hasn't been started yet. Shows button if the request is assigned to technician
                if($row['status'] == 'Assigned'){
                  echo '<form action="" method="POST" class="d-inline"> 
                    <input type="hidden" name="id" value='. $row["request_id"] .'>
                    <button type="submit" class="btn btn-sm btn-secondary  mr-2 mb-2" title="Start Work" name="startwork" value="Start"><i class="fas fa-play"></i> Start</button>
                  </form>';
                }
                //Only shows the Complete button once work has started. Clicking it opens a confirmation popup modal.
                if($row['status'] == 'Work Started'){
                  echo '<button type="button" class="btn btn-sm btn-info mb-2" title="Complete Work" data-toggle="modal" data-target="#completeModal'.$row["request_id"].'"><i class="fas fa-check-double"></i> Mark as Complete</button>
                    <form action="" method="POST" class="d-inline"> 
                    <input type="hidden" name="id" value='. $row["request_id"] .'>
                  </form>';
                }
                //For already-finished request, shows a green "Completed" badge
                if($row['status'] == 'Completed'){
                  echo '<span class="badge badge-success p-2">Completed</span>';
                }
              echo '</td>';
            echo '</tr>';
      }

          echo '</tbody>
        </table>';
        echo '</div>';
    } else {
      echo '<div class="alert alert-info" role="alert">No work orders assigned to you yet.</div>';
    }
  ?>
  <?php if(isset($msg)) {echo $msg; } ?>

  <?php
    // Fetch all 'Work Started' jobs for this technician to render their confirmation popup modals
    $sql_display = "SELECT * FROM assignwork_tb WHERE assign_tech='$tName' AND status='Work Started'";
    $result2 = $conn->query($sql_display);
    if($result2 && $result2->num_rows > 0){
      while($row2 = $result2->fetch_assoc()){
  ?>
  <!-- Modal for Complete Work with Price -->
  <div class="modal fade" id="completeModal<?php echo $row2["request_id"]; ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content shadow-lg border-0">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">
            <i class="fas fa-check-double"></i> Complete Work Order #<?php echo $row2["request_id"]; ?>
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" method="POST">
          <div class="modal-body">
            <input type="hidden" name="id" value="<?php echo $row2["request_id"]; ?>">
            <p class="text-muted mb-2">Are you sure you want to mark Work Order #<?php echo $row2["request_id"]; ?> as completed?</p>
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success" name="completework"><i class="fas fa-check"></i> Finalize Work</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php 
      }
    }
    // else: no 'Work Started' jobs — no modals needed, nothing to render
  ?>
  </div>
</div>
</div>

<?php
include('includes/footer.php'); 
?>