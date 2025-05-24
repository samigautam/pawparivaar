<?php
require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * FROM `rescue_operations` WHERE id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<style>
    #uni_modal .modal-footer{
        display: flex;
        justify-content: flex-end;
    }
    
    /* Map styling */
    #map {
        height: 400px; /* Ensure the map container has a fixed height */
        width: 100%;   /* Ensure the map container spans the full width */
        margin-bottom: 20px;
        border: 2px solid #ddd;
        border-radius: 8px;
        z-index: 1; /* Ensure map is not hidden by other elements */
    }
    
    .rescue-details {
        margin-bottom: 20px;
    }
    
    .rescue-details dt {
        font-weight: bold;
        color: #6f42c1;
    }
    
    .badge-status {
        font-size: 0.9rem;
        padding: 0.4rem 0.8rem;
        border-radius: 50px;
    }
    
    .badge-pending {
        background-color: #ffc107;
        color: #212529;
    }
    
    .badge-in-progress {
        background-color: #17a2b8;
        color: white;
    }
    
    .badge-completed {
        background-color: #28a745;
        color: white;
    }
    
    .badge-cancelled {
        background-color: #dc3545;
        color: white;
    }

    /* Override modal z-index to ensure leaflet controls work */
    .modal {
        z-index: 1040 !important;
    }
    .modal-backdrop {
        z-index: 1039 !important;
    }
    
    /* Fix for map controls */
    .leaflet-top, .leaflet-bottom {
        z-index: 1000 !important;
    }
</style>

<!-- Include Leaflet CSS and JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4 class="text-center"><?php echo isset($location_name) ? $location_name : "Rescue Location" ?></h4>
            
            <!-- Map Container -->
            <div id="map"></div>
            
            <!-- Map loading indicator -->
            <div id="map-loading" class="text-center mb-2 d-none">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading map...</span>
                </div>
                <p>Loading map...</p>
            </div>
            
            <!-- Map error message -->
            <div id="map-error" class="alert alert-danger d-none">
                Failed to load the map. Please refresh the page.
            </div>
            
            <div class="rescue-details">
                <dl class="row">
                    <dt class="col-md-4">Status:</dt>
                    <dd class="col-md-8">
                        <?php 
                            $status_class = '';
                            switch($status) {
                                case 'Pending':
                                    $status_class = 'badge-pending';
                                    break;
                                case 'In Progress':
                                    $status_class = 'badge-in-progress';
                                    break;
                                case 'Completed':
                                    $status_class = 'badge-completed';
                                    break;
                                case 'Cancelled':
                                    $status_class = 'badge-cancelled';
                                    break;
                                default:
                                    $status_class = 'badge-secondary';
                            }
                        ?>
                        <span class="badge <?php echo $status_class; ?>"><?php echo $status; ?></span>
                    </dd>
                    
                    <dt class="col-md-4">Requested By:</dt>
                    <dd class="col-md-8"><?php echo $name; ?></dd>
                    
                    <dt class="col-md-4">Contact:</dt>
                    <dd class="col-md-8"><?php echo $phone; ?></dd>
                    
                    <dt class="col-md-4">Email:</dt>
                    <dd class="col-md-8"><?php echo $email; ?></dd>
                    
                    <dt class="col-md-4">Animal Type:</dt>
                    <dd class="col-md-8"><?php echo $animal_type; ?></dd>
                    
                    <dt class="col-md-4">Count:</dt>
                    <dd class="col-md-8"><?php echo $animal_count; ?></dd>
                    
                    <dt class="col-md-4">Condition:</dt>
                    <dd class="col-md-8"><?php echo $animal_condition; ?></dd>
                    
                    <dt class="col-md-4">Requested On:</dt>
                    <dd class="col-md-8"><?php echo date("F d, Y h:i A", strtotime($created_at)); ?></dd>
                    
                    <?php if(!empty($additional_details)): ?>
                    <dt class="col-md-4">Additional Details:</dt>
                    <dd class="col-md-8"><?php echo nl2br($additional_details); ?></dd>
                    <?php endif; ?>
                </dl>
                
                <?php if($status == 'In Progress' || $status == 'Completed'): ?>
                <!-- Show status history -->
                <h5 class="mt-4">Status Updates</h5>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Status Change</th>
                            <th>By</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $logs = $conn->query("SELECT * FROM rescue_status_log WHERE rescue_id = '{$id}' ORDER BY changed_at DESC");
                        while($row = $logs->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?php echo date("M d, Y h:i A", strtotime($row['changed_at'])); ?></td>
                            <td><?php echo $row['previous_status'] . ' → ' . $row['new_status']; ?></td>
                            <td><?php echo $row['changed_by']; ?></td>
                            <td><?php echo $row['notes']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show loading indicator
    document.getElementById('map-loading').classList.remove('d-none');
    
    try {
        // Get coordinates from PHP variables
        const latitude = <?php echo isset($latitude) && is_numeric($latitude) ? $latitude : 27.699929 ?>;
        const longitude = <?php echo isset($longitude) && is_numeric($longitude) ? $longitude : 85.319628 ?>;
        const locationName = "<?php echo isset($location_name) ? addslashes($location_name) : 'Rescue Location' ?>";
        
        console.log("Initializing map with coordinates:", latitude, longitude);
        
        // Initialize map
        const map = L.map('map', { zoomControl: true }).setView([latitude, longitude], 15);
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
        }).addTo(map);
        
        // Add marker at the rescue location
        const marker = L.marker([latitude, longitude]).addTo(map);
        marker.bindPopup(`<b>${locationName}</b><br>Animal rescue location`).openPopup();
        
        // Hide loading indicator
        document.getElementById('map-loading').classList.add('d-none');
        
        // Ensure map renders correctly in the modal
        setTimeout(function() {
            map.invalidateSize();
        }, 300);
        
        // Debugging: Check if the map container has dimensions
        const mapElement = document.getElementById('map');
        console.log("Map container dimensions:", mapElement.offsetWidth, mapElement.offsetHeight);
        
        // If the modal is shown in a tab or after some delay, trigger resize
        $('#uni_modal').on('shown.bs.modal', function() {
            setTimeout(function() {
                map.invalidateSize();
            }, 300);
        });
        
    } catch (error) {
        // Hide loading indicator and show error message
        document.getElementById('map-loading').classList.add('d-none');
        document.getElementById('map-error').classList.remove('d-none');
        console.error("Error initializing map:", error);
    }
});

// Make sure close button works
$(document).ready(function() {
    $('[data-dismiss="modal"]').on('click', function() {
        $('#uni_modal').modal('hide');
    });
});
</script>