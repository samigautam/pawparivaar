<?php
// Database connection
require_once '../classes/DBConnection.php';
$db = new DBConnection();
$conn = $db->conn;

// Initialize variables
$animal_id = isset($_GET['animal_id']) ? (int)$_GET['animal_id'] : 0;
$success_message = '';
$error_message = '';
$animal = null;

// Fetch animal details if ID is provided
if($animal_id > 0) {
    $sql = "SELECT * FROM animals WHERE animal_id = $animal_id";
    $result = $conn->query($sql);
    if($result->num_rows > 0) {
        $animal = $result->fetch_assoc();
    } else {
        header('Location: browse_animals.php');
        exit;
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Collect form data
        $applicant_name = $_POST['applicant_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        $housing_type = $_POST['housing_type'] ?? '';
        $owns_home = isset($_POST['owns_home']) ? 1 : 0;
        $has_children = isset($_POST['has_children']) ? 1 : 0;
        $has_pets = isset($_POST['has_pets']) ? 1 : 0;
        $current_pets = $_POST['current_pets'] ?? '';
        $reason_for_adoption = $_POST['reason_for_adoption'] ?? '';
        $additional_info = $_POST['additional_info'] ?? '';
        $animal_id = $_POST['animal_id'] ?? $animal_id;

        // Validate required fields
        $required_fields = ['applicant_name', 'email', 'phone', 'address', 'housing_type', 'reason_for_adoption'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("All required fields must be completed.");
            }
        }

        // Insert into database
        $sql = "INSERT INTO adoption_applications 
                (animal_id, applicant_name, email, phone, address, housing_type, owns_home, has_children, 
                 has_pets, current_pets, reason_for_adoption, additional_info, application_date, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'Pending')";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Database prepare error: " . $conn->error);
        }
        
        $stmt->bind_param("issssiissss", 
            $animal_id, 
            $applicant_name, 
            $email, 
            $phone,
            $address, 
            $housing_type, 
            $owns_home, 
            $has_children, 
            $has_pets, 
            $current_pets, 
            $reason_for_adoption, 
            $additional_info
        );
        
        $result = $stmt->execute();
        if (!$result) {
            throw new Exception("Database error: " . $stmt->error);
        }
        
        $success_message = "Your adoption application has been submitted successfully! We will contact you soon.";
        
        // Clear form data after successful submission
        $_POST = [];
        
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adoption Application - PawParivaar</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #495057;
        }
        .application-header {
            background: linear-gradient(135deg, #6f42c1, #20c997);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        .form-label {
            font-weight: 500;
        }
        .required-field::after {
            content: " *";
            color: #dc3545;
        }
        .btn-primary {
            background: linear-gradient(135deg, #6f42c1, #20c997);
            border: none;
            padding: 10px 25px;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5e35b1, #1a9e76);
        }
        .animal-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .animal-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .custom-control-input:checked ~ .custom-control-label::before {
            border-color: #6f42c1;
            background-color: #6f42c1;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="application-header text-center">
            <h1><i class="fas fa-paw me-2"></i> Adoption Application</h1>
            <?php if($animal): ?>
            <p class="mb-0">You're applying to adopt <?php echo htmlspecialchars($animal['name']); ?></p>
            <?php endif; ?>
        </div>
        
        <?php if($success_message): ?>
        <div class="alert alert-success" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?php echo $success_message; ?>
            <div class="mt-3">
                <a href="browse_animals.php" class="btn btn-outline-success">Browse More Animals</a>
            </div>
        </div>
        <?php else: ?>
            
        <?php if($error_message): ?>
        <div class="alert alert-danger" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error_message; ?>
        </div>
        <?php endif; ?>
        
        <div class="row">
            <?php if($animal): ?>
            <div class="col-md-4 mb-4">
                <div class="animal-card">
                    <img src="<?php echo !empty($animal['primary_image']) ? 'uploads/' . htmlspecialchars($animal['primary_image']) : 'images/no-image.jpg'; ?>" 
                         class="animal-img" alt="<?php echo htmlspecialchars($animal['name']); ?>">
                    <div class="p-3">
                        <h3><?php echo htmlspecialchars($animal['name']); ?></h3>
                        <p><?php echo htmlspecialchars($animal['species']); ?> | 
                           <?php echo htmlspecialchars($animal['breed']); ?> | 
                           <?php echo htmlspecialchars($animal['gender']); ?>
                        </p>
                        <div class="badge bg-success">Ready for Adoption</div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="<?php echo $animal ? 'col-md-8' : 'col-md-12'; ?>">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title mb-4">Your Information</h2>
                        
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                            <?php if($animal_id): ?>
                            <input type="hidden" name="animal_id" value="<?php echo $animal_id; ?>">
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <label for="applicant_name" class="form-label required-field">Full Name</label>
                                <input type="text" class="form-control" id="applicant_name" name="applicant_name" 
                                       value="<?php echo isset($_POST['applicant_name']) ? htmlspecialchars($_POST['applicant_name']) : ''; ?>" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label required-field">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label required-field">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                           value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label required-field">Home Address</label>
                                <textarea class="form-control" id="address" name="address" rows="2" required><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="housing_type" class="form-label required-field">Type of Housing</label>
                                    <select class="form-select" id="housing_type" name="housing_type" required>
                                        <option value="">Select...</option>
                                        <option value="House" <?php echo (isset($_POST['housing_type']) && $_POST['housing_type'] == 'House') ? 'selected' : ''; ?>>House</option>
                                        <option value="Apartment" <?php echo (isset($_POST['housing_type']) && $_POST['housing_type'] == 'Apartment') ? 'selected' : ''; ?>>Apartment</option>
                                        <option value="Condo" <?php echo (isset($_POST['housing_type']) && $_POST['housing_type'] == 'Condo') ? 'selected' : ''; ?>>Condo</option>
                                        <option value="Mobile Home" <?php echo (isset($_POST['housing_type']) && $_POST['housing_type'] == 'Mobile Home') ? 'selected' : ''; ?>>Mobile Home</option>
                                        <option value="Other" <?php echo (isset($_POST['housing_type']) && $_POST['housing_type'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" value="1" id="owns_home" name="owns_home"
                                               <?php echo (isset($_POST['owns_home']) && $_POST['owns_home']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="owns_home">
                                            I own my home
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="has_children" name="has_children"
                                               <?php echo (isset($_POST['has_children']) && $_POST['has_children']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="has_children">
                                            I have children in the home
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="has_pets" name="has_pets"
                                               <?php echo (isset($_POST['has_pets']) && $_POST['has_pets']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="has_pets">
                                            I have other pets
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="current_pets" class="form-label">Current Pets (if any)</label>
                                <textarea class="form-control" id="current_pets" name="current_pets" rows="2"><?php echo isset($_POST['current_pets']) ? htmlspecialchars($_POST['current_pets']) : ''; ?></textarea>
                                <div class="form-text">Please list species, breed, age, and temperament of your current pets</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="reason_for_adoption" class="form-label required-field">Why do you want to adopt this animal?</label>
                                <textarea class="form-control" id="reason_for_adoption" name="reason_for_adoption" rows="3" required><?php echo isset($_POST['reason_for_adoption']) ? htmlspecialchars($_POST['reason_for_adoption']) : ''; ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="additional_info" class="form-label">Additional Information</label>
                                <textarea class="form-control" id="additional_info" name="additional_info" rows="3"><?php echo isset($_POST['additional_info']) ? htmlspecialchars($_POST['additional_info']) : ''; ?></textarea>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="<?php echo $animal_id ? 'animal_detail.php?id='.$animal_id : 'browse_animals.php'; ?>" class="btn btn-outline-secondary me-md-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Submit Application</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
