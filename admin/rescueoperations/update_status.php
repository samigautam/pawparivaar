<?php
require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * FROM `rescue_operations` WHERE id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    } else {
        echo "<div class='alert alert-danger'>Rescue operation not found</div>";
        exit;
    }
}
?>

<div class="container-fluid">
    <form action="" id="status-form">
        <input type="hidden" name="rescue_id" value="<?php echo isset($id) ? $id : '' ?>">
        <input type="hidden" name="previous_status" value="<?php echo isset($status) ? $status : 'Pending' ?>">
        
        <!-- Display error message placeholder -->
        <div id="msg"></div>
        
        <div class="form-group">
            <label for="new_status" class="control-label">Update Status</label>
            <select name="new_status" id="new_status" class="form-control" required>
                <option value="Pending" <?php echo isset($status) && $status == 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Under Review" <?php echo isset($status) && $status == 'Under Review' ? 'selected' : '' ?>>Under Review</option>
                <option value="Team Dispatched" <?php echo isset($status) && $status == 'Team Dispatched' ? 'selected' : '' ?>>Team Dispatched</option>
                <option value="In Progress" <?php echo isset($status) && $status == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                <option value="On Hold" <?php echo isset($status) && $status == 'On Hold' ? 'selected' : '' ?>>On Hold</option>
                <option value="Completed" <?php echo isset($status) && $status == 'Completed' ? 'selected' : '' ?>>Completed</option>
                <option value="Cancelled" <?php echo isset($status) && $status == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>
        
        <!-- Add notes field -->
        <div class="form-group">
            <label for="notes" class="control-label">Notes</label>
            <textarea name="notes" id="notes" rows="4" class="form-control" placeholder="Add notes about this status change"><?php echo isset($additional_details) ? $additional_details : '' ?></textarea>
            <small class="text-muted">These notes will be added to additional details and logged in the status change history.</small>
        </div>
        
        <!-- Rescue team section -->
        <div class="form-group" id="rescue_team_container" style="display: <?php echo (isset($status) && $status == 'In Progress') ? 'block' : 'none'; ?>">
            <div class="card card-outline card-info">
                <div class="card-header">Rescue Team Information</div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="rescue_team">Team Members</label>
                        <textarea class="form-control" id="rescue_team" name="rescue_team" rows="3" placeholder="Enter team member names and roles"><?php echo isset($rescue_team) ? $rescue_team : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="estimated_completion">Estimated Completion Date</label>
                        <input type="date" class="form-control" id="estimated_completion" name="estimated_completion" value="<?php echo isset($estimated_completion) ? date('Y-m-d', strtotime($estimated_completion)) : ''; ?>">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Rescue outcome section (when status is Completed) -->
        <div class="form-group" id="rescue_outcome_container" style="display: <?php echo (isset($status) && $status == 'Completed') ? 'block' : 'none'; ?>">
            <div class="card card-outline card-success">
                <div class="card-header">Rescue Outcome</div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="animals_rescued">Number of Animals Rescued</label>
                        <input type="number" class="form-control" id="animals_rescued" name="animals_rescued" value="<?php echo isset($animals_rescued) ? $animals_rescued : ''; ?>" min="0">
                    </div>
                    <div class="form-group">
                        <label for="rescue_outcome">Outcome Details</label>
                        <textarea class="form-control" id="rescue_outcome" name="rescue_outcome" rows="3"><?php echo isset($rescue_outcome) ? $rescue_outcome : ''; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Cancellation reason (when status is Cancelled) -->
        <div class="form-group" id="cancel_reason_container" style="display: <?php echo (isset($status) && $status == 'Cancelled') ? 'block' : 'none'; ?>">
            <div class="card card-outline card-danger">
                <div class="card-header">Cancellation Reason</div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="cancel_reason">Reason for Cancellation</label>
                        <textarea class="form-control" id="cancel_reason" name="cancel_reason" rows="3"><?php echo isset($cancel_reason) ? $cancel_reason : ''; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function(){
        // Initialize the appropriate containers based on the initial status
        var initialStatus = $('#new_status').val();
        toggleContainers(initialStatus);
        
        // Show/hide sections based on status selection
        $('#new_status').change(function(){
            var status = $(this).val();
            toggleContainers(status);
        });
        
        function toggleContainers(status) {
            // Hide all containers first
            $('#rescue_team_container, #rescue_outcome_container, #cancel_reason_container').hide();
            
            // Show the appropriate container based on status
            if(status == 'Team Dispatched' || status == 'In Progress') {
                $('#rescue_team_container').show();
            } 
            else if(status == 'Completed') {
                $('#rescue_outcome_container').show();
            }
            else if(status == 'Cancelled' || status == 'On Hold') {
                $('#cancel_reason_container').show();
            }
        }
        
        $('#status-form').submit(function(e){
            e.preventDefault();
            var _this = $(this);
            $('.err-msg').remove();
            
            // Debug form data
            var formData = new FormData($(this)[0]);
            console.log("Submitting form with:");
            for(var pair of formData.entries()) {
                console.log(pair[0]+ ': ' + pair[1]); 
            }
            
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Master.php?f=update_rescue_status",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error:function(xhr, status, error){
                    console.log("AJAX Error:");
                    console.log(xhr.responseText);
                    alert_toast("An error occurred. Check console for details.",'error');
                    end_loader();
                },
                success:function(resp){
                    if(typeof resp =='object' && resp.status == 'success'){
                        location.href = './?page=rescueoperations';
                    }else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                        el.addClass("alert alert-danger err-msg").text(resp.msg)
                        _this.prepend(el)
                        el.show('slow')
                        $("html, body").animate({ scrollTop: _this.offset().top }, "fast");
                        end_loader()
                    }else{
                        alert_toast("An error occurred",'error');
                        end_loader();
                        console.log(resp)
                    }
                }
            });
        });
    });
</script>