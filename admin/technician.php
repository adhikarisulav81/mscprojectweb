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
  <h3 class="title text-center font-weight-bold text-dark mb-5 mt-5" style="font-family: Arial, Helvetica, sans-serif;"><i class="fas fa-chalkboard-teacher"></i> LIST OF <span>TECHNICIANS</span></h3>
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
          <th scope="col">Emp ID</th>
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
            echo '<th scope="row">'.$row["empid"].'.'.'</th>';
            echo '<td>'. $row["empName"].'</td>';
            echo '<td>'.$row["empMobile"].'</td>';
            echo '<td>'.$row["empEmail"].'</td>';

            // If rating is greater than 0, show the rating number obtained by a technician (1 complete work =1 rating), if rating is 0 just show 0.
            // $rating_display = ($row["empRating"] > 0) ? (int)$row["empRating"] . ' <i class="fas fa-star text-warning"></i>' : '0 <i class="fas fa-star text-warning"></i>';
            // echo '<td>'.$rating_display.'</td>';


            echo '<td>
              <form action="editemp.php" method="POST"> 
                <input type="hidden" name="id" value='. $row["empid"] .'>
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
  <a href="insertemp.php" class="btn fixed-bottom" title="Add Technician" style="background-color: #138496;"><i class="fas fa-plus fa"></i> Add New Technician</a>
  </div>
</div>

<?php
include('includes/footer.php'); 
?>