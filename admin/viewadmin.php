<?php
define('TITLE', 'Admin');
define('PAGE', 'viewadmin');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();

 if(isset($_SESSION['is_adminlogin'])){
  $aEmail = $_SESSION['aEmail'];
 } else {
  echo "<script> location.href='adminLogin.php'; </script>";
 }
?>


<div class="col-sm-9 col-md-10">
  <!--Table-->
  <h3 class="title text-center font-weight-bold text-dark mt-5 mb-5" style="font-family: Arial, Helvetica, sans-serif;"><i class="fas fa-users"></i> LIST OF <span>ADMINS</span></h3>
  <?php
    $sql = "SELECT * FROM adminlogin_tb";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
      echo '<div class="table-responsive-sm">';
      echo '<table id="dataTableID" class="table">
        <thead>
        <tr>
          <th scope="col">Admin ID</th>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <th scope="col">Action</th>
        </tr>
        </thead>
        <tbody>';
          while($row = $result->fetch_assoc()){
            echo '<tr>';
              echo '<th scope="row">'.$row["id"]. '.'.'</th>';
              echo '<td>'. $row["name"].'</td>';
              echo '<td>'.$row["email"].'</td>';
              echo '<td>
                <form action="editadmin.php" method="POST" class="d-inline"> 
                  <input type="hidden" name="id" value='. $row["id"] .'>
                  <button type="submit" class="btn btn-sm btn-primary mr-2 mt-2" name="view" value="View"><i class="fas fa-edit"></i> Edit</button>
                </form>
              </td>
            </tr>';
          }
        echo '</tbody>
      </table>';
      echo '</div>';
    } else {
      echo "No Records Found.";
    }
// Deletion logic removed
?>

  </div>
</div>

<div class="text-center">
  <a href="insertadmin.php" class="btn fixed-bottom" title="Add User" style="background-color: #17a2b8;"><i class="fas fa-plus fa"></i> Add New Admin</a>
</div>
</div>

<?php
include('includes/footer.php'); 
?>