<?php
// check_application_status.php - Allows applicants to check their application status

// Database connection
require_once('db_connect.php');

$application_id = $_GET['application_id'] ?? 0;
$email = $_GET['email'] ?? '';
$error_message = '';
$application = null;
$status_history = [];

if ($application_id && $email) {
    // Sanitize inputs
    $application_id = (int)$application_id;
    $email = mysqli_real_escape_string($conn, $email);
    
    // Query database for application
    $sql = "SELECT a.application_id, a.animal_id, a.first_name, a.last_name, 
                   a.status, a.submission_date, a.last_updated,
                   an.name as animal_name, an.primary_image
            FROM adoption_applications a
            JOIN animals an ON a.animal_id = an.animal_id
            WHERE a.application_id = ? AND a.email = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $application_id, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $application = $result->fetch_assoc();
        
        // Get status history
        $history_sql = "SELECT previous_status, new_status, changed_at, notes
                       FROM application_status_log
                       WHERE application_id = ?
                       ORDER BY changed_at DESC";
        
        $history_stmt = $conn->prepare($history_sql);
        $history_stmt->bind_param("i", $application_id);
        $history_stmt->execute();
        $history_result = $history_stmt->get_result();
        
        while ($row = $history_result->fetch_assoc()) {
            $status_history[] = $row;
        }
    } else {
        $error_message = "No application found with the provided ID and email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status | PawParivaar</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Check Application Status</h1>
        
        <?php if (!$application): ?>
            <div class="form-container">
                <?php if ($error_message): ?>
                    <div class="error-message"><?php echo $error_message; ?></div>
                <?php endif; ?>
                
                <form method="GET" action="check_application_status.php">
                    <div class="form-group">
                        <label for="application_id">Application ID:</label>
                        <input type="number" id="application_id" name="application_id" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Check Status</button>
                </form>
            </div>
        <?php else: ?>
            <div class="application-status">
                <div class="animal-info">
                    <?php if ($application['primary_image']): ?>
                        <img src="<?php echo htmlspecialchars($application['primary_image']); ?>" alt="<?php echo htmlspecialchars($application['animal_name']); ?>">
                    <?php endif; ?>
                    <h2>Application for <?php echo htmlspecialchars($application['animal_name']); ?></h2>
                </div>
                
                <div class="status-info">
                    <p><strong>Application ID:</strong> <?php echo $application['application_id']; ?></p>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($application['first_name'] . ' ' . $application['last_name']); ?></p>
                    <p><strong>Submission Date:</strong> <?php echo date('F j, Y', strtotime($application['submission_date'])); ?></p>
                    <p><strong>Current Status:</strong> <span class="status-badge status-<?php echo strtolower($application['status']); ?>"><?php echo $application['status']; ?></span></p>
                    <p><strong>Last Updated:</strong> <?php echo date('F j, Y g:i a', strtotime($application['last_updated'])); ?></p>
                </div>
                
                <div class="status-timeline">
                    <h3>Application Timeline</h3>
                    <?php if (empty($status_history)): ?>
                        <p>No status updates available yet.</p>
                    <?php else: ?>
                        <ul class="timeline">
                            <?php foreach ($status_history as $history): ?>
                                <li>
                                    <div class="timeline-date"><?php echo date('M j, Y g:i a', strtotime($history['changed_at'])); ?></div>
                                    <div class="timeline-content">
                                        <p><strong>Status changed to:</strong> <?php echo htmlspecialchars($history['new_status']); ?></p>
                                        <?php if ($history['notes']): ?>
                                            <p><?php echo htmlspecialchars($history['notes']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
                
                <div class="next-steps">
                    <h3>What's Next?</h3>
                    <?php switch($application['status']):
                        case 'Pending': ?>
                            <p>Your application is currently being reviewed by our team. We'll contact you within 3-5 business days.</p>
                            <?php break; 
                        case 'Under Review': ?>
                            <p>We're reviewing your information and may contact your references soon. If you have any questions, please contact us.</p>
                            <?php break;
                        case 'Home Visit': ?>
                            <p>A home visit is being scheduled. We'll contact you soon to arrange a convenient time.</p>
                            <?php break;
                        case 'Approved': ?>
                            <p>Congratulations! Your application has been approved. We'll contact you to schedule a time to finalize the adoption.</p>
                            <?php break;
                        case 'Denied': ?>
                            <p>We're sorry, but your application has been denied. Please contact us if you have any questions about this decision.</p>
                            <?php break;
                        case 'Completed': ?>
                            <p>Congratulations on your new family member! Don't forget to share photos with us!</p>
                            <?php break;
                        default: ?>
                            <p>Please check back later for updates or contact us with any questions.</p>
                    <?php endswitch; ?>
                </div>
                
                <a href="check_application_status.php" class="btn btn-secondary">Check Another Application</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>