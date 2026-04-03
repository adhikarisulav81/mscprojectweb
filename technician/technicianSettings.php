<?php
define('TITLE', 'Technician Settings');
define('PAGE', 'technicianSettings');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();




if($_SESSION['is_techlogin']){
  $tEmail = $_SESSION['tEmail'];
 } else {
  echo "<script> location.href='technicianLogin.php'; </script>";
 }

 $sql = "SELECT * FROM technician_tb WHERE empEmail='$tEmail'";
 $result = $conn->query($sql);
 if($result->num_rows == 1){
 $row = $result->fetch_assoc();
 $tName = trim($row["empName"]);
//  $tCity = trim($row["empCity"]);
 $tMobile = trim($row["empMobile"]);
}

if(isset($_REQUEST['nameupdate'])){
//   if(($_REQUEST['tName'] == "") || ($_REQUEST['tCity'] == "") || ($_REQUEST['tMobile'] == "")){
    if(($_REQUEST['tName'] == "") || ($_REQUEST['tMobile'] == "")){

   // msg displayed if required field missing
   $passmsg = '<div class="alert alert-warning col-sm-6 mt-3" role="alert"> Fill All Fields </div>';
  } else {
   $tName = trim($_REQUEST["tName"]);
//    $tCity = trim($_REQUEST["tCity"]);
   $tMobile = trim($_REQUEST["tMobile"]);
//    $sql = "UPDATE technician_tb SET empName = '$tName', empCity = '$tCity', empMobile = '$tMobile' WHERE empEmail = '$tEmail'";
   $sql = "UPDATE technician_tb SET empName = '$tName', empMobile = '$tMobile' WHERE empEmail = '$tEmail'";

   if($conn->query($sql) == TRUE){
   // below msg display on form submit success
   $passmsg = '<div class="alert alert-success col-sm-6 mt-3" role="alert"> Updated Successfully </div>';
   } else {
   // below msg display on form submit failed
   $passmsg = '<div class="alert alert-danger col-sm-6 mt-3" role="alert"> Unable to Update </div>';
    }
  }
}
?>






<?php
  if(isset($_SESSION['is_techlogin'])){
  $tEmail = $_SESSION['tEmail'];
 } else {
  echo "<script> location.href='technicianLogin.php'; </script>";
 }

 $tEmail = $_SESSION['tEmail'];
 if(isset($_REQUEST['passupdate'])){
  if(($_REQUEST['tPassword'] == "")){
   // msg displayed if required field missing
   $passmsg = '<div class="alert alert-warning col-sm-6 mt-2" role="alert"> Fill All Fields </div>';
  } else {
   $tPass = $_REQUEST['tPassword'];
   $tNewPass = md5($_REQUEST['tNewPassword']);
   $sql = "SELECT empEmail, password FROM technician_tb WHERE empEmail='$tEmail' AND password='".md5($tPass)."'";
   $result = $conn->query($sql);
   if($result->num_rows == 1){
    $sql = "UPDATE technician_tb SET password = '$tNewPass' WHERE empEmail = '$tEmail'";
     if($conn->query($sql) == TRUE){
     // below msg display on form submit success
     $passmsg = '<div class="alert alert-success col-sm-6 mt-2" role="alert"> Updated Successfully </div>';
     } else {
     // below msg display on form submit failed
     $passmsg = '<div class="alert alert-danger col-sm-6 mt-2" role="alert"> Unable to Update </div>';
     }
   } else {
     $passmsg = '<div class="alert alert-danger col-sm-6 mt-2" role="alert"> Old Password does not Match </div>';
   }
  }
 }
?>









<!-- <img class="wave" src="../images/wave.png"> -->
  <div class="col-sm-6">
    <form class="" method="POST">
    <!-- <h3 class="text-center font-weight-bold text-dark mb-5 mt-5" style="font-family: Arial, Helvetica, sans-serif; background-color: #2E8B57; color: white; padding: 10px;">Welcome <?php echo $tName ?>!!</h3> -->
      <h3 class="title text-center font-weight-bold text-dark mt-3" style="font-family: Arial, Helvetica, sans-serif;"><i class="fas fa-user"></i> TECHNICIAN <span>PROFILE</span></h3>

      <div class="form-group">
        <label for="inputEmail" class="pl-2 font-weight-bold"><i class="far fa-envelope"></i> Email</label>
        <input type="email" class="form-control" id="inputEmail" value=" <?php echo $tEmail ?>" readonly>
      </div>
      <div class="form-group">
        <label for="inputName" class="pl-2 font-weight-bold"><i class="fas fa-user-tie"></i> Name</label>
        <input type="text" pattern="\s*\S+.*" class="form-control" id="inputName" name="tName" value="<?php echo $tName ?>">
      </div>
      <!-- <div class="form-group">
        <label for="inputCity" class="pl-2 font-weight-bold"><i class="fas fa-city"></i> City</label>
        <input type="text" class="form-control" id="inputCity" name="tCity" value="<?php /*echo $tCity */?>">
      </div> -->
      <div class="form-group">
        <label for="inputMobile" class="pl-2 font-weight-bold"><i class="fas fa-mobile"></i> Mobile</label>
        <input type="text" class="form-control" id="inputMobile" name="tMobile" value="<?php echo $tMobile ?>">
      </div>
      <button type="submit" class="btn" name="nameupdate" style="background-color: #17A2B8; color: white;"><i class="fas fa-user-edit"></i> Update</button>
      <?php if(isset($passmsg)) {echo $passmsg; } ?>
    </form>



<form class="mt-1" method="POST">
  <h3 class="title text-center font-weight-bold text-dark mt-1" style="font-family: Arial, Helvetica, sans-serif;"><i class="fas fa-lock"></i> CHANGE <span>PASSWORD</span></h3>
   <div class="form-group">
    <label for="inputEmail" class="pl-2 font-weight-bold"><i class="far fa-envelope"></i> Email</label>
    <input type="email" class="form-control" id="inputEmail" value="<?php echo $tEmail ?>" readonly>
   </div>
   <div class="form-group">
    <label for="inputnewpassword" class="pl-2 font-weight-bold"><i class="fas fa-key"></i> Old Password</label>
    <input type="password" class="form-control" id="inputnewpassword" placeholder="Old Password" name="tPassword">
   </div>
   <div class="form-group">
    <label for="inputnewpassword" class="pl-2 font-weight-bold"><i class="fas fa-lock"></i> New Password</label>
    <input type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" class="form-control" id="inputnewpassword" placeholder="New Password" name="tNewPassword">
   </div>
   <button type="submit" class="btn" name="passupdate" style="background-color: #17A2B8; color: white;"><i class="fas fa-edit"></i> Update</button>
   <button type="reset" class="btn btn-secondary"><i class="fas fa-cut"></i> Reset</button>
   <?php if(isset($passmsg)) {echo $passmsg; } ?>
  </form>

  </div>








<?php
include('includes/footer.php'); 
?>