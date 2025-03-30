<?php
// Initialize variables
$location = isset($_GET['location']) ? htmlspecialchars($_GET['location']) : '';
$lat = isset($_GET['lat']) ? floatval($_GET['lat']) : 0;
$lng = isset($_GET['lng']) ? floatval($_GET['lng']) : 0;

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $conn = new mysqli("localhost", "root", "", "pawparivaar_db");
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Prepare and bind parameters
    $stmt = $conn->prepare("INSERT INTO rescue_operations (name, email, phone, location_name, latitude, longitude, animal_type, animal_count, animal_condition, additional_details) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $location_name = $_POST['location_name'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $animal_type = $_POST['animal_type'];
    $animal_count = $_POST['animal_count'];
    $animal_condition = $_POST['animal_condition'];
    $additional_details = $_POST['additional_details'];
    
    $stmt->bind_param("ssssddsisd", $name, $email, $phone, $location_name, $latitude, $longitude, $animal_type, $animal_count, $animal_condition, $additional_details);
    
    if ($stmt->execute()) {
        // Redirect to confirmation page or show success message
        echo "<script>
                alert('Rescue operation request submitted successfully!');
                window.location.href = 'http://localhost/pawparivaar';
              </script>";
        exit;
    } else {
        $error_message = "Error: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Rescue Operation - PawParivaar</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: #f0f2f5;
            padding: 20px;
        }
        
        .main {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 20px 0;
            background-color: transparent;
        }
        
        .rescue-form-container {
            max-width: 800px;
            width: 100%;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .rescue-form-container h1 {
            text-align: center;
            color: #e74c3c;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 600;
            position: relative;
            padding-bottom: 15px;
        }
        
        .rescue-form-container h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: #e74c3c;
        }
        
        .error-message {
            background-color: #ffebee;
            color: #e53935;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 8px;
            border-left: 4px solid #e53935;
        }
        
        .location-display {
            background-color: #f5f9ff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 4px solid #3498db;
        }
        
        .location-display strong {
            color: #2c3e50;
            font-size: 17px;
        }
        
        #mini-map {
            height: 250px;
            margin-top: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        
        .animal-icon label::before {
            content: '🐾 ';
        }
        
        .form-group input, 
        .form-group select, 
        .form-group textarea {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus, 
        .form-group select:focus, 
        .form-group textarea:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .form-actions {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 35px;
        }
        
        .form-actions button {
            padding: 14px 35px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 50px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .form-actions button[type="submit"] {
            background-color: #27ae60;
            color: white;
            border: none;
            box-shadow: 0 4px 6px rgba(39, 174, 96, 0.2);
        }
        
        .form-actions button[type="submit"]:hover {
            background-color: #2ecc71;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(39, 174, 96, 0.3);
        }
        
        .form-actions button[type="button"] {
            background-color: white;
            color: #e74c3c;
            border: 2px solid #e74c3c;
        }
        
        .form-actions button[type="button"]:hover {
            background-color: #f8f9fa;
            color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="main">
        <div class="rescue-form-container">
            <h1>Request Animal Rescue</h1>
            
            <?php if (isset($error_message)): ?>
                <div class="error-message">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <div class="location-display">
                <strong>Selected Location:</strong> <?php echo $location; ?>
                <div id="mini-map"></div>
            </div>
            
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?location=' . urlencode($location) . '&lat=' . $lat . '&lng=' . $lng; ?>">
                <!-- Hidden fields for location data -->
                <input type="hidden" name="location_name" value="<?php echo $location; ?>">
                <input type="hidden" name="latitude" value="<?php echo $lat; ?>">
                <input type="hidden" name="longitude" value="<?php echo $lng; ?>">
                
                <div class="form-group">
                    <label for="name">Your Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
                
                <div class="form-group animal-icon">
                    <label for="animal_type">Animal Type:</label>
                    <select id="animal_type" name="animal_type" required>
                        <option value="">-- Select Animal Type --</option>
                        <option value="dog">Dog</option>
                        <option value="cat">Cat</option>
                        <option value="bird">Bird</option>
                        <option value="cow">Cow</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div class="form-group animal-icon">
                    <label for="animal_count">Number of Animals:</label>
                    <input type="number" id="animal_count" name="animal_count" min="1" value="1" required>
                </div>
                
                <div class="form-group animal-icon">
                    <label for="animal_condition">Animal Condition:</label>
                    <textarea id="animal_condition" name="animal_condition" required placeholder="Describe the condition of the animal(s) and why they need rescue"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="additional_details">Additional Details (Optional):</label>
                    <textarea id="additional_details" name="additional_details" placeholder="Any additional information that might help us with the rescue"></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit">Submit Rescue Request</button>
                    <button type="button" onclick="window.location.href='index.html'">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Leaflet.js JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Initialize mini map
        document.addEventListener('DOMContentLoaded', function() {
            var lat = <?php echo $lat; ?>;
            var lng = <?php echo $lng; ?>;
            
            var miniMap = L.map('mini-map').setView([lat, lng], 15);
            
            L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(miniMap);
            
            // Add marker at the selected location
            L.marker([lat, lng]).addTo(miniMap)
                .bindPopup('<?php echo $location; ?>')
                .openPopup();
        });
    </script>
</body>
</html>