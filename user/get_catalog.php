<?php
header('Content-Type: application/json');
include('../dbConnection.php');

$action = $_GET['action'] ?? '';

if ($action == 'get_categories') {
    $sql = "SELECT * FROM categories_tb ORDER BY category_name"; //Fetches all categories from categories_tb sorted alphabetically

    $result = $conn->query($sql);
    $data = []; //create an empty array to collect results

    if($result){
        while ($row = $result->fetch_assoc()) { //The while loop goes through every row in the result and adds it to $data

            $data[] = $row; //push this row onto the end of the array
        }
    }
    echo json_encode($data); //json_encode($data) converts the PHP array into a JSON string and sends it back


} elseif ($action == 'get_subcategories') {
    $cat_id = $_GET['category_id'];
    $sql = "SELECT * FROM subcategories_tb WHERE category_id = '$cat_id' ORDER BY sub_name";
    $result = $conn->query($sql);
    $data = [];
    if($result){
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    echo json_encode($data);
} elseif ($action == 'get_models') {
    $sub_id = $_GET['sub_id'];
    $sql = "SELECT * FROM models_tb WHERE sub_id = '$sub_id' ORDER BY model_name";
    $result = $conn->query($sql);
    $data = [];
    if($result){
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    echo json_encode($data);
} elseif ($action == 'get_services') {
    $model_id = $_GET['model_id'];
    $sql = "SELECT * FROM services_tb WHERE model_id = '$model_id' ORDER BY service_name";
    $result = $conn->query($sql);
    $data = [];
    if($result){
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    echo json_encode($data);
} elseif ($action == 'get_price') { //- Called when the user **picks a service**
//- Gets `service_id` from the AJAX request
    $service_id = $_GET['service_id'];
    $sql = "SELECT service_price FROM services_tb WHERE service_id = '$service_id'"; //- Fetches only the price for that specific service (not all columns)
    $result = $conn->query($sql);
    if($result && $row = $result->fetch_assoc()){
        echo json_encode($row); //If found sends back the price

    } else {
        echo json_encode(['service_price' => 0]); //- If not found sends back "0" as a safe default so the form doesn't break
    }
} else {
    echo json_encode(['error' => 'Invalid action']);
}
?>
