<?php
define('TITLE', 'My Requests');
define('PAGE', 'myRequests');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();

//Checks if the user is logged in by looking at the session.
if($_SESSION['is_login']){
  //If logged in, save their email into $rEmail for later use.
 $rEmail = $_SESSION['rEmail'];
} else {
  //else redirect to login page
 echo "<script> location.href='userLogin.php'; </script>";
}
?>


<div class="col-sm-9 col-md-10 mt-5">
  <h3 class="title text-center font-weight-bold text-dark mb-5" style="font-family: Arial, Helvetica, sans-serif;">
    <i class="fas fa-list-alt"></i> MY <span>SUBMITTED REQUESTS</span>
  </h3>

  <div class="d-flex justify-content-between mb-4 flex-wrap align-items-center">
    <!-- Reads the URL to see which filter the user clicked. E.g., if the URL is myRequests.php?status=Completed, then $status_filter = 'Completed'. If nothing is in the URL, it defaults to 'All'. -->
    <?php $status_filter = isset($_GET['status']) ? $_GET['status'] : 'All'; ?>
    <div class="btn-group mb-2" role="group" aria-label="Status Filter">

    <!-- Creates a clickable filter button. The ? : part is a shorthand if-else. If this filter is currently active, use a primary color in button; otherwise use an outline primary color in button.  -->
       <a href="myRequests.php?status=All" class="btn <?php echo ($status_filter == 'All') ? 'btn-primary' : 'btn-outline-primary'; ?>">
        All
      </a>
      <a href="myRequests.php?status=Not Assigned" class="btn <?php echo ($status_filter == 'Not Assigned') ? 'btn-warning' : 'btn-outline-warning'; ?>">
        Not Assigned
      </a>
      <a href="myRequests.php?status=Assigned" class="btn <?php echo ($status_filter == 'Assigned') ? 'btn-dark' : 'btn-outline-dark'; ?>">
        Assigned
      </a>
    
      <a href="myRequests.php?status=Work Started" class="btn <?php echo ($status_filter == 'Work Started') ? 'btn-secondary' : 'btn-outline-secondary'; ?>">
        Started
      </a>
      <a href="myRequests.php?status=Completed" class="btn <?php echo ($status_filter == 'Completed') ? 'btn-success' : 'btn-outline-success'; ?>">
        Completed
      </a>
      <a href="myRequests.php?status=Rejected" class="btn <?php echo ($status_filter == 'Rejected') ? 'btn-danger' : 'btn-outline-danger'; ?>">
        Rejected
      </a>
    </div>

    <!-- Create Button -->
    <a href="submitRequest.php" class="btn btn-success shadow-sm mb-2"><i class="fas fa-plus"></i> Create New</a>
  </div>

  <?php
    //This query fetches requests that haven't been assigned to any technician yet. 'Not Assigned' as status — since these aren't in the submitrequest_tb, we manually label their status as "Not Assigned"
// '-' as technician — no technician yet, so show a dash. AND request_id NOT IN (SELECT request_id FROM assignwork_tb) — exclude any request that already appears in the assigned work table (those are handled separately below)
    $unassigned_base = "SELECT request_id, request_info, request_date, 'Not Assigned' as status, '-' as technician FROM submitrequest_tb WHERE requester_email = '$rEmail' AND request_id NOT IN (SELECT request_id FROM assignwork_tb)";
    

    // This query fetches requests that have been assigned (and may be started, completed, or rejected):
    //assign_date as request_date — renames the column so both queries use the same column name; assign_tech as technician — renames the technician column
    $assigned_base = "SELECT request_id, request_info, assign_date as request_date, 
    status, assign_tech as technician FROM assignwork_tb WHERE requester_email = '$rEmail'";
    
    // If the user clicked "Not Assigned", only run $unassigned_base sql query and sort newest first.
    if($status_filter == 'Not Assigned') {
        $sql = $unassigned_base . " ORDER BY request_date DESC";
    //If the user clicked any other specific filter (Assigned, Completed, etc.), run the $assigned_base sql query with that status condition added.
      } elseif ($status_filter != 'All') {
        $sql = $assigned_base . " AND status = '$status_filter' ORDER BY request_date DESC";
    } else {
      //If "All" is selected, combine both queries using UNION. This merges the results from both tables into one list, sorted by date. 
        $sql = "($unassigned_base) UNION ($assigned_base) ORDER BY request_date DESC";
    }
    
    //sends the query to the database and stores the results.
    $result = $conn->query($sql);

    //Only draws a table if at least one request was found.
    if($result->num_rows > 0){
      echo '<div class="table-responsive-sm">';
      echo '<table id="dataTableID" class="table table-hover">
        <thead>
          <tr>
            <th scope="col">Req ID</th>
            <th scope="col">Request Info</th>
            <th scope="col">Technician</th>
            <th scope="col">Date</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>';

        //Loops through every request result one by one. Each loop gives us one row of data in $row.
        while($row = $result->fetch_assoc()){
          $status = $row["status"];
          $status_class = "badge-info"; // Default, will be overridden

          if($status == "Completed"){
            $status_class = "badge-success";
          } elseif($status == "Work Started"){
            $status_class = "badge-secondary";
          } elseif($status == "Assigned" || $status == "Pending"){
            $status_class = "badge-dark";
          } elseif($status == "Not Assigned"){
            $status_class = "badge-warning";
          } elseif($status == "Rejected"){
            $status_class = "badge-danger";
            
          }

          echo '<tr>
            <th scope="row">'.$row["request_id"].'</th>
            <td style="max-width: 400px;">'.$row["request_info"].'</td>
            <td>'.$row["technician"].'</td>
            <td>'.$row["request_date"].'</td>

            <!--Displays the status as a coloured badge inside the table cell. -->
            <td><span class="badge '.$status_class.' p-2">'.$status.'</span></td>
            <td>

            <!-- Clicking view button submits the request ID to viewRequest.php so the user can see full details. The hidden input secretly sends the ID along with the click. -->
              <form action="viewRequest.php" method="POST" class="d-inline">
                <input type="hidden" name="checkid" value="'.$row["request_id"].'">
                <button type="submit" class="btn btn-sm btn-info" name="search"><i class="fas fa-eye"></i> </button>
              </form>';

            
              // Only shows the Edit button if the request hasn't been assigned yet. Once a technician is assigned, the user can no longer edit it.
              if($status == "Not Assigned"){
                echo ' <a href="editRequest.php?id='.$row["request_id"].'" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> </a>';                
              }

            //Only shows an Invoice button for completed jobs.
              if($status == "Completed") {
                echo '<form action="../admin/generate_invoice.php" method="POST" class="d-inline" target="_blank"> 
                    <input type="hidden" name="id" value="'. $row["request_id"] .'">
                    <button type="submit" class="btn btn-success btn-sm" title="Generate Invoice"><i class="fas fa-file-invoice"></i></button>
                </form>';
            } 
            
              
          echo '</td>
          </tr>';
        }
        echo '</tbody></table></div>';
    } else {

      //If the user has no requests at all, display a message instead of an empty table.
      echo '<div class="alert alert-info" role="alert">No requests submitted yet.</div>';
    }
  ?>
</div>

<?php
include('includes/footer.php'); 
?>