<?php
require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT a.*, an.name as animal_name, an.species, an.breed 
                        FROM `adoption_applications` a 
                        LEFT JOIN `animals` an ON a.animal_id = an.animal_id
                        WHERE a.application_id = '{$_GET['id']}' ");
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
    .application-info dt {
        font-weight: bold;
        color: #555;
    }
    .application-info dd {
        margin-bottom: 1rem;
    }
    .application-section {
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eee;
    }
    .status-history {
        max-height: 200px;
        overflow-y: auto;
    }
</style>
<div class="container-fluid application-info">
    <div class="row application-section">
        <div class="col-md-12">
            <h5 class="text-primary">Application Status</h5>
            <div class="row">
                <div class="col-md-6">
                    <dl>
                        <dt>Current Status</dt>
                        <dd>
                            <?php 
                                $status = isset($status) ? $status : 'Pending';
                                $badge_class = 'secondary';
                                if($status == 'Pending') $badge_class = 'warning';
                                if($status == 'Under Review') $badge_class = 'info';
                                if($status == 'Approved') $badge_class = 'success';
                                if($status == 'Rejected') $badge_class = 'danger';
                                if($status == 'Completed') $badge_class = 'primary';
                            ?>
                            <span class="badge badge-<?php echo $badge_class ?>"><?php echo $status ?></span>
                        </dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl>
                        <dt>Submission Date</dt>
                        <dd><?php echo isset($submission_date) ? date("F d, Y h:i A", strtotime($submission_date)) : "N/A" ?></dd>
                        <dt>Last Updated</dt>
                        <dd><?php echo isset($last_updated) ? date("F d, Y h:i A", strtotime($last_updated)) : "N/A" ?></dd>
                    </dl>
                </div>
            </div>
            <?php if(isset($staff_notes) && !empty($staff_notes)): ?>
            <dl>
                <dt>Staff Notes</dt>
                <dd><?php echo $staff_notes ?></dd>
            </dl>
            <?php endif; ?>
            
            <!-- Status History -->
            <h6>Status History</h6>
            <div class="status-history">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Previous Status</th>
                            <th>New Status</th>
                            <th>Notes</th>
                            <th>Changed By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $history = $conn->query("SELECT * FROM application_status_log WHERE application_id = '{$_GET['id']}' ORDER BY changed_at DESC");
                        if($history->num_rows > 0):
                            while($row = $history->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?php echo date("M d, Y h:i A", strtotime($row['changed_at'])) ?></td>
                            <td><?php echo $row['previous_status'] ?></td>
                            <td><?php echo $row['new_status'] ?></td>
                            <td><?php echo $row['notes'] ?></td>
                            <td><?php echo $row['changed_by'] ?></td>
                        </tr>
                        <?php 
                            endwhile;
                        else:
                        ?>
                        <tr>
                            <td colspan="5" class="text-center">No status history found</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="row application-section">
        <div class="col-md-12">
            <h5 class="text-primary">Animal Information</h5>
            <div class="row">
                <div class="col-md-6">
                    <dl>
                        <dt>Animal Name</dt>
                        <dd><?php echo isset($animal_name) ? $animal_name : "N/A" ?></dd>
                        <dt>Species</dt>
                        <dd><?php echo isset($species) ? $species : "N/A" ?></dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl>
                        <dt>Breed</dt>
                        <dd><?php echo isset($breed) ? $breed : "N/A" ?></dd>
                        <dt>Animal ID</dt>
                        <dd><?php echo isset($animal_id) ? $animal_id : "N/A" ?></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row application-section">
        <div class="col-md-12">
            <h5 class="text-primary">Applicant Information</h5>
            <div class="row">
                <div class="col-md-6">
                    <dl>
                        <dt>Name</dt>
                        <dd><?php echo isset($first_name) && isset($last_name) ? $first_name . ' ' . $last_name : "N/A" ?></dd>
                        <dt>Email</dt>
                        <dd><?php echo isset($email) ? $email : "N/A" ?></dd>
                        <dt>Phone</dt>
                        <dd><?php echo isset($phone) ? $phone : "N/A" ?></dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl>
                        <dt>Address</dt>
                        <dd>
                            <?php 
                                $address_parts = array();
                                if(isset($address) && !empty($address)) $address_parts[] = $address;
                                if(isset($city) && !empty($city)) $address_parts[] = $city;
                                if(isset($state) && !empty($state)) $address_parts[] = $state;
                                if(isset($zip) && !empty($zip)) $address_parts[] = $zip;
                                echo !empty($address_parts) ? implode(', ', $address_parts) : "N/A";
                            ?>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row application-section">
        <div class="col-md-12">
            <h5 class="text-primary">Housing Information</h5>
            <div class="row">
                <div class="col-md-6">
                    <dl>
                        <dt>Housing Type</dt>
                        <dd><?php echo isset($housing_type) ? $housing_type : "N/A" ?></dd>
                        <dt>Has Yard</dt>
                        <dd><?php echo isset($has_yard) ? ($has_yard == 1 ? 'Yes' : 'No') : "N/A" ?></dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl>
                        <dt>Yard Description</dt>
                        <dd><?php echo isset($yard_description) && !empty($yard_description) ? $yard_description : "N/A" ?></dd>
                        <dt>Landlord Information</dt>
                        <dd><?php echo isset($landlord_info) && !empty($landlord_info) ? $landlord_info : "N/A" ?></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row application-section">
        <div class="col-md-12">
            <h5 class="text-primary">Household Information</h5>
            <div class="row">
                <div class="col-md-12">
                    <dl>
                        <dt>Household Members</dt>
                        <dd><?php echo isset($household_members) && !empty($household_members) ? $household_members : "N/A" ?></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row application-section">
        <div class="col-md-12">
            <h5 class="text-primary">Pet Experience</h5>
            <div class="row">
                <div class="col-md-6">
                    <dl>
                        <dt>Current Pets</dt>
                        <dd><?php echo isset($current_pets) && !empty($current_pets) ? $current_pets : "None" ?></dd>
                        <dt>Previous Pets</dt>
                        <dd><?php echo isset($previous_pets) && !empty($previous_pets) ? $previous_pets : "None" ?></dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl>
                        <dt>Veterinarian Information</dt>
                        <dd><?php echo isset($vet_info) && !empty($vet_info) ? $vet_info : "N/A" ?></dd>
                        <dt>Hours Pet Will Be Alone</dt>
                        <dd><?php echo isset($hours_pet_alone) && !empty($hours_pet_alone) ? $hours_pet_alone : "N/A" ?></dd>
                    </dl>
                </div>
            </div>
            <dl>
                <dt>Experience Notes</dt>
                <dd><?php echo isset($experience_notes) && !empty($experience_notes) ? $experience_notes : "None provided" ?></dd>
            </dl>
        </div>
    </div>
    
    <div class="row application-section">
        <div class="col-md-12">
            <h5 class="text-primary">References</h5>
            <div class="row">
                <div class="col-md-6">
                    <dl>
                        <dt>Reference 1</dt>
                        <dd>
                            <?php if(isset($reference_1_name) && !empty($reference_1_name)): ?>
                                Name: <?php echo $reference_1_name ?><br>
                                Phone: <?php echo isset($reference_1_phone) ? $reference_1_phone : "N/A" ?><br>
                                Relation: <?php echo isset($reference_1_relation) ? $reference_1_relation : "N/A" ?>
                            <?php else: ?>
                                Not provided
                            <?php endif; ?>
                        </dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl>
                        <dt>Reference 2</dt>
                        <dd>
                            <?php if(isset($reference_2_name) && !empty($reference_2_name)): ?>
                                Name: <?php echo $reference_2_name ?><br>
                                Phone: <?php echo isset($reference_2_phone) ? $reference_2_phone : "N/A" ?><br>
                                Relation: <?php echo isset($reference_2_relation) ? $reference_2_relation : "N/A" ?>
                            <?php else: ?>
                                Not provided
                            <?php endif; ?>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    
    <?php if(isset($home_visit_date) || isset($home_visit_notes) || isset($home_visit_passed)): ?>
    <div class="row application-section">
        <div class="col-md-12">
            <h5 class="text-primary">Home Visit</h5>
            <div class="row">
                <div class="col-md-4">
                    <dl>
                        <dt>Visit Date</dt>
                        <dd><?php echo isset($home_visit_date) && !empty($home_visit_date) ? date("F d, Y", strtotime($home_visit_date)) : "Not scheduled" ?></dd>
                    </dl>
                </div>
                <div class="col-md-4">
                    <dl>
                        <dt>Visit Passed</dt>
                        <dd><?php echo isset($home_visit_passed) ? ($home_visit_passed == 1 ? 'Yes' : 'No') : "Not determined" ?></dd>
                    </dl>
                </div>
            </div>
            <?php if(isset($home_visit_notes) && !empty($home_visit_notes)): ?>
            <dl>
                <dt>Visit Notes</dt>
                <dd><?php echo $home_visit_notes ?></dd>
            </dl>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if(isset($custom_questions_answers) && !empty($custom_questions_answers)): ?>
    <div class="row application-section">
        <div class="col-md-12">
            <h5 class="text-primary">Additional Questions</h5>
            <dl>
                <dd><?php echo $custom_questions_answers ?></dd>
            </dl>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-12">
            <dl>
                <dt>Terms Accepted</dt>
                <dd><?php echo isset($terms_accepted) && $terms_accepted == 1 ? 'Yes' : 'No' ?></dd>
            </dl>
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="mx-auto">
        <button type="button" class="btn btn-primary update_status" data-id="<?php echo $_GET['id'] ?>" data-status="<?php echo $status ?>">
            <i class="fa fa-edit"></i> Update Status
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
</div>

<script>
    $(function() {
        $('.update_status').click(function(){
            uni_modal("Update Application Status","adoptionapplications/update_status.php?id="+$(this).attr('data-id')+"&status="+$(this).attr('data-status'))
        });
    });
</script>