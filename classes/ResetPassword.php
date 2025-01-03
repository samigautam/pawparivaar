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
