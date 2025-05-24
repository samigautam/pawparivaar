<?php
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `animals` where animal_id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<div class="card card-outline card-info">
	<div class="card-header">
		<h3 class="card-title"><?php echo isset($animal_id) ? "Update ": "Add New " ?> Animal</h3>
	</div>
	<div class="card-body">
		<form action="" id="animal-form" enctype="multipart/form-data">
			<input type="hidden" name="animal_id" value="<?php echo isset($animal_id) ? $animal_id : '' ?>">
			
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="name" class="control-label">Name</label>
						<input type="text" class="form-control" required name="name" value="<?php echo isset($name) ? $name : '' ?>">
					</div>
					<div class="form-group">
						<label for="species" class="control-label">Species</label>
						<input type="text" class="form-control" required name="species" value="<?php echo isset($species) ? $species : '' ?>">
					</div>
					<div class="form-group">
						<label for="breed" class="control-label">Breed</label>
						<input type="text" class="form-control" name="breed" value="<?php echo isset($breed) ? $breed : '' ?>">
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="age_years" class="control-label">Age (Years)</label>
								<input type="number" min="0" class="form-control" name="age_years" value="<?php echo isset($age_years) ? $age_years : '0' ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="age_months" class="control-label">Age (Months)</label>
								<input type="number" min="0" max="11" class="form-control" name="age_months" value="<?php echo isset($age_months) ? $age_months : '0' ?>">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="gender" class="control-label">Gender</label>
						<select name="gender" class="form-control" required>
							<option value="" disabled <?php echo !isset($gender) ? 'selected' : '' ?>>Select Gender</option>
							<option value="Male" <?php echo isset($gender) && $gender == 'Male' ? 'selected' : '' ?>>Male</option>
							<option value="Female" <?php echo isset($gender) && $gender == 'Female' ? 'selected' : '' ?>>Female</option>
							<option value="Unknown" <?php echo isset($gender) && $gender == 'Unknown' ? 'selected' : '' ?>>Unknown</option>
						</select>
					</div>
					<div class="form-group">
						<label for="size" class="control-label">Size</label>
						<select name="size" class="form-control" required>
							<option value="" disabled <?php echo !isset($size) ? 'selected' : '' ?>>Select Size</option>
							<option value="Small" <?php echo isset($size) && $size == 'Small' ? 'selected' : '' ?>>Small</option>
							<option value="Medium" <?php echo isset($size) && $size == 'Medium' ? 'selected' : '' ?>>Medium</option>
							<option value="Large" <?php echo isset($size) && $size == 'Large' ? 'selected' : '' ?>>Large</option>
							<option value="Extra Large" <?php echo isset($size) && $size == 'Extra Large' ? 'selected' : '' ?>>Extra Large</option>
						</select>
					</div>
					<div class="form-group">
						<label for="color" class="control-label">Color</label>
						<input type="text" class="form-control" name="color" value="<?php echo isset($color) ? $color : '' ?>">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="intake_date" class="control-label">Intake Date</label>
						<input type="date" class="form-control" name="intake_date" value="<?php echo isset($intake_date) ? date('Y-m-d', strtotime($intake_date)) : date('Y-m-d') ?>">
					</div>
					<div class="form-group">
						<label for="adoption_status" class="control-label">Adoption Status</label>
						<select name="adoption_status" class="form-control" required>
							<option value="" disabled <?php echo !isset($adoption_status) ? 'selected' : '' ?>>Select Status</option>
							<option value="Available" <?php echo isset($adoption_status) && $adoption_status == 'Available' ? 'selected' : '' ?>>Available</option>
							<option value="Pending" <?php echo isset($adoption_status) && $adoption_status == 'Pending' ? 'selected' : '' ?>>Pending</option>
							<option value="Adopted" <?php echo isset($adoption_status) && $adoption_status == 'Adopted' ? 'selected' : '' ?>>Adopted</option>
							<option value="Not Available" <?php echo isset($adoption_status) && $adoption_status == 'Not Available' ? 'selected' : '' ?>>Not Available</option>
						</select>
					</div>
					<div class="form-group">
						<label for="adoption_fee" class="control-label">Adoption Fee</label>
						<input type="number" min="0" step="0.01" class="form-control" name="adoption_fee" value="<?php echo isset($adoption_fee) ? $adoption_fee : '0' ?>">
					</div>
					<div class="form-group">
						<label for="location" class="control-label">Location</label>
						<input type="text" class="form-control" name="location" value="<?php echo isset($location) ? $location : '' ?>">
					</div>
					<div class="form-group">
						<label for="microchip_number" class="control-label">Microchip Number</label>
						<input type="text" class="form-control" name="microchip_number" value="<?php echo isset($microchip_number) ? $microchip_number : '' ?>">
					</div>
					<div class="form-group">
						<label for="" class="control-label">Health Status</label>
						<div class="custom-control custom-checkbox">
							<input type="checkbox" class="custom-control-input" id="spayed_neutered" name="spayed_neutered" value="1" <?php echo isset($spayed_neutered) && $spayed_neutered == 1 ? 'checked' : '' ?>>
							<label class="custom-control-label" for="spayed_neutered">Spayed/Neutered</label>
						</div>
						<div class="custom-control custom-checkbox">
							<input type="checkbox" class="custom-control-input" id="vaccinations_current" name="vaccinations_current" value="1" <?php echo isset($vaccinations_current) && $vaccinations_current == 1 ? 'checked' : '' ?>>
							<label class="custom-control-label" for="vaccinations_current">Vaccinations Current</label>
						</div>
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<label for="description" class="control-label">Description</label>
				<textarea name="description" id="description" cols="30" rows="3" class="form-control summernote"><?php echo isset($description) ? $description : '' ?></textarea>
			</div>
			
			<div class="form-group">
				<label for="medical_history" class="control-label">Medical History</label>
				<textarea name="medical_history" id="medical_history" cols="30" rows="3" class="form-control"><?php echo isset($medical_history) ? $medical_history : '' ?></textarea>
			</div>
			
			<div class="form-group">
				<label for="behavioral_notes" class="control-label">Behavioral Notes</label>
				<textarea name="behavioral_notes" id="behavioral_notes" cols="30" rows="3" class="form-control"><?php echo isset($behavioral_notes) ? $behavioral_notes : '' ?></textarea>
			</div>
			
			<div class="form-group">
				<label for="special_needs" class="control-label">Special Needs</label>
				<textarea name="special_needs" id="special_needs" cols="30" rows="3" class="form-control"><?php echo isset($special_needs) ? $special_needs : '' ?></textarea>
			</div>
			
			<div class="form-group">
				<label for="primary_image" class="control-label">Image</label>
				<div class="custom-file">
					<input type="file" class="custom-file-input" id="customFile" name="primary_image" onchange="displayImg(this,$(this))">
					<label class="custom-file-label" for="customFile">Choose file</label>
				</div>
			</div>
			<div class="form-group d-flex justify-content-center">
				<img src="<?php echo validate_image(isset($primary_image) ? $primary_image : '') ?>" alt="Animal Image" id="animal-img" class="img-fluid img-thumbnail">
			</div>
		</form>
	</div>
	<div class="card-footer">
		<button class="btn btn-flat btn-primary" form="animal-form">Save</button>
		<a class="btn btn-flat btn-default" href="?page=animalsforadoption">Cancel</a>
	</div>
</div>
<script>
    function displayImg(input,_this) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#animal-img').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
		_this.siblings('.custom-file-label').html(input.files[0].name);
	}
	
	$(document).ready(function(){
		$('#animal-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			$('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_animal",
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
						location.href = "./?page=animalsforadoption";
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

        $('.summernote').summernote({
		        height: 200,
		        toolbar: [
		            [ 'style', [ 'style' ] ],
		            [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
		            [ 'fontname', [ 'fontname' ] ],
		            [ 'fontsize', [ 'fontsize' ] ],
		            [ 'color', [ 'color' ] ],
		            [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
		            [ 'table', [ 'table' ] ],
		            [ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
		        ]
		    })
	})
</script>