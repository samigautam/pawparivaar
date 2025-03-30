<?php
session_start();
if (!isset($_SESSION['userdata']['id'])) {
    header("Location: login.php");
    exit();
}

// Get donation information if available
$amount = isset($_GET['amount']) ? htmlspecialchars($_GET['amount']) : 'your';
$donation_id = isset($_GET['donation_id']) ? htmlspecialchars($_GET['donation_id']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You For Your Donation</title>
    <style>
        :root {
            --primary-color: #4caf50;
            --secondary-color: #ff8c00;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 40px;
            border-radius: 15px;
            background-color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .success-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 30px;
            background-color: #e8f5e9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            color: var(--primary-color);
        }
        
        h2 {
            color: var(--primary-color);
            font-size: 32px;
            margin-bottom: 20px;
        }
        
        .donation-amount {
            font-size: 24px;
            font-weight: bold;
            color: var(--secondary-color);
            margin: 20px 0;
        }
        
        .impact-message {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            margin: 30px 0;
            font-size: 18px;
        }
        
        .confirmation-number {
            color: #666;
            font-size: 14px;
            margin-bottom: 30px;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 10px;
            transition: background-color 0.3s ease;
        }
        
        .btn:hover {
            background-color: #3d8b40;
        }
        
        .btn.secondary {
            background-color: #f0f0f0;
            color: #333;
        }
        
        .btn.secondary:hover {
            background-color: #e0e0e0;
        }
        
        .social-share {
            margin-top: 30px;
        }
        
        .social-share p {
            margin-bottom: 15px;
            color: #555;
        }
        
        .social-icons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .social-icon {
            width: 40px;
            height: 40px;
            background-color: #e0e0e0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            transition: background-color 0.3s ease;
        }
        
        .social-icon:hover {
            background-color: #ccc;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">✓</div>
        
        <h2>Thank You For Your Donation!</h2>
        
        <p>Your support means the world to the animals in our care.</p>
        
        <div class="donation-amount">
            $<?php echo $amount; ?>
        </div>
        
        <div class="impact-message">
            <p>Your generous donation will help provide food, shelter, medical care, and love for animals waiting for their forever homes.</p>
        </div>
        
        <?php if ($donation_id): ?>
        <div class="confirmation-number">
            Confirmation #: <?php echo $donation_id; ?>
        </div>
        <?php endif; ?>
        
        <div>
            <a href="index.php" class="btn">Return to Home</a>
            <a href="adoption.php" class="btn secondary">View Adoptable Pets</a>
        </div>
        
        <div class="social-share">
            <p>Help spread the word about our mission:</p>
            <div class="social-icons">
                <a href="#" class="social-icon">f</a>
                <a href="#" class="social-icon">t</a>
                <a href="#" class="social-icon">in</a>
            </div>
        </div>
    </div>
</body>
</html>