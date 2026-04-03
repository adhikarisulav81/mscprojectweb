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
    $sql = "SELECT * FROM assignwork_tb WHERE request_id = $id";
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
       max-width: 800px;
       margin: auto;
       background: white;
       padding: 40px;
       border-radius: 8px;
       box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
       font-size: 16px;
       line-height: 24px;
       font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
       color: #333;
   }
   .invoice-box table { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; }
   .invoice-box table td { padding: 10px; vertical-align: top; }
   .invoice-box table tr td:nth-child(2) { text-align: right; }
   .invoice-box table tr.top table td { padding-bottom: 30px; }
   .invoice-box table tr.top table td.title { font-size: 38px; line-height: 45px; color: #24ad7f; font-weight: bold; }
   .invoice-box table tr.information table td { padding-bottom: 20px; }
   .invoice-box table tr.heading td { background: #f0f0f0; border-bottom: 2px solid #ddd; font-weight: bold; }
   .invoice-box table tr.details td { padding-bottom: 20px; }
   .invoice-box table tr.item td{ border-bottom: 1px solid #eee; }
   .invoice-box table tr.item.last td { border-bottom: none; }
   .signature-box { margin-top: 50px; text-align: right; }
 </style>
</head>
<body>
    <div class="invoice-box mt-5 mb-5 align-items-center">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <i class="fas fa-file-invoice-dollar"></i> INVOICE
                            </td>
                            
                            <td>
                                <strong>Invoice #:</strong> <?php echo $row['request_id']; ?><br>
                                <strong>Assigned Date:</strong> <?php echo $row['assign_date']; ?><br>
                                <!-- <strong>Delivery Date:</strong>  -->
                                <?php /*echo $row['deliveryDate']; */?>
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
                                <strong class="text-secondary">FROM:</strong><br>
                                <strong>Web Based Smartphone Repair Service</strong><br>
                                Admin<br>
                                <strong>Technician:</strong> <?php echo htmlspecialchars($row['assign_tech']); ?>
                            </td>
                            
                            <td>
                                <strong class="text-secondary">TO:</strong><br>
                                <strong><?php echo htmlspecialchars($row['requester_name']); ?></strong><br>
                                <?php echo htmlspecialchars($row['requester_add1']); ?><br>
                                <?php echo htmlspecialchars($row['requester_email']); ?> / <?php echo htmlspecialchars($row['requester_mobile']); ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="heading">
                <td>Item / Service Details</td>
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
                <td colspan="2">Description</td>
            </tr>
            <tr class="item last">
                <td colspan="2"><?php echo nl2br(htmlspecialchars($row['request_desc'])); ?></td>
            </tr>
        </table>
        
        <div class="signature-box mt-5 pt-5">
            <strong>Authorized Signature</strong><br>
            _______________________________
        </div>
        
        <div class="text-center mt-5 d-print-none">
            <button onclick="window.print();" class="btn btn-success btn-md shadow"><i class="fas fa-print"></i> Print Invoice</button>
        </div>
    </div>

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="../css/all.min.css">
</body>
</html>
