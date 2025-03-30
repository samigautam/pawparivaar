<?php
// adoption_submit.php - Handles the adoption application form submission

// Database connection
require_once('db_connect.php'); // Your database connection file

// Initialize response
$response = [
    'success' => false,
    'message' => '',
    'errors' => []
];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required fields
    $required_fields = [
        'animal_id', 'first_name', 'last_name', 'email', 'phone',
        'address', 'city', 'state', 'zip', 'housing_type',
        'terms_accepted'
    ];
    
    $errors = [];
    
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[] = "Field '$field' is required.";
        }
    }
    
    // Validate email
    if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    
    // Validate terms accepted
    if (isset($_POST['terms_accepted']) && $_POST['terms_accepted'] !== '1') {
        $errors[] = "You must accept the terms and conditions.";
    }
    
    // If validation passes, process the form
    if (empty($errors)) {
        try {
            // Start transaction
            $conn->begin_transaction();
            
            // Sanitize and prepare data
            $animal_id = (int)$_POST['animal_id'];
            $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
            $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $phone = mysqli_real_escape_string($conn, $_POST['phone']);
            $address = mysqli_real_escape_string($conn, $_POST['address']);
            $city = mysqli_real_escape_string($conn, $_POST['city']);
            $state = mysqli_real_escape_string($conn, $_POST['state']);
            $zip = mysqli_real_escape_string($conn, $_POST['zip']);
            $housing_type = mysqli_real_escape_string($conn, $_POST['housing_type']);
            $has_yard = isset($_POST['has_yard']) ? 1 : 0;
            $yard_description = mysqli_real_escape_string($conn, $_POST['yard_description'] ?? '');
            $landlord_info = mysqli_real_escape_string($conn, $_POST['landlord_info'] ?? '');
            $household_members = mysqli_real_escape_string($conn, $_POST['household_members'] ?? '');
            $current_pets = mysqli_real_escape_string($conn, $_POST['current_pets'] ?? '');
            $previous_pets = mysqli_real_escape_string($conn, $_POST['previous_pets'] ?? '');
            $vet_info = mysqli_real_escape_string($conn, $_POST['vet_info'] ?? '');
            $employment_status = mysqli_real_escape_string($conn, $_POST['employment_status'] ?? '');
            $hours_pet_alone = isset($_POST['hours_pet_alone']) ? (int)$_POST['hours_pet_alone'] : 0;
            $experience_notes = mysqli_real_escape_string($conn, $_POST['experience_notes'] ?? '');
            $reference_1_name = mysqli_real_escape_string($conn, $_POST['reference_1_name'] ?? '');
            $reference_1_phone = mysqli_real_escape_string($conn, $_POST['reference_1_phone'] ?? '');
            $reference_1_relation = mysqli_real_escape_string($conn, $_POST['reference_1_relation'] ?? '');
            $reference_2_name = mysqli_real_escape_string($conn, $_POST['reference_2_name'] ?? '');
            $reference_2_phone = mysqli_real_escape_string($conn, $_POST['reference_2_phone'] ?? '');
            $reference_2_relation = mysqli_real_escape_string($conn, $_POST['reference_2_relation'] ?? '');
            
            // Process custom questions if they exist
            $custom_questions_answers = [];
            
            if (isset($_POST['question']) && is_array($_POST['question'])) {
                foreach ($_POST['question'] as $q_id => $answer) {
                    $custom_questions_answers[] = [
                        'question_id' => (int)$q_id,
                        'answer' => mysqli_real_escape_string($conn, $answer)
                    ];
                }
            }
            
            $custom_questions_json = json_encode($custom_questions_answers);
            
            // Insert into database
            $sql = "INSERT INTO adoption_applications (
                animal_id, first_name, last_name, email, phone, address, city, state, zip,
                housing_type, has_yard, yard_description, landlord_info, household_members,
                current_pets, previous_pets, vet_info, employment_status, hours_pet_alone,
                experience_notes, reference_1_name, reference_1_phone, reference_1_relation,
                reference_2_name, reference_2_phone, reference_2_relation,
                custom_questions_answers, terms_accepted, status
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?,
                ?, ?, ?, ?,
                ?, ?, ?,
                ?, 1, 'Pending'
            )";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssssssssisssssssissssss",
                $animal_id, $first_name, $last_name, $email, $phone, $address, $city, $state, $zip,
                $housing_type, $has_yard, $yard_description, $landlord_info, $household_members,
                $current_pets, $previous_pets, $vet_info, $employment_status, $hours_pet_alone,
                $experience_notes, $reference_1_name, $reference_1_phone, $reference_1_relation,
                $reference_2_name, $reference_2_phone, $reference_2_relation,
                $custom_questions_json
            );
            
            $stmt->execute();
            $application_id = $stmt->insert_id;
            
            // Update animal status to 'Pending Adoption'
            $update_sql = "UPDATE animals SET adoption_status = 'Pending Adoption' WHERE animal_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $animal_id);
            $update_stmt->execute();
            
            // Log status change
            $log_sql = "INSERT INTO application_status_log (application_id, previous_status, new_status, notes) 
                       VALUES (?, NULL, 'Pending', 'Initial application submitted')";
            $log_stmt = $conn->prepare($log_sql);
            $log_stmt->bind_param("i", $application_id);
            $log_stmt->execute();
            
            // Commit transaction
            $conn->commit();
            
            // Send email notifications
            sendApplicantNotification($email, $first_name, $application_id);
            sendStaffNotification($animal_id, $application_id);
            
            $response['success'] = true;
            $response['message'] = "Your application has been submitted successfully! Your application ID is: " . $application_id;
            $response['application_id'] = $application_id;
            
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            $response['message'] = "An error occurred while processing your application. Please try again later.";
            error_log("Adoption application error: " . $e->getMessage());
        }
    } else {
        $response['errors'] = $errors;
        $response['message'] = "Please correct the errors in your application form.";
    }
}

// Function to send email confirmation to applicant
function sendApplicantNotification($email, $name, $application_id) {
    $subject = "Your Adoption Application - ID: $application_id";
    $message = "Dear $name,\n\n";
    $message .= "Thank you for submitting your adoption application. Your application ID is: $application_id.\n\n";
    $message .= "We will review your application and contact you soon. You can check the status of your application ";
    $message .= "by visiting our website and entering your application ID and email address.\n\n";
    $message .= "Thank you for choosing to adopt from PawParivaar!\n\n";
    $message .= "Best regards,\nThe PawParivaar Team";
    
    $headers = "From: noreply@pawparivaar.org";
    
    mail($email, $subject, $message, $headers);
}

// Function to notify staff of new application
function sendStaffNotification($animal_id, $application_id) {
    // Get animal details
    global $conn;
    $sql = "SELECT name FROM animals WHERE animal_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $animal_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $animal = $result->fetch_assoc();
    
    $staff_email = "adoptions@pawparivaar.org"; // Change to your staff email
    $subject = "New Adoption Application - ID: $application_id";
    $message = "A new adoption application (ID: $application_id) has been submitted for {$animal['name']}.\n\n";
    $message .= "Please log in to the admin panel to review this application.";
    
    $headers = "From: system@pawparivaar.org";
    
    mail($staff_email, $subject, $message, $headers);
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>