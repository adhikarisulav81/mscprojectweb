<?php
define('TITLE', 'Admin Settings');
define('PAGE', 'adminSettings');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();

 if(isset($_SESSION['is_adminlogin'])){
  $aEmail = $_SESSION['aEmail'];
 } else {
  echo "<script> location.href='adminLogin.php'; </script>";
 }
 $aEmail = $_SESSION['aEmail'];
 if(isset($_REQUEST['passupdate'])){
  if(empty($_REQUEST['aPassword']) || empty($_REQUEST['oldPassword'])){
   // msg displayed if required field missing
   $passmsg = '<div class="alert alert-warning col-sm-6 mt-2" role="alert"> Fill All Fields </div>';
  } else {
    $sql = "SELECT * FROM adminlogin_tb WHERE email='$aEmail'";
    $result = $conn->query($sql);
    if($result->num_rows == 1){
     $row = $result->fetch_assoc();
     $oldPassCheck = md5($_REQUEST['oldPassword']);
    //  $oldPassCheck = $_REQUEST['oldPassword'];

     if($oldPassCheck == $row['password']){
       $aPass = md5($_POST['aPassword']);
      //  $aPass = $_POST['aPassword'];

       $sql = "UPDATE adminlogin_tb SET password = '$aPass' WHERE email = '$aEmail'";
       if($conn->query($sql) == TRUE){
        // below msg display on form submit success
        $passmsg = '<div class="alert alert-success col-sm-6 mt-2" role="alert"> Updated Successfully </div>';
       } else {
        // below msg display on form submit failed
        $passmsg = '<div class="alert alert-danger col-sm-6 mt-2" role="alert"> Unable to Update </div>';
       }
     } else {
       $passmsg = '<div class="alert alert-danger col-sm-6 mt-2" role="alert"> Old Password is Incorrect </div>';
     }
    }
  }
}
?>

<!-- <img class="wave" src="../images/wave.png"> -->

<div class="col-sm-6">
  <form class="" method="POST">
    <h3 class="title text-center font-weight-bold text-dark mt-5 mb-5" style="font-family: Arial, Helvetica, sans-serif;"><i class="fas fa-lock"></i> CHANGE <span>ADMIN PASSWORD</span></h3>
    <div class="form-group">
      <label for="inputEmail"><i class="far fa-envelope"></i> Email</label>
      <input type="email" class="form-control" id="inputEmail" value=" <?php echo $aEmail ?>" readonly>
    </div>
    <div class="form-group">
      <label for="oldPassword"><i class="fas fa-lock"></i> Old Password</label>
      <input type="password" class="form-control" id="oldPassword" placeholder="Old Password" name="oldPassword" required>
    </div>
    <div class="form-group">
      <label for="inputnewpassword"><i class="fas fa-lock"></i> New Password</label>
      <input type="password" class="form-control" id="inputnewpassword" placeholder="New Password" name="aPassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters">
    </div>
    <button type="submit" class="btn mr-2 mt-4" name="passupdate" style="background-color: #17A2B8;"><i class="fas fa-user-edit"></i> Update</button>
    <button type="reset" class="btn btn-secondary mt-4"><i class="fas fa-cut"></i> Reset</button>
    <?php if(isset($passmsg)) {echo $passmsg; } ?>
  </form>
</div>

<?php
include('includes/footer.php'); 
?>