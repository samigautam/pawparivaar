<?php
require_once '../config.php';
require_once '../libs/PHPMailer/src/Exception.php';
require_once '../libs/PHPMailer/src/PHPMailer.php';
require_once '../libs/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ForgotPassword extends DBConnection {
    public function __construct(){
		parent::__construct();
		ini_set('display_error', 1);
	}

    public function sendPasswordResetEmail($email) {
        $qry = $this->conn->query("SELECT * from clients where email = '$email'");
		
        if(! $qry->num_rows){
            die("User with this email not found. Please try again with valid email address");
        }

        $token = $this->generateToken(32);

        $email = $this->conn->real_escape_string($email);
        $token = $this->conn->real_escape_string($token);
        $query = "INSERT INTO password_reset_tokens (email, token, created_at) VALUES ('$email', '$token', NOW())";
        $result = $this->conn->query($query);


        $mail = new PHPMailer(true); 

        try {

            $mail->isSMTP();                                            
            $mail->Host       = 'smtp.gmail.com';                     
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = 'samigautam20@gmail.com';      // Email address from which email will be sent.                
            $mail->Password   = 'rlxdxhagkvgghjkb';    // App password used from gmail. (search if don't know about the gmail app password)                           
            $mail->SMTPSecure = 'tls';                          
            $mail->Port       = 587;                                    

            $mail->setFrom('samigautam20@gmail.com', 'Sami Gautam');
            $mail->addAddress($email);                                  

            
            $mail->isHTML(true);                                        
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = 'Dear user,<br><br>' .
                             'You have requested to reset your password. Please click the link below to reset your password:<br>' .
                             '<a href="' . base_url . '?p=reset-password&email=' . urlencode($email) . '&token=' . urlencode($token) . '">Reset Password</a><br><br>' .
                             'If you did not request this, please ignore this email.<br><br>' .
                             'Regards,<br>Paw Parivaar Team';
        
            if ($mail->send()){
                return true;
            }

            return false;
        } catch (Exception $e) {

            error_log('PHPMailer Error: ' . $e->getMessage(), 0);
            return false;
        }
    }

    private function generateToken($length = 32) {
        $token = base64_encode(openssl_random_pseudo_bytes($length));
        $token = preg_replace('/[^a-zA-Z0-9]/', '', $token);
        return $token;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $forgotPassword = new ForgotPassword();

    if ($forgotPassword->sendPasswordResetEmail($email)) {
        header("Location: ". base_url. '?p=forgot-password-confirm');
        exit();
    } else {
        die("Failed to send reset password email. Please try again later.");
    }
}