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
  <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="userProfile.php" title="Web Based Mobile Repair Management Application">Fix Mobile - <i class="fas fa-user"></i> Customer Portal</a>
  <?php 
    $display_name = '';
    if(isset($_SESSION['rName']) && !empty($_SESSION['rName'])){
      $display_name = $_SESSION['rName'];
    }
  ?>
  <?php if(!empty($display_name)){ ?>
    <span class="navbar-text text-white mr-3 d-none d-md-inline"><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($display_name); ?></span>
  <?php } ?>
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
 <div class="container-fluid mb-5 " style="margin-top:40px;">
  <div class="row">
   <nav class="col-md-2 d-md-block bg-light sidebar collapse py-md-5 d-print-none" id="sidebarMenu">
    <div class="sidebar-sticky pt-5">
    <ul class="nav flex-column">
      <li class="nav-item">
       <a class="nav-link <?php if(PAGE == 'userDashboard') { echo 'active'; } ?>" href="userDashboard.php">
       <i class="fas fa-chart-bar"></i>
        Customer Dashboard
       </a>
      </li>
      <li class="nav-item">
       <a class="nav-link <?php if(PAGE == 'submitRequest') { echo 'active'; } ?>" href="submitRequest.php">
       <i class="far fa-clipboard"></i>
        Submit Requests
       </a>
      </li>
      <li class="nav-item">
       <a class="nav-link <?php if(PAGE == 'myRequests') { echo 'active'; } ?>" href="myRequests.php">
       <i class="fas fa-hand-paper"></i>
        My Requests
       </a>
      </li>
      
      <li class="nav-item">
       <a class="nav-link <?php if(PAGE == 'checkStatus') { echo 'active'; } ?>" href="checkStatus.php">
       <i class="fas fa-clock"></i>
        Track Requests
       </a>
      </li>
      <li class="nav-item">
       <a class="nav-link <?php if(PAGE == 'userProfile') { echo 'active'; } ?>" href="userProfile.php">
       <i class="fas fa-user"></i>
        Customer Profile
       </a>
      </li>
      <li class="nav-item">
       <a class="nav-link <?php if(PAGE == 'userSettings') { echo 'active'; } ?>" href="userSettings.php">
       <i class="fas fa-cog"></i>
        Customer Settings
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