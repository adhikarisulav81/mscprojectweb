<?php
define('TITLE', 'Add New Admin');
define('PAGE', 'insertadmin');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();

 if(isset($_SESSION['is_adminlogin'])){
  $aEmail = $_SESSION['aEmail'];
 } else {
  echo "<script> location.href='adminLogin.php'; </script>";
 }
if(isset($_REQUEST['addadmin'])){
 // Checking for Empty Fields
 if(($_REQUEST['name'] == "") || ($_REQUEST['email'] == "") || ($_REQUEST['password'] == "")){
  // msg displayed if required field missing
  $msg = '<div class="alert alert-warning col-sm-6 mt-2" role="alert"> Fill All Fileds </div>';
 } else {
   // Assigning User Values to Variable
   $aname = $_REQUEST['name'];
   $aEmail = $_REQUEST['email'];
   $aPassword = md5($_POST['password']);
   $sql = "INSERT INTO adminlogin_tb (name, email, password) VALUES ('$aname', '$aEmail', '$aPassword')";
   if($conn->query($sql) == TRUE){
    // below msg display on form submit success
    $msg = '<div class="alert alert-success col-sm-6 mt-2" role="alert"> Added Successfully </div>';
    echo "<script> location.href='viewadmin.php'; </script>";
   } else {
    // below msg display on form submit failed
    $msg = '<div class="alert alert-danger col-sm-6 mt-2" role="alert"> Unable to Add </div>';
   }
 }
}
?>


<div class="col-sm-6">
<h3 class="title text-center font-weight-bold text-dark mb-5 mt-5" style="font-family: Arial, Helvetica, sans-serif;"><i class="fas fa-users"></i> ADD <span>NEW ADMIN</span></h3>

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
      <button type="submit" class="btn btn-success" id="addadmin" name="addadmin"><i class="fas fa-user-plus"></i> Add</button>
      <a href="viewadmin.php" class="btn btn-danger"><i class="far fa-times-circle"></i> Close</a>
    </div>
    <?php if(isset($msg)) {echo $msg; } ?>
  </form>
</div>

<?php
include('includes/footer.php'); 
?>