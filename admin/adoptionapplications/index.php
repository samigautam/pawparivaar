<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">List of Adoption Applications</h3>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-bordered table-stripped">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="15%">
					<col width="15%">
					<col width="15%">
					<col width="15%">
					<col width="10%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Applicant Name</th>
						<th>Animal</th>
						<th>Contact</th>
						<th>Submission Date</th>
						<th>Status</th>
						<th>Last Updated</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
						$qry = $conn->query("SELECT a.*, an.name as animal_name FROM `adoption_applications` a
                                            LEFT JOIN `animals` an ON a.animal_id = an.animal_id 
                                            ORDER BY a.submission_date DESC");
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo $row['first_name'] . ' ' . $row['last_name'] ?></td>
							<td><?php echo $row['animal_name'] ?></td>
							<td>
                                <small>
                                    <i class="fa fa-envelope"></i> <?php echo $row['email'] ?><br>
                                    <i class="fa fa-phone"></i> <?php echo $row['phone'] ?>
                                </small>
                            </td>
							<td><?php echo date("M d, Y h:i A", strtotime($row['submission_date'])) ?></td>
							<td>
                                <?php 
                                    $status = $row['status'];
                                    $badge_class = 'secondary';
                                    if($status == 'Pending') $badge_class = 'warning';
                                    if($status == 'Under Review') $badge_class = 'info';
                                    if($status == 'Approved') $badge_class = 'success';
                                    if($status == 'Rejected') $badge_class = 'danger';
                                    if($status == 'Completed') $badge_class = 'primary';
                                ?>
                                <span class="badge badge-<?php echo $badge_class ?>"><?php echo $status ?></span>
                            </td>
							<td><?php echo date("M d, Y", strtotime($row['last_updated'])) ?></td>
							<td align="center">
								 <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
				                  		Action
				                    <span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
									<a class="dropdown-item view_application" href="javascript:void(0)" data-id="<?php echo $row['application_id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
									<a class="dropdown-item update_status" href="javascript:void(0)" data-id="<?php echo $row['application_id'] ?>" data-status="<?php echo $row['status'] ?>"><span class="fa fa-tasks text-info"></span> Update Status</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item delete_application" href="javascript:void(0)" data-id="<?php echo $row['application_id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
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
		$('.view_application').click(function(){
			uni_modal("Application Details","adoptionapplications/view_application.php?id="+$(this).attr('data-id'), "large")
		})
		
		$('.update_status').click(function(){
			uni_modal("Update Application Status","adoptionapplications/update_status.php?id="+$(this).attr('data-id')+"&status="+$(this).attr('data-status'))
		})
		
		$('.delete_application').click(function(){
			_conf("Are you sure to delete this application permanently?","delete_application",[$(this).attr('data-id')])
		})
		
		$('.table').dataTable();
	})
	
	function delete_application($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_application",
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