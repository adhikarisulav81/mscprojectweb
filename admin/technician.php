<?php
define('TITLE', 'Technician');
define('PAGE', 'technician');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();

 if(isset($_SESSION['is_adminlogin'])){
  $aEmail = $_SESSION['aEmail'];
 } else {
  echo "<script> location.href='adminLogin.php'; </script>";
  exit();
 }
?>


<div class="col-sm-9 col-md-10">
  <!--Table-->
  <h4 class="title font-weight-bold text-center text-white bg-dark mb-3 mt-4" style="padding: 7px; border-radius: 5px;">
LIST OF TECHNICIANS
  </h4>  
  <?php

  // get every technician from the database with all their details.
    $sql = "SELECT * FROM technician_tb";
    $result = $conn->query($sql);

    // Only builds the table if at least one technician exists.
    if($result->num_rows > 0){
      echo '<div class="table-responsive-sm">';
      echo '<table id="dataTableID" class="table">
        <thead>
        <tr>
          <th scope="col">Technician ID</th>
          <th scope="col">Name</th>
          <th scope="col">Mobile</th>
          <th scope="col">Email</th>
          <!-- <th scope="col">Rating</th> -->
          <th scope="col">Action</th>
        </tr>
        </thead>
        <tbody>';

        // Loops through every technician one at a time. Each loop gives one technician's data in the $row array.
        while($row = $result->fetch_assoc()){
            echo '<tr>';
            echo '<th scope="row">'.$row["techid"].'.'.'</th>';
            echo '<td>'. $row["techName"].'</td>';
            echo '<td>'.$row["techMobile"].'</td>';
            echo '<td>'.$row["techEmail"].'</td>';


            echo '<td>
              <form action="edittech.php" method="POST"> 
                <input type="hidden" name="id" value='. $row["techid"] .'>
                <button type="submit" class="btn btn-primary btn-sm mb-2" name="view" title="Edit Technician"><i class="fas fa-edit"></i> Edit</button>
              </form>';              
              echo '
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
  <a href="inserttech.php" class="btn fixed-bottom" title="Add Technician" style="background-color: #138496;"><i class="fas fa-plus fa"></i> Add New Technician</a>
  </div>
</div>

<?php
include('includes/footer.php'); 
?>