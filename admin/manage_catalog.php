<?php
define('TITLE', 'Manage Catalog');
define('PAGE', 'manage_catalog');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();
if(isset($_SESSION['is_adminlogin'])){
 $aEmail = $_SESSION['aEmail'];
} else {
 echo "<script> location.href='adminLogin.php'; </script>";
}

// Handle Delete Operations
//delete services
if(isset($_GET['delete_service'])){
    $service_id = $_GET['delete_service'];
    $sql = "DELETE FROM services_tb WHERE service_id = '$service_id'";
    if($conn->query($sql) === TRUE){
        $_SESSION['catalog_msg'] = '<div class="alert alert-success col-sm-6 ml-5 mt-2">Service Deleted Successfully</div>';
    } else {
        $_SESSION['catalog_msg'] = '<div class="alert alert-danger col-sm-6 ml-5 mt-2">Unable to Delete Service</div>';
    }
    header("Location: manage_catalog.php");
    exit();
}


//delete models
if(isset($_GET['delete_model'])){
    $model_id = $_GET['delete_model'];
    // Check if model has services
    $check_sql = "SELECT COUNT(*) as count FROM services_tb WHERE model_id = '$model_id'";
    $check_result = $conn->query($check_sql);
    $check_row = $check_result->fetch_assoc();
    
    if($check_row['count'] > 0){
        $_SESSION['catalog_msg'] = '<div class="alert alert-warning col-sm-6 ml-5 mt-2">Cannot delete model. Please delete associated services first.</div>';
    } else {
        $sql = "DELETE FROM models_tb WHERE model_id = '$model_id'";
        if($conn->query($sql) === TRUE){
            $_SESSION['catalog_msg'] = '<div class="alert alert-success col-sm-6 ml-5 mt-2">Model Deleted Successfully</div>';
        } else {
            $_SESSION['catalog_msg'] = '<div class="alert alert-danger col-sm-6 ml-5 mt-2">Unable to Delete Model</div>';
        }
    }
    header("Location: manage_catalog.php");
    exit();
}

//delete sub categories
if(isset($_GET['delete_subcategory'])){
    $sub_id = $_GET['delete_subcategory'];
    // Check if subcategory has models
    $check_sql = "SELECT COUNT(*) as count FROM models_tb WHERE sub_id = '$sub_id'";
    $check_result = $conn->query($check_sql);
    $check_row = $check_result->fetch_assoc();
    
    if($check_row['count'] > 0){
        $_SESSION['catalog_msg'] = '<div class="alert alert-warning col-sm-6 ml-5 mt-2">Cannot delete subcategory. Please delete associated models first.</div>';
    } else {
        $sql = "DELETE FROM subcategories_tb WHERE sub_id = '$sub_id'";
        if($conn->query($sql) === TRUE){
            $_SESSION['catalog_msg'] = '<div class="alert alert-success col-sm-6 ml-5 mt-2">Subcategory Deleted Successfully</div>';
        } else {
            $_SESSION['catalog_msg'] = '<div class="alert alert-danger col-sm-6 ml-5 mt-2">Unable to Delete Subcategory</div>';
        }
    }
    header("Location: manage_catalog.php");
    exit();
}

//delete categories
if(isset($_GET['delete_category'])){
    $cat_id = $_GET['delete_category'];
    // Check if category has subcategories
    $check_sql = "SELECT COUNT(*) as count FROM subcategories_tb WHERE category_id = '$cat_id'";
    $check_result = $conn->query($check_sql);
    $check_row = $check_result->fetch_assoc();
    
    if($check_row['count'] > 0){
        $_SESSION['catalog_msg'] = '<div class="alert alert-warning col-sm-6 ml-5 mt-2">Cannot delete category. Please delete associated subcategories first.</div>';
    } else {
        $sql = "DELETE FROM categories_tb WHERE category_id = '$cat_id'";
        if($conn->query($sql) === TRUE){
            $_SESSION['catalog_msg'] = '<div class="alert alert-success col-sm-6 ml-5 mt-2">Category Deleted Successfully</div>';
        } else {
            $_SESSION['catalog_msg'] = '<div class="alert alert-danger col-sm-6 ml-5 mt-2">Unable to Delete Category</div>';
        }
    }
    header("Location: manage_catalog.php");
    exit();
}

// Handle Update Operations

//update categories
if(isset($_POST['update_category'])){
    $cat_id = $_POST['category_id'];
    $cat_name = $_POST['category_name'];
    $sql = "UPDATE categories_tb SET category_name = '$cat_name' WHERE category_id = '$cat_id'";
    if($conn->query($sql) === TRUE){
        $_SESSION['catalog_msg'] = '<div class="alert alert-success col-sm-6 ml-5 mt-2">Category Updated Successfully</div>';
    } else {
        $_SESSION['catalog_msg'] = '<div class="alert alert-danger col-sm-6 ml-5 mt-2">Unable to Update Category</div>';
    }
    header("Location: manage_catalog.php");
    exit();
}

//update sub categories
if(isset($_POST['update_subcategory'])){
    $sub_id = $_POST['subcategory_id'];
    $sub_name = $_POST['subcategory_name'];
    $sql = "UPDATE subcategories_tb SET sub_name = '$sub_name' WHERE sub_id = '$sub_id'";
    if($conn->query($sql) === TRUE){
        $_SESSION['catalog_msg'] = '<div class="alert alert-success col-sm-6 ml-5 mt-2">Subcategory Updated Successfully</div>';
    } else {
        $_SESSION['catalog_msg'] = '<div class="alert alert-danger col-sm-6 ml-5 mt-2">Unable to Update Subcategory</div>';
    }
    header("Location: manage_catalog.php");
    exit();
}

//update models
if(isset($_POST['update_model'])){
    $model_id = $_POST['model_id'];
    $model_name = $_POST['model_name'];
    $sql = "UPDATE models_tb SET model_name = '$model_name' WHERE model_id = '$model_id'";
    if($conn->query($sql) === TRUE){
        $_SESSION['catalog_msg'] = '<div class="alert alert-success col-sm-6 ml-5 mt-2">Model Updated Successfully</div>';
    } else {
        $_SESSION['catalog_msg'] = '<div class="alert alert-danger col-sm-6 ml-5 mt-2">Unable to Update Model</div>';
    }
    header("Location: manage_catalog.php");
    exit();
}

//update services
if(isset($_POST['update_service'])){
    $service_id = $_POST['service_id'];
    $service_name = $_POST['service_name'];
    $service_price = $_POST['service_price'];
    $sql = "UPDATE services_tb SET service_name = '$service_name', service_price = '$service_price' WHERE service_id = '$service_id'";
    if($conn->query($sql) === TRUE){
        $_SESSION['catalog_msg'] = '<div class="alert alert-success col-sm-6 ml-5 mt-2">Service Updated Successfully</div>';
    } else {
        $_SESSION['catalog_msg'] = '<div class="alert alert-danger col-sm-6 ml-5 mt-2">Unable to Update Service</div>';
    }
    header("Location: manage_catalog.php");
    exit();
}

// Handle Form Submissions (POST-Redirect-GET Pattern)
if(isset($_POST['add_category'])){
    $cat_name = $_REQUEST['category_name'];
    $check = $conn->query("SELECT category_id FROM categories_tb WHERE LOWER(category_name) = LOWER('$cat_name')");
    if($check->num_rows > 0){
      $_SESSION['catalog_msg'] = '<div class="alert alert-warning col-sm-6 ml-5 mt-2">Category already exists. Add a new category.</div>';
  } else {
    $sql = "INSERT INTO categories_tb (category_name) VALUES ('$cat_name')";

    if($conn->query($sql) === TRUE){
        $_SESSION['catalog_msg'] = '<div class="alert alert-success col-sm-6 ml-5 mt-2">Category Added Successfully</div>';
    } else {
        $_SESSION['catalog_msg'] = '<div class="alert alert-danger col-sm-6 ml-5 mt-2">Unable to Add Category</div>';
    }
    header("Location: manage_catalog.php");
    exit();
}
}

if(isset($_POST['add_subcategory'])){
    $cat_id = $_REQUEST['category_id'];
    $sub_name = $_REQUEST['subcategory_name'];
    $check = $conn->query("SELECT sub_id, category_id, sub_name FROM subcategories_tb WHERE category_id = $cat_id AND LOWER(sub_name) = LOWER('$sub_name')");
    if($check->num_rows > 0){
      $_SESSION['catalog_msg'] = '<div class="alert alert-warning col-sm-6 ml-5 mt-2">Sub Category already exists. Add a new subcategory.</div>';
  } else {
    $sql = "INSERT INTO subcategories_tb (category_id, sub_name) VALUES ('$cat_id', '$sub_name')";
    if($conn->query($sql) === TRUE){
        $_SESSION['catalog_msg'] = '<div class="alert alert-success col-sm-6 ml-5 mt-2">Subcategory Added Successfully</div>';
    } else {
        $_SESSION['catalog_msg'] = '<div class="alert alert-danger col-sm-6 ml-5 mt-2">Unable to Add Subcategory</div>';
    }
    header("Location: manage_catalog.php");
    exit();
}
}

if(isset($_POST['add_model'])){
    $sub_id = $_REQUEST['subcategory_id'];
    $model_name = $_REQUEST['model_name'];
    $check = $conn->query("SELECT model_id,sub_id, model_name FROM models_tb WHERE sub_id = $sub_id AND LOWER(model_name) = LOWER('$model_name')");
    if($check->num_rows > 0){
      $_SESSION['catalog_msg'] = '<div class="alert alert-warning col-sm-6 ml-5 mt-2">Model already exists. Add a new model.</div>';
  } else {
    $sql = "INSERT INTO models_tb (sub_id, model_name) VALUES ('$sub_id', '$model_name')";
    if($conn->query($sql) === TRUE){
        $_SESSION['catalog_msg'] = '<div class="alert alert-success col-sm-6 ml-5 mt-2">Model Added Successfully</div>';
    } else {
        $_SESSION['catalog_msg'] = '<div class="alert alert-danger col-sm-6 ml-5 mt-2">Unable to Add Model</div>';
    }
    header("Location: manage_catalog.php");
    exit();
}
}

if(isset($_POST['add_service'])){
    $model_id = $_REQUEST['model_id'];
    $service_name = $_REQUEST['service_name'];
    $service_price = $_REQUEST['service_price'];
    $check = $conn->query("SELECT service_id, model_id, service_name, service_price FROM services_tb WHERE model_id = $model_id AND LOWER(service_name) = LOWER('$service_name')");
    if($check->num_rows > 0){
      $_SESSION['catalog_msg'] = '<div class="alert alert-warning col-sm-6 ml-5 mt-2">Service Name already exists. Add a new service.</div>';
  } else {
    $sql = "INSERT INTO services_tb (model_id, service_name, service_price) VALUES ('$model_id', '$service_name', '$service_price')";
    if($conn->query($sql) === TRUE){
        $_SESSION['catalog_msg'] = '<div class="alert alert-success col-sm-6 ml-5 mt-2">Service Added Successfully</div>';
    } else {
        $_SESSION['catalog_msg'] = '<div class="alert alert-danger col-sm-6 ml-5 mt-2">Unable to Add Service</div>';
    }
    header("Location: manage_catalog.php");
    exit();
}
}
// Display session message and clear it. Display CRUD message
$msg = '';
if(isset($_SESSION['catalog_msg'])){
    $msg = $_SESSION['catalog_msg'];
    unset($_SESSION['catalog_msg']);
}
?>

<div class="col-sm-9 col-md-10 mt-5 text-center">
  <p class="bg-dark text-white p-2">Manage Service Catalog</p>
  <?php if($msg != '') {echo $msg; } ?>
  
  <div class="row">
    <!-- Add Category -->
    <div class="col-md-3">
        <h5>Add Category</h5>
        <form action="" method="POST">
            <div class="form-group">
                <input type="text" class="form-control" name="category_name" placeholder="Category Name" required>
            </div>
            <button type="submit" class="btn btn-primary" name="add_category">Add</button>
        </form>
    </div>

    <!-- Add Subcategory -->
    <div class="col-md-3">
        <h5>Add Subcategory</h5>
        <form action="" method="POST">
            <div class="form-group">
                <select class="form-control" name="category_id" required>
                    <option value="">Select Category</option>
                    <?php
                    $sql = "SELECT * FROM categories_tb";
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()){
                        echo "<option value='".$row['category_id']."'>".$row['category_name']."</option>";
                    }
                    ?>
                </select>
                <input type="text" class="form-control mt-2" name="subcategory_name" placeholder="Subcategory Name" required>
            </div>
            <button type="submit" class="btn btn-primary" name="add_subcategory">Add</button>
        </form>
    </div>

    <!-- Add Model -->
    <div class="col-md-3">
        <h5>Add Model</h5>
        <form action="" method="POST">
            <div class="form-group">
                <select class="form-control" name="subcategory_id" required>
                    <option value="">Select Subcategory</option>
                    <?php
                    $sql = "SELECT sub_id, sub_name, category_name FROM subcategories_tb JOIN categories_tb ON subcategories_tb.category_id = categories_tb.category_id";
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()){
                        echo "<option value='".$row['sub_id']."'>".$row['category_name']." - ".$row['sub_name']."</option>";
                    }
                    ?>
                </select>
                <input type="text" class="form-control mt-2" name="model_name" placeholder="Model Name" required>
            </div>
            <button type="submit" class="btn btn-primary" name="add_model">Add</button>
        </form>
    </div>

    <!-- Add Service -->
    <div class="col-md-3">
        <h5>Add Service</h5>
        <form action="" method="POST">
            <div class="form-group">
                 <select class="form-control" name="model_id" required>
                    <option value="">Select Model</option>
                    <?php
                    $sql = "SELECT model_id, model_name, sub_name FROM models_tb JOIN subcategories_tb ON models_tb.sub_id = subcategories_tb.sub_id";
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()){
                        echo "<option value='".$row['model_id']."'>".$row['sub_name']." - ".$row['model_name']."</option>";
                    }
                    ?>
                </select>
                <input type="text" class="form-control mt-2" name="service_name" placeholder="Service Name" required>
                <input type="number" class="form-control mt-2" name="service_price" min="1" placeholder="Price" required>
            </div>
            <button type="submit" class="btn btn-primary" name="add_service">Add</button>
        </form>
    </div>
  </div>

  <!-- Display Catalog with Edit/Delete -->
  <div class="mt-5">
    <div class="d-flex justify-content-between mb-3 align-items-center flex-wrap">
      <h5 class="mb-2"><i class="fas fa-list-alt"></i> Current Catalog</h5>
      <form action="" method="GET" class="form-inline mb-2">
          <label class="mr-2 font-weight-bold">Filter by Category:</label>
          <select name="cat_id" class="form-control" onchange="this.form.submit()">
              <option value="All">All Categories</option>
              <?php
              $cat_filter = isset($_GET['cat_id']) ? $_GET['cat_id'] : 'All';
              $cat_sql = "SELECT * FROM categories_tb ORDER BY category_name";
              $cat_res = $conn->query($cat_sql);
              while($cat_row = $cat_res->fetch_assoc()){
                  $selected = ($cat_filter == $cat_row['category_id']) ? 'selected' : '';
                  echo "<option value='{$cat_row['category_id']}' {$selected}>{$cat_row['category_name']}</option>";
              }
              ?>
          </select>
      </form>
    </div>
    
    <div class="table-responsive-sm mb-5">
      <table id="dataTableID" class="table table-bordered table-hover">
          <thead class="thead-dark">
              <tr>
                  <th>Category</th>
                  <th>Subcategory</th>
                  <th>Model</th>
                  <th>Service</th>
                  <th>Price</th>
                  <th>Actions</th>
              </tr>
          </thead>
          <tbody>
              <?php
              $sql = "SELECT c.category_id, c.category_name, s.sub_id, s.sub_name, m.model_id, m.model_name, 
                      sv.service_id, sv.service_name, sv.service_price 
                      FROM services_tb sv 
                      JOIN models_tb m ON sv.model_id = m.model_id
                      JOIN subcategories_tb s ON m.sub_id = s.sub_id
                      JOIN categories_tb c ON s.category_id = c.category_id";
              
              if($cat_filter != 'All') {
                  $sql .= " WHERE c.category_id = '$cat_filter'";
              }
              
              $sql .= " ORDER BY c.category_name, s.sub_name, m.model_name";
              $result = $conn->query($sql);
              while($row = $result->fetch_assoc()){
                  echo "<tr>
                    <td>{$row['category_name']}</td>
                    <td>{$row['sub_name']}</td>
                    <td>{$row['model_name']}</td>
                    <td>{$row['service_name']}</td>
                    <td>{$row['service_price']}</td>
                    <td>
                      <button class='btn btn-sm btn-info' onclick='editService({$row['service_id']}, \"{$row['service_name']}\", {$row['service_price']})'>
                        <i class='fas fa-edit'></i>
                      </button>
                      <a href='?delete_service={$row['service_id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this service?\")'>
                        <i class='fas fa-trash'></i>
                      </a>
                    </td>
                  </tr>";
              }
              ?>
          </tbody>
      </table>
    </div>
  </div>

  <!-- Separate Management Tables -->
  <div class="row mt-5">
      <!-- Categories Table -->
      <div class="col-md-4">
          <h5>Manage Categories</h5>
          <table class="table table-sm table-bordered">
              <thead class="thead-light">
                  <tr>
                      <th>Category Name</th>
                      <th>Actions</th>
                  </tr>
              </thead>
              <tbody>
                  <?php
                  $sql = "SELECT * FROM categories_tb ORDER BY category_name";
                  $result = $conn->query($sql);
                  while($row = $result->fetch_assoc()){
                      echo "<tr>
                        <td>{$row['category_name']}</td>
                        <td>
                          <button class='btn btn-sm btn-info' onclick='editCategory({$row['category_id']}, \"{$row['category_name']}\")'>
                            <i class='fas fa-edit'></i>
                          </button>
                          <a href='?delete_category={$row['category_id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this category?\")'>
                            <i class='fas fa-trash'></i>
                          </a>
                        </td>
                      </tr>";
                  }
                  ?>
              </tbody>
          </table>
      </div>

      <!-- Subcategories Table -->
      <div class="col-md-4">
          <h5>Manage Subcategories</h5>
          <table class="table table-sm table-bordered">
              <thead class="thead-light">
                  <tr>
                      <th>Subcategory</th>
                      <th>Category</th>
                      <th>Actions</th>
                  </tr>
              </thead>
              <tbody>
                  <?php
                  $sql = "SELECT s.sub_id, s.sub_name, c.category_name 
                          FROM subcategories_tb s 
                          JOIN categories_tb c ON s.category_id = c.category_id 
                          ORDER BY c.category_name, s.sub_name";
                  $result = $conn->query($sql);
                  while($row = $result->fetch_assoc()){
                      echo "<tr>
                        <td>{$row['sub_name']}</td>
                        <td>{$row['category_name']}</td>
                        <td>
                          <button class='btn btn-sm btn-info' onclick='editSubcategory({$row['sub_id']}, \"{$row['sub_name']}\")'>
                            <i class='fas fa-edit'></i>
                          </button>
                          <a href='?delete_subcategory={$row['sub_id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this subcategory?\")'>
                            <i class='fas fa-trash'></i>
                          </a>
                        </td>
                      </tr>";
                  }
                  ?>
              </tbody>
          </table>
      </div>

      <!-- Models Table -->
      <div class="col-md-4">
          <h5>Manage Models</h5>
          <table class="table table-sm table-bordered">
              <thead class="thead-light">
                  <tr>
                      <th>Model</th>
                      <th>Subcategory</th>
                      <th>Actions</th>
                  </tr>
              </thead>
              <tbody>
                  <?php
                  $sql = "SELECT m.model_id, m.model_name, s.sub_name 
                          FROM models_tb m 
                          JOIN subcategories_tb s ON m.sub_id = s.sub_id 
                          ORDER BY s.sub_name, m.model_name";
                  $result = $conn->query($sql);
                  while($row = $result->fetch_assoc()){
                      echo "<tr>
                        <td>{$row['model_name']}</td>
                        <td>{$row['sub_name']}</td>
                        <td>
                          <button class='btn btn-sm btn-info' onclick='editModel({$row['model_id']}, \"{$row['model_name']}\")'>
                            <i class='fas fa-edit'></i>
                          </button>
                          <a href='?delete_model={$row['model_id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this model?\")'>
                            <i class='fas fa-trash'></i>
                          </a>
                        </td>
                      </tr>";
                  }
                  ?>
              </tbody>
          </table>
      </div>
  </div>
</div>

<!-- Edit Modals -->
<!-- Category Edit Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Category</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form method="POST">
        <div class="modal-body">
          <input type="hidden" name="category_id" id="edit_category_id">
          <div class="form-group">
            <label>Category Name</label>
            <input type="text" class="form-control" name="category_name" id="edit_category_name" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" name="update_category">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Subcategory Edit Modal -->
<div class="modal fade" id="editSubcategoryModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Subcategory</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form method="POST">
        <div class="modal-body">
          <input type="hidden" name="subcategory_id" id="edit_subcategory_id">
          <div class="form-group">
            <label>Subcategory Name</label>
            <input type="text" class="form-control" name="subcategory_name" id="edit_subcategory_name" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" name="update_subcategory">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Model Edit Modal -->
<div class="modal fade" id="editModelModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Model</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form method="POST">
        <div class="modal-body">
          <input type="hidden" name="model_id" id="edit_model_id">
          <div class="form-group">
            <label>Model Name</label>
            <input type="text" class="form-control" name="model_name" id="edit_model_name" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" name="update_model">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Service Edit Modal -->
<div class="modal fade" id="editServiceModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Service</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form method="POST">
        <div class="modal-body">
          <input type="hidden" name="service_id" id="edit_service_id">
          <div class="form-group">
            <label>Service Name</label>
            <input type="text" class="form-control" name="service_name" id="edit_service_name" required>
          </div>
          <div class="form-group">
            <label>Service Price</label>
            <input type="number" class="form-control" name="service_price" min="1" id="edit_service_price" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" name="update_service">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// edit category by clicking edit icon in manage categories table
function editCategory(id, name) {
    $('#edit_category_id').val(id);
    $('#edit_category_name').val(name);
    $('#editCategoryModal').modal('show');
}
// edit sub category by clicking edit icon in manage sub categories table
function editSubcategory(id, name) {
    $('#edit_subcategory_id').val(id);
    $('#edit_subcategory_name').val(name);
    $('#editSubcategoryModal').modal('show');
}

// edit model by clicking edit icon in manage models table
function editModel(id, name) {
    $('#edit_model_id').val(id);
    $('#edit_model_name').val(name);
    $('#editModelModal').modal('show');
}

// edit service and price by clicking edit icon in current catalog table
function editService(id, name, price) {
    $('#edit_service_id').val(id);
    $('#edit_service_name').val(name);
    $('#edit_service_price').val(price);
    $('#editServiceModal').modal('show');
}
</script>

<?php
include('includes/footer.php'); 
?>