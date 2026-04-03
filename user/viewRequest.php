<?php
define('TITLE', 'Request Details');
define('PAGE', 'myRequests');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();

if($_SESSION['is_login']){
 $rEmail = $_SESSION['rEmail'];
} else {
 echo "<script> location.href='userLogin.php'; </script>";
}
?>


<div class="col-sm-6 mt-5">
    <h3 class="title text-center font-weight-bold text-dark" style="font-family: Arial, Helvetica, sans-serif;">
    <i class="fas fa-file-invoice"></i> REQUEST <span>DETAILS</span></h3>

 <?php
 if(isset($_REQUEST['checkid']) || isset($_REQUEST['id'])){
    $rid = intval(isset($_REQUEST['checkid']) ? $_REQUEST['checkid'] : $_REQUEST['id']);
    
    // First check assignwork_tb
    // $sql = "SELECT * FROM assignwork_tb WHERE request_id = $rid AND requester_email = '$rEmail'";


    // Join is used to connect with assignwork_tb with submitrequest_tb to get the request_date data from the submitrequest_tb
    $sql = "SELECT * FROM assignwork_tb a
            LEFT JOIN submitrequest_tb s ON a.request_id = s.request_id
            WHERE a.request_id = $rid";
            
    $result = $conn->query($sql);
    if($result && $result->num_rows > 0){
        $row = $result->fetch_assoc();
    } else {
        // Fallback to submitrequest_tb
        // $sql = "SELECT *, '-' as assign_date, '-' as assign_tech, 'Not Assigned' as status, '-' as deliveryDate FROM submitrequest_tb WHERE request_id = $rid AND requester_email = '$rEmail'";
        $sql = "SELECT *, 'Not Assigned Yet' as assign_date, 'Request Not Assigned to Technician' as assign_tech, 'Not Assigned' as status FROM submitrequest_tb WHERE request_id = $rid AND requester_email = '$rEmail'";

        $result = $conn->query($sql);
        if($result && $result->num_rows > 0){
            $row = $result->fetch_assoc();
        } else {
            echo '<div class="alert alert-danger mx-5 mt-4">Record not found or access denied.</div>';
            // Stop execution of the rest for this element
        }
    }
 }
 ?>

 <?php if(isset($row)) { ?>
 <div class="table-responsive-sm mt-4">
   <table class="table table-bordered table-striped">
      <tbody>
         <tr>
         <td class="font-weight-bold w-50" style="background-color: #f8f9fa;">Request ID</td>
         <td><?php if(isset($row['request_id'])) {echo $row['request_id']; }?></td>
         </tr>
         <tr>
         <td class="font-weight-bold" style="background-color: #f8f9fa;">Request Info</td>
         <td><?php if(isset($row['request_info'])) {echo $row['request_info']; }?></td>
         </tr>
         <tr>
         <td class="font-weight-bold" style="background-color: #f8f9fa;">Description</td>
         <td><?php if(isset($row['request_desc'])) {echo $row['request_desc']; }?></td>
         </tr>
         <tr>
         <td class="font-weight-bold" style="background-color: #f8f9fa;">Customer Name</td>
         <td><?php if(isset($row['requester_name'])) {echo $row['requester_name']; }?></td>
         </tr>
         <tr>
         <td class="font-weight-bold" style="background-color: #f8f9fa;">Address</td>
         <td>
            <?php 
            if(isset($row['requester_add1'])) {
                echo $row['requester_add1'];
            }
            ?>
         </td>
         </tr>
         
         <tr>
         <td class="font-weight-bold" style="background-color: #f8f9fa;">Email</td>
         <td><?php if(isset($row['requester_email'])) {echo $row['requester_email']; }?></td>
         </tr>
         <tr>
         <td class="font-weight-bold" style="background-color: #f8f9fa;">Mobile</td>
         <td><?php if(isset($row['requester_mobile'])) {echo $row['requester_mobile']; }?></td>
         </tr>
         <tr>
         <td class="font-weight-bold" style="background-color: #f8f9fa;">Requested Date</td>
         <td><?php if(isset($row['request_date'])) {echo $row['request_date']; }?></td>
         </tr>
         <tr>
         <td class="font-weight-bold" style="background-color: #f8f9fa;">Assigned Date</td>
         <td>
         <?php if(isset($row['assign_date'])) {echo $row['assign_date']; }?></td>
         </tr>
         <tr>
         <td class="font-weight-bold text-primary" style="background-color: #f8f9fa;">Technician Name</td>
         <td class="text-primary font-weight-bold"><?php if(isset($row['assign_tech'])) {echo $row['assign_tech']; }?></td>
         </tr>
         <tr>
         <td class="font-weight-bold" style="background-color: #f8f9fa;">Status</td>
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
         <tr>
         <!-- <td class="font-weight-bold text-success" style="background-color: #f8f9fa;">Delivery Date</td> -->
         <!-- <td class="text-success font-weight-bold"><?php /*if(isset($row['deliveryDate'])) {echo $row['deliveryDate']; }*/?></td> -->
         </tr>
         <?php /* if(isset($row['status']) && $row['status'] == "Completed") { ?>
         
         <?php } */?>
      </tbody>
   </table>
 </div>

 <div class="text-center mt-4 d-print-none">
    <?php if(isset($row['status']) && $row['status'] == "Completed") { ?>
      <button class="btn btn-dark btn-md mr-3 shadow-sm" onClick='window.print()'><i class="fas fa-print"></i> Print Receipt</button>
    <?php } else { ?>
      <button class="btn btn-info btn-md mr-3 shadow-sm" onClick='window.print()'><i class="fas fa-print"></i> Print Details</button>
    <?php } ?>
    <a href="myRequests.php" class="btn btn-outline-secondary btn-md shadow-sm"><i class="far fa-times-circle"></i> Close</a>
 </div>
 <?php } ?>
</div>

<?php
include('includes/footer.php'); 
?>
