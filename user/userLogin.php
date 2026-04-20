<?php
include('../dbConnection.php');
session_start(); //Starts the session so $_SESSION variables can be used


if(!isset($_SESSION['is_login'])){ //Checks if the user is already logged in

  if(isset($_POST['rEmail'])){ //Checks if the login form has actually been submitted

    //Getting the form values:
    $rEmail = $_POST['rEmail']; 
    $rPassword = md5($_POST['rPassword']);

    //Searches the database for a user with that exact email and password, LIMIT 1 means stop after finding the first match
    $sql = "SELECT email, password FROM userlogin_tb WHERE email='".$rEmail."' AND password='".$rPassword."' limit 1";
    $result = $conn->query($sql);
    if($result->num_rows == 1){     
      
          $_SESSION['is_login'] = true;
          $_SESSION['rEmail'] = $rEmail; //Saves the user's email address into the session, This means any page can access $_SESSION['rEmail'] to know who is logged in


          // Redirecting to userDashboard page on Correct Email and Pass
          echo "<script> location.href='userDashboard.php'; </script>";
          exit;
      
    } else {
      $msg = '<div class="alert alert-warning mt-2" role="alert"> Enter Valid Email and Password </div>';
    }
  }
} else {
  //If already logged in redirect to userDashboard.php
  echo "<script> location.href='userDashboard.php'; </script>";
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


  <title>User Login</title>
</head>
<body>

	<header class="container mt-5 shadow-lg p-5 mb-5 bg-white rounded" id="register" data-aos="fade-down">
		
		<div class="login-content">
      <form action="" method="POST">
      <h4 class="text-center text-white bg-dark mb-3 mt-4" style="padding: 7px; border-radius: 5px;">
    Customer Login Panel
  </h4> 
          <div class="input-div one mb-2">
          <div class="i font-weight-bold">
            <i class="fas fa-user"></i> Email
          </div>
          <div class="div">
            <input type="email" class="form-control" placeholder="Enter Your Email Address" name="rEmail">
          </div>
        </div>
        <div class="input-div pass mb-3">
          <div class="i font-weight-bold"> 
            <i class="fas fa-lock"></i> Password
          </div>
          <div class="div">
            <input type="password" class="form-control" placeholder="Enter Your Password" name="rPassword" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
          </div>
        </div>
        <button type="submit" class="btn btn-primary mt-2 mb-4"><i class="fas fa-sign-in-alt"></i> Login</button>
        <?php if(isset($msg)) {echo $msg; } ?>
        
        
        <a href="../index.php" class="btn btn-secondary mt-2 mb-4 text-center" title="Back"><i class="fas fa-backward"></i> Back to Home</a>
        <div class="">Do not Have an Account ? 
        <a href="../userRegistration.php" class="text-center"><i class="fas fa-user-plus"></i> Click Here To Create An Account</a>
        </div>

      </form>  
   
    </div>

  </header>


  <?php
include('includes/footer.php'); 
?>
