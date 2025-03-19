<?php
require_once 'config.php'; // Config and session setup happens first
require_once 'classes/Master.php';
require_once 'libs/PHPMailer/src/Exception.php';
require_once 'libs/PHPMailer/src/PHPMailer.php';
require_once 'libs/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $conn;

    $firstname = $conn->real_escape_string($_POST['firstname']);
    $lastname = $conn->real_escape_string($_POST['lastname']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = md5($_POST['password']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $default_delivery_address = $conn->real_escape_string($_POST['default_delivery_address']);
    $token = bin2hex(random_bytes(32));

    $query = "INSERT INTO email_verifications (firstname, lastname, email, password, contact, gender, default_delivery_address, verification_token, is_verified, created_at) 
              VALUES ('$firstname', '$lastname', '$email', '$password', '$contact', '$gender', '$default_delivery_address', '$token', 0, NOW())";

    if ($conn->query($query)) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'samigautam20@gmail.com';
            $mail->Password = 'rlxdxhagkvgghjkb'; // Use app password, not your main email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('samigautam20@gmail.com', 'Sami Gautam');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = "Verify Your Email Address";
            $verification_link = "http://localhost/pawparivaar/verify_email.php?token=$token";
                $mail->Body = "
                <div style='font-family: Arial, sans-serif; line-height: 1.5;'>
                    <h2 style='color: #333;'>Welcome to Paw Parivaar!</h2>
                    <p>Dear $firstname $lastname,</p>
                    <p>Thank you for registering with <strong>Paw Parivaar</strong>. We are thrilled to have you on board as part of our mission to rescue and adopt pets.</p>
                    <p>To complete your registration, please verify your email address by clicking the link below:</p>
                    <p style='text-align: center;'>
                        <a href='$verification_link' style='display: inline-block; padding: 10px 20px; background-color:rgb(31, 41, 33); color: #fff; text-decoration: none; font-weight: bold; border-radius: 5px;'>Verify Email Address</a>
                    </p>
                    <p>If the button above doesn’t work, copy and paste the following link into your web browser:</p>
                    <p><a href='$verification_link' style='color: #007bff;'>$verification_link</a></p>
                    <p>If you did not sign up for Paw Parivaar, please ignore this email.</p>
                    <br>
                    <p>Best regards,</p>
                    <p><strong>The Paw Parivaar Team</strong></p>
                    <hr>
                    <p style='font-size: 12px; color: #666;'>This email was sent by Paw Parivaar. Please do not reply to this email as it is not monitored. For assistance, contact us at samigautam20@gmail.com .</p>
                </div>
            ";            

            if ($mail->send()) {
                echo json_encode(['status' => 'success', 'msg' => 'Registration successful! Please check your email to verify your account.']);
            } else {
                echo json_encode(['status' => 'failed', 'msg' => 'Failed to send verification email. Please try again later.']);
            }
        } catch (Exception $e) {
            error_log('PHPMailer Error: ' . $e->getMessage(), 0);
            echo json_encode(['status' => 'failed', 'msg' => 'Failed to send verification email. Please try again later.']);
        }
    } else {
        echo json_encode(['status' => 'failed', 'msg' => 'Registration failed. Please try again later.']);
    }
} else {
    echo json_encode(['status' => 'failed', 'msg' => 'Invalid request.']);
}
?>