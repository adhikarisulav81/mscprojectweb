<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">

  <title>Online Mobile Repair Service Center</title>
</head>

<body>
  <!-- Start Navigation -->
  <nav class="navbar navbar-expand-sm navbar-dark pl-2 fixed-top" style="background-color: #17A2B8;">
    <a href="index.php" class="navbar-brand" title="Online Mobile Repair Service Center Management System"><i class="fas fa-mobile-alt"></i> Mobile <span>Repair<span></a>
    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#myMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="myMenu">
      <ul class="navbar-nav pl-2 custom-nav">
        <li class="nav-item"><a href="index.php" class="nav-link"><i class="fas fa-home"></i> HOME</a></li>
      
      </ul>
      
    </div>
  </nav> <!-- End Navigation -->

  <div class="carousel-item active">
        <img style="width: 100%; max-height:780px; opacity: 45%;" src="https://images.unsplash.com/photo-1611396000732-f8c9a933424f?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="">
        
          <div class="carousel-caption d-flex flex-column justify-content-center h-100" style="bottom: 0;">
            <h1 class="title text-uppercase font-weight-bold" style="color: black;">Welcome To <span>A "Web Based Smartphone Repair Application"</span></h1>
            <!-- <p class="font-italic text-dark mt-2">Get Your Device Repaired</p> -->
            <div class="button">
            <a href="userRegistration.php" class="btn mt-2" title="Create User Account" style="background-color: #28c38e;"><i class="fas fa-user-plus"></i> User Sign Up</a>

              <a href="user/userLogin.php" class="btn btn-success mr-2 mt-2" title="User Login"><i class="fas fa-user-tie"></i> User Login</a>
              <a href="admin/adminLogin.php" class="btn btn-success mr-2 mt-2" title="Admin Login"><i class="fas fa-user-tie"></i> Admin Login</a>
              <a href="technician/technicianLogin.php" class="btn btn-success mr-2 mt-2" title="Technician Login"><i class="fas fa-user-tie"></i> Technician Login</a>
            
            </div>
          </div>
  </div>
  
  
  <script src="js/jquery.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <!-- font awesome -->
  <script src="js/all.min.js"></script> 
  
</body>

</html>