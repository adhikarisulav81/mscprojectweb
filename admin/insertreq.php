<?php
define('TITLE', 'Add New User');
define('PAGE', 'insertreq');
include('includes/header.php'); 
include('../dbConnection.php');
include('../emailConfig.php');
session_start();

 if(isset($_SESSION['is_adminlogin'])){
  $aEmail = $_SESSION['aEmail'];
 } else {
  echo "<script> location.href='adminLogin.php'; </script>";
 }
if(isset($_REQUEST['reqsubmit'])){
 // Checking for Empty Fields
 if(($_REQUEST['name'] == "") || ($_REQUEST['email'] == "") || ($_REQUEST['password'] == "")){
  // msg displayed if required field missing
  $msg = '<div class="alert alert-warning col-sm-6 mt-2" role="alert"> Fill All Fileds </div>';
 } else {
   // Assigning User Values to Variable
   $rname = $_REQUEST['name'];
   $rEmail = $_REQUEST['email'];
   $rPassword = md5($_POST['password']);


   //Duplicate email check before inserting
$sql = "SELECT id FROM userlogin_tb WHERE email='".$_POST['email']."'";
$result = $conn->query($sql);

if($result->num_rows > 0){
  // Email already exists — block the insert
  $msg = '<div class="alert alert-danger col-sm-6 mt-2" role="alert">
     <i class="fas fa-exclamation-circle"></i> An user with this email already exists. Please use a different email.
   </div>';
  $result->close();
} else {
 $result->close();


   $sql = "INSERT INTO userlogin_tb (name, email, password) VALUES ('$rname', '$rEmail', '$rPassword')";
   if($conn->query($sql) == TRUE){
    // Send welcome email with login credentials to the new user
     sendNewUserAccountEmail($rname, $rEmail, $_POST['password']);
     // below msg display on form submit success
    $msg = '<div class="alert alert-success col-sm-6 mt-2" role="alert"> New User Added Successfully and Credentials Sent to their Email.</div>';
    // Redirect after 3 seconds
    echo "<script>setTimeout(function(){ location.href='user.php'; }, 5000);</script>";
   } else {
    // below msg display on form submit failed
    $msg = '<div class="alert alert-danger col-sm-6 mt-2" role="alert"> Unable to Add User</div>';
   }
 }
}
}
?>

<!-- <img class="wave" src="../images/wave.png"> -->

<div class="col-sm-6">
<h3 class="title text-center font-weight-bold text-dark mb-5 mt-5" style="font-family: Arial, Helvetica, sans-serif;"><i class="fas fa-users"></i> ADD <span>NEW USER</span></h3>

  <form action="" method="POST">
    <div class="form-group">
      <label for="name"><i class="fas fa-user-tie"></i> Name</label>
      <input type="text" class="form-control" placeholder="Enter Name" id="name" name="name">
    </div>
    <div class="form-group">
      <label for="email"><i class="far fa-envelope"></i> Email</label>
      <input type="email" class="form-control" placeholder="Enter Email" id="email" name="email">
    </div>
    <div class="form-group">
      <label for="password"><i class="fas fa-lock"></i> Password</label>
      <input type="password" class="form-control" placeholder="Enter Password" id="password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters">
    </div>
    <div class="text-center">
      <button type="submit" class="btn btn-success" id="reqsubmit" name="reqsubmit"><i class="fas fa-user-plus"></i> Add</button>
      <a href="user.php" class="btn btn-danger"><i class="far fa-times-circle"></i> Close</a>
    </div>
    <?php if(isset($msg)) {echo $msg; } ?>
  </form>
</div>

<?php
include('includes/footer.php'); 
?>