<?php
include('../dbConnection.php');
session_start();

if(!isset($_SESSION['is_techlogin'])){
  if(isset($_POST['tEmail'])){
    $tEmail = $_POST['tEmail'];
    $tPassword = md5($_POST['tPassword']);
    $sql = "SELECT techEmail, password FROM technician_tb WHERE techEmail='".$tEmail."' AND password='".$tPassword."' limit 1";
    $result = $conn->query($sql);
    
    if($result->num_rows == 1){     
      
          $_SESSION['is_techlogin'] = true;
          $_SESSION['tEmail'] = $tEmail;
          echo "<script> location.href='technicianDashboard.php'; </script>";
          exit;
      
    } else {
      $msg = '<div class="alert alert-warning mt-2" role="alert"> Enter Valid Email and Password </div>';
    }
  }
} else {
  echo "<script> location.href='technicianDashboard.php'; </script>";
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

  <title>Technician Login</title>
</head>

<body>

	<header class="container mt-5 shadow-lg mb-5 bg-white rounded" id="register" data-aos="fade-down">
		
		<div class="login-content">
      <form action="" method="POST">
        <h2 class="title"><span>TECHNICIAN</span> LOGIN PANEL</h2>
        <div class="input-div one">
          <div class="i">
            <i class="fas fa-envelope"></i> Email
          </div>
          <div class="div">
            <input type="email" class="form-control" placeholder="Enter Your Email Address" name="tEmail" required>
          </div>
        </div>
        <div class="input-div pass">
          <div class="i"> 
            <i class="fas fa-lock"></i> Password
          </div>
          <div class="div">
            <input type="password" class="form-control" placeholder="Enter Your Password" name="tPassword" required>
          </div>
        </div>
        <button type="submit" class="btn btn-sm btn-primary mt-3 mb-3"><i class="fas fa-sign-in-alt"></i> Login</button>
        <?php if(isset($msg)) {echo $msg; } ?>
        <a href="../index.php" class="btn btn-sm btn-secondary text-center mt-3 mb-3" title="Back"><i class="fas fa-backward"></i> Back to Home</a>

      </form>  
   
    </div>

  </header>


  <!-- Boostrap JavaScript -->
  <script src="../js/jquery.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/all.min.js"></script>
</body>

</html>
