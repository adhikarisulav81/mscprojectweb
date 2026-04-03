<?php
define('TITLE', 'Work Details');
define('PAGE', 'technicianWork');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();

 if(isset($_SESSION['is_techlogin'])){
  $tEmail = $_SESSION['tEmail'];
 } else {
  echo "<script> location.href='technicianLogin.php'; </script>";
 }

 // Get technician name
 $sql = "SELECT empName FROM technician_tb WHERE empEmail='$tEmail'";
 $result = $conn->query($sql);
 if($result->num_rows == 1){
  $row = $result->fetch_assoc();
  $tName = $row['empName'];
 }
?>


<div class="col-sm-6 ">
    <h3 class="title text-center font-weight-bold text-dark mt-5" style="font-family: Arial, Helvetica, sans-serif  ;">
    <i class="fas fa-briefcase"></i> WORK ORDER <span>DETAILS</span></h3>

 <?php
 if(isset($_REQUEST['view'])){
    $rid = $_REQUEST['id'];
    // get data from assignwork_tb
    $sql = "SELECT * FROM assignwork_tb 
            WHERE request_id = $rid";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    
    // Verify this work is assigned to the logged-in technician
    if($row['assign_tech'] != $tName){
      echo '<div class="alert alert-danger" role="alert">You are not authorized to view this work order.</div>';
      echo '<a href="technicianWork.php" class="btn btn-secondary"><i class="far fa-times-circle"></i> Back</a>';
      include('includes/footer.php'); 
      exit;
    }
 }
 ?>
<div class="table-responsive-sm">
   <table class="table table-bordered">
      <tbody>
         <tr>
         <td>Request ID</td>
         <td>
         <?php if(isset($row['request_id'])) {echo $row['request_id']; }?>
         </td>
         </tr>
         <tr>
         <td>Request Info</td>
         <td>
         <?php if(isset($row['request_info'])) {echo $row['request_info']; }?>
         </td>
         </tr>
         <tr>
         <td>Request Description</td>
         <td>
         <?php if(isset($row['request_desc'])) {echo $row['request_desc']; }?>
         </td>
         </tr>
         
         <tr>
         <td>Customer Name</td>
         <td>
         <?php if(isset($row['requester_name'])) {echo $row['requester_name']; }?>
         </td>
         </tr>
         <tr>
         <td>Address</td>
         <td>
         <?php if(isset($row['requester_add1'])) {echo $row['requester_add1']; }?>
         </td>
         </tr>
         
         <tr>
         <td>Email</td>
         <td>
         <?php if(isset($row['requester_email'])) {echo $row['requester_email']; }?>
         </td>
         </tr>
         <tr>
         <td>Mobile</td>
         <td>
         <?php if(isset($row['requester_mobile'])) {echo $row['requester_mobile']; }?>
         </td>
         </tr>
         <tr>
         <td>Assigned Date</td>
         <td>
         <?php if(isset($row['assign_date'])) {echo $row['assign_date']; }?>
         </td>
         </tr>
         <tr>
         <td>Technician Name</td>
         <td>
         <?php if(isset($row['assign_tech'])) {echo $row['assign_tech']; }?>
         </td>
         </tr>
         <tr>
         <td>Device Status</td>
         <td>
         <?php 
            if(isset($row['status'])) {
                $status_class = "badge-info";
                if($row['status'] == "Completed") $status_class = "badge-success";
                elseif($row['status'] == "Work Started") $status_class = "badge-secondary";
                elseif($row['status'] == "Assigned" || $row['status'] == "Pending") $status_class = "badge-dark";
                elseif($row['status'] == "Not Assigned") $status_class = "badge-warning";
                elseif($row['status'] == "Rejected") $status_class = "badge-danger";
                echo '<span class="badge '.$status_class.' p-2">'.$row['status'].'</span>'; 
            }
            ?>
        
         </td>
         </tr>
         <!-- <tr>
         <td>Delivery Date</td>
         <td>
         <?php/* if(isset($row['deliveryDate'])) {echo $row['deliveryDate']; }*/?>
         </td>
         </tr> -->
         
      </tbody>
   </table>
 </div>

 <div class="text-center">
    <button type="submit" class="btn btn-info d-print-none" onClick='window.print()'><i class="fas fa-print"></i> Print</button>
    <a href="technicianWork.php" type="submit" class="btn btn-secondary d-print-none"><i class="far fa-times-circle"></i> Close</a>
 </div>
</div>

<?php
include('includes/footer.php'); 
?>
