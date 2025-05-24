<?php
require_once '../config.php';

class ResetPassword extends DBConnection {
    public function resetPassword($email, $newPassword) {
        $email = $this->conn->real_escape_string($email);
        $newPassword = $this->conn->real_escape_string($newPassword);
        $hashedPassword = md5($newPassword); 

        $query = "UPDATE clients SET password = '$hashedPassword' WHERE email = '$email'";
        $result = $this->conn->query($query);

        if ($result) {
            // Get client ID for notification
            $client_query = "SELECT id FROM clients WHERE email = '$email'";
            $client_result = $this->conn->query($client_query);
            
            if($client_result && $client_result->num_rows > 0) {
                $client = $client_result->fetch_assoc();
                $client_id = $client['id'];
                
                // Add notification
                $notification_title = "Password Reset";
                $notification_message = "Your password has been reset. If you did not request this change, please contact us immediately.";
                $notif_sql = "INSERT INTO notifications (user_id, title, message, type) VALUES (?, ?, ?, 'security')";
                $notif_stmt = $this->conn->prepare($notif_sql);
                $notif_stmt->bind_param("iss", $client_id, $notification_title, $notification_message);
                $notif_stmt->execute();
            }
            
            return true;
        } else {
            return false;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];
    $confirmNewPassword = $_POST['confirm_new_password'];

    if ($newPassword !== $confirmNewPassword) {
        $error = "Passwords do not match.";
    } else {
        $resetPassword = new ResetPassword();

        if ($resetPassword->resetPassword($email, $newPassword)) {
            header("Location: ".base_url.'?p=reset-password-success');
            exit();
        } else {
            $error = "Failed to reset password. Please try again.";
            die($error);
        }
    }
}
?>
