<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">

  <title>Smartphone Repair Application</title>
</head>

<body>
  <!-- Start Navigation -->
  <center>

  <nav class="navbar navbar-expand-sm navbar-dark pl-2 fixed-top" style="background-color: #17A2B8;">
    <a href="index.php" class="navbar-brand" title="A web based smartphone repair service management application"><i class="fa fa-mobile"></i> Fix <span>Mobile<span></a>
    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#myMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    
  </nav> <!-- End Navigation -->
  </center>

  <div class="carousel-item active">
        <img style="width: 100%; max-height:780px; opacity: 45%;" src="https://images.unsplash.com/photo-1611396000732-f8c9a933424f?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="">
        
          <div class="carousel-caption d-flex flex-column justify-content-center h-100" style="bottom: 0;">
            <h1 class="title text-uppercase font-weight-bold" style="color: black;">Welcome To <span>A "Web Based Smartphone Repair Application"</span></h1>
            <div class="button">
            <a href="userRegistration.php" class="btn btn-info mt-2" title="Create User Account"><i class="fas fa-user-plus"></i> Customer Sign Up</a>

              <a href="user/userLogin.php" class="btn btn-secondary mr-2 mt-2" title="User Login"><i class="fas fa-user-tie"></i> Customer Login</a>
              <a href="admin/adminLogin.php" class="btn btn-light mr-2 mt-2" title="Admin Login"><i class="fas fa-user-tie"></i> Admin Login</a>
              <a href="technician/technicianLogin.php" class="btn btn-dark mr-2 mt-2" title="Technician Login"><i class="fas fa-user-tie"></i> Technician Login</a>
            
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