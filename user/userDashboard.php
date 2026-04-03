<?php
define('TITLE', 'User Dashboard');
define('PAGE', 'userDashboard');
include('includes/header.php'); 
include('../dbConnection.php');

session_start();
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

 // Priority filter
 $priority_filter = isset($_GET['priority']) ? $_GET['priority'] : 'All';
?>
<!-- <img class="wave d-print-none" src="../images/wave.png"> -->

<div class="col-sm-9 col-md-10 mt-4">
  <h3 class="title text-center font-weight-bold text-dark mb-4 mt-2" style="font-family: Arial, Helvetica, sans-serif;">
    <i class="fas fa-chart-bar"></i> USER <span>DASHBOARD</span>
  </h3>
  <h4 class="text-center text-white bg-dark mb-4" style="padding: 7px; border-radius: 5px;">
    Welcome <?php echo $rName ?> !
  </h4>
  <?php
  // ============================================
  // SECTION 1: Summary Dashboard Cards
  // ============================================
  
  // Total Completed Services
  $completed_sql = "SELECT COUNT(*) as total_completed FROM assignwork_tb WHERE requester_email='$rEmail' AND status = 'Completed'";
  $completed_res = $conn->query($completed_sql);
  $total_completed = ($completed_res && $r = $completed_res->fetch_assoc()) ? $r['total_completed'] : 0;

  // Total Rejected
  $rejected_sql = "SELECT COUNT(*) as total_rejected FROM assignwork_tb WHERE requester_email='$rEmail' AND status = 'Rejected'";
  $rejected_res = $conn->query($rejected_sql);
  $total_rejected = ($rejected_res && $r = $rejected_res->fetch_assoc()) ? $r['total_rejected'] : 0;

  // Total In Progress
  $progress_sql = "SELECT COUNT(*) as total_started FROM assignwork_tb WHERE requester_email='$rEmail' AND status = 'Work Started'";
  $progress_res = $conn->query($progress_sql);
  $total_started = ($progress_res && $r = $progress_res->fetch_assoc()) ? $r['total_started'] : 0;

  // Total Pending (Assigned/Pending)
  $pending_sql = "SELECT COUNT(*) as total_assigned FROM assignwork_tb WHERE requester_email='$rEmail' AND status = 'Assigned'";
  $pending_res = $conn->query($pending_sql);
  $total_assigned = ($pending_res && $r = $pending_res->fetch_assoc()) ? $r['total_assigned'] : 0;

  // Total Unassigned
  $unassigned_sql = "SELECT COUNT(*) as total_unassigned FROM submitrequest_tb s LEFT JOIN assignwork_tb a ON s.request_id = a.request_id WHERE s.requester_email = '$rEmail' AND a.request_id IS NULL";
  $unassigned_res = $conn->query($unassigned_sql);
  $total_unassigned = ($unassigned_res && $r = $unassigned_res->fetch_assoc()) ? $r['total_unassigned'] : 0;

  // Total High
  $high_sql = "SELECT COUNT(*) as total_high FROM submitrequest_tb WHERE requester_email='$rEmail' AND priority IN ('High')";
  $high_res = $conn->query($high_sql);
  $total_high = ($high_res && $r = $high_res->fetch_assoc()) ? $r['total_high'] : 0;

  // Total Normal
  $normal_sql = "SELECT COUNT(*) as total_normal FROM submitrequest_tb WHERE requester_email='$rEmail' AND priority IN ('Normal')";
  $normal_res = $conn->query($normal_sql);
  $total_normal = ($normal_res && $r = $normal_res->fetch_assoc()) ? $r['total_normal'] : 0;

  // Total Sales Completed Requests
  $sales_sql = "SELECT COUNT(*) as total_sales, COALESCE(SUM(service_price), 0) as total_revenue FROM assignwork_tb WHERE requester_email='$rEmail' AND status = 'Completed'";
  $sales_res = $conn->query($sales_sql);
  $sales_data = ($sales_res) ? $sales_res->fetch_assoc() : ['total_sales' => 0, 'total_revenue' => 0];

  ?>

  <!-- Summary Dashboard Cards -->
  <div class="row mx-3 mb-4 d-print-none">
    <div class="col-xl-3 col-md-6 mb-3">
      <div class="card border-left-success shadow h-100" style="border-left: 4px solid #1cc88a !important;">
        <div class="card-body py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Completed Requests</div>
              <div class="h4 mb-0 font-weight-bold text-dark"><?php echo $total_completed; ?></div>



            </div>
            <div><i class="fas fa-check-circle fa-2x text-success"></i></div>
          </div>
        </div>

        <center>
                  <div class="h4 font-weight-bold text-dark">
                    <a href="myRequests.php" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> View</a>
                  </div>
                  </center>

      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
      <div class="card border-left-info shadow h-100" style="border-left: 4px solid #36b9cc !important;">
        <div class="card-body py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Work Started</div>
              <div class="h4 mb-0 font-weight-bold text-dark"><?php echo $total_started; ?></div>




            </div>

            
            <div><i class="fas fa-spinner fa-2x text-info"></i></div>
            
          </div>
        </div>
        <center>
                  <div class="h4 font-weight-bold text-dark">
                  <a href="myRequests.php" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> View</a>
                  </div>
                  </center>
      </div>
      
      
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
      <div class="card border-left-warning shadow h-100" style="border-left: 4px solid #f6c23e !important;">
        <div class="card-body py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Not Assigned</div>
              <div class="h4 mb-0 font-weight-bold text-dark"><?php echo $total_unassigned; ?></div>
            </div>
            <div><i class="fas fa-clock fa-2x text-warning"></i></div>
          </div>
        </div>


        <center>
                  <div class="h4 font-weight-bold text-dark">
                  <a href="myRequests.php" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> View</a>
                  </div>
                  </center>

      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
      <div class="card border-left-danger shadow h-100" style="border-left: 4px solid #f6c23e !important;">
        <div class="card-body py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">High Requests</div>
              <div class="h4 mb-0 font-weight-bold text-dark"><?php echo $total_high; ?></div>
            </div>
            <div><i class="fas fa-clock fa-2x text-danger"></i></div>
          </div>
        </div>

        
        <center>
                  <div class="h4 font-weight-bold text-dark">
                  <a href="myRequests.php" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> View</a>
                  </div>
                  </center>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
      <div class="card border-left-success shadow h-100" style="border-left: 4px solid #f6c23e !important;">
        <div class="card-body py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Normal Requests</div>
              <div class="h4 mb-0 font-weight-bold text-dark"><?php echo $total_normal; ?></div>
            </div>
            <div><i class="fas fa-clock fa-2x text-success"></i></div>
          </div>
        </div>

        
        <center>
                  <div class="h4 font-weight-bold text-dark">
                  <a href="myRequests.php" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> View</a>
                  </div>
                  </center>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
      <div class="card border-left-dark shadow h-100" style="border-left: 4px solid #212529 !important;">
        <div class="card-body py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Assigned</div>
              <div class="h4 mb-0 font-weight-bold text-dark"><?php echo $total_assigned; ?></div>
            </div>
            <div><i class="fas fa-clock fa-2x text-dark"></i></div>
          </div>
        </div>

        
        <center>
                  <div class="h4 font-weight-bold text-dark">
                  <a href="myRequests.php" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> View</a>
                  </div>
                  </center>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
      <div class="card border-left-danger shadow h-100" style="border-left: 4px solid #e74a3b !important;">
        <div class="card-body py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Rejected</div>
              <div class="h4 mb-0 font-weight-bold text-dark"><?php echo $total_rejected; ?></div>
            </div>
            <div><i class="fas fa-times-circle fa-2x text-danger"></i></div>
          </div>
        </div>
         
      <center>
                  <div class="h4 font-weight-bold text-dark">
                  <a href="myRequests.php" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> View</a>
                  </div>
                  </center>
      </div>

     
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
      <div class="card border-left-danger shadow h-100" style="border-left: 4px solid #e74a3b !important;">
      <div class="card-body py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Sales </div>
              <div class="h4 mb-0 font-weight-bold text-dark">Rs. <?php echo number_format($sales_data['total_revenue'], 2); ?></div>
              <small class="text-muted"><?php echo $sales_data['total_sales']; ?> transactions</small>
            </div>
            <div><i class="fas fa-rupee-sign fa-2x text-primary"></i></div>
          </div>
        </div>
         
      <center>
                  <div class="h4 font-weight-bold text-dark">
                  <a href="#sales_report" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> View</a>
                  </div>
                  </center>
      </div>

     
    </div>
  </div>

  <!-- ============================================ -->
  <!-- SECTION 2: Completed Services Report (assignwork_tb) -->
  <!-- ============================================ -->
  <div class="card shadow mb-4 mx-3" id="sales_report">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between d-print-none">
      <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-check-circle"></i> Completed Services Report</h6>
    </div>
    <div class="card-body">
      <!-- Date Filter -->
      <form action="" method="POST" class="form-inline d-flex justify-content-center mb-3 d-print-none">
        <div class="form-group mb-2 mr-sm-3">
          <label for="startdate" class="mr-2 font-weight-bold"><i class="fas fa-calendar-alt"></i> From:</label>
          <input type="date" class="form-control" id="startdate" name="startdate" value="<?php if(isset($_REQUEST['startdate'])) { echo $_REQUEST['startdate']; } ?>" required>
        </div>
        <div class="form-group mb-2 mr-sm-3">
          <label for="enddate" class="mr-2 font-weight-bold"><i class="fas fa-calendar-check"></i>   To:</label>
          <input type="date" class="form-control" id="enddate" name="enddate" value="<?php if(isset($_REQUEST['enddate'])) { echo $_REQUEST['enddate']; } ?>" required>
        </div>
        <button type="submit" class="btn btn-primary mb-2" name="searchsubmit"><i class="fas fa-search"></i> Search</button>
        <?php if(isset($_REQUEST['searchsubmit'])) { ?>
            <a href="soldproductreport.php" class="btn btn-secondary mb-2 ml-2"><i class="fas fa-undo"></i> Reset</a>
        <?php } ?>
      </form>

      <?php
        if(isset($_REQUEST['searchsubmit'])){
            $startdate = $_REQUEST['startdate'];
            $enddate = $_REQUEST['enddate'];
            $prod_sql = "SELECT * FROM assignwork_tb WHERE status = 'Completed' AND assign_date BETWEEN '$startdate' AND '$enddate' ORDER BY assign_date DESC";
        } else {
            $prod_sql = "SELECT * FROM assignwork_tb WHERE status = 'Completed' ORDER BY assign_date DESC";
        }
        
        $prod_result = $conn->query($prod_sql);
        if($prod_result && $prod_result->num_rows > 0){
      ?>
      <div class="table-responsive">
        <table id="dataTableID" class="table table-hover table-bordered w-100">
            <thead class="thead-light">
            <tr>
                <th>Req ID</th>
                <th>Customer</th>
                <th style="max-width: 150px;">Service Info</th>
                <th>Technician</th>
                <th>Assigned Date</th>
                <th>Base Price</th>
                <th>Priority Charge</th>
                <th>Total Cost</th>
            </tr>
            </thead>
            <tbody>
            <?php 
            $grandTotal = 0;
            while($row = $prod_result->fetch_assoc()){
                $total_price = floatval(isset($row["service_price"]) ? $row["service_price"] : 0);
                $priority = isset($row["priority"]) ? $row["priority"] : 'Normal';
                
                if($priority == 'High'){
                    $base_price = $total_price / 1.10;
                    $priority_charge = $total_price - $base_price;
                    $p_badge = '<br><span class="badge badge-danger">High (+10%)</span>';
                } else {
                    $base_price = $total_price;
                    $priority_charge = 0;
                    $p_badge = '';
                }

                $grandTotal += $total_price;
                echo '<tr>
                    <th scope="row">'.$row["request_id"].'</th>
                    <td>'.$row["requester_name"].'</td>
                    <td style="max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="'.htmlspecialchars($row["request_info"]).'">'.$row["request_info"].$p_badge.'</td>
                    <td>'.$row["assign_tech"].'</td>
                    <td>'.$row["assign_date"].'</td>
                    <td>Rs. '.number_format($base_price, 2).'</td>
                    <td class="'.($priority_charge > 0 ? 'text-danger font-weight-bold' : 'text-muted').'">Rs. '.number_format($priority_charge, 2).'</td>
                    <td><strong>Rs. '.number_format($total_price, 2).'</strong></td>
                </tr>';
            }
            ?>
            </tbody>
            <tfoot>
                <tr class="bg-light font-weight-bold">
                    <td colspan="7" class="text-right align-middle">Grand Total Revenue:</td>
                    <td class="text-success h5 mb-0">Rs. <?php echo number_format($grandTotal, 2); ?></td>
                </tr>
            </tfoot>
        </table>
      </div>
      <?php } else { ?>
        <div class="alert alert-warning text-center"><i class="fas fa-exclamation-triangle"></i> No completed service records found<?php echo isset($_REQUEST['searchsubmit']) ? ' for the selected date range' : ''; ?>.</div>
      <?php } ?>
    </div>
    <!-- Print Button -->
  <div class="text-center mb-3 d-print-none">
    <button class="btn btn-success btn-md shadow" onClick="window.print()"><i class="fas fa-print"></i> Print Full Report</button>
  </div>
  </div>
  

<?php
include('includes/footer.php'); 
?>






