<?php
session_start();
require_once 'config.php';
require_once 'classes/DBConnection.php';

// Check if the user is logged in
if (!isset($_SESSION['userdata']['id'])) {
    // Store donation data in session for after login
    if (isset($_GET['paymentId'])) {
        $_SESSION['pending_donation'] = $_GET;
        header("Location: login.php?redirect=process_donation.php");
    } else {
        header("Location: login.php");
    }
    exit();
}

$db = new DBConnection();
$conn = $db->conn; // Access the MySQLi connection object

// Get client_id from users table or directly from clients table based on your schema
$user_id = $_SESSION['userdata']['id'];
$client_id = $user_id;

// Using prepared statement for security
$stmt = $conn->prepare("SELECT id FROM clients WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$client_id = $result->num_rows > 0 ? $result->fetch_assoc()['id'] : $user_id;

// Handle PayPal response
if (isset($_GET['paymentId']) && isset($_GET['PayerID'])) {
    $paymentId = $_GET['paymentId'];
    $payerId = $_GET['PayerID'];
    $amount = $_GET['amount'];
    $message = $_GET['message'] ?? '';
    $donationType = $_GET['type'] ?? 'one-time';
    $currentDate = date('Y-m-d H:i:s');
    
    // Insert donation into the database using prepared statement
    $stmt = $conn->prepare("INSERT INTO donations (client_id, donation_id, amount, message, donation_type, payment_status, donation_date) 
                         VALUES (?, ?, ?, ?, ?, 'completed', ?)");
    $stmt->bind_param("isssss", $client_id, $paymentId, $amount, $message, $donationType, $currentDate);
    
    if ($stmt->execute()) {
        $donation_id = $conn->insert_id;
        // Log successful donation
        error_log("Donation ID: {$donation_id} successfully processed for client: {$client_id}");
        
        // Redirect to thank you page with donation info
        header("Location: thank_you.php?donation_id={$donation_id}&amount={$amount}");
    } else {
        // Log error
        error_log("Error processing donation: " . $stmt->error);
        header("Location: donation_error.php");
    }
    
    exit();
}

// If no payment data is found, redirect to donation form
header("Location: donate.php");
exit();
?>