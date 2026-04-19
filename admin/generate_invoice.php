<?php
define('TITLE', 'Invoice');
session_start();
include('../dbConnection.php');

if(!isset($_SESSION['is_adminlogin'])){
  echo "<script> location.href='adminLogin.php'; </script>";
  exit;
}

if(isset($_POST['id'])){
    $id = $_POST['id'];
    $sql = "SELECT *, created_at FROM assignwork_tb WHERE request_id = $id";
    $result = $conn->query($sql);
    if($result->num_rows == 1){
        $row = $result->fetch_assoc();
    } else {
        echo "Invoice not found!"; exit;
    }
} else {
    echo "Invalid Request!"; exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Invoice #<?php echo $row['request_id']; ?></title>
 <!-- Bootstrap CSS -->
 <link rel="stylesheet" href="../css/bootstrap.min.css">

 <style>
   /* @media print{
     .d-print-none { display: none !important; }
   } */
   /* body { background-color: #f8f9fa; } */
   .invoice-box {
       max-width: 900px;
       margin: auto;
       background: white;
       padding: 40px;
       border-radius: 12px;
       box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
       font-size: 17px;
       /* line-height: 24px; */
       font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
       color: #333;
   }
   .invoice-box table { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; }
   .invoice-box table td { padding: 7px; vertical-align: top; }
   .invoice-box table tr td:nth-child(2) { text-align: right; }
   .invoice-box table tr.top table td { padding-bottom: 28px; }
   .invoice-box table tr.top table td.title { font-size: 40px; line-height: 45px; color: #24ad7f; font-weight: bold; }
   .invoice-box table tr.information table td { padding-bottom: 20px; }
   .invoice-box table tr.heading td { background: #f0f0f0; border-bottom: 2px solid #ddd; font-weight: bold; }
   .invoice-box table tr.details td { padding-bottom: 20px; }
   /* .invoice-box table tr.item td{ border-bottom: 1px solid #eee; } */
   /* .invoice-box table tr.item.last td { border-bottom: none; } */
   .signature-box { text-align: right; }
 </style>
</head>
<body>
    <div class="invoice-box mt-4 mb-4 align-items-center">
    <h4 class="title font-weight-bold text-center text-white bg-dark mb-3" style="padding: 7px; border-radius: 5px;">
    <i class="fas fa-receipt"></i> INVOICE DETAILS
  </h4>
        <table>
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <!-- <td class="title">
                            
                                <i class="fas fa-receipt"></i> INVOICE
                            </td> -->
                            
                            <td>
                            <br><br>
                            <strong>Invoice ID:</strong> <?php echo $row['request_id']; ?><br>
                                
                                <!-- <?php $invoice_date = date('d M Y'); ?> // today date -->

                                <strong>Invoice Date:</strong> <?php echo $invoice_date; ?><br>
                                <strong>Assigned:</strong> <?php echo $row['assign_date']; ?><br>
                                <strong>Completed:</strong> <?php echo $row['created_at']; ?><br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                <h4><strong class="text-dark"><u>FROM</u></strong></h4><br>
                                Web Based Smartphone Repair Service<br>
                                Admin<br>
                                Technician: <?php echo htmlspecialchars($row['assign_tech']); ?>
                            </td>
                            
                            <td>
                            <h4><strong class="text-dark"><u>TO</u></strong></h4><br>
                                <?php echo htmlspecialchars($row['requester_name']); ?><br>
                                <?php echo htmlspecialchars($row['requester_add1']); ?><br>
                                <?php echo htmlspecialchars($row['requester_email']); ?> / <?php echo htmlspecialchars($row['requester_mobile']); ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="heading">
                <td>Request Details</td>
                <td>Priority</td>
            </tr>
            
            <tr class="details">
                <td><?php echo htmlspecialchars($row['request_info']); ?></td>
                <td>
                    <?php if($row['priority'] == 'High') { ?>
                    <span class="badge badge-danger">High</span>
                    <?php } else { ?>
                    <span class="badge badge-secondary">Normal</span>
                    <?php } ?>
                </td>

            </tr>
            <tr class="heading">
                <td>Description</td>
                <td>Total Price</td>

            </tr>
            <tr class="item last">
                <td><?php echo $row['request_desc']; ?></td>
                <td class="rounded bg-secondary border border-dark text-white"><b><?php echo $row['service_price']; ?></b></td>

            </tr>
        </table>
        
        <div class="text-center mt-2 d-print-none">
            <button onclick="window.print();" class="btn btn-success btn-md shadow"><i class="fas fa-print"></i> Print Invoice</button>
            <!-- <a href="../user/myRequests.php" class="btn btn-danger btn-md shadow"><i class="fas fa-times-circle"></i> Close</a> -->

        </div>
    </div>

    <?php
include('includes/footer.php'); 
?>
</body>
</html>
