<?php
// This script ensures all required tables exist

require_once 'config.php';
$db = new DBConnection();
$conn = $db->conn;

// Check if application_status_log table exists, and create it if it doesn't
$check_table = $conn->query("SHOW TABLES LIKE 'application_status_log'");

if($check_table->num_rows == 0) {
    $create_table = "CREATE TABLE `application_status_log` (
        `log_id` int(30) NOT NULL AUTO_INCREMENT,
        `application_id` int(30) NOT NULL,
        `previous_status` varchar(100) NOT NULL,
        `new_status` varchar(100) NOT NULL,
        `changed_at` datetime NOT NULL,
        `changed_by` varchar(100) NOT NULL,
        `notes` text DEFAULT NULL,
        PRIMARY KEY (`log_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    if($conn->query($create_table)) {
        echo "application_status_log table created successfully.<br>";
    } else {
        echo "Error creating application_status_log table: " . $conn->error . "<br>";
    }
}

// Add home_visit fields to adoption_applications table if they don't exist
$check_column = $conn->query("SHOW COLUMNS FROM `adoption_applications` LIKE 'home_visit_date'");
if($check_column->num_rows == 0) {
    $alter_table = "ALTER TABLE `adoption_applications` 
                   ADD COLUMN `home_visit_date` date DEFAULT NULL,
                   ADD COLUMN `home_visit_notes` text DEFAULT NULL,
                   ADD COLUMN `home_visit_passed` tinyint(1) DEFAULT 0,
                   ADD COLUMN `staff_notes` text DEFAULT NULL,
                   ADD COLUMN `last_updated` datetime DEFAULT NULL";
    
    if($conn->query($alter_table)) {
        echo "Added home visit fields to adoption_applications table.<br>";
    } else {
        echo "Error adding fields: " . $conn->error . "<br>";
    }
}

echo "Table setup complete.";
?>
