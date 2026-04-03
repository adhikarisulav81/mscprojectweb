<?php    
define('TITLE', 'Update Admin');
define('PAGE', 'editadmin');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();

 if(isset($_SESSION['is_adminlogin'])){
  $aEmail = $_SESSION['aEmail'];
 } else {
  echo "<script> location.href='adminLogin.php'; </script>";
 }
 // update
 if(isset($_REQUEST['adminupdate'])){
  // Checking for Empty Fields
  if(($_REQUEST['id'] == "") || ($_REQUEST['name'] == "") || ($_REQUEST['email'] == "")){
   // msg displayed if required field missing
   $msg = '<div class="alert alert-warning col-sm-6 mt-2" role="alert"> Fill All Fileds </div>';
  } else {
    // Assigning User Values to Variable
    $aid = $_REQUEST['id'];
    $aname = $_REQUEST['name'];
    $aemail = $_REQUEST['email'];

    $sql = "UPDATE adminlogin_tb SET id = '$aid', name = '$aname', email = '$aemail' WHERE id = '$aid'";
    if($conn->query($sql) == TRUE){
     // below msg display on form submit success
     $msg = '<div class="alert alert-success col-sm-6 mt-2" role="alert"> Updated Successfully </div>';
     echo "<script> location.href='viewadmin.php'; </script>";
    } else {
     // below msg display on form submit failed
     $msg = '<div class="alert alert-danger col-sm-6 mt-2" role="alert"> Unable to Update </div>';
    }
  }
}
?>

<div class="col-sm-6">
  <h3 class="title text-center font-weight-bold text-dark mt-5" style="font-family: Arial, Helvetica, sans-serif;"><i class="fas fa-briefcase"></i> UPDATE <span>ADMIN DETAILS</span></h3>
  
  <?php
    if(isset($_REQUEST['view'])){
      $sql = "SELECT * FROM adminlogin_tb WHERE id = {$_REQUEST['id']}";
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
    }
  ?>
  
  <form action="" method="POST">
    <div class="form-group">
      <label for="id">Admin ID</label>
      <input type="text" class="form-control" id="id" name="id" readonly value="<?php if(isset($row['id'])) {echo $row['id']; }?>">
    </div>
    <div class="form-group">
      <label for="name">Name</label>
      <input type="text" class="form-control" id="name" name="name" value="<?php if(isset($row['name'])) {echo $row['name']; }?>">
    </div>
    <div class="form-group">
      <label for="email">Email</label>
      <input type="text" class="form-control" id="email" name="email" value="<?php if(isset($row['email'])) {echo $row['email']; }?>">
    </div>

    <div class="text-center">
      <button type="submit" class="btn" id="adminupdate" name="adminupdate" style="background-color: #28c38e;"><i class="fas fa-user-edit"></i> Update</button>
      <a href="viewadmin.php" class="btn btn-danger"><i class="far fa-times-circle"></i>  Close</a>
    </div>
    <?php if(isset($msg)) {echo $msg; } ?>
  </form>
</div>

<?php
include('includes/footer.php'); 
?>