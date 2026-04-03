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

<!-- For Datatables to use prebuilt search functionality and pagination in PHP -->
<link href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body>
 <!-- Top Navbar -->
  <nav class="navbar navbar-dark fixed-top p-0 shadow flex-md-nowrap" style="background-color: #17A2B8;">
   <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="dashboard.php" title="Web Based Mobile Repair Management Application"><i class="fas fa-mobile"></i> Fix Mobile - Admin Portal</a>
   <button class="navbar-toggler d-md-none collapsed mr-2" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
   </button>
  </nav>


  <!-- Side Bar -->
  <div class="container-fluid mb-5" style="margin-top:40px;">
   <div class="row">
<style>
    .active{
        background-color: #17A2B8;
        /* 24ad7f */
        color: white;
    }
</style>
    <nav class="col-md-2 d-md-block bg-light sidebar collapse py-md-5 d-print-none" id="sidebarMenu">
     <div class="sidebar-sticky pt-5">
     <ul class="nav flex-column">
     <li class="nav-item">
       <a class="nav-link <?php if(PAGE == 'adminDashboard') { echo 'active'; } ?>" href="adminDashboard.php">
       <i class="fas fa-chart-bar"></i>
        Admin Dashboard
       </a>
      </li>
      <li class="nav-item">
       <a class="nav-link <?php if(PAGE == 'request') { echo 'active'; } ?>" href="request.php">
       <i class="fas fa-people-carry"></i>
        Pending Request
       </a>
      </li>
      <li class="nav-item">
       <a class="nav-link <?php if(PAGE == 'work') { echo 'active'; } ?>" href="work.php">
       <i class="fas fa-briefcase"></i>
        Work Order
       </a>
      </li>

      <li class="nav-item">
       <a class="nav-link <?php if(PAGE == 'manage_catalog') { echo 'active'; } ?>" href="manage_catalog.php">
       <i class="fas fa-sitemap"></i>
        Service and Device
       </a>
      </li>

     
      <li class="nav-item">
       <a class="nav-link <?php if(PAGE == 'viewadmin') { echo 'active'; } ?>" href="viewadmin.php">
       <i class="fas fa-user-cog"></i>
        Admin
       </a>
      </li>
      <li class="nav-item">
       <a class="nav-link <?php if(PAGE == 'user') { echo 'active'; } ?>" href="user.php">
       <i class="fas fa-user-cog"></i>
        User
       </a>
      </li>
      <li class="nav-item">
       <a class="nav-link <?php if(PAGE == 'technician') { echo 'active'; } ?>" href="technician.php">
       <i class="fas fa-user-cog"></i>
        Technician
       </a>
      </li>

      <li class="nav-item">
       <a class="nav-link <?php if(PAGE == 'adminSettings') { echo 'active'; } ?>" href="adminSettings.php">
       <i class="fas fa-user-cog"></i>
        Admin Settings
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