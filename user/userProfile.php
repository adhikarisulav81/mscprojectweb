<?php
define('TITLE', 'User Profile');
define('PAGE', 'userProfile');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();


//include('../UserModel.php');
 if($_SESSION['is_login']){
  $rEmail = $_SESSION['rEmail'];
 } else {
  echo "<script> location.href='userLogin.php'; </script>";
 }

 $sql = "SELECT * FROM userlogin_tb WHERE email='$rEmail'";
 $result = $conn->query($sql);
 if($result->num_rows == 1){
 $row = $result->fetch_assoc();
 $rName = trim($row["name"]); //trim is used to remove space in the form textbox
}

if(isset($_REQUEST['nameupdate'])){
  if(($_REQUEST['rName'] == "")){
   // msg displayed if required field missing
   $passmsg = '<div class="alert alert-warning col-sm-6 mt-3" role="alert"> Fill All Fileds </div>';
  } else {
   $rName = trim($_REQUEST["rName"]);
   $sql = "UPDATE userlogin_tb SET name = '$rName' WHERE email = '$rEmail'";
   if($conn->query($sql) == TRUE){
   $_SESSION['rName'] = $rName;
   // below msg display on form submit success
   $passmsg = '<div class="alert alert-success col-sm-6 mt-3" role="alert"> Updated Successfully </div>';
   } else {
   // below msg display on form submit failed
   $passmsg = '<div class="alert alert-danger col-sm-6 mt-3" role="alert"> Unable to Update </div>';
    }
  }
}
?>



<div class="col-sm-6">
    <form class="" method="POST">
    <!-- <h3 class="text-center font-weight-bold text-dark mb-5 mt-5" style="font-family: Arial, Helvetica, sans-serif; background-color: #28c38e">Welcome <?php echo $rName ?> !!</h3> -->
      <h4 class="title font-weight-bold text-center text-white bg-dark mb-3 mt-5" style="padding: 7px; border-radius: 5px;">
  UPDATE USER PROFILE
  </h4>
      <div class="form-group">
        <label for="inputEmail" class="pl-2 font-weight-bold"><i class="far fa-envelope"></i> Email<span class="text-danger"> *</span></label>
        <input type="email" class="form-control" id="inputEmail" value=" <?php echo $rEmail ?>" readonly>
      </div>
      <div class="form-group">
        <label for="inputName" class="pl-2 font-weight-bold"><i class="fas fa-user-tie"></i> Name<span class="text-danger"> *</span></label>
        <input type="text" pattern="\s*\S+.*" class="form-control" id="inputName" name="rName" value="<?php echo $rName ?>">
      </div>
      <button type="submit" class="btn" name="nameupdate" style="background-color: #17A2B8;"><i class="fas fa-user-edit"></i> Update</button>
      <?php if(isset($passmsg)) {echo $passmsg; } ?>
    </form>

</div>

<?php
include('includes/footer.php'); 
?>