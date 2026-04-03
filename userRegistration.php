<?php
  include('dbConnection.php');

  //if clicked sign up button
  if(isset($_POST['rSignup'])){

    //getting values
    $rName = $_POST['rName'];
    $rEmail = $_POST['rEmail'];
    $rPassword = md5($_POST['rPassword']);
    $cPassword = md5($_POST['cPassword']);

    // Checking for Empty Fields
    if(($_POST['rName'] == "") || ($_POST['rEmail'] == "") || ($_POST['rPassword'] == "") || ($_POST['cPassword'] == "")){
      $msg = '<div class="alert alert-warning mt-2" role="alert"> All Fields are Required. </div>';
      
      //validating password
    }elseif($rPassword != $cPassword){
      $msg = '<div class="alert alert-warning mt-2" role="alert"> Password did not Match. </div>';
    }
    else{
      //checking duplicate email
      $sql = "SELECT email FROM userlogin_tb WHERE email='".$_POST['rEmail']."'";
      $result = $conn->query($sql);
      if($result->num_rows == 1){
        $msg = '<div class="alert alert-warning mt-2" role="alert"> Email ID Already Registered. </div>';
      } else {

        //inserting values to database
        $sql = "INSERT INTO userlogin_tb(name, email, password) VALUES ('$rName','$rEmail', '$rPassword')";
        if($conn->query($sql) == TRUE){
          $msg = '<div class="alert alert-success mt-2" role="alert"> Account Created Successfully. </div>';
        } else {
          $msg = '<div class="alert alert-danger mt-2" role="alert"> Unable to Create Account </div>';
        }
      }
    }   
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">


  <title>User Registration</title>
</head>

<body>

	<header class="container mt-5 shadow-lg mb-5 bg-white rounded" id="register">
		
		<div class="login-content">
    
    <form action="" method="POST">
        <h2 class="title"><span>Create</span> An Account</h2>
           		<div class="input-div one">
           		   <div class="i">
           		   		<i class="fas fa-user"></i>
           		   </div>
           		   <div class="div">
                  <input type="text"
            class="form-control" placeholder="Enter Name" name="rName" value="">
           		   </div>
           		</div>
               <div class="input-div one">
           		   <div class="i">
                  <i class="far fa-envelope"></i>
           		   </div>
           		   <div class="div">
                  <input type="email"
            class="form-control" placeholder="Enter Email" name="rEmail" value="">
           		   </div>
           		</div>

           		<div class="input-div pass">
           		   <div class="i"> 
           		    	<i class="fas fa-lock"></i>
           		   </div>
           		   <div class="div">
                  <input type="password" class="form-control" placeholder="Enter Password" name="rPassword" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters">
            	   </div>
            	</div>

              <div class="input-div pass">
           		   <div class="i"> 
           		    	<i class="fas fa-lock"></i>
           		   </div>
           		   <div class="div">
                  <input type="password" class="form-control" placeholder="Confirm Password" name="cPassword" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters">
            	   </div>
            	</div>

              <button type="submit" class="btn btn-primary btn-sm" name="rSignup" title="Register Your Account"><i class="fas fa-user-plus"></i> Sign Up</button>
              <?php if(isset($msg)) {echo $msg; } ?>
              <a href="index.php" class="btn text-center" title="Back"><i class="fas fa-backward"></i> Back
              to Home</a>
              <div class="">Already Have an Account ? <a href="user/userLogin.php" class="text-center"><i class="fas fa-sign-in-alt"></i> Click Here To Login</a></div>
            </form>              
        </div>
</header>

  <!-- Boostrap JavaScript -->
  <script src="js/jquery.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/all.min.js"></script>
</body>

</html>