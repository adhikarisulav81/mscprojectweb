<?php
define('TITLE', 'Users');
define('PAGE', 'user');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();

 if(isset($_SESSION['is_adminlogin'])){
  $aEmail = $_SESSION['aEmail'];
 } else {
  echo "<script> location.href='adminLogin.php'; </script>";
 }

// Deletion Logic Removed

// Enable/Disable Logic
// if(isset($_REQUEST['toggle_status'])){
//   $status_val = intval($_REQUEST['status']);
//   $sql = "UPDATE userlogin_tb SET is_active = $status_val WHERE id = {$_REQUEST['id']}";
//   if($conn->query($sql) === TRUE){
//     header("Location: user.php?status_updated");
//     exit;
//   } else {
//     $errorMsg = "Unable to Update Status. SQL Error: " . $conn->error;
//   }
// }
?>

<!-- <img class="wave" src="../images/wave.png"> -->

<div class="col-sm-9 col-md-10">
  <!--Table-->
  <h3 class="title text-center font-weight-bold text-dark mt-5 mb-5" style="font-family: Arial, Helvetica, sans-serif;"><i class="fas fa-users"></i> LIST OF <span>USERS</span></h3>
  <?php if(isset($errorMsg)) { echo '<div class="alert alert-danger">'.$errorMsg.'</div>'; } ?>
  <?php
    $sql = "SELECT * FROM userlogin_tb";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
      echo '<div class="table-responsive-sm">';
      echo '<table id="dataTableID" class="table">
        <thead>
        <tr>
          <th scope="col">User ID</th>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <!-- <th scope="col">Status</th> -->
          <th scope="col">Action</th>
        </tr>
        </thead>
        <tbody>';
          while($row = $result->fetch_assoc()){
            // $is_active = isset($row['is_active']) ? $row['is_active'] : 1;
            // $status_badge = ($is_active == 1) ? '<span class="badge badge-success p-2">Active</span>' : '<span class="badge badge-danger p-2">Disabled</span>';
            // $toggle_action = ($is_active == 1) ? 0 : 1;
            // $toggle_btn_class = ($is_active == 1) ? 'btn-warning' : 'btn-success';
            // $toggle_icon = ($is_active == 1) ? 'fa-user-slash' : 'fa-user-check';
            // $toggle_title = ($is_active == 1) ? 'Disable User' : 'Enable User';

            echo '<tr>';
              echo '<th scope="row">'.$row["id"]. '.'.'</th>';
              echo '<td>'. $row["name"].'</td>';
              echo '<td>'.$row["email"].'</td>';
              // echo '<td>'.$status_badge.'</td>';
              echo '<td>
                <form action="editreq.php" method="POST" class="d-inline"> 
                  <input type="hidden" name="id" value='. $row["id"] .'>
                  <button type="submit" class="btn btn-sm btn-primary mt-2" name="view" title="View"><i class="fas fa-edit"></i> Edit</button>
                </form>  
                <!-- <form action="" method="POST" class="d-inline">
                  <input type="hidden" name="id" value='./* $row["id"] .*/'>
                  <input type="hidden" name="status" value='. /*$toggle_action .*/'>
                  <button type="submit" class="btn ' ./* $toggle_btn_class .*/ ' btn-sm mt-2 ml-1" name="toggle_status" title="'./*$toggle_title.*/'"><i class="fas './*$toggle_icon.*/'"></i></button>
                </form>-->
              </td>
            </tr>';
          }
        echo '</tbody>
      </table>';
      echo '</div>';
    } else {
      echo "No Records Found.";
    }
?>

  </div>
</div>

<div class="text-center">
  <a href="insertreq.php" class="btn fixed-bottom" title="Add User" style="background-color: #138496;"><i class="fas fa-plus fa"></i> Add New User</a>
</div>
</div>

<?php
include('includes/footer.php'); 
?>