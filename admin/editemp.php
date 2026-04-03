<?php    
define('TITLE', 'Update Technician');
define('PAGE', 'editemp');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();

 if(isset($_SESSION['is_adminlogin'])){
  $aEmail = $_SESSION['aEmail'];
 } else {
  echo "<script> location.href='adminLogin.php'; </script>";
 }
 // update
 if(isset($_REQUEST['empupdate'])){
  // Checking for Empty Fields
  if(($_REQUEST['empName'] == "") || ($_REQUEST['empMobile'] == "") || ($_REQUEST['empEmail'] == "")){
   // msg displayed if required field missing
   $msg = '<div class="alert alert-warning col-sm-6 mt-2" role="alert"> Fill All Fileds </div>';
  } else {
    // Assigning User Values to Variable
    $eId = $_REQUEST['empId'];
    $eName = $_REQUEST['empName'];
    $eMobile = $_REQUEST['empMobile'];
    $eEmail = $_REQUEST['empEmail'];
    $sql = "UPDATE technician_tb SET empName = '$eName', empMobile = '$eMobile', empEmail = '$eEmail' WHERE empid = '$eId'";
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
?>


<div class="col-sm-6">
  <h3 class="title text-center font-weight-bold text-dark mt-5" style="font-family: Arial, Helvetica, sans-serif;"><i class="fas fa-briefcase"></i> UPDATE <span>TECHNICIAN DETAILS</span></h3>
  <?php
    if(isset($_REQUEST['view'])){
      $sql = "SELECT * FROM technician_tb WHERE empid = {$_REQUEST['id']}";
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
    }
  ?>
  <form action="" method="POST">
    <div class="form-group">
      <label for="empId">Emp ID</label>
      <input type="text" class="form-control" id="empId" name="empId" readonly value="<?php if(isset($row['empid'])) {echo $row['empid']; }?>"
        readonly>
    </div>
    <div class="form-group">
      <label for="empName">Name</label>
      <input type="text" class="form-control" id="empName" name="empName" value="<?php if(isset($row['empName'])) {echo $row['empName']; }?>">
    </div>
    
    <div class="form-group">
      <label for="empMobile">Mobile</label>
      <input type="text" class="form-control" id="empMobile" pattern="[9][0-9]{9}" title="Start with 9 and must contain 10 digits" name="empMobile" value="<?php if(isset($row['empMobile'])) {echo $row['empMobile']; }?>"
        onkeypress="isInputNumber(event)">
    </div>
    <div class="form-group">
      <label for="empEmail">Email</label>
      <input type="email" class="form-control" id="empEmail" name="empEmail" value="<?php if(isset($row['empEmail'])) {echo $row['empEmail']; }?>">
    </div>
    <div class="text-center">
      <button type="submit" class="btn" id="empupdate" name="empupdate" style="background-color: #28c38e;"><i class="fas fa-user-edit"></i> Update</button>
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