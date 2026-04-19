<?php
define('TITLE', 'Technician Profile');
define('PAGE', 'technicianProfile');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();




if($_SESSION['is_techlogin']){
  $tEmail = $_SESSION['tEmail'];
 } else {
  echo "<script> location.href='technicianLogin.php'; </script>";
 }

 $sql = "SELECT * FROM technician_tb WHERE techEmail='$tEmail'";
 $result = $conn->query($sql);
 if($result->num_rows == 1){
 $row = $result->fetch_assoc();
 $tName = trim($row["techName"]);
 $tMobile = trim($row["techMobile"]);
}

if(isset($_REQUEST['nameupdate'])){
    if(($_REQUEST['tName'] == "") || ($_REQUEST['tMobile'] == "")){

   // msg displayed if required field missing
   $passmsg = '<div class="alert alert-warning col-sm-6 mt-3" role="alert"> Fill All Fields </div>';
  } else {
   $tName = trim($_REQUEST["tName"]);
   $tMobile = trim($_REQUEST["tMobile"]);
   $sql = "UPDATE technician_tb SET techName = '$tName', techMobile = '$tMobile' WHERE techEmail = '$tEmail'";

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




<div class="col-sm-6">
<form class="" method="POST">
      <h3 class="title text-center font-weight-bold text-dark mt-3" style="font-family: Arial, Helvetica, sans-serif;"><i class="fas fa-user"></i> TECHNICIAN <span>PROFILE</span></h3>

      <div class="form-group">
        <label for="inputEmail" class="pl-2 font-weight-bold"><i class="far fa-envelope"></i> Email</label>
        <input type="email" class="form-control" id="inputEmail" value=" <?php echo $tEmail ?>" readonly>
      </div>
      <div class="form-group">
        <label for="inputName" class="pl-2 font-weight-bold"><i class="fas fa-user-tie"></i> Name</label>
        <input type="text" pattern="\s*\S+.*" class="form-control" id="inputName" name="tName" value="<?php echo $tName ?>">
      </div>
      
      <div class="form-group">
        <label for="inputMobile" class="pl-2 font-weight-bold"><i class="fas fa-mobile"></i> Mobile</label>
        <input type="text" class="form-control" id="inputMobile" name="tMobile" value="<?php echo $tMobile ?>">
      </div>
      <button type="submit" class="btn" name="nameupdate" style="background-color: #17A2B8; color: white;"><i class="fas fa-user-edit"></i> Update</button>
      <?php if(isset($passmsg)) {echo $passmsg; } ?>
    </form>
  </div>

<?php
include('includes/footer.php'); 
?>