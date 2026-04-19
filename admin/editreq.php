<?php    
define('TITLE', 'Update User');
define('PAGE', 'editreq');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();

 if(isset($_SESSION['is_adminlogin'])){
  $aEmail = $_SESSION['aEmail'];
 } else {
  echo "<script> location.href='adminLogin.php'; </script>";
 }
 // update
 if(isset($_REQUEST['requpdate'])){
  // Checking for Empty Fields
  if(($_REQUEST['id'] == "") || ($_REQUEST['name'] == "") || ($_REQUEST['email'] == "")){
   // msg displayed if required field missing
   $msg = '<div class="alert alert-warning col-sm-6 mt-2" role="alert"> Fill All Fileds </div>';
  } else {
    // Assigning User Values to Variable
    $rid = $_REQUEST['id'];
    $rname = $_REQUEST['name'];
    $remail = $_REQUEST['email'];


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

    $sql = "UPDATE userlogin_tb SET id = '$rid', name = '$rname', email = '$remail' WHERE id = '$rid'";
    if($conn->query($sql) == TRUE){
     // below msg display on form submit success
     $msg = '<div class="alert alert-success col-sm-6 mt-2" role="alert"> Updated Successfully </div>';
     echo "<script> location.href='user.php'; </script>";
    } else {
     // below msg display on form submit failed
     $msg = '<div class="alert alert-danger col-sm-6 mt-2" role="alert"> Unable to Update </div>';
    }
  }
}
}
?>

<div class="col-sm-6">
  <h3 class="title text-center font-weight-bold text-dark mt-5" style="font-family: Arial, Helvetica, sans-serif;"><i class="fas fa-briefcase"></i> UPDATE <span>USER DETAILS</span></h3>
  
  <?php
    if(isset($_REQUEST['view'])){
      $sql = "SELECT * FROM userlogin_tb WHERE id = {$_REQUEST['id']}";
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
    }
  ?>
  
  <form action="" method="POST">
    <div class="form-group">
      <label for="id">User ID</label>
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
      <button type="submit" class="btn" id="requpdate" name="requpdate" style="background-color: #28c38e;"><i class="fas fa-user-edit"></i> Update</button>
      <a href="user.php" class="btn btn-danger"><i class="far fa-times-circle"></i>  Close</a>
    </div>
    <?php if(isset($msg)) {echo $msg; } ?>
  </form>
</div>

<?php
include('includes/footer.php'); 
?>