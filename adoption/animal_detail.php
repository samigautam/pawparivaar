<?php
// Database connection
require_once '../classes/DBConnection.php';
$db = new DBConnection(); // Initialize the DBConnection class
$conn = $db->conn; // Assign the connection to $conn

// Check if animal ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: browse_animals.php');
    exit;
}

$animal_id = (int)$_GET['id'];

// Get animal details
$sql = "SELECT * FROM animals WHERE animal_id = $animal_id";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    header('Location: browse_animals.php');
    exit;
}

$animal = $result->fetch_assoc();



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($animal['name']); ?> - PawParivaar Adoption</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <!-- Lightbox CSS for image gallery -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #495057;
            line-height: 1.7;
        }
        
        .container {
            padding: 30px 15px;
            max-width: 1300px;
        }
        
        .animal-header {
            position: relative;
            margin-bottom: 30px;
        }
        
        .animal-name {
            font-family: 'Pacifico', cursive;
            color: #6f42c1;
            font-size: 2.8rem;
            margin-bottom: 0.5rem;
        }
        
        .animal-badge {
            display: inline-block;
            background: linear-gradient(135deg, #6f42c1, #20c997);
            color: white;
            padding: 5px 15px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 1rem;
        }
        
        .animal-main-image {
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            width: 100%;
            height: 400px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .animal-main-image:hover {
            transform: scale(1.02);
        }
        
        .thumbnail-container {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
        }
        
        .thumbnail {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            object-fit: cover;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 3px solid transparent;
        }
        
        .thumbnail:hover {
            transform: translateY(-5px);
            border-color: #6f42c1;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }
        
        .card-header {
            background: linear-gradient(135deg, #6f42c1, #20c997);
            color: white;
            font-weight: 600;
            padding: 15px 20px;
            border-bottom: none;
        }
        
        .card-header h3 {
            margin: 0;
            font-size: 1.5rem;
        }
        
        .card-body {
            padding: 25px;
        }
        
        .animal-info-section {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .animal-info-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .animal-info-section h5 {
            color: #343a40;
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 1.25rem;
        }
        
        .detail-item {
            display: flex;
            margin-bottom: 10px;
        }
        
        .detail-label {
            font-weight: 600;
            min-width: 160px;
            color: #495057;
        }
        
        .detail-value {
            color: #6c757d;
        }
        
        .badge {
            font-size: 0.9rem;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
        }
        
        .badge-success {
            background-color: #20c997;
            color: white;
        }
        
        .badge-warning {
            background-color: #ffc107;
            color: #343a40;
        }
        
        .btn-adopt {
            background: linear-gradient(135deg, #6f42c1, #20c997);
            border: none;
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            padding: 12px 25px;
            border-radius: 50px;
            margin-top: 20px;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-adopt:hover {
            background: linear-gradient(135deg, #5e35b1, #1a9e76);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        
        .btn-adopt[disabled] {
            background: #adb5bd;
            cursor: not-allowed;
        }
        
        .btn-back {
            background: #e9ecef;
            color: #495057;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-back:hover {
            background: #dee2e6;
            color: #212529;
        }
        
        .list-group-item {
            border: none;
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .list-group-item:last-child {
            border-bottom: none;
        }
        
        .list-group-item i {
            margin-right: 10px;
            color: #6f42c1;
        }
        
        .special-needs-alert {
            background-color: #fff3cd;
            color: #856404;
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .special-needs-alert i {
            font-size: 1.5rem;
            color: #ffc107;
        }
        
        .carousel-indicators {
            bottom: -50px;
        }
        
        .carousel-indicators button {
            background-color: #6f42c1;
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }
        
        .personality-traits {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }
        
        .personality-tag {
            background-color: #e9ecef;
            color: #495057;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .animal-name {
                font-size: 2.2rem;
            }
            
            .animal-main-image {
                height: 300px;
            }
            
            .detail-item {
                flex-direction: column;
            }
            
            .detail-label {
                min-width: unset;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="animal-header text-center text-md-start">
        <h1 class="animal-name"><?php echo htmlspecialchars($animal['name']); ?></h1>
        <div class="animal-badge">
            <?php if($animal['adoption_status'] === 'Available'): ?>
                <i class="fas fa-heart me-1"></i> Available for Adoption
            <?php else: ?>
                <i class="fas fa-paw me-1"></i> <?php echo htmlspecialchars($animal['adoption_status']); ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <!-- Animal Images -->
            <div class="card">
                <div class="card-body p-0">
                    <a href="<?php echo !empty($animal['primary_image']) ? 'uploads/' . htmlspecialchars($animal['primary_image']) : 'images/no-image.jpg'; ?>" data-lightbox="animal-gallery" data-title="<?php echo htmlspecialchars($animal['name']); ?>">
                        <img src="<?php echo !empty($animal['primary_image']) ? 'uploads/' . htmlspecialchars($animal['primary_image']) : 'images/no-image.jpg'; ?>" 
                             class="animal-main-image" alt="<?php echo htmlspecialchars($animal['name']); ?>">
                    </a>
                    
                    <?php if (!empty($additional_images)): ?>
                    <div class="thumbnail-container px-3 pb-3">
                        <?php foreach ($additional_images as $img): ?>
                        <a href="uploads/<?php echo htmlspecialchars($img); ?>" data-lightbox="animal-gallery" data-title="<?php echo htmlspecialchars($animal['name']); ?>">
                            <img src="uploads/<?php echo htmlspecialchars($img); ?>" class="thumbnail" alt="<?php echo htmlspecialchars($animal['name']); ?>">
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Animal Details -->
            <div class="card">
                <div class="card-header">
                    <h3>About <?php echo htmlspecialchars($animal['name']); ?></h3>
                </div>
                <div class="card-body">
                    <div class="animal-info-section">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <div class="detail-label"><i class="fas fa-paw me-2"></i>Species:</div>
                                    <div class="detail-value"><?php echo htmlspecialchars($animal['species']); ?></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label"><i class="fas fa-tag me-2"></i>Breed:</div>
                                    <div class="detail-value"><?php echo !empty($animal['breed']) ? htmlspecialchars($animal['breed']) : 'N/A'; ?></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label"><i class="fas fa-birthday-cake me-2"></i>Age:</div>
                                    <div class="detail-value">
                                        <?php 
                                        if ($animal['age_years'] > 0) {
                                            echo $animal['age_years'] . ' year' . ($animal['age_years'] > 1 ? 's' : '');
                                            if ($animal['age_months'] > 0) {
                                                echo ', ' . $animal['age_months'] . ' month' . ($animal['age_months'] > 1 ? 's' : '');
                                            }
                                        } else {
                                            echo $animal['age_months'] . ' month' . ($animal['age_months'] > 1 ? 's' : '');
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label"><i class="fas fa-venus-mars me-2"></i>Gender:</div>
                                    <div class="detail-value"><?php echo htmlspecialchars($animal['gender']); ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <div class="detail-label"><i class="fas fa-weight me-2"></i>Size:</div>
                                    <div class="detail-value"><?php echo !empty($animal['size']) ? htmlspecialchars($animal['size']) : 'N/A'; ?></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label"><i class="fas fa-palette me-2"></i>Color:</div>
                                    <div class="detail-value"><?php echo !empty($animal['color']) ? htmlspecialchars($animal['color']) : 'N/A'; ?></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label"><i class="fas fa-cut me-2"></i>Spayed/Neutered:</div>
                                    <div class="detail-value"><?php echo $animal['spayed_neutered'] ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>'; ?></div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label"><i class="fas fa-syringe me-2"></i>Vaccinated:</div>
                                    <div class="detail-value"><?php echo $animal['vaccinations_current'] ? '<span class="text-success">Yes</span>' : '<span class="text-danger">No</span>'; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="animal-info-section">
                        <h5><i class="fas fa-heart me-2"></i>Description</h5>
                        <p><?php echo nl2br(htmlspecialchars($animal['description'])); ?></p>
                        
                        <!-- Optional: Personality Traits -->
                        <?php 
                        $traits = [
                            "Friendly", "Playful", "Energetic", "Calm", "Shy", "Affectionate", 
                            "Curious", "Independent", "Social", "Intelligent"
                        ];
                        // Randomly select 3-5 traits for demo purposes. In a real app, you'd have this data stored.
                        $num_traits = rand(3, 5);
                        shuffle($traits);
                        $animal_traits = array_slice($traits, 0, $num_traits);
                        ?>
                        <div class="personality-traits">
                            <?php foreach($animal_traits as $trait): ?>
                                <span class="personality-tag"><i class="fas fa-star me-1"></i><?php echo $trait; ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($animal['medical_history'])): ?>
                    <div class="animal-info-section">
                        <h5><i class="fas fa-medkit me-2"></i>Medical History</h5>
                        <p><?php echo nl2br(htmlspecialchars($animal['medical_history'])); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($animal['behavioral_notes'])): ?>
                    <div class="animal-info-section">
                        <h5><i class="fas fa-brain me-2"></i>Behavioral Notes</h5>
                        <p><?php echo nl2br(htmlspecialchars($animal['behavioral_notes'])); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($animal['special_needs'])): ?>
                    <div class="special-needs-alert">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <h5 class="mb-1">Special Needs</h5>
                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($animal['special_needs'])); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-5">
            <!-- Adoption Information -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-home me-2"></i>Adoption Information</h3>
                </div>
                <div class="card-body">
                    <div class="detail-item">
                        <div class="detail-label"><i class="fas fa-info-circle me-2"></i>Status:</div>
                        <div class="detail-value">
                            <span class="badge badge-<?php echo $animal['adoption_status'] === 'Available' ? 'success' : 'warning'; ?>">
                                <?php echo htmlspecialchars($animal['adoption_status']); ?>
                            </span>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label"><i class="fas fa-rupee-sign me-2"></i>Adoption Fee:</div>
                        <div class="detail-value">Nrs.<?php echo number_format($animal['adoption_fee'], 2); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label"><i class="fas fa-calendar-alt me-2"></i>At Shelter Since:</div>
                        <div class="detail-value"><?php echo date('F j, Y', strtotime($animal['intake_date'])); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label"><i class="fas fa-map-marker-alt me-2"></i>Location:</div>
                        <div class="detail-value"><?php echo !empty($animal['location']) ? htmlspecialchars($animal['location']) : 'Main Shelter'; ?></div>
                    </div>
                    
                    <?php if ($animal['adoption_status'] === 'Available'): ?>
                        <a href="adoption_application.php?animal_id=<?php echo $animal_id; ?>" class="btn btn-adopt">
                            <i class="fas fa-paw me-2"></i> Apply to Adopt <?php echo htmlspecialchars($animal['name']); ?>
                        </a>
                    <?php else: ?>
                        <button class="btn btn-adopt" disabled>
                            <i class="fas fa-times-circle me-2"></i> Not Available for Adoption
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Adoption Requirements -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-clipboard-check me-2"></i>Adoption Requirements</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush px-3">
                        <li class="list-group-item"><i class="fas fa-id-card"></i> Valid identification</li>
                        <li class="list-group-item"><i class="fas fa-home"></i> Proof of residence</li>
                        <li class="list-group-item"><i class="fas fa-file-signature"></i> Landlord approval (if renting)</li>
                        <li class="list-group-item"><i class="fas fa-users"></i> Meet and greet with all household members</li>
                        <li class="list-group-item"><i class="fas fa-house-user"></i> Home visit may be required</li>
                        <li class="list-group-item"><i class="fas fa-clock"></i> Application review process (1-3 days)</li>
                    </ul>
                </div>
            </div>
            
            <!-- Why Adopt Section -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-heart me-2"></i>Why Adopt?</h3>
                </div>
                <div class="card-body">
                    <p>When you adopt, you save a life and create space for another animal who needs help. Our adoption fees help cover:</p>
                    <ul>
                        <li>Veterinary care and vaccinations</li>
                        <li>Spay/neuter procedures</li>
                        <li>Microchipping</li>
                        <li>Food and shelter while in our care</li>
                        <li>Behavioral assessment and training</li>
                    </ul>
                    <p>Every adoption makes a difference in an animal's life!</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4 text-center text-md-start">
        <a href="browse_animals.php" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Back to Browse
        </a>
    </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Lightbox JS for image gallery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script>
    // Initialize Lightbox
    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true,
        'showImageNumberLabel': false
    });
</script>
</body>
</html>

