<?php
require_once(__DIR__ . '/../../config.php');

class AdminNotifications extends DBConnection {
    private $settings;
    
    public function __construct(){
        global $_settings;
        $this->settings = $_settings;
        parent::__construct();
    }
    
    public function __destruct(){
        parent::__destruct();
    }
    
    public function send_notification($admin_id, $title, $message, $type = 'general') {
        $sql = "INSERT INTO admin_notifications (admin_id, title, message, type) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isss", $admin_id, $title, $message, $type);
        return $stmt->execute();
    }
    
    public function get_unread_count($admin_id) {
        $sql = "SELECT COUNT(*) as count FROM admin_notifications WHERE admin_id = ? AND is_read = 0";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $admin_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['count'];
    }
    
    public function mark_as_read($notification_id) {
        $sql = "UPDATE admin_notifications SET is_read = 1 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $notification_id);
        return $stmt->execute();
    }
    
    public function get_notifications($admin_id, $limit = 10) {
        $sql = "SELECT * FROM admin_notifications WHERE admin_id = ? ORDER BY date_created DESC LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $admin_id, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $notifications = array();
        while($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }
        return $notifications;
    }
    
    // Send notification to all admins
    public function notify_all_admins($title, $message, $type = 'general') {
        $sql = "SELECT id FROM users WHERE type = 1"; // Assuming type 1 is admin
        $result = $this->conn->query($sql);
        while($admin = $result->fetch_assoc()) {
            $this->send_notification($admin['id'], $title, $message, $type);
        }
    }
}
?> 