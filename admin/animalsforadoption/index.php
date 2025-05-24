<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">List of Animals for Adoption</h3>
		<div class="card-tools">
			<a href="?page=animalsforadoption/manage_animals" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> Add New Animal</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-bordered table-stripped">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="15%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Image</th>
						<th>Name</th>
						<th>Species</th>
						<th>Breed</th>
						<th>Age</th>
						<th>Gender</th>
						<th>Adoption Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
						$qry = $conn->query("SELECT * FROM `animals` ORDER BY intake_date DESC");
						while($row = $qry->fetch_assoc()):
							$age = '';
							if($row['age_years'] > 0) {
								$age .= $row['age_years'] . ' year' . ($row['age_years'] > 1 ? 's' : '');
							}
							if($row['age_months'] > 0) {
								$age .= ($age != '' ? ' ' : '') . $row['age_months'] . ' month' . ($row['age_months'] > 1 ? 's' : '');
							}
							if($age == '') $age = 'Unknown';
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td class="text-center">
								<img src="<?php echo validate_image($row['primary_image']) ?>" alt="Animal Image" class="img-thumbnail" style="height: 75px; width: 75px; object-fit: cover;">
							</td>
							<td><?php echo $row['name'] ?></td>
							<td><?php echo $row['species'] ?></td>
							<td><?php echo $row['breed'] ?></td>
							<td><?php echo $age ?></td>
							<td><?php echo $row['gender'] ?></td>
							<td><?php echo $row['adoption_status'] ?></td>
							<td align="center">
								 <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
				                  		Action
				                    <span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
									<a class="dropdown-item view_animal" href="javascript:void(0)" data-id="<?php echo $row['animal_id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
				                    <a class="dropdown-item" href="?page=animalsforadoption/manage_animals&id=<?php echo $row['animal_id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item delete_animal" href="javascript:void(0)" data-id="<?php echo $row['animal_id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
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
		$('.view_animal').click(function(){
			uni_modal("Animal Details","animalsforadoption/view_animal.php?id="+$(this).attr('data-id'))
		})
		$('.delete_animal').click(function(){
			_conf("Are you sure to delete this animal record permanently?","delete_animal",[$(this).attr('data-id')])
		})
		$('.table').dataTable();
	})
	
	function delete_animal($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_animal",
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