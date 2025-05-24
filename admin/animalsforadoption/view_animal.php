<?php
require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `animals` where animal_id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<style>
    #uni_modal .modal-footer{
        display:none
    }
    .animal-img{
        width:100%;
        height:10em;
        object-fit:cover;
        object-position:center;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <img src="<?php echo validate_image(isset($primary_image) ? $primary_image : '') ?>" alt="Animal Image" class="img-thumbnail animal-img">
        </div>
        <div class="col-md-6">
            <dl>
                <dt class="text-muted">Name</dt>
                <dd class="pl-4"><?php echo isset($name) ? $name : "" ?></dd>
                <dt class="text-muted">Species</dt>
                <dd class="pl-4"><?php echo isset($species) ? $species : "" ?></dd>
                <dt class="text-muted">Breed</dt>
                <dd class="pl-4"><?php echo isset($breed) ? $breed : "N/A" ?></dd>
                <dt class="text-muted">Age</dt>
                <dd class="pl-4">
                    <?php 
                        $age = '';
                        if(isset($age_years) && $age_years > 0) {
                            $age .= $age_years . ' year' . ($age_years > 1 ? 's' : '');
                        }
                        if(isset($age_months) && $age_months > 0) {
                            $age .= ($age != '' ? ' ' : '') . $age_months . ' month' . ($age_months > 1 ? 's' : '');
                        }
                        echo ($age != '') ? $age : 'Unknown';
                    ?>
                </dd>
                <dt class="text-muted">Gender</dt>
                <dd class="pl-4"><?php echo isset($gender) ? $gender : "Unknown" ?></dd>
                <dt class="text-muted">Size</dt>
                <dd class="pl-4"><?php echo isset($size) ? $size : "N/A" ?></dd>
                <dt class="text-muted">Color</dt>
                <dd class="pl-4"><?php echo isset($color) ? $color : "N/A" ?></dd>
            </dl>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <dt class="text-muted">Intake Date</dt>
                    <dd class="pl-4"><?php echo isset($intake_date) ? date("F d, Y", strtotime($intake_date)) : "N/A" ?></dd>
                </div>
                <div class="col-md-6">
                    <dt class="text-muted">Adoption Status</dt>
                    <dd class="pl-4"><?php echo isset($adoption_status) ? $adoption_status : "N/A" ?></dd>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <dt class="text-muted">Adoption Fee</dt>
                    <dd class="pl-4"><?php echo isset($adoption_fee) && $adoption_fee > 0 ? number_format($adoption_fee, 2) : "Free" ?></dd>
                </div>
                <div class="col-md-6">
                    <dt class="text-muted">Location</dt>
                    <dd class="pl-4"><?php echo isset($location) ? $location : "N/A" ?></dd>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <dt class="text-muted">Microchip Number</dt>
                    <dd class="pl-4"><?php echo isset($microchip_number) && !empty($microchip_number) ? $microchip_number : "N/A" ?></dd>
                </div>
                <div class="col-md-6">
                    <dt class="text-muted">Health Status</dt>
                    <dd class="pl-4">
                        <?php if(isset($spayed_neutered) && $spayed_neutered == 1): ?>
                            <span class="badge badge-success">Spayed/Neutered</span>
                        <?php endif; ?>
                        <?php if(isset($vaccinations_current) && $vaccinations_current == 1): ?>
                            <span class="badge badge-success">Vaccinations Current</span>
                        <?php endif; ?>
                        <?php if((!isset($spayed_neutered) || $spayed_neutered != 1) && (!isset($vaccinations_current) || $vaccinations_current != 1)): ?>
                            <span class="badge badge-secondary">No records</span>
                        <?php endif; ?>
                    </dd>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <dt class="text-muted">Description</dt>
                    <dd class="pl-4"><?php echo isset($description) ? $description : "No description available" ?></dd>
                </div>
            </div>
            
            <?php if(isset($medical_history) && !empty($medical_history)): ?>
            <div class="row">
                <div class="col-md-12">
                    <dt class="text-muted">Medical History</dt>
                    <dd class="pl-4"><?php echo $medical_history ?></dd>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if(isset($behavioral_notes) && !empty($behavioral_notes)): ?>
            <div class="row">
                <div class="col-md-12">
                    <dt class="text-muted">Behavioral Notes</dt>
                    <dd class="pl-4"><?php echo $behavioral_notes ?></dd>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if(isset($special_needs) && !empty($special_needs)): ?>
            <div class="row">
                <div class="col-md-12">
                    <dt class="text-muted">Special Needs</dt>
                    <dd class="pl-4"><?php echo $special_needs ?></dd>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>