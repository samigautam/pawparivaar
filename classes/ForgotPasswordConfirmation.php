<?php
require_once '../config.php';

class ForgotPasswordConfirmation extends DBConnection {
    public function verifyToken($email, $token) {
        $email = $this->conn->real_escape_string($email);
        $token = $this->conn->real_escape_string($token);
        $query = "SELECT * FROM password_reset_tokens WHERE email = '$email' AND token = '$token' AND TIMESTAMPDIFF(HOUR, created_at, NOW()) <= 24";
        $result = $this->conn->query($query);

        if ($result && $result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET["reset-password"])) {
    $email = $_POST['email'];

    $forgotPasswordConfirmation = new ForgotPasswordConfirmation();

    $token = $_GET['token']; 
    if ($forgotPasswordConfirmation->verifyToken($email, $token)) {
        header("Location: reset-password.php?email=" . urlencode($email) . "&token=" . urlencode($token));
        exit();
    } else {
        $error = "Invalid or expired token. Please try again or request a new password reset email.";
        die($error);
    }
}
?>
