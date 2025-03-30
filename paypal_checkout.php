<?php
session_start();
require_once 'config.php';
require_once 'classes/DBConnection.php';

// Get donation details from URL parameters
$amount = isset($_GET['amount']) ? floatval($_GET['amount']) : 50;
$message = isset($_GET['message']) ? $_GET['message'] : '';
$donationType = isset($_GET['type']) ? $_GET['type'] : 'one-time';

// Store in session in case user needs to log in
$_SESSION['pending_donation'] = [
    'amount' => $amount,
    'message' => $message,
    'type' => $donationType
];

// Check if user is logged in
if (!isset($_SESSION['userdata']['id'])) {
    header("Location: login.php?redirect=paypal_checkout.php");
    exit();
}

// Sandbox PayPal credentials
$paypal_client_id = 'AdDNu0ZwC3bqzdjiiQlmQ4BRJsOarwyMVD_L4YQPrQm4ASuBg4bV5ZoH-uveg8K_l9JLCmipuiKt4fxn';
$paypal_secret = 'YOUR_PAYPAL_SECRET'; // You would need to add your actual secret

// Format amount for display
$formatted_amount = number_format($amount, 2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Donation</title>
    <style>
        :root {
            --primary-color: #4caf50;
            --secondary-color: #ff8c00;
            --background-color: #f5f5f5;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-color);
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 15px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        h2 {
            color: var(--primary-color);
        }
        
        .donation-summary {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: left;
        }
        
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: var(--secondary-color);
            margin: 10px 0;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #0070ba; /* PayPal blue */
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 10px;
            transition: background-color 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn:hover {
            background-color: #005ea6;
        }
        
        .btn.secondary {
            background-color: #f0f0f0;
            color: #333;
        }
        
        .btn.secondary:hover {
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Complete Your Donation</h2>
        
        <div class="donation-summary">
            <p><strong>Donation Type:</strong> <?php echo ucfirst($donationType); ?></p>
            <p><strong>Amount:</strong> <span class="amount">$<?php echo $formatted_amount; ?></span></p>
            <?php if (!empty($message)): ?>
            <p><strong>Your Message:</strong> <?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
        </div>
        
        <p>You'll be redirected to PayPal to complete your donation securely.</p>
        
        <!-- PayPal button -->
        <div id="paypal-button-container"></div>
        
        <a href="donate.php" class="btn secondary">Back to Donation Form</a>
    </div>
    
    <!-- PayPal JavaScript SDK -->
    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo $paypal_client_id; ?>&currency=USD"></script>
    
    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '<?php echo $amount; ?>'
                        },
                        description: '<?php echo $donationType === "monthly" ? "Monthly donation to animal rescue" : "One-time donation to animal rescue"; ?>'
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    // Redirect to process donation with order details
                    window.location.href = `process_donation.php?paymentId=${details.id}&PayerID=${details.payer.payer_id}&amount=<?php echo $amount; ?>&message=<?php echo urlencode($message); ?>&type=<?php echo $donationType; ?>`;
                });
            }
        }).render('#paypal-button-container');
    </script>
</body>
</html>