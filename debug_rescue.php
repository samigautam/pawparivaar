<?php
require_once('config.php');

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Rescue Status Update Debugging</h1>";

// Check if the Master class and update_rescue_status function exists
if(file_exists('classes/Master.php')) {
    echo "<p>Master.php file found.</p>";
    
    require_once('classes/Master.php');
    if(class_exists('Master')) {
        echo "<p>Master class exists.</p>";
        
        $master = new Master();
        if(method_exists($master, 'update_rescue_status')) {
            echo "<p>update_rescue_status method exists.</p>";
        } else {
            echo "<p>Error: update_rescue_status method does not exist.</p>";
        }
    } else {
        echo "<p>Error: Master class does not exist.</p>";
    }
} else {
    echo "<p>Error: Master.php file not found.</p>";
}

// Check database tables
$db = new DBConnection();
$conn = $db->conn;

// Check rescue_operations table
$rescue_table = $conn->query("SHOW TABLES LIKE 'rescue_operations'");
if($rescue_table->num_rows > 0) {
    echo "<p>rescue_operations table exists.</p>";
    
    // Check columns in rescue_operations table
    $columns = $conn->query("SHOW COLUMNS FROM rescue_operations");
    echo "<p>Columns in rescue_operations table:</p>";
    echo "<ul>";
    while($column = $columns->fetch_assoc()) {
        echo "<li>{$column['Field']} - {$column['Type']}</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Error: rescue_operations table does not exist.</p>";
}

// Check rescue_status_log table
$log_table = $conn->query("SHOW TABLES LIKE 'rescue_status_log'");
if($log_table->num_rows > 0) {
    echo "<p>rescue_status_log table exists.</p>";
    
    // Check columns in rescue_status_log table
    $columns = $conn->query("SHOW COLUMNS FROM rescue_status_log");
    echo "<p>Columns in rescue_status_log table:</p>";
    echo "<ul>";
    while($column = $columns->fetch_assoc()) {
        echo "<li>{$column['Field']} - {$column['Type']}</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Error: rescue_status_log table does not exist.</p>";
}

echo "<p>End of debug information.</p>";
?>
