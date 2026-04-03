<?php
define('TITLE', 'Add New Technician');
define('PAGE', 'insertemp');
include('includes/header.php'); 
include('../dbConnection.php');
include('../emailConfig.php');

session_start();

 if(isset($_SESSION['is_adminlogin'])){
  $aEmail = $_SESSION['aEmail'];
 } else {
  echo "<script> location.href='adminLogin.php'; </script>";
 }
if(isset($_REQUEST['empsubmit'])){
 // Checking for Empty Fields
 if(($_REQUEST['empName'] == "") || ($_REQUEST['empMobile'] == "") || ($_REQUEST['empEmail'] == "")){
  // msg displayed if required field missing
  $msg = '<div class="alert alert-warning col-sm-6 mt-2" role="alert"> Fill All Fields </div>';
 } else {
   // Assigning User Values to Variable
   $eName = $_REQUEST['empName'];
   $eMobile = $_REQUEST['empMobile'];
   $eEmail = $_REQUEST['empEmail'];
   
   // Generate a random password for the technician
   $randomPassword = 'Tech' . rand(1000, 9999);
   $hashedPassword = md5($randomPassword);
   
   $sql = "INSERT INTO technician_tb (empName, empMobile, empEmail, password) VALUES ('$eName', '$eMobile', '$eEmail', '$hashedPassword')";
   if($conn->query($sql) == TRUE){
     // Send email to the technician with login credentials
     if(sendTechnicianCredentials($eName, $eEmail, $randomPassword)){

       $msg = '<div class="alert alert-warning col-sm-6 mt-2" role="alert"> Technician Added. Password: ' . $randomPassword . '</div>';
      } else {
        $msg = '<div class="alert alert-warning col-sm-6 mt-2" role="alert"> Technician Added but email could not be sent. Password: ' . $randomPassword . '</div>';
      }

     // Redirect after 3 seconds
     echo "<script>setTimeout(function(){ location.href='technician.php'; }, 5000);</script>";
   } else {
    // below msg display on form submit failed
    $msg = '<div class="alert alert-danger col-sm-6 mt-2" role="alert"> Unable to Add </div>';
   }
 }
}
?>

<div class="col-sm-6">
  <h3 class="title text-center font-weight-bold text-dark mb-5 mt-5" style="font-family: Arial, Helvetica, sans-serif;"><i class="fas fa-chalkboard-teacher"></i> ADD <span>NEW TECHNICIAN</span></h3>
  <form action="" method="POST">
    <div class="form-group">
      <label for="empName"><i class="fas fa-user-tie"></i> Name</label>
      <input type="text" class="form-control" placeholder="Enter Name" id="empName" name="empName">
    </div>
    
    <div class="form-group">
      <label for="empMobile"><i class="fas fa-mobile"></i> Mobile</label>
      <input type="text" class="form-control" placeholder="Enter Mobile" id="empMobile" name="empMobile" pattern="[9][0-9]{9}" title="Start with 9 and must contain 10 digits" onkeypress="isInputNumber(event)">
    </div>
    <div class="form-group">
      <label for="empEmail"><i class="far fa-envelope"></i> Email</label>
      <input type="email" class="form-control" placeholder="Enter Email" id="empEmail" name="empEmail">
    </div>
    <div class="text-center">
      <button type="submit" class="btn btn-success" id="empsubmit" name="empsubmit"><i class="fas fa-user-plus"></i> Add</button>
      <a href="technician.php" class="btn btn-danger"><i class="far fa-times-circle"></i> Close</a>
    </div>
    <?php if(isset($msg)) {echo $msg; } ?>
  </form>
</div>

<!-- Only Number for input fields -->
<script>
  function isInputNumber(evt) {
    var ch = String.fromCharCode(evt.which);
    if (!(/[0-9]/.test(ch))) {
      evt.preventDefault();
    }
  }
</script>

<?php
include('includes/footer.php'); 
?>