<?php
define('TITLE', 'Assigned Work');
define('PAGE', 'viewassignwork');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();

 if(isset($_SESSION['is_adminlogin'])){
  $aEmail = $_SESSION['aEmail'];
 } else {
  echo "<script> location.href='adminLogin.php'; </script>";
  exit();
 }
?>


<div class="col-sm-6 ">
    <h3 class="title text-center font-weight-bold text-dark mt-5" style="font-family: Arial, Helvetica, sans-serif  ;">
    <i class="fas fa-briefcase"></i> ASSIGNED <span>WORK DETAILS</span></h3>

 <?php

//  Checks if the page was reached with either a view button click or an id value in the URL/form. 
 if(isset($_REQUEST['view']) || isset($_REQUEST['id'])){
   $rid = $_REQUEST['id'];
   
   // Find the reqeuest with this ID in the assignwork_tb." This table contains requests that have been assigned to a technician.
   $sql = "SELECT * FROM assignwork_tb WHERE request_id = $rid";
   $result = $conn->query($sql);
   
   if($result && $result->num_rows > 0){ //($result &&) makes sure the query didn't fail
       $row = $result->fetch_assoc();
       
       // assignwork_tb has assign_date but no request_date. Runs this SQL qiery to get just the request_date from the submitrequest_tb 
       $sql2 = "SELECT request_date FROM submitrequest_tb WHERE request_id = $rid";
       $result2 = $conn->query($sql2);
       if($result2 && $result2->num_rows > 0){
           $row2 = $result2->fetch_assoc();
           $row['request_date'] = $row2['request_date']; // merge into $row
       } else {
           $row['request_date'] = 'N/A';
       }

   } else {
      // If the request wasn't found in assignwork_tb, it means it hasn't been assigned yet. Falls back to fetching from submitrequest_tb.
      // Since there's no assign date yet, manually sets $row['assign_date'] = 'Not Assigned Yet'
        $sql = "SELECT * FROM submitrequest_tb WHERE request_id = $rid";
       $result = $conn->query($sql);
       if($result && $result->num_rows > 0){
           $row = $result->fetch_assoc();
           $row['assign_date'] = 'Not Assigned Yet'; 
           $row['assign_tech'] = 'Request Not Assigned to Technician';
           $row['status'] = $row['status'] ?? 'Not Assigned';
       } else {
         //  If the request ID doesn't exist in either table, shows an error message
           echo '<div class="alert alert-danger mx-5 mt-4">Record not found or access denied.</div>';
       }
   }
}
 ?>
<div class="table-responsive-sm">
   <table class="table table-bordered">
      <tbody>
         <tr>
         <td>Request ID</td>
         <td>
         <!-- isset() checks the value exists before printing it, preventing PHP warnings if a field is missing -->
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
         <td>Name</td>
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
         <td>Requested Date</td>
         <td>
         <?php if(isset($row['request_date'])) {echo $row['request_date']; }?>
         
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
         <?php /*if(isset($row['deliveryDate'])) {echo $row['deliveryDate']; }*/?>
         </td>
         </tr> -->
         
      </tbody>
   </table>
 </div>

 <div class="text-center">
    <button type="submit" class="btn btn-info d-print-none" onClick='window.print()'><i class="fas fa-print"></i> Print</button>
    <a href="request.php" type="submit" class="btn btn-secondary d-print-none"><i class="far fa-times-circle"></i> Close</a>
 </div>
</div>

<?php
include('includes/footer.php'); 
?>