<?php
define('TITLE', 'Add New Technician');
define('PAGE', 'inserttech');
include('includes/header.php'); 
include('../dbConnection.php');
include('../emailConfig.php');

session_start();

 if(isset($_SESSION['is_adminlogin'])){
  $aEmail = $_SESSION['aEmail'];
 } else {
  echo "<script> location.href='adminLogin.php'; </script>";
  exit();
 }
if(isset($_REQUEST['techsubmit'])){
 // Checking for Empty Fields
 if(($_REQUEST['techName'] == "") || ($_REQUEST['techMobile'] == "") || ($_REQUEST['techEmail'] == "")){
  // msg displayed if required field missing
  $msg = '<div class="alert alert-warning col-sm-6 mt-2" role="alert"> Fill All Fields </div>';
 } else {
   // Assigning User Values to Variable
   $eName = $_REQUEST['techName'];
   $eMobile = $_REQUEST['techMobile'];
   $eEmail = $_REQUEST['techEmail'];

   //Duplicate email check before inserting
   $sql = "SELECT techid FROM technician_tb WHERE techEmail='".$_POST['techEmail']."'";
   $result = $conn->query($sql);

   if($result->num_rows > 0){
     // Email already exists, block the insert
     $msg = '<div class="alert alert-danger col-sm-6 mt-2" role="alert">
        <i class="fas fa-exclamation-circle"></i> A technician with this email already exists. Please use a different email.
      </div>';
     $result->close();
   } else {
    $result->close();
   
   // Generate a random password for the technician
   $randomPassword = 'Tech' . rand(1000, 9999);
   $hashedPassword = md5($randomPassword);
   
   $sql = "INSERT INTO technician_tb (techName, techMobile, techEmail, password) VALUES ('$eName', '$eMobile', '$eEmail', '$hashedPassword')";
   if($conn->query($sql) == TRUE){
     // Send email to the technician with login credentials
     if(sendTechnicianCredentials($eName, $eEmail, $randomPassword)){

      //  $msg = '<div class="alert alert-success col-sm-6 mt-2" role="alert"> Technician Added. Password: ' . $randomPassword . '</div>';
       $msg = '<div class="alert alert-success col-sm-6 mt-2" role="alert"> New Technician Added Sucessfully and Credentials Sent to their Email.</div>';

      } else {
        $msg = '<div class="alert alert-warning col-sm-6 mt-2" role="alert"> New Technician Added but email could not be sent. Password: ' . $randomPassword . '</div>';
      }

     // Redirect after 3 seconds
     echo "<script>setTimeout(function(){ location.href='technician.php'; }, 3000);</script>";
   } else {
    // below msg display on form submit failed
    $msg = '<div class="alert alert-danger col-sm-6 mt-2" role="alert"> Unable to Add </div>';
   }
 }

}
}
?>

<div class="col-sm-6">
<h4 class="title font-weight-bold text-center text-white bg-dark mb-3 mt-4" style="padding: 7px; border-radius: 5px;">
ADD NEW TECHNICIAN
  </h4>  
    <form action="" method="POST">
    <div class="form-group">
      <label for="techName" class="font-weight-bold"><i class="fas fa-user-tie"></i> Name</label>
      <input type="text" class="form-control" placeholder="Enter Name" id="techName" name="techName">
    </div>
    
    <div class="form-group">
      <label for="techMobile" class="font-weight-bold"><i class="fas fa-mobile"></i> Mobile</label>


      <input type="text" class="form-control" id="techMobile"
                       placeholder="Enter Mobile Number" pattern="^07\d{9}$" title="e.g. 07123456789" name="techMobile" required>
    </div>
    <div class="form-group">
      <label for="techEmail" class="font-weight-bold"><i class="fas fa-envelope"></i> Email</label>
      <input type="email" class="form-control" placeholder="Enter Email" id="techEmail" name="techEmail">
    </div>
    <div class="text-center">
      <button type="submit" class="btn btn-success" id="techsubmit" name="techsubmit"><i class="fas fa-user-plus"></i> Add</button>
      <a href="technician.php" class="btn btn-danger"><i class="far fa-times-circle"></i> Close</a>
    </div>
    <?php if(isset($msg)) {echo $msg; } ?>
  </form>
</div>


<?php
include('includes/footer.php'); 
?>