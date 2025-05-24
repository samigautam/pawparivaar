<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">List of Donations</h3>
		<div class="card-tools">
			<a href="?page=donations/manage_donation" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Create New</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-bordered table-stripped">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="15%">
					<col width="10%">
					<col width="15%">
					<col width="15%">
					<col width="15%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Donation ID</th>
						<th>Client</th>
						<th>Amount</th>
						<th>Purpose</th>
						<th>Payment Status</th>
						<th>Donation Date</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
						// Modified query to use firstname and lastname from clients table
						$check_table = $conn->query("SHOW TABLES LIKE 'clients'");
						if($check_table->num_rows > 0) {
							// Check if firstname and lastname columns exist
							$check_firstname = $conn->query("SHOW COLUMNS FROM `clients` LIKE 'firstname'");
							$check_lastname = $conn->query("SHOW COLUMNS FROM `clients` LIKE 'lastname'");
							
							if($check_firstname->num_rows > 0 && $check_lastname->num_rows > 0) {
								// Both firstname and lastname columns exist, concatenate them
								$qry = $conn->query("SELECT d.*, CONCAT(c.firstname, ' ', c.lastname) as client_name 
													FROM `donations` d 
													LEFT JOIN `clients` c ON c.id = d.client_id 
													ORDER BY d.donation_date DESC");
							} else {
								// Missing firstname or lastname columns, query without them
								$qry = $conn->query("SELECT d.* FROM `donations` d ORDER BY d.donation_date DESC");
							}
						} else {
							// Clients table doesn't exist, query without join
							$qry = $conn->query("SELECT * FROM `donations` ORDER BY donation_date DESC");
						}
						
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo $row['donation_id'] ?></td>
							<td>
                                <?php 
                                if(isset($row['is_anonymous']) && $row['is_anonymous'] == 1):
                                    echo "<i>Anonymous</i>";
                                else:
                                    echo isset($row['client_name']) && $row['client_name'] ? $row['client_name'] : "<i>N/A</i>";
                                endif;
                                ?>
                            </td>
							<td class="text-right"><?php echo number_format($row['amount'], 2) ?></td>
							<td><?php echo isset($row['purpose']) && $row['purpose'] ? $row['purpose'] : (isset($row['donation_type']) && $row['donation_type'] ? $row['donation_type'] : "General") ?></td>
							<td>
                                <?php 
                                if($row['payment_status'] == 'completed'):
                                    echo "<span class='badge badge-success'>Completed</span>";
                                elseif($row['payment_status'] == 'pending'):
                                    echo "<span class='badge badge-warning'>Pending</span>";
                                else:
                                    echo "<span class='badge badge-danger'>Failed</span>";
                                endif;
                                ?>
                            </td>
							<td><?php echo date("M d, Y", strtotime($row['donation_date'])) ?></td>
							<td align="center">
								<button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                    Action
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                    <a class="dropdown-item" href="?page=donations/manage_donation&id=<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item view_details" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
                                </div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this donation permanently?","delete_donation",[$(this).attr('data-id')])
		})
        
        $('.view_details').click(function(){
			uni_modal("Donation Details","donations/view_donation.php?id="+$(this).attr('data-id'),"mid-large")
		})
        
		$('.table').dataTable();
	})
    
	function delete_donation($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_donation",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occurred.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occurred.",'error');
					end_loader();
				}
			}
		})
	}
</script>