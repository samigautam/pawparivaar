<?php
// verify_email.php - Handling email verification and completing user registration

require_once 'config.php';
require_once 'classes/Master.php';

if (isset($_GET['token'])) {
    global $conn;
    $token = $conn->real_escape_string($_GET['token']);

    // Verify token
    $result = $conn->query("SELECT * FROM email_verifications WHERE verification_token = '$token' AND is_verified = 0");

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];

        // Mark email as verified
        $conn->query("UPDATE email_verifications SET is_verified = 1 WHERE verification_token = '$token'");

        // Now insert the full user data into the 'clients' table
        $firstname = $conn->real_escape_string($row['firstname']);
        $lastname = $conn->real_escape_string($row['lastname']);
        $contact = $conn->real_escape_string($row['contact']);
        $gender = $conn->real_escape_string($row['gender']);
        $default_delivery_address = $conn->real_escape_string($row['default_delivery_address']);
        $password = $conn->real_escape_string($row['password']); // No need to hash as it was hashed earlier

        // Insert the user into the 'clients' table
        $insert_query = "INSERT INTO clients (firstname, lastname, gender, contact, email, password, default_delivery_address, date_created) 
                         VALUES ('$firstname', '$lastname', '$gender', '$contact', '$email', '$password', '$default_delivery_address', NOW())";
        
        if ($conn->query($insert_query)) {
            echo "
<div style='display: flex; justify-content: center; align-items: center; height: 100vh; font-family: Arial, sans-serif;'>
    <div style='text-align: center;'>
        <p style='font-size: 20px; color: #333; margin-bottom: 20px;'>Email verified successfully! You can now log in.</p>
        <a href='http://localhost/pawparivaar/' style='display: inline-block; padding: 10px 20px; background-color:rgb(32, 38, 44); color: white; text-decoration: none; font-size: 18px; border-radius: 5px;'>Go to Login</a>
    </div>
</div>
";
        } else {
            echo "<p>Failed to complete registration. Please try again.</p>";
        }
    } else {
        echo "<p>Invalid or expired token. Please register again.</p>";
    }
} else {
    echo "<p>No token provided. Please check your email for the verification link.</p>";
}
?>
