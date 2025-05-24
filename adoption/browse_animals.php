<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Animals - PawParivaar</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #495057;
            line-height: 1.7;
        }

        .container {
            max-width: 1300px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .page-title {
            font-family: 'Pacifico', cursive;
            color:rgb(17, 13, 23);
            text-align: center;
            margin-bottom: 30px;
            font-size: 3rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            position: relative;
        }
        
        .page-title:after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 150px;
            height: 5px;
            background: linear-gradient(to right,rgb(30, 23, 42),rgb(66, 78, 139));
            border-radius: 25px;
        }
        
        .filter-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            margin-bottom: 40px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .filter-card .card-body {
            padding: 25px 30px;
        }
        
        .filter-card:hover {
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
            transform: translateY(-5px);
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #dce4ec;
            font-size: 15px;
            transition: all 0.3s ease;
            box-shadow: none;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #6f42c1;
            box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.25);
        }
        
        .btn {
            padding: 12px 20px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(to right, #6f42c1, #20c997);
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(to right, #5e35b1, #1a9e76);
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(111, 66, 193, 0.3);
        }
        
        .btn-secondary {
            background-color: #f8f9fa;
            color: #6c757d;
            border: 1px solid #dce4ec;
        }
        
        .btn-secondary:hover {
            background-color: #e9ecef;
            color: #495057;
        }
        
        .animal-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s ease;
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
            height: 100%;
        }
        
        .animal-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        .animal-card img {
            transition: all 0.5s ease;
            height: 250px;
            object-fit: cover;
        }
        
        .animal-card:hover img {
            transform: scale(1.05);
        }
        
        .animal-card .card-body {
            padding: 20px;
        }
        
        .animal-card .card-title {
            font-weight: 700;
            font-size: 1.4rem;
            color: #343a40;
            margin-bottom: 10px;
        }
        
        .animal-card .card-text {
            color: #6c757d;
        }
        
        .animal-stats {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        
        .animal-stat {
            background-color: #f1f3f5;
            color: #495057;
            padding: 5px 10px;
            border-radius: 50px;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .animal-stat i {
            color:rgb(19, 17, 23);
        }
        
        .view-details-btn {
            width: 100%;
            border-radius: 10px;
            background: linear-gradient(to right,rgb(29, 24, 37),rgb(49, 40, 119));
            color: white;
            border: none;
            padding: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .view-details-btn:hover {
            background: linear-gradient(to right, #5e35b1, #1a9e76);
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(111, 66, 193, 0.2);
        }
        
        .special-needs-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: #ffc107;
            color: #343a40;
            padding: 5px 10px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .pagination {
            margin-top: 40px;
        }
        
        .pagination .page-item .page-link {
            color: #6f42c1;
            border: none;
            margin: 0 5px;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .pagination .page-item .page-link:hover {
            background-color: #e9d8fd;
        }
        
        .pagination .page-item.active .page-link {
            background-color: #6f42c1;
            color: white;
        }
        
        .pagination .page-item.disabled .page-link {
            color: #adb5bd;
        }
        
        .form-check-input:checked {
            background-color: #6f42c1;
            border-color: #6f42c1;
        }
        
        .form-check-label {
            cursor: pointer;
        }
        
        .alert-info {
            background-color: #e3f2fd;
            color: #0c63e4;
            border: none;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(12, 99, 228, 0.1);
        }
        
        /* Filter tags */
        .filter-tags-container {
            margin: 20px 0;
        }
        
        .filter-tag {
            display: inline-block;
            background-color: #e3f2fd;
            color: #0c63e4;
            padding: 5px 15px;
            margin: 5px;
            border-radius: 50px;
            font-size: 0.9rem;
        }
        
        .filter-tag .remove-filter {
            margin-left: 8px;
            color: #0c63e4;
            cursor: pointer;
        }
        
        /* Animation for card appearance */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animal-card-wrapper {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        /* Custom styling for select dropdowns */
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3E%3Cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 8px 10px;
        }
    </style>
</head>
<body>
<?php
// Database connection
require_once '../classes/DBConnection.php';
$db = new DBConnection();
$conn = $db->conn;

// Initialize filter variables
$species_filter = isset($_GET['species']) ? $_GET['species'] : '';
$age_filter = isset($_GET['age']) ? $_GET['age'] : '';
$gender_filter = isset($_GET['gender']) ? $_GET['gender'] : '';
$size_filter = isset($_GET['size']) ? $_GET['size'] : '';
$special_needs_filter = isset($_GET['special_needs']) ? $_GET['special_needs'] : '';
$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'intake_date';
$sort_order = isset($_GET['order']) ? $_GET['order'] : 'DESC';

// Pagination
$results_per_page = 12;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;

// Build the SQL query with filters
$sql = "SELECT * FROM animals WHERE adoption_status = 'Available'";

if (!empty($species_filter)) {
    $sql .= " AND species = '$species_filter'";
}
if (!empty($age_filter)) {
    if ($age_filter == 'baby') {
        $sql .= " AND age_years = 0 AND age_months <= 6";
    } elseif ($age_filter == 'young') {
        $sql .= " AND ((age_years = 0 AND age_months > 6) OR (age_years = 1))";
    } elseif ($age_filter == 'adult') {
        $sql .= " AND age_years > 1 AND age_years <= 7";
    } elseif ($age_filter == 'senior') {
        $sql .= " AND age_years > 7";
    }
}
if (!empty($gender_filter)) {
    $sql .= " AND gender = '$gender_filter'";
}
if (!empty($size_filter)) {
    $sql .= " AND size = '$size_filter'";
}
if (!empty($special_needs_filter)) {
    $sql .= " AND special_needs IS NOT NULL";
}
if (!empty($search_term)) {
    $sql .= " AND (name LIKE '%$search_term%' OR description LIKE '%$search_term%' OR breed LIKE '%$search_term%')";
}

// Add sorting
$sql .= " ORDER BY $sort_by $sort_order";

// Add pagination
$sql .= " LIMIT $start_from, $results_per_page";

// Execute query
$result = $conn->query($sql);

// Count total records for pagination
$count_sql = "SELECT COUNT(*) as total FROM animals WHERE adoption_status = 'Available'";
if (!empty($species_filter)) {
    $count_sql .= " AND species = '$species_filter'";
}
if (!empty($age_filter)) {
    if ($age_filter == 'baby') {
        $count_sql .= " AND age_years = 0 AND age_months <= 6";
    } elseif ($age_filter == 'young') {
        $count_sql .= " AND ((age_years = 0 AND age_months > 6) OR (age_years = 1))";
    } elseif ($age_filter == 'adult') {
        $count_sql .= " AND age_years > 1 AND age_years <= 7";
    } elseif ($age_filter == 'senior') {
        $count_sql .= " AND age_years > 7";
    }
}
if (!empty($gender_filter)) {
    $count_sql .= " AND gender = '$gender_filter'";
}
if (!empty($size_filter)) {
    $count_sql .= " AND size = '$size_filter'";
}
if (!empty($special_needs_filter)) {
    $count_sql .= " AND special_needs IS NOT NULL";
}
if (!empty($search_term)) {
    $count_sql .= " AND (name LIKE '%$search_term%' OR description LIKE '%$search_term%' OR breed LIKE '%$search_term%')";
}

$count_result = $conn->query($count_sql);
$count_row = $count_result->fetch_assoc();
$total_pages = ceil($count_row['total'] / $results_per_page);

// Get unique values for filter dropdowns
$species_query = "SELECT DISTINCT species FROM animals WHERE adoption_status = 'Available' ORDER BY species";
$species_result = $conn->query($species_query);

$size_query = "SELECT DISTINCT size FROM animals WHERE adoption_status = 'Available' AND size IS NOT NULL ORDER BY size";
$size_result = $conn->query($size_query);

?>

<div class="container">
    <h1 class="page-title">Find Your Perfect Companion</h1>
    
    <!-- Search and Filter Section -->
    <div class="card filter-card">
        <div class="card-body">
            <form method="GET" action="browse_animals.php" id="filter-form">
                <div class="row g-3">
                    <div class="col-md-3 mb-2">
                        <label for="search" class="form-label"><i class="fas fa-search me-2"></i>Search</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Name, breed or keywords" value="<?php echo htmlspecialchars($search_term); ?>">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label for="species" class="form-label"><i class="fas fa-paw me-2"></i>Species</label>
                        <select name="species" id="species" class="form-control">
                            <option value="">All Species</option>
                            <?php while($species = $species_result->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($species['species']); ?>" <?php if($species_filter == $species['species']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($species['species']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label for="age" class="form-label"><i class="fas fa-birthday-cake me-2"></i>Age</label>
                        <select name="age" id="age" class="form-control">
                            <option value="">All Ages</option>
                            <option value="baby" <?php if($age_filter == 'baby') echo 'selected'; ?>>Baby (0-6 months)</option>
                            <option value="young" <?php if($age_filter == 'young') echo 'selected'; ?>>Young (6 months-1 year)</option>
                            <option value="adult" <?php if($age_filter == 'adult') echo 'selected'; ?>>Adult (1-7 years)</option>
                            <option value="senior" <?php if($age_filter == 'senior') echo 'selected'; ?>>Senior (7+ years)</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label for="gender" class="form-label"><i class="fas fa-venus-mars me-2"></i>Gender</label>
                        <select name="gender" id="gender" class="form-control">
                            <option value="">All Genders</option>
                            <option value="Male" <?php if($gender_filter == 'Male') echo 'selected'; ?>>Male</option>
                            <option value="Female" <?php if($gender_filter == 'Female') echo 'selected'; ?>>Female</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label for="size" class="form-label"><i class="fas fa-weight me-2"></i>Size</label>
                        <select name="size" id="size" class="form-control">
                            <option value="">All Sizes</option>
                            <?php while($size = $size_result->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($size['size']); ?>" <?php if($size_filter == $size['size']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($size['size']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-1 mb-2">
                        <label class="form-label d-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-1"></i> Filter</button>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3 mb-2 d-flex align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="special_needs" value="1" id="specialNeedsCheck" <?php if($special_needs_filter) echo 'checked'; ?>>
                            <label class="form-check-label" for="specialNeedsCheck">
                                <i class="fas fa-heart me-2 text-danger"></i>Special Needs
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="sort" class="form-label"><i class="fas fa-sort me-2"></i>Sort By</label>
                        <select name="sort" id="sort" class="form-control">
                            <option value="intake_date" <?php if($sort_by == 'intake_date') echo 'selected'; ?>>Newest Arrivals</option>
                            <option value="name" <?php if($sort_by == 'name') echo 'selected'; ?>>Name</option>
                            <option value="age_years" <?php if($sort_by == 'age_years') echo 'selected'; ?>>Age</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="order" class="form-label"><i class="fas fa-arrows-alt-v me-2"></i>Order</label>
                        <select name="order" id="order" class="form-control">
                            <option value="DESC" <?php if($sort_order == 'DESC') echo 'selected'; ?>>Descending</option>
                            <option value="ASC" <?php if($sort_order == 'ASC') echo 'selected'; ?>>Ascending</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="form-label d-block">&nbsp;</label>
                        <a href="browse_animals.php" class="btn btn-secondary w-100"><i class="fas fa-redo me-1"></i> Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <?php if(!empty($species_filter) || !empty($age_filter) || !empty($gender_filter) || !empty($size_filter) || !empty($special_needs_filter) || !empty($search_term)): ?>
    <div class="filter-tags-container">
        <div class="d-flex align-items-center">
            <span class="me-2"><i class="fas fa-tag me-1"></i> Active filters:</span>
            <?php if(!empty($species_filter)): ?>
                <div class="filter-tag">Species: <?php echo htmlspecialchars($species_filter); ?> <span class="remove-filter" onclick="removeFilter('species')">×</span></div>
            <?php endif; ?>
            <?php if(!empty($age_filter)): ?>
                <div class="filter-tag">Age: <?php echo htmlspecialchars($age_filter); ?> <span class="remove-filter" onclick="removeFilter('age')">×</span></div>
            <?php endif; ?>
            <?php if(!empty($gender_filter)): ?>
                <div class="filter-tag">Gender: <?php echo htmlspecialchars($gender_filter); ?> <span class="remove-filter" onclick="removeFilter('gender')">×</span></div>
            <?php endif; ?>
            <?php if(!empty($size_filter)): ?>
                <div class="filter-tag">Size: <?php echo htmlspecialchars($size_filter); ?> <span class="remove-filter" onclick="removeFilter('size')">×</span></div>
            <?php endif; ?>
            <?php if(!empty($special_needs_filter)): ?>
                <div class="filter-tag">Special Needs <span class="remove-filter" onclick="removeFilter('special_needs')">×</span></div>
            <?php endif; ?>
            <?php if(!empty($search_term)): ?>
                <div class="filter-tag">Search: "<?php echo htmlspecialchars($search_term); ?>" <span class="remove-filter" onclick="removeFilter('search')">×</span></div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Animals Grid Section -->
    <?php if($result->num_rows > 0): ?>
        <div class="row g-4">
            <?php $delay = 0; while($animal = $result->fetch_assoc()): $delay += 0.1; ?>
                <div class="col-md-3 mb-4 animal-card-wrapper" style="animation-delay: <?php echo $delay; ?>s">
                    <div class="card animal-card h-100">
                        <?php if (!empty($animal['special_needs'])): ?>
                            <div class="special-needs-badge">
                                <i class="fas fa-heart me-1"></i> Special Needs
                            </div>
                        <?php endif; ?>
                        <div class="position-relative overflow-hidden">
                            <img src="<?php echo !empty($animal['primary_image']) ? 'uploads/' . htmlspecialchars($animal['primary_image']) : 'images/no-image.jpg'; ?>" 
                                class="card-img-top" alt="<?php echo htmlspecialchars($animal['name']); ?>">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($animal['name']); ?></h5>
                            <div class="animal-stats">
                                <span class="animal-stat"><i class="fas fa-paw"></i> <?php echo htmlspecialchars($animal['species']); ?></span>
                                <span class="animal-stat"><i class="fas fa-venus-mars"></i> <?php echo htmlspecialchars($animal['gender']); ?></span>
                                <span class="animal-stat"><i class="fas fa-birthday-cake"></i> 
                                    <?php 
                                    if($animal['age_years'] > 0) {
                                        echo $animal['age_years'] . ' yr' . ($animal['age_years'] > 1 ? 's' : '');
                                        if($animal['age_months'] > 0) {
                                            echo ', ' . $animal['age_months'] . ' mo' . ($animal['age_months'] > 1 ? 's' : '');
                                        }
                                    } else {
                                        echo $animal['age_months'] . ' mo' . ($animal['age_months'] > 1 ? 's' : '');
                                    }
                                    ?>
                                </span>
                                <?php if(!empty($animal['breed'])): ?>
                                    <span class="animal-stat"><i class="fas fa-tag"></i> <?php echo htmlspecialchars($animal['breed']); ?></span>
                                <?php endif; ?>
                            </div>
                            <p class="card-text flex-grow-1"><?php echo substr(htmlspecialchars($animal['description']), 0, 80); ?>...</p>
                            <a href="animal_detail.php?id=<?php echo $animal['animal_id']; ?>" class="btn view-details-btn">
                                <i class="fas fa-paw me-1"></i> Meet <?php echo htmlspecialchars($animal['name']); ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        
        <!-- Pagination -->
        <?php if($total_pages > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php if($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page-1; ?>&species=<?php echo urlencode($species_filter); ?>&age=<?php echo urlencode($age_filter); ?>&gender=<?php echo urlencode($gender_filter); ?>&size=<?php echo urlencode($size_filter); ?>&special_needs=<?php echo urlencode($special_needs_filter); ?>&search=<?php echo urlencode($search_term); ?>&sort=<?php echo urlencode($sort_by); ?>&order=<?php echo urlencode($sort_order); ?>">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php if($i == $page) echo 'active'; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&species=<?php echo urlencode($species_filter); ?>&age=<?php echo urlencode($age_filter); ?>&gender=<?php echo urlencode($gender_filter); ?>&size=<?php echo urlencode($size_filter); ?>&special_needs=<?php echo urlencode($special_needs_filter); ?>&search=<?php echo urlencode($search_term); ?>&sort=<?php echo urlencode($sort_by); ?>&order=<?php echo urlencode($sort_order); ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page+1; ?>&species=<?php echo urlencode($species_filter); ?>&age=<?php echo urlencode($age_filter); ?>&gender=<?php echo urlencode($gender_filter); ?>&size=<?php echo urlencode($size_filter); ?>&special_needs=<?php echo urlencode($special_needs_filter); ?>&search=<?php echo urlencode($search_term); ?>&sort=<?php echo urlencode($sort_by); ?>&order=<?php echo urlencode($sort_order); ?>">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            No animals found matching your criteria. Please try different filters.
        </div>
    <?php endif; ?>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Function to remove a filter and resubmit the form
    function removeFilter(filter) {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.delete(filter);
        window.location.href = `browse_animals.php?${urlParams.toString()}`;
    }
    
    // Animation for cards on page load
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.animal-card-wrapper');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '1';
            }, index * 100);
        });
    });
</script>
</body>
</html>
