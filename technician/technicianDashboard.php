<?php
define('TITLE', 'Technician Dashboard');
define('PAGE', 'technicianDashboard');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();

 if(isset($_SESSION['is_techlogin'])){
  $tEmail = $_SESSION['tEmail'];
 } else {
  echo "<script> location.href='technicianLogin.php'; </script>";
  exit();
 }

 // Get technician details
 $sql = "SELECT techName FROM technician_tb WHERE techEmail='$tEmail'";
 $result = $conn->query($sql);
 if($result->num_rows == 1){
  $row = $result->fetch_assoc();
  $tName = $row['techName'];
 }

 // Count assigned work for this technician
 $sql = "SELECT * FROM assignwork_tb WHERE assign_tech='$tName'";
 $result = $conn->query($sql);
 $assignedwork = $result->num_rows;








// Total All Request
$total_sql = "SELECT COUNT(status) as total FROM assignwork_tb WHERE assign_tech='$tName'";
$total_res = $conn->query($total_sql);
$total = ($total_res && $r = $total_res->fetch_assoc()) ? $r['total'] : 0;


 // Total Completed Services
 $completed_sql = "SELECT COUNT(*) as total_completed FROM assignwork_tb WHERE assign_tech='$tName' AND status = 'Completed'";
 $completed_res = $conn->query($completed_sql);
 $total_completed = ($completed_res && $r = $completed_res->fetch_assoc()) ? $r['total_completed'] : 0;


 // Total Work Started
 $progress_sql = "SELECT COUNT(*) as total_started FROM assignwork_tb WHERE assign_tech='$tName' AND status = 'Work Started'";
 $progress_res = $conn->query($progress_sql);
 $total_started = ($progress_res && $r = $progress_res->fetch_assoc()) ? $r['total_started'] : 0;

 // Total Assigned
 $pending_sql = "SELECT COUNT(*) as total_assigned FROM assignwork_tb WHERE status = 'Assigned' AND assign_tech='$tName'";
 $pending_res = $conn->query($pending_sql);
 $total_assigned = ($pending_res && $r = $pending_res->fetch_assoc()) ? $r['total_assigned'] : 0;



?>

<!-- <img class="wave" src="../images/wave.png"> -->

<div class="col-sm-9 col-md-10">
  <!-- <h3 class="title text-center font-weight-bold text-dark mb-3 mt-5" style="font-family: Arial, Helvetica, sans-serif;"><i class="fas fa-tachometer-alt"></i> TECHNICIAN <span>DASHBOARD</span></h3> -->
  
  <h4 class="text-center text-white bg-dark mb-4 mt-4" style="padding: 7px; border-radius: 5px;">
    Welcome <?php echo $tName ?> !
  </h4>

  <div class="row text-center">
    <div class="col-xl-3 col-md-6 mb-3">
      <div class="card border-left-success shadow h-100" style="border-left: 4px solid #198754 !important;">
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
        <div class="h4 font-weight-bold text-success">
          <a href="technicianWork.php" class="btn btn-sm btn-success"><i class="fas fa-eye"></i> View</a>
        </div>
        </center>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
      <div class="card border-left-secondary shadow h-100" style="border-left: 4px solid #6C757D !important;">
        <div class="card-body py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Work Started</div>
              <div class="h4 mb-0 font-weight-bold text-secondary"><?php echo $total_started; ?></div>
            </div>
            <div><i class="fas fa-play fa-2x text-secondary"></i></div>
          </div>
        </div>
        <center>
        <div class="h4 font-weight-bold text-dark">
          <a href="technicianWork.php" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i> View</a>
        </div>
        </center>
      </div>  
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
      <div class="card border-left-primary shadow h-100" style="border-left: 4px solid #0d6efd !important;">
        <div class="card-body py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Request</div>
              <div class="h4 mb-0 font-weight-bold text-primary"><?php echo $total; ?></div>
            </div>
            <div><i class="fas fa-paper-plane fa-2x text-primary"></i></div>
          </div>
        </div>
        <center>
        <div class="h4 font-weight-bold text-dark">
          <a href="technicianWork.php" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i> View</a>
        </div>
        </center>
      </div>  
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
      <div class="card border-left-dark shadow h-100" style="border-left: 4px solid #212529 !important;">
        <div class="card-body py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Total Assigned</div>
              <div class="h4 mb-0 font-weight-bold text-dark"><?php echo $total_assigned; ?></div>
            </div>
            <div><i class="fas fa-link fa-2x text-dark"></i></div>
          </div>
        </div>
        <center>
        <div class="h4 font-weight-bold text-dark">
          <a href="technicianWork.php" class="btn btn-sm btn-dark"><i class="fas fa-eye"></i> View</a>
        </div>
        </center>
      </div>  
    </div>
  </div>

















<?php
include('includes/footer.php'); 
?>
