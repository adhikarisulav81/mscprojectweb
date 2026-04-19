<?php    
define('TITLE', 'Update Technician');
define('PAGE', 'edittech');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();

 if(isset($_SESSION['is_adminlogin'])){
  $aEmail = $_SESSION['aEmail'];
 } else {
  echo "<script> location.href='adminLogin.php'; </script>";
 }
 // update
 if(isset($_REQUEST['techupdate'])){
  // Checking for Empty Fields
  if(($_REQUEST['techName'] == "") || ($_REQUEST['techMobile'] == "") || ($_REQUEST['techEmail'] == "")){
   // msg displayed if required field missing
   $msg = '<div class="alert alert-warning col-sm-6 mt-2" role="alert"> Fill All Fileds </div>';
  } else {
    // Assigning User Values to Variable
    $eId = $_REQUEST['techId'];
    $eName = $_REQUEST['techName'];
    $eMobile = $_REQUEST['techMobile'];
    $eEmail = $_REQUEST['techEmail'];


//Duplicate email check before inserting
$sql = "SELECT techid FROM technician_tb WHERE techEmail='".$_POST['techEmail']."'";
$result = $conn->query($sql);

if($result->num_rows > 0){
  // Email already exists — block the insert
  $msg = '<div class="alert alert-danger col-sm-6 mt-2" role="alert">
     <i class="fas fa-exclamation-circle"></i> A technician with this email already exists. Please use a different email.
   </div>';
  $result->close();
} else {
 $result->close();


    $sql = "UPDATE technician_tb SET techName = '$eName', techMobile = '$eMobile', techEmail = '$eEmail' WHERE techid = '$eId'";
    if($conn->query($sql) == TRUE){
     // below msg display on form submit success
     $msg = '<div class="alert alert-success col-sm-6 mt-2" role="alert"> Updated Successfully </div>';
     echo "<script> location.href='technician.php'; </script>";
    } else {
     // below msg display on form submit failed
     $msg = '<div class="alert alert-danger col-sm-6 mt-2" role="alert"> Unable to Update </div>';
    }
  }
}
 }
?>


<div class="col-sm-6">
  <h3 class="title text-center font-weight-bold text-dark mt-5" style="font-family: Arial, Helvetica, sans-serif;"><i class="fas fa-briefcase"></i> UPDATE <span>TECHNICIAN DETAILS</span></h3>
  <?php
    if(isset($_REQUEST['view'])){
      $sql = "SELECT * FROM technician_tb WHERE techid = {$_REQUEST['id']}";
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
    }
  ?>
  <form action="" method="POST">
    <div class="form-group">
      <label for="techId">tech ID</label>
      <input type="text" class="form-control" id="techId" name="techId" readonly value="<?php if(isset($row['techid'])) {echo $row['techid']; }?>"
        readonly>
    </div>
    <div class="form-group">
      <label for="techName">Name</label>
      <input type="text" class="form-control" id="techName" name="techName" value="<?php if(isset($row['techName'])) {echo $row['techName']; }?>">
    </div>
    
    <div class="form-group">
      <label for="techMobile">Mobile</label>
      <input type="text" class="form-control" id="techMobile"
                       placeholder="Enter Mobile Number" pattern="^07\d{9}$" title="e.g. 07123456789" name="techMobile" value="<?php if(isset($row['techMobile'])) {echo $row['techMobile']; }?>" required>



    </div>
    <div class="form-group">
      <label for="techEmail">Email</label>
      <input type="email" class="form-control" id="techEmail" name="techEmail" value="<?php if(isset($row['techEmail'])) {echo $row['techEmail']; }?>">
    </div>
    <div class="text-center">
      <button type="submit" class="btn" id="techupdate" name="techupdate" style="background-color: #28c38e;"><i class="fas fa-user-edit"></i> Update</button>
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