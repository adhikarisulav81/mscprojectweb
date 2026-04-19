<?php
/**
 * Email Configuration and Helper Functions
 * Uses PHPMailer with Gmail SMTP
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Email configuration - UPDATE THESE WITH YOUR GMAIL CREDENTIALS
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'noconfidential0@gmail.com'); 
define('SMTP_PASSWORD', 'gurs rqso inrp mpvq');
define('SMTP_FROM_EMAIL', 'noconfidential0@gmail.com');
define('SMTP_FROM_NAME', 'Online Smartphone Repair Service');


function sendEmail($to, $subject, $message, $recipientName = '') {
    if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
        error_log('PHPMailer not installed. Run: composer require phpmailer/phpmailer');
        return false;
    }
    
    require __DIR__ . '/vendor/autoload.php';
    
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USERNAME;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;
        
        // Recipients
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($to, $recipientName);
        $mail->addReplyTo(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        
        // Content
        $mail->isHTML(false); // Set to true if sending HTML email
        $mail->Subject = $subject;
        $mail->Body    = $message;
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email sending failed: {$mail->ErrorInfo}");
        return false;
    }
}

/**
 * Send technician credentials email
 * 
 * @param string $technicianName Technician's name
 * @param string $technicianEmail Technician's email
 * @param string $password Temporary password
 * @return bool True on success, false on failure
 */

/**
 * Send email to a new technician about their account created by admin
 */
function sendTechnicianCredentials($technicianName, $technicianEmail, $password) {
    $subject = "Your Technician Portal Login Credentials";
    
    $message = "Dear $technicianName,\n\n";
    $message .= "Your technician account has been created successfully.\n\n";
    $message .= "Login Details:\n";
    // $message .= "Portal URL: http://localhost:8000/technician/technicianLogin.php\n";
    $message .= "Email: $technicianEmail\n";
    $message .= "Password: $password\n\n";
    $message .= "IMPORTANT: Please login and change your password immediately.\n\n";
    $message .= "If you have any query, please contact the admin.\n\n";
    $message .= "Best Regards,\n";
    $message .= "Online Smartphone Repair Service Team";
    
    return sendEmail($technicianEmail, $subject, $message, $technicianName);
}

/**
 * Send email to a new user about their account created by admin
 */
function sendNewUserAccountEmail($userName, $userEmail, $plainPassword) {
    $subject = "Your User Portal Login Credentials";

    $message  = "Dear $userName,\n\n";
    $message .= "Your account has been created successfully.\n\n";
    $message .= "Your Login Details:\n";
    // $message .= "Portal URL: http://localhost:8000/user/userLogin.php\n";
    $message .= "Email   : $userEmail\n";
    $message .= "Password: $plainPassword\n\n";
    $message .= "IMPORTANT: Please login and change your password immediately.\n\n";
    $message .= "If you have any query, please contact our team.\n\n";
    $message .= "Best Regards,\n";
    $message .= "Online Smartphone Repair Service Team";

    return sendEmail($userEmail, $subject, $message, $userName);
}

/**
 * Send notification to user that their request has been received
 */
function sendRequestReceivedNotification($userName, $userEmail, $requestId, $requestInfo) {
    $subject = "Request Received: Your Repair Request ID #$requestId";
    
    $message = "Dear $userName,\n\n";
    $message .= "Your service request has been successfully submitted and received by our admin.\n\n";
    $message .= "Request Details:\n";
    $message .= "Request ID: $requestId\n";
    $message .= "Request Info: $requestInfo\n\n";
    $message .= "The system will assign a technician to your request shortly. You can check the status of your request on our portal.\n\n";
    $message .= "Best Regards,\n";
    $message .= "Online Smartphone Repair Service Team";
    
    return sendEmail($userEmail, $subject, $message, $userName);
}

/**
 * Send request assignment notification to user
 */
function sendWorkAssignedToUser($userName, $userEmail, $technicianName, $requestId, $description) {
    $subject = "Work Assigned: Your Repair Request ID #$requestId";
    
    $message = "Dear $userName,\n\n";
    $message .= "Your request has been assigned to a technician.\n\n";
    $message .= "Request ID: $requestId\n";
    $message .= "Request Description: $description\n";
    $message .= "Assigned Technician Name: $technicianName\n\n";
    $message .= "The technician will start working on your device soon. You can check the status of your request on your portal.\n\n";
    $message .= "Best Regards,\n";
    $message .= "Online Smartphone Repair Service Team";
    
    return sendEmail($userEmail, $subject, $message, $userName);
}

/**
 * Send request assignment notification to technician
 */
function sendWorkAssignedToTechnician($techName, $techEmail, $userName, $requestId, $description, $assignDate) {
    $subject = "New Task Assigned: Request #$requestId";
    
    $message = "Dear $techName,\n\n";
    $message .= "A new request has been assigned to you.\n\n";
    $message .= "Request Details:\n";
    $message .= "Request ID: $requestId\n";
    $message .= "Customer Name: $userName\n";
    $message .= "Request Description: $description\n";
    $message .= "Assigned Date: $assignDate\n\n";
    $message .= "Please login to your Portal to view full details of the assigned request.\n";
    // $message .= "Portal URL: http://localhost:8000/technician/technicianLogin.php\n\n";
    $message .= "Best Regards,\n";
    $message .= "Online Smartphone Repair Service Team";
    
    return sendEmail($techEmail, $subject, $message, $techName);
}

/**
 * Send notification to user that technician is busy
 */
function sendTechnicianBusyNotification($userName, $userEmail, $technicianName, $requestId) {
    $subject = "Technician Busy: Your Repair Request ID #$requestId";
    
    $message = "Dear $userName,\n\n";
    $message .= "Your request #$requestId has been assigned to $technicianName.\n\n";
    $message .= "Note: The technician is currently busy with another request.\n\n";
    $message .= "You will be notified as soon as the work on your device officially starts. You can also view the status of your request on your portal.\n\n";
    $message .= "Best Regards,\n";
    $message .= "Online Smartphone Repair Service Team";
    
    return sendEmail($userEmail, $subject, $message, $userName);
}

/**
 * Send notification to user that technician status is updated from busy to available (free)
 */
function sendTechnicianAvailableNotification($userName, $userEmail, $technicianName, $requestId) {
    $subject = "Technician Availabile: Your Repair Request ID #$requestId";
    
    $message = "Dear $userName,\n\n";
    $message .= "We are pleased to inform you that the technician is now available and will let you know when your work starts.\n\n";
    $message .= "Technician $technicianName is now handling your device.\n\n";
    $message .= "You will receive another notification once the request is started.\n\n";
    $message .= "Best Regards,\n";
    $message .= "Online Smartphone Repair Service Team";
    
    return sendEmail($userEmail, $subject, $message, $userName);
}

/**
 * Send notification to user that request has been started
 */
function sendWorkStartedNotification($userName, $userEmail, $technicianName, $requestId) {
    $subject = "Work Started: Your Repair Request ID #$requestId";
    
    $message = "Dear $userName,\n\n";
    $message .= "We are pleased to inform you that the work on your request #$requestId has officially started.\n\n";
    $message .= "Technician $technicianName is now working on your device.\n\n";
    $message .= "You will receive another notification once the request is completed.\n\n";
    $message .= "Best Regards,\n";
    $message .= "Online Smartphone Repair Service Team";
    
    return sendEmail($userEmail, $subject, $message, $userName);
}

/**
 * Send request completion notification to user
 */
function sendWorkCompletionToUser($userName, $userEmail, $technicianName, $requestId, $description) {
    $subject = "Request Completed: Your Repair Request ID #$requestId";
    
    $message = "Dear $userName,\n\n";
    $message .= "We are pleased to inform you that your request has been completed.\n\n";
    $message .= "Request ID: $requestId\n";
    $message .= "Request Description: $description\n";
    $message .= "Technician Name: $technicianName\n";
    $message .= "Request Status: COMPLETED\n\n";
    $message .= "You can now visit your completed request invoice on your portal.\n\n";
    $message .= "Thank you for choosing us!!\n\n";
    $message .= "Best Regards,\n";
    $message .= "Online Smartphone Repair Service Team";
    
    return sendEmail($userEmail, $subject, $message, $userName);
}

/**
 * Send notification to user that their request has been rejected
 */
function sendRequestRejectionToUser($userName, $userEmail, $requestId, $description, $rejectionReason = '') {
    $subject = "Request Rejected: Your Repair Request ID #$requestId";
    
    $message = "Dear $userName,\n\n";
    $message .= "We are very sorry to inform you that your request #$requestId has been rejected.\n\n";
    $message .= "Request ID: $requestId\n";
    $message .= "Request Description: $description\n\n\n";
    if(!empty($rejectionReason)){
        $message .= "Reason for Rejection:\n";
        $message .= "$rejectionReason\n\n";
    }
    $message .= "Please consider the rejection reason and submit a new valid request.\n\n";
    $message .= "If you have any query, please contact our support team.\n\n";
    $message .= "Best Regards,\n";
    $message .= "Online Smartphone Repair Service Team";
    
    return sendEmail($userEmail, $subject, $message, $userName);
}
?>