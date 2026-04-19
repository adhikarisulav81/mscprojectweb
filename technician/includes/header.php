<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <meta http-equiv="X-UA-Compatible" content="ie=edge">
 <title>
  <?php echo TITLE ?>
 </title>
 <!-- Bootstrap CSS -->
 <link rel="stylesheet" href="../css/bootstrap.min.css">

 <!-- Font Awesome CSS -->
 <!-- <link rel="stylesheet" href="../css/all.min.css"> -->

<!-- For Datatables to use prebuilt search functionality and pagination in PHP -->
<link href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css" rel="stylesheet">

 <!-- Custome CSS -->
 <!-- <link rel="stylesheet" href="../css/style.css"> -->

</head>

<body>
 <!-- Top Navbar -->
  <nav class="navbar navbar-dark fixed-top p-0 shadow flex-md-nowrap" style="background-color: #17A2B8;">
   <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="technicianWork.php" title="Technician Portal">Fix Mobile - <i class="fas fa-user-cog"></i> Technician Portal</a>
   <button class="navbar-toggler d-md-none collapsed mr-2" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
   </button>
  </nav>

  <!-- Side Bar -->
 <style>
    .active{
        background-color: #17A2B8;
        color: white;
    }
</style>

  <!-- Side Bar -->
  <div class="container-fluid mb-5" style="margin-top:40px;">
   <div class="row">
    <nav class="col-md-2 d-md-block bg-light sidebar collapse py-md-5 d-print-none" id="sidebarMenu">
     <div class="sidebar-sticky pt-3">
     <ul class="nav flex-column">
      <!-- -->
      <li class="nav-item">
       <a class="nav-link <?php if(PAGE == 'technicianDashboard') { echo 'active'; } ?>" href="technicianDashboard.php">
       <i class="fas fa-chart-bar"></i>
        Technician Dashboard
       </a>
      </li>
     
      
      <li class="nav-item">
       <a class="nav-link <?php if(PAGE == 'technicianWork') { echo 'active'; } ?>" href="technicianWork.php">
       <i class="fas fa-briefcase"></i>
        Assigned Work
       </a>
      </li>
      <li class="nav-item">
       <a class="nav-link <?php if(PAGE == 'technicianProfile') { echo 'active'; } ?>" href="technicianProfile.php">
       <i class="fas fa-user-cog"></i>
        Technician Profile
       </a>
      </li>
      <li class="nav-item">
       <a class="nav-link <?php if(PAGE == 'technicianSettings') { echo 'active'; } ?>" href="technicianSettings.php">
       <i class="fas fa-cog"></i>
        Technician Settings
       </a>
      </li>
      
      <li class="nav-item">
       <a class="nav-link" href="../logout.php">
        <i class="fas fa-sign-out-alt"></i>
        Logout
       </a>
      </li>
     </ul>
    </div>
   </nav>
