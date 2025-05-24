<?php
require_once('../config.php');

class Users extends DBConnection {
    private $settings;
    
    public function __construct(){
        global $_settings;
        $this->settings = $_settings;
        parent::__construct();
        ini_set('display_error', 1);
    }
    
    public function __destruct(){
        parent::__destruct();
    }

    public function send_notification(){
        extract($_POST);
        
        if(empty($user_id) || empty($title) || empty($message) || empty($type)){
            return ['status' => 'error', 'msg' => 'Please fill in all required fields'];
        }
        
        try {
            // Verify that the client exists
            $check = $this->conn->query("SELECT id FROM clients WHERE id = ?", [$user_id]);
            if($check->num_rows == 0){
                throw new Exception("Invalid client selected");
            }
            
            $sql = "INSERT INTO notifications (user_id, title, message, type) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            if(!$stmt){
                throw new Exception("Failed to prepare statement: " . $this->conn->error);
            }
            
            $stmt->bind_param("isss", $user_id, $title, $message, $type);
            
            if(!$stmt->execute()){
                throw new Exception("Failed to execute statement: " . $stmt->error);
            }
            
            $resp['status'] = 'success';
            $resp['msg'] = 'Notification sent successfully';
            
        } catch (Exception $e) {
            $resp['status'] = 'failed';
            $resp['msg'] = 'Failed to send notification: ' . $e->getMessage();
        }
        
        return $resp;
    }
}

$users = new Users();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
switch ($action) {
    case 'send_notification':
        header('Content-Type: application/json');
        echo json_encode($users->send_notification());
    break;
    default:
        // echo $sysset->index();
        break;
} 