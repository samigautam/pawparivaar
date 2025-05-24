<?php
function send_notification($conn, $user_id, $title, $message, $type = 'general'){
    $sql = "INSERT INTO notifications (user_id, title, message, type) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $user_id, $title, $message, $type);
    return $stmt->execute();
}

// Example usage in admin panel:
// send_notification($conn, $user_id, "Order Status Update", "Your order #123 has been shipped", "order");
// send_notification($conn, $user_id, "New Message", "You have received a new message", "message");
// send_notification($conn, $user_id, "System Update", "System maintenance scheduled", "system");
?> 