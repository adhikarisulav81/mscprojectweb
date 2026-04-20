<?php
include('../dbConnection.php');
session_start();

if(!isset($_SESSION['is_adminlogin'])){
  if(isset($_REQUEST['aEmail'])){
    $aEmail = $_REQUEST['aEmail'];
    // $aPassword = $_POST['aPassword'];
    $aPassword = md5($_POST['aPassword']);

    $sql = "SELECT email, password FROM adminlogin_tb WHERE email='".$aEmail."' AND password='".$aPassword."' limit 1";
    $result = $conn->query($sql);
    if($result->num_rows == 1){
      $_SESSION['is_adminlogin'] = true;
      $_SESSION['aEmail'] = $aEmail;
      // Redirecting to Admin Dashboard page on Correct Email and Password
      echo "<script> location.href='adminDashboard.php'; </script>";
      exit;
    } else {
      $msg = '<div class="alert alert-warning mt-2" role="alert"> Enter Valid Email and Password </div>';
    }
  }
} else {
  echo "<script> location.href='adminDashboard.php'; </script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../css/bootstrap.min.css">

  <title>Admin Login</title>
</head>

<body>
	<header class="container mt-5 shadow-lg p-5 mb-5 bg-white rounded" id="register" data-aos="fade-down">
		
		<div class="login-content">
    <form action="" method="POST">
    <h4 class="text-center text-white bg-dark mb-3 mt-4" style="padding: 7px; border-radius: 5px;">
    Admin Login Panel
  </h4> 
           		<div class="input-div one mb-2">
           		   <div class="i font-weight-bold">
                  <i class="fas fa-envelope"></i> Email
           		   </div>
           		   <div class="div">

                  <input type="email" class="form-control" placeholder="Email" name="aEmail">
           		   </div>
           		</div>
           		<div class="input-div pass mb-3">
           		   <div class="i font-weight-bold"> 
           		    	<i class="fas fa-lock"></i> Password
           		   </div>
           		   <div class="div">

                  <input type="password" class="form-control" placeholder="Password" name="aPassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters">
            	   </div>
            	</div>

              <button type="submit" class="btn btn-primary mt-3 mb-3"><i class="fas fa-sign-in-alt"></i> Login</button>
              <?php if(isset($msg)) {echo $msg; } ?>

                <a href="../index.php" class="btn btn-secondary mt-3 mb-3 text-center" title="Back"><i class="fas fa-backward"></i> Back
              to Home</a>

            </form>
            
        </div>
</header>

</body>
</html>

<?php
include('includes/footer.php'); 
?>