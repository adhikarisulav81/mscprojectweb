<?php
define('TITLE', 'Assign Work');
define('PAGE', 'request');
include('includes/header.php'); 
include('../dbConnection.php');
include('../emailConfig.php');

session_start();

// ─── Login Check ───────────────────────────────────────────────────────────────
if(isset($_SESSION['is_adminlogin'])){
    $aEmail = $_SESSION['aEmail'];
} else {
    header("Location: adminLogin.php");
    exit();
}

// ─── Load Request Data ─────────────────────────────────────────────────────────
if(isset($_REQUEST['id'])){
    $rid = intval($_REQUEST['id']);

    // Check if already assigned — redirect away if so
    $check_sql    = "SELECT * FROM assignwork_tb WHERE request_id = '$rid'";
    $check_result = $conn->query($check_sql);

    if($check_result->num_rows > 0){
        header("Location: work.php");
        exit();
    } else {
        // Not assigned yet — load original request details to pre-fill form
        $sql    = "SELECT * FROM submitrequest_tb WHERE request_id = '$rid'";
        $result = $conn->query($sql);
        $row    = $result->fetch_assoc();
    }
}

// ─── Handle Manual Assign Form Submission ─────────────────────────────────────
if(isset($_REQUEST['assign'])){

    if(($_REQUEST['request_id']      == "") || ($_REQUEST['request_info']   == "") ||
       ($_REQUEST['requestdesc']     == "") || ($_REQUEST['requestername']  == "") ||
       ($_REQUEST['address1']        == "") || ($_REQUEST['requesteremail'] == "") ||
       ($_REQUEST['requestermobile'] == "") || ($_REQUEST['assigntech']     == "") ||
       ($_REQUEST['inputdate']       == "")){
        $msg = '<div class="alert alert-warning col-sm-6 mt-2" role="alert"> Fill All Fields </div>';

    } else {

        $rid         = $_REQUEST['request_id'];
        $rinfo       = $_REQUEST['request_info'];
        $rdesc       = $_REQUEST['requestdesc'];
        $rname       = $_REQUEST['requestername'];
        $radd1       = $_REQUEST['address1'];
        $remail      = $_REQUEST['requesteremail'];
        $rmobile     = $_REQUEST['requestermobile'];
        $rassigntech = $_REQUEST['assigntech'];
        $rdate       = $_REQUEST['inputdate'];
        $status      = isset($_REQUEST['status'])       ? $_REQUEST['status']       : 'Assigned';
        // $ddate       = isset($_REQUEST['deliveryDate']) ? $_REQUEST['deliveryDate'] : NULL;
        $rpriority   = $_REQUEST['priority'];

        // Fetch price from DB — never trust form input for price
        $price_sql     = "SELECT service_price FROM submitrequest_tb WHERE request_id = '$rid'";
        $price_res     = $conn->query($price_sql);
        $price_row     = $price_res->fetch_assoc();
        $rserviceprice = $price_row['service_price'];

        $sql = "INSERT INTO assignwork_tb 
                    (request_id, request_info, request_desc, requester_name, requester_add1,
                     requester_email, requester_mobile, assign_tech, assign_date, status,
                     priority, service_price) 
                VALUES 
                    ('$rid', '$rinfo', '$rdesc', '$rname', '$radd1',
                     '$remail', '$rmobile', '$rassigntech', '$rdate', '$status',
                      '$rpriority', '$rserviceprice')";

        if($conn->query($sql) == TRUE){
            $msg = '<div class="alert alert-success col-sm-6 mt-2" role="alert"> Work Assigned Successfully </div>';
            echo "<script>setTimeout(function(){ location.href='work.php'; }, 2000);</script>";
            sendWorkAssignedToTechnician($rassigntech, $tech_email, $rname, $rid, $rinfo, $rdate);

          // Check technician workload and send appropriate user notification
          $workload_sql = "SELECT COUNT(*) as active_jobs FROM assignwork_tb WHERE assign_tech = '$rassigntech' AND status != 'Completed' AND request_id != '$rid'";
          $workload_res = $conn->query($workload_sql);
          $workload_row = $workload_res->fetch_assoc();
          
          if($workload_row['active_jobs'] > 0){
            // Technician is busy
            sendTechnicianBusyNotification($rname, $remail, $rassigntech, $rid);
          } else {
            // Send Work Assigned Notification
            sendWorkAssignedToUser($rname, $remail, $rassigntech, $rid, $rdesc);
          }
        } else {
            $msg = '<div class="alert alert-danger col-sm-6 mt-2" role="alert"> Unable to Assign Work </div>';
        }
    }
}
?>

<div class="col-sm-9 col-md-10 mt-5">

    <h3 class="title text-center font-weight-bold text-dark"
        style="font-family: Arial, Helvetica, sans-serif;">
        <i class="fas fa-briefcase"></i> ASSIGN <span>WORK FORM</span>
    </h3>

    <div class="alert alert-info mx-3 mt-3">
        <i class="fas fa-info-circle"></i>
        Requests are <strong>automatically assigned</strong> to the most available technician
        when you click <strong>Assign</strong> from the Pending Requests page.
        Use this form for manual override if needed.
    </div>

    <form action="" method="POST">

        <div class="form-group">
            <label for="request_id"><i class="fas fa-id-card-alt"></i> Request ID</label>
            <input type="text" class="form-control" id="request_id" name="request_id"
                   value="<?php if(isset($row['request_id'])) { echo $row['request_id']; } ?>" readonly>
        </div>

        <div class="form-group">
            <label for="request_info"><i class="fas fa-info-circle"></i> Request Info</label>
            <input type="text" class="form-control" id="request_info" name="request_info"
                   value="<?php if(isset($row['request_info'])) { echo $row['request_info']; } ?>" readonly>
        </div>

        <div class="form-group">
            <label for="priority"><i class="fas fa-exclamation-circle"></i> Priority</label>
            <input type="text" class="form-control" id="priority" name="priority"
                   value="<?php echo (isset($row['priority']) && $row['priority'] != '') ? $row['priority'] : 'Normal'; ?>" readonly>
        </div>

        <div class="form-group">
            <label for="requestdesc"><i class="fas fa-pen-nib"></i> Description</label>
            <input type="text" class="form-control" id="requestdesc" name="requestdesc"
                   value="<?php if(isset($row['request_desc'])) { echo $row['request_desc']; } ?>" readonly>
        </div>

        <div class="form-group">
            <label for="requestername"><i class="fas fa-users"></i> Customer Name</label>
            <input type="text" class="form-control" id="requestername" name="requestername"
                   value="<?php if(isset($row['requester_name'])) { echo $row['requester_name']; } ?>" readonly>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="address1"><i class="fas fa-map-marker-alt"></i> Address</label>
                <input type="text" class="form-control" id="address1" name="address1"
                       value="<?php if(isset($row['requester_add1'])) { echo $row['requester_add1']; } ?>" readonly>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-8">
                <label for="requesteremail"><i class="far fa-envelope"></i> Email</label>
                <input type="email" class="form-control" id="requesteremail" name="requesteremail"
                       value="<?php if(isset($row['requester_email'])) { echo $row['requester_email']; } ?>" readonly>
            </div>
            <div class="form-group col-md-4">
                <label for="requestermobile"><i class="fas fa-mobile"></i> Mobile</label>
                <input type="text" class="form-control" id="requestermobile" name="requestermobile"
                       pattern="[9][0-9]{9}" title="Start with 9, must be 10 digits"
                       value="<?php if(isset($row['requester_mobile'])) { echo $row['requester_mobile']; } ?>"
                       onkeypress="isInputNumber(event)" readonly>
            </div>
        </div>

        <div class="form-row">

            <!-- Technician dropdown — sorted by fewest active jobs, then highest rating -->
            <div class="form-group col-md-6">
                <label for="assigntech">
                    <i class="fas fa-chalkboard-teacher"></i> Assign to Technician
                </label>
                <select class="form-control" id="assigntech" name="assigntech">
                    <option value="">Select Technician</option>
                    <?php
                    // $sql_tech = "SELECT t.empName, t.empRating,
                    //                     COUNT(CASE WHEN a.status NOT IN ('Completed','Rejected') THEN 1 END) AS active_count
                    //              FROM technician_tb t
                    //              LEFT JOIN assignwork_tb a ON t.empName = a.assign_tech
                    //              GROUP BY t.empName, t.empRating
                    //              ORDER BY active_count ASC, t.empRating DESC";
                    $sql_tech = "SELECT t.empName, t.empEmail,
                                        COUNT(CASE WHEN a.status NOT IN ('Completed','Rejected') THEN 1 END) AS active_count
                                 FROM technician_tb t
                                 LEFT JOIN assignwork_tb a ON t.empName = a.assign_tech
                                 GROUP BY t.empName, t.empEmail
                                 ORDER BY active_count ASC";

                    $result_tech = $conn->query($sql_tech);
                    if($result_tech->num_rows > 0){
                        while($row_tech = $result_tech->fetch_assoc()){
                            $tech_name    = $row_tech["empName"];
                            $tech_email    = $row_tech["empEmail"];

                            // $rating       = $row_tech["empRating"];
                            $active_count = $row_tech["active_count"];

                            $active_label = ($active_count > 0)
                                ? " [" . $active_count . " active jobs]"
                                : " [Available]";

                            if($rating > 4)       { $label = " (Highly Rated - " .(int)$rating." works) [Very Busy]"; }
                            elseif($rating >= 2)  { $label = " (Medium Rated - " .(int)$rating." works) [Moderate]";  }
                            elseif($rating > 0)   { $label = " (Low Rated - "    .(int)$rating." works) [Less Busy]"; }
                            else                  { $label = " (No Rating) [Available]"; }

                            echo '<option value="' . $tech_name . '">'
                                . $tech_name . $label . $active_label . '</option>';
                        }
                    }
                    ?>
                </select>
                <small class="form-text text-muted">
                    <i class="fas fa-sort-amount-up"></i>
                    Sorted by availability — fewest active jobs first.
                </small>
            </div>

            <!-- Assigned Date — today only -->
            <div class="form-group col-md-6">
                <label for="inputDate"><i class="fas fa-calendar-alt"></i> Assigned Date</label>
                <input type="date" class="form-control" id="inputDate" name="inputdate"
                       value="<?php echo date('Y-m-d'); ?>"
                       min="<?php echo date('Y-m-d'); ?>">
            </div>

            <script>
                const assignToday     = new Date().toISOString().split('T')[0];
                const assignDateInput = document.getElementById('inputDate');
                assignDateInput.min   = assignToday;
                assignDateInput.max   = assignToday;
            </script>

            <!-- Hidden status — always Assigned for new assignments -->
            <input type="hidden" name="status" value="Assigned">

            <!-- Delivery Date -->
            <!-- <div class="form-group col-md-6"> -->
                <!-- <label for="deliveryDate"><i class="fas fa-calendar-alt"></i> Delivery Date</label> -->
                <!-- <input type="date" class="form-control" id="deliveryDate" name="deliveryDate" value=" -->
                <?php /*if(isset($row['deliveryDate'])) { echo $row['deliveryDate']; } ?>" min="<?php echo date('Y-m-d'); */?>
                <!-- "> -->
            </div>

        </div>

        <div class="float-right mb-5">
            <button type="submit" class="btn btn-success" name="assign">
                <i class="fas fa-user-check"></i> Assign
            </button>
            <a href="request.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

    </form>

    <div class="mt-5">
        <?php if(isset($msg)) { echo $msg; } ?>
    </div>

</div>

<script>
    function isInputNumber(evt){
        var ch = String.fromCharCode(evt.which);
        if(!(/[0-9]/.test(ch))){ evt.preventDefault(); }
    }
</script>

<?php
include('includes/footer.php');
$conn->close();
?>