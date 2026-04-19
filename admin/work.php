<?php
define('TITLE', 'Work Order');
define('PAGE', 'work');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();

//Login Check
if(isset($_SESSION['is_adminlogin'])){
    $aEmail = $_SESSION['aEmail'];
} else {
    echo "<script> location.href='adminLogin.php'; </script>";
    exit();
}


// Both filters read from the URL and default to 'All' if not set. They run independently and simultaneously.
$priority_filter = isset($_GET['priority']) ? $_GET['priority'] : 'All';
$status_filter   = isset($_GET['status'])   ? $_GET['status']   : 'All';
?>

<div class="col-sm-9 col-md-10">

    <h3 class="title text-center font-weight-bold text-dark mb-3 mt-5"
        style="font-family: Arial, Helvetica, sans-serif;">
        <i class="fas fa-briefcase"></i> ALL <span>REQUESTS</span>
    </h3>

    <!-- ── Dual Filter Bar ────────────────────────────────────────────────────── -->
    <div class="mx-5 mb-3 d-flex justify-content-between align-items-center flex-wrap">

        <!-- Priority Filter — preserves current status filter in URL -->
        <div class="btn-group mb-2" role="group" aria-label="Priority Filter">
            <a href="work.php?priority=All<?php    echo isset($_GET['status']) ? '&status='.$_GET['status'] : ''; ?>"
               class="btn <?php echo ($priority_filter == 'All')    ? 'btn-primary' : 'btn-outline-primary'; ?>">
                <i class="fas fa-list"></i> All Priorities
            </a>
            <a href="work.php?priority=High<?php   echo isset($_GET['status']) ? '&status='.$_GET['status'] : ''; ?>"
               class="btn <?php echo ($priority_filter == 'High')   ? 'btn-danger'  : 'btn-outline-danger'; ?>">
                <i class="fas fa-arrow-up"></i> High
            </a>
            <a href="work.php?priority=Normal<?php echo isset($_GET['status']) ? '&status='.$_GET['status'] : ''; ?>"
               class="btn <?php echo ($priority_filter == 'Normal') ? 'btn-success' : 'btn-outline-success'; ?>">
                <i class="fas fa-minus"></i> Normal
            </a>
        </div>

        <!-- Status Filter — preserves current priority filter in URL -->
        <div class="btn-group mb-2 flex-wrap" role="group" aria-label="Status Filter">
            <a href="work.php?status=All<?php         echo isset($_GET['priority']) ? '&priority='.$_GET['priority'] : ''; ?>"
               class="btn <?php echo ($status_filter == 'All')          ? 'btn-primary'   : 'btn-outline-primary'; ?>">All</a>

            <a href="work.php?status=Not Assigned<?php echo isset($_GET['priority']) ? '&priority='.$_GET['priority'] : ''; ?>"
               class="btn <?php echo ($status_filter == 'Not Assigned') ? 'btn-warning'   : 'btn-outline-warning'; ?>">Not Assigned</a>

            <a href="work.php?status=Assigned<?php    echo isset($_GET['priority']) ? '&priority='.$_GET['priority'] : ''; ?>"
               class="btn <?php echo ($status_filter == 'Assigned')     ? 'btn-dark'      : 'btn-outline-dark'; ?>">Assigned</a>

            <a href="work.php?status=Not Started<?php echo isset($_GET['priority']) ? '&priority='.$_GET['priority'] : ''; ?>"
               class="btn <?php echo ($status_filter == 'Not Started')  ? 'btn-info'      : 'btn-outline-info'; ?>">Not Started</a>

            <a href="work.php?status=Started<?php     echo isset($_GET['priority']) ? '&priority='.$_GET['priority'] : ''; ?>"
               class="btn <?php echo ($status_filter == 'Started')      ? 'btn-secondary' : 'btn-outline-secondary'; ?>">Started</a>

            <a href="work.php?status=Completed<?php   echo isset($_GET['priority']) ? '&priority='.$_GET['priority'] : ''; ?>"
               class="btn <?php echo ($status_filter == 'Completed')    ? 'btn-success'   : 'btn-outline-success'; ?>">Completed</a>

            <a href="work.php?status=Rejected<?php    echo isset($_GET['priority']) ? '&priority='.$_GET['priority'] : ''; ?>"
               class="btn <?php echo ($status_filter == 'Rejected')     ? 'btn-danger'    : 'btn-outline-danger'; ?>">Rejected</a>
        </div>

    </div>

    <?php

    // Unassigned: requests in submitrequest_tb with NO matching row in assignwork_tb
    // LEFT JOIN + WHERE a.request_id IS NULL finds truly unassigned requests
    $unassigned_base = "SELECT s.request_id, s.request_info, s.requester_name,
                                s.request_date, 'Not Assigned' AS status,
                                '-' AS assign_tech, s.priority
                         FROM submitrequest_tb s
                         LEFT JOIN assignwork_tb a ON s.request_id = a.request_id
                         WHERE a.request_id IS NULL";

    // Assigned/Completed/Rejected: all records in assignwork_tb
    // assign_date renamed to request_date so UNION columns match
    $assigned_base = "SELECT request_id, request_info, requester_name,
                             assign_date AS request_date, status, assign_tech, priority
                      FROM assignwork_tb";

    // Build Priority Filter Conditions, Two separate arrays because:
    // - unassigned query uses s.priority (table alias needed due to JOIN)
    // - assigned query uses plain priority (single table, no alias needed)
    $u_filter = [];
    $a_filter = [];
    if($priority_filter != 'All'){
        $u_filter[] = "s.priority = '$priority_filter'";
        $a_filter[] = "priority = '$priority_filter'";
    }

    // Build Final SQL Based on Status Filter
    if($status_filter == 'Not Started'){
        // "Not Started" = unassigned requests + requests with Assigned status (work not begun)
        $u_sql = $unassigned_base;
        $a_sql = $assigned_base . " WHERE status = 'Assigned'";
        if(!empty($u_filter)){
            $u_sql .= " AND " . implode(" AND ", $u_filter);
            $a_sql .= " AND " . implode(" AND ", $a_filter);
        }
        $sql = "($u_sql) UNION ($a_sql) ORDER BY request_date DESC";

    } elseif($status_filter == 'Assigned'){
        // Only Assigned status — single table query
        $a_sql = $assigned_base . " WHERE status = 'Assigned'";
        if(!empty($a_filter)){ $a_sql .= " AND " . implode(" AND ", $a_filter); }
        $sql = $a_sql . " ORDER BY request_date DESC";

    } elseif($status_filter == 'Not Assigned'){
        // unassigned (not in assignwork_tb at all)
        $u_sql = $unassigned_base;
        if(!empty($u_filter)){ $u_sql .= " AND " . implode(" AND ", $u_filter); }
        $sql = $u_sql . " ORDER BY request_date DESC";

    } elseif($status_filter == 'Started'){
        // Work Started status — single table
        $a_sql = $assigned_base . " WHERE status = 'Work Started'";
        if(!empty($a_filter)){ $a_sql .= " AND " . implode(" AND ", $a_filter); }
        $sql = $a_sql . " ORDER BY request_date DESC";

    } elseif($status_filter == 'Completed'){
        // Completed — single table
        $a_sql = $assigned_base . " WHERE status = 'Completed'";
        if(!empty($a_filter)){ $a_sql .= " AND " . implode(" AND ", $a_filter); }
        $sql = $a_sql . " ORDER BY request_date DESC";

    } elseif($status_filter == 'Rejected'){
        // Rejected — single table
        $a_sql = $assigned_base . " WHERE status = 'Rejected'";
        if(!empty($a_filter)){ $a_sql .= " AND " . implode(" AND ", $a_filter); }
        $sql = $a_sql . " ORDER BY request_date DESC";

    } else {
        // All — UNION of both tables (unassigned + all assigned records)
        $u_sql = $unassigned_base;
        $a_sql = $assigned_base;
        if(!empty($u_filter)){
            // Unassigned already has WHERE, so use AND
            $u_sql .= " AND "   . implode(" AND ", $u_filter);
            // Assigned has no WHERE yet, so use WHERE
            $a_sql .= " WHERE " . implode(" AND ", $a_filter);
        }
        $sql = "($u_sql) UNION ($a_sql) ORDER BY request_date DESC";
    }

    $result = $conn->query($sql);

    // Table
    if($result->num_rows > 0):
        echo '<div class="table-responsive-sm mx-5">';
        echo '<table id="dataTableID" class="table table-hover">
              <thead>
                <tr>
                  <th>Req ID</th>
                  <th style="max-width:150px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                      Request Info
                  </th>
                  <th>Customer</th>
                  <th>Priority</th>
                  <th>Technician</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>';

        while($row = $result->fetch_assoc()):
            $status = $row["status"];

            // Status badge colour
            if($status == "Completed")      { $status_class = "badge-success";   }
            elseif($status == "Assigned")   { $status_class = "badge-dark";      }
            elseif($status == "Not Assigned"){ $status_class = "badge-warning";  }
            elseif($status == "Work Started"){ $status_class = "badge-secondary";}
            elseif($status == "Rejected")   { $status_class = "badge-danger";    }
            else                            { $status_class = "badge-info";      }

            // Priority badge colour
            $priority_badge = (isset($row["priority"]) && $row["priority"] == "High")
                ? '<span class="badge badge-danger p-2">High</span>'
                : '<span class="badge badge-success p-2">Normal</span>';

            echo '<tr>
                  <th scope="row">' . $row["request_id"] . '</th>
                  <td>' . htmlspecialchars($row["request_info"]) . '</td>
                  <td>' . htmlspecialchars($row["requester_name"]) . '</td>
                  <td>' . $priority_badge . '</td>
                  <td>' . htmlspecialchars($row["assign_tech"]) . '</td>
                  <td>' . $row["request_date"] . '</td>
                  <td><span class="badge ' . $status_class . ' p-2">' . $status . '</span></td>
                  <td>';

            // View button — always shown
            echo '<form action="viewassignwork.php" method="POST" class="d-inline">
                    <input type="hidden" name="id" value="' . $row["request_id"] . '">
                    <button type="submit" class="btn btn-info btn-sm mb-1" title="View Details">
                        <i class="far fa-eye"></i>
                    </button>
                  </form> ';

            // Invoice button — only for completed jobs
            if($status == "Completed"){
                echo '<form action="generate_invoice.php" method="POST" class="d-inline" target="_blank">
                        <input type="hidden" name="id" value="' . $row["request_id"] . '">
                        <button type="submit" class="btn btn-success btn-sm mb-1" title="Generate Invoice">
                            <i class="fas fa-file-invoice"></i>
                        </button>
                      </form>';
            }

            echo '</td></tr>';

        endwhile;

        echo '</tbody></table>';
        echo '</div>';

    else:
        echo '<div class="alert alert-info mx-5" role="alert">
                No records found for the selected filters.
              </div>';
    endif;
    ?>

</div>

<?php include('includes/footer.php'); ?>