<?php
define('TITLE', 'Submit Request Success');
define('PAGE', 'submitRequestSuccess');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();
if($_SESSION['is_login']){
 $rEmail = $_SESSION['rEmail'];
} else {
 echo "<script> location.href='userLogin.php'; </script>";
}
$sql = "SELECT * FROM submitrequest_tb WHERE request_id = {$_SESSION['myid']}";
$result = $conn->query($sql);

if($result->num_rows == 1){
 $row = $result->fetch_assoc();

 echo "


 <div class='table-responsive-sm ml-5'>
  <h3 class='title text-center font-weight-bold text-dark mb-5 mt-5' style='font-family: Arial, Helvetica, sans-serif;'>
  <i class='far fa-share-square'></i> REQUEST <span>SUBMITTED</span></h3>
  <div class='alert alert-dark col-sm-6'>Remember Your <strong class='text-danger'>Request ID</strong> to Track your Request Status<a href='checkStatus.php' class alert> <i>Click here to Track your Request</i></a></div>

  <form class='' action='' method='POST' enctype='multipart/form-data'>
  <table class='table'>
  <tbody>
  <tr>
  <th>Request ID</th>
  <td>".$row['request_id']."</td>
  </tr>
  
   <tr>
     <th>Name</th>
     <td>".$row['requester_name']."</td>
   </tr>
   <tr>
   <th>Email ID</th>
   <td>".$row['requester_email']."</td>
  </tr>
   <tr>
    <th>Request Info</th>
    <td>".$row['request_info']."</td>
   </tr>
   <tr>
    <th>Request Description</th>
    <td>".$row['request_desc']."</td>
   </tr>

  </tbody>
 </table> 
        <a class='btn btn-info mt-5' name='Print' onClick='window.print()''><i class='fas fa-print'></i> Print</a>
        <a href='submitRequest.php' class='btn btn-secondary mt-5' name='Back''><i class='fas fa-backward'></i> Back</a>
 </div>
 ";
 
} else {
  echo "Failed";
}
?>


<?php
include('includes/footer.php'); 
$conn->close();
?>