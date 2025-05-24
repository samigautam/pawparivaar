<?php
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `donations` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<div class="card card-outline card-info">
	<div class="card-header">
		<h3 class="card-title"><?php echo isset($id) ? "Update ": "Create New " ?> Donation</h3>
	</div>
	<div class="card-body">
		<form action="" id="donation-form">
			<input type="hidden" name ="id" value="<?php echo isset($id) ? $id : '' ?>">
            
            <div class="form-group">
				<label for="donation_id" class="control-label">Donation ID</label>
                <input type="text" class="form-control form" required name="donation_id" value="<?php echo isset($donation_id) ? $donation_id : '' ?>">
            </div>
            
            <div class="form-group">
				<label for="client_id" class="control-label">Client</label>
                <select name="client_id" id="client_id" class="custom-select select2">
                    <option value="">Anonymous</option>
                    <?php
                        $qry = $conn->query("SELECT * FROM `clients` order by name asc");
                        while($row= $qry->fetch_assoc()):
                    ?>
                    <option value="<?php echo $row['id'] ?>" <?php echo isset($client_id) && $client_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['name'] ?></option>
                    <?php endwhile; ?>
                </select>
			</div>
            
            <div class="form-group">
                <label for="is_anonymous" class="control-label">Anonymous Donation</label>
                <select name="is_anonymous" id="is_anonymous" class="custom-select">
                    <option value="0" <?php echo isset($is_anonymous) && $is_anonymous == 0 ? 'selected' : '' ?>>No</option>
                    <option value="1" <?php echo isset($is_anonymous) && $is_anonymous == 1 ? 'selected' : '' ?>>Yes</option>
                </select>
            </div>
            
            <div class="form-group">
				<label for="amount" class="control-label">Amount</label>
                <input type="number" step="any" class="form-control form" required name="amount" value="<?php echo isset($amount) ? $amount : '' ?>">
            </div>
            
            <div class="form-group">
				<label for="donation_type" class="control-label">Donation Type</label>
                <select name="donation_type" id="donation_type" class="custom-select">
                    <option value="General" <?php echo isset($donation_type) && $donation_type == 'General' ? 'selected' : '' ?>>General</option>
                    <option value="Monthly" <?php echo isset($donation_type) && $donation_type == 'Monthly' ? 'selected' : '' ?>>Monthly</option>
                    <option value="Annual" <?php echo isset($donation_type) && $donation_type == 'Annual' ? 'selected' : '' ?>>Annual</option>
                    <option value="One-time" <?php echo isset($donation_type) && $donation_type == 'One-time' ? 'selected' : '' ?>>One-time</option>
                    <option value="Campaign" <?php echo isset($donation_type) && $donation_type == 'Campaign' ? 'selected' : '' ?>>Campaign</option>
                </select>
            </div>
            
            <div class="form-group">
				<label for="purpose" class="control-label">Specific Purpose</label>
                <input type="text" class="form-control form" name="purpose" value="<?php echo isset($purpose) ? $purpose : '' ?>">
            </div>
            
            <div class="form-group">
				<label for="message" class="control-label">Message</label>
                <textarea class="form-control" name="message" id="message" rows="4"><?php echo isset($message) ? $message : '' ?></textarea>
            </div>
            
            <div class="form-group">
				<label for="payment_status" class="control-label">Payment Status</label>
                <select name="payment_status" id="payment_status" class="custom-select" required>
                    <option value="completed" <?php echo isset($payment_status) && $payment_status == 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="pending" <?php echo isset($payment_status) && $payment_status == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="failed" <?php echo isset($payment_status) && $payment_status == 'failed' ? 'selected' : '' ?>>Failed</option>
                </select>
            </div>
            
            <div class="form-group">
				<label for="PayPal_payment_ID" class="control-label">PayPal Payment ID</label>
                <input type="text" class="form-control form" name="PayPal_payment_ID" value="<?php echo isset($PayPal_payment_ID) ? $PayPal_payment_ID : '' ?>">
            </div>
            
            <div class="form-group">
				<label for="donation_date" class="control-label">Donation Date</label>
                <input type="datetime-local" class="form-control form" required name="donation_date" value="<?php echo isset($donation_date) ? date('Y-m-d\TH:i', strtotime($donation_date)) : '' ?>">
            </div>
            
            <div class="form-group">
                <label for="receipt_sent" class="control-label">Receipt Sent</label>
                <select name="receipt_sent" id="receipt_sent" class="custom-select">
                    <option value="0" <?php echo isset($receipt_sent) && $receipt_sent == 0 ? 'selected' : '' ?>>No</option>
                    <option value="1" <?php echo isset($receipt_sent) && $receipt_sent == 1 ? 'selected' : '' ?>>Yes</option>
                </select>
            </div>
		</form>
	</div>
	<div class="card-footer">
		<button class="btn btn-flat btn-primary" form="donation-form">Save</button>
		<a class="btn btn-flat btn-default" href="?page=donations">Cancel</a>
	</div>
</div>
<script>
	$(document).ready(function(){
        $('.select2').select2({placeholder:"Please Select here",width:"relative"})
		$('#donation-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_donation",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occurred",'error');
					end_loader();
				},
				success:function(resp){
					if(typeof resp =='object' && resp.status == 'success'){
						location.href = "./?page=donations";
					}else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                            $("html, body").animate({ scrollTop: _this.closest('.card').offset().top }, "fast");
                            end_loader()
                    }else{
						alert_toast("An error occurred",'error');
						end_loader();
                        console.log(resp)
					}
				}
			})
		})
        
        // Toggle client dropdown visibility based on anonymous donation
        $('#is_anonymous').change(function(){
            if($(this).val() == '1'){
                $('#client_id').closest('.form-group').hide();
            } else {
                $('#client_id').closest('.form-group').show();
            }
        }).trigger('change');
	})
</script>