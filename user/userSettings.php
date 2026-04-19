<?php
define('TITLE', 'User Settings');
define('PAGE', 'userSettings');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();


 if($_SESSION['is_login']){
  $rEmail = $_SESSION['rEmail'];
 } else {
  echo "<script> location.href='userLogin.php'; </script>";
 }

$rEmail = $_SESSION['rEmail'];
 if(isset($_REQUEST['passupdate'])){
  if(empty($_REQUEST['rPassword']) || empty($_REQUEST['oldPassword'])){
   // msg displayed if required field missing
   $passmsg = '<div class="alert alert-warning col-sm-6 mt-2" role="alert"> Fill All Fields </div>';
  } else {
    $sql = "SELECT * FROM userlogin_tb WHERE email='$rEmail'";
    $result = $conn->query($sql);
    if($result->num_rows == 1){
     $row = $result->fetch_assoc();
     $oldPassCheck = md5($_REQUEST['oldPassword']);
     if($oldPassCheck == $row['password']){
       $rPass = md5($_POST['rPassword']);
       $sql = "UPDATE userlogin_tb SET password = '$rPass' WHERE email = '$rEmail'";
       if($conn->query($sql) == TRUE){
        // below msg display on form submit success
        $passmsg = '<div class="alert alert-success col-sm-6 mt-3" role="alert"> Updated Successfully </div>';
       } else {
        // below msg display on form submit failed
        $passmsg = '<div class="alert alert-danger col-sm-6 mt-3" role="alert"> Unable to Update </div>';
       }
     } else {
       $passmsg = '<div class="alert alert-danger col-sm-6 mt-3" role="alert"> Old Password is Incorrect </div>';
     }
    }
  }
}
?>

  <div class="col-sm-6">
    
    <form class="" method="POST">
    <h4 class="title font-weight-bold text-center text-white bg-dark mb-3 mt-5" style="padding: 7px; border-radius: 5px;">
CHANGE USER PASSWORD  </h4> 
     <div class="form-group">
        <i class="fas fa-envelope"></i>
        <label for="inputEmail">Email <span class="text-danger"> *</span></label>
        <input type="email" class="form-control" id="inputEmail" value=" <?php echo $rEmail ?>" readonly>
      </div>
      <div class="form-group">
        <i class="fas fa-lock"></i>
        <label for="oldPassword">Old Password <span class="text-danger"> *</span></label>
        <input type="password" class="form-control" id="oldPassword" placeholder="Old Password" name="oldPassword" required>
      </div>
      <div class="form-group">
        <i class="fas fa-lock"></i>
        <label for="inputnewpassword">New Password <span class="text-danger"> *</span></label>
        <input type="password" class="form-control" id="inputnewpassword" placeholder="New Password" name="rPassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters">
      </div>
      <button type="submit" class="btn mr-2 mt-4" name="passupdate" style="background-color: #17A2B8;"><i class="fas fa-user-edit"></i> Update</button>
      <button type="reset" class="btn btn-secondary mt-4"><i class="fas fa-cut"></i> Reset</button>
      <?php if(isset($passmsg)) {echo $passmsg; } ?>
    </form>
  </div>


<?php
include('includes/footer.php'); 
?>