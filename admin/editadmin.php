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


//Duplicate email check before inserting
$sql = "SELECT id FROM adminlogin_tb WHERE email='".$_POST['email']."'";
$result = $conn->query($sql);

if($result->num_rows > 0){
  // Email already exists — block the insert
  $msg = '<div class="alert alert-danger col-sm-6 mt-2" role="alert">
     <i class="fas fa-exclamation-circle"></i> An admin with this email already exists. Please use a different email.
   </div>';
  $result->close();
} else {
 $result->close();


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
}
?>

<div class="col-sm-6">
<h4 class="title font-weight-bold text-center text-white bg-dark mb-3 mt-4" style="padding: 7px; border-radius: 5px;">
UPDATE ADMIN DETAILS
  </h4>   
  <?php
    if(isset($_REQUEST['view'])){
      $sql = "SELECT * FROM adminlogin_tb WHERE id = {$_REQUEST['id']}";
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
    }
  ?>
  
  <form action="" method="POST">
    <div class="form-group">
      <label for="id"><i class="fas fa-tag"></i> ID</label>
      <input type="text" class="form-control" id="id" name="id" readonly value="<?php if(isset($row['id'])) {echo $row['id']; }?>">
    </div>
    <div class="form-group">
    <label for="email" class="font-weight-bold"><i class="fas fa-envelope"></i> Email</label>
      <input type="text" class="form-control" id="email" name="email" value="<?php if(isset($row['email'])) {echo $row['email']; }?>" readonly>
    </div>
    <div class="form-group">
    <label for="name" class="font-weight-bold"><i class="fas fa-user-tie"></i> Name</label>
      <input type="text" class="form-control" id="name" name="name" value="<?php if(isset($row['name'])) {echo $row['name']; }?>">
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