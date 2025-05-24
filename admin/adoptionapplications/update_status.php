<?php
require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT a.*, an.name as animal_name 
                        FROM `adoption_applications` a 
                        LEFT JOIN `animals` an ON a.animal_id = an.animal_id
                        WHERE a.application_id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
} else {
        echo "<div class='alert alert-danger'>Application not found</div>";
        exit;
    }
}
?>

<div class="container-fluid">
    <form action="" id="status-form">
        <input type="hidden" name="application_id" value="<?php echo isset($application_id) ? $application_id : '' ?>">
        <input type="hidden" name="animal_id" value="<?php echo isset($animal_id) ? $animal_id : '' ?>">
        <input type="hidden" name="previous_status" value="<?php echo isset($status) ? $status : 'Pending' ?>">
        
        <div class="form-group">
            <label for="new_status" class="control-label">Update Status</label>
            <select name="new_status" class="form-control" required>
                <option value="Pending" <?php echo isset($status) && $status == 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Under Review" <?php echo isset($status) && $status == 'Under Review' ? 'selected' : '' ?>>Under Review</option>
                <option value="Approved" <?php echo isset($status) && $status == 'Approved' ? 'selected' : '' ?>>Approved</option>
                <option value="Rejected" <?php echo isset($status) && $status == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                <option value="Completed" <?php echo isset($status) && $status == 'Completed' ? 'selected' : '' ?>>Completed</option>
            </select>
        </div>
        
<!-- Add staff notes field -->
        <div class="form-group">
            <label for="notes" class="control-label">Staff Notes</label>
            <textarea name="notes" id="notes" rows="4" class="form-control" placeholder="Add notes about this status change"><?php echo isset($staff_notes) ? $staff_notes : '' ?></textarea>
        </div>
        
<!-- Home visit scheduling section -->
        <div class="form-group" id="home_visit_container" style="display: <?php echo (isset($status) && $status == 'Under Review') ? 'block' : 'none'; ?>">
            <div class="card card-outline card-info">
                <div class="card-header">Home Visit Information</div>
                <div class="card-body">
                    <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="schedule_home_visit" name="schedule_home_visit" value="1" <?php echo isset($home_visit_date) && !empty($home_visit_date) ? 'checked' : ''; ?>>
                <label class="custom-control-label" for="schedule_home_visit">Schedule Home Visit</label>
            </div>
            <div id="home_visit_details" style="display: <?php echo isset($home_visit_date) && !empty($home_visit_date) ? 'block' : 'none'; ?>; padding-left: 20px; margin-top: 10px;">
                <div class="form-group">
                    <label for="home_visit_date">Visit Date</label>
                    <input type="date" class="form-control" id="home_visit_date" name="home_visit_date" value="<?php echo isset($home_visit_date) ? date('Y-m-d', strtotime($home_visit_date)) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="home_visit_notes">Visit Notes</label>
                    <textarea class="form-control" id="home_visit_notes" name="home_visit_notes" rows="2"><?php echo isset($home_visit_notes) ? $home_visit_notes : ''; ?></textarea>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="home_visit_passed" name="home_visit_passed" value="1" <?php echo isset($home_visit_passed) && $home_visit_passed == 1 ? 'checked' : ''; ?>>
                        <label class="custom-control-label" for="home_visit_passed">Home Visit Passed</label>
                    </div>
                </div>
            </div>
        </div>
</div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function(){
        $('#status-form').submit(function(e){
            e.preventDefault();
            var _this = $(this);
            $('.err-msg').remove();
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Master.php?f=update_application_status",
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error:err=>{
                    console.log(err);
                    alert_toast("An error occurred",'error');
                    end_loader();
                },
                success:function(resp){
                    if(typeof resp =='object' && resp.status == 'success'){
                        location.reload();
                    }else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                            end_loader()
                    }else{
                        alert_toast("An error occurred",'error');
                        end_loader();
                        console.log(resp)
                    }
                }
            })
        });
    });
</script>