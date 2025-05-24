<?php
require_once(__DIR__ . '/../config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	// Save Animal Function
function save_animal(){
    extract($_POST);
    $data = "";
    foreach($_POST as $k => $v){
        if(!in_array($k, array('animal_id'))){
            if(!empty($data)) $data .= ",";
            if($k == 'spayed_neutered' || $k == 'vaccinations_current'){
                // For checkboxes, set to 0 if not checked
                $v = isset($v) ? 1 : 0;
            }
            $v = addslashes(trim($v));
            $data .= " `{$k}`='{$v}' ";
        }
    }
    
    // Handle file upload
    if(!empty($_FILES['primary_image']['tmp_name'])){
        $upload_path = '../uploads/animals/';
        if(!is_dir(base_app.$upload_path)){
            mkdir(base_app.$upload_path, 0777, true);
        }
        $fname = $upload_path.strtotime(date('y-m-d H:i')).'_'.$_FILES['primary_image']['name'];
        $move = move_uploaded_file($_FILES['primary_image']['tmp_name'],base_app.$fname);
        if($move){
            $data .= ", `primary_image` = '{$fname}' ";
        }
    }
    
    // Add timestamps
    $date_now = date("Y-m-d H:i:s");
    if(empty($animal_id)){
        $data .= ", `created_at` = '{$date_now}' ";
    }
    $data .= ", `updated_at` = '{$date_now}' ";
    
    // Insert or update record
    if(empty($animal_id)){
        $sql = "INSERT INTO `animals` SET {$data}";
    }else{
        $sql = "UPDATE `animals` SET {$data} WHERE animal_id = '{$animal_id}'";
    }
    
    $save = $this->conn->query($sql);
    if($save){
        $aid = !empty($animal_id) ? $animal_id : $this->conn->insert_id;
        $resp['status'] = 'success';
        $resp['aid'] = $aid;
        if(empty($animal_id))
            $this->settings->set_flashdata('success',"Animal record successfully added.");
        else
            $this->settings->set_flashdata('success',"Animal record successfully updated.");
    }else{
        $resp['status'] = 'failed';
        $resp['err'] = $this->conn->error."[{$sql}]";
    }
    return json_encode($resp);
}

// Delete Animal Function
function delete_animal(){
    extract($_POST);
    $del = $this->conn->query("DELETE FROM `animals` WHERE animal_id = '{$id}'");
    if($del){
        $resp['status'] = 'success';
        $this->settings->set_flashdata('success',"Animal record successfully deleted.");
    }else{
        $resp['status'] = 'failed';
        $resp['error'] = $this->conn->error;
    }
    return json_encode($resp);
}

function update_application_status(){
    extract($_POST);
    
    // Debug received data
    error_log("Received POST data: " . json_encode($_POST));
    
    $data = "";
    // Check if required fields are present
    if(!isset($application_id) || !isset($previous_status) || !isset($new_status)) {
        $resp['status'] = 'failed';
        $resp['msg'] = "Missing required fields";
        return json_encode($resp);
    }
    
    // Handle any potential empty values for home_visit fields
    if(!isset($home_visit_date) || empty($home_visit_date)) {
        $home_visit_date = null;
    }
    if(!isset($home_visit_notes)) {
        $home_visit_notes = '';
    }
    $home_visit_passed = isset($home_visit_passed) ? 1 : 0;
    
    try {
        // Start transaction
        $this->conn->begin_transaction();
        
        // Update application status
        $stmt = $this->conn->prepare("UPDATE `adoption_applications` SET 
                                    `status`= ?, 
                                    `staff_notes`= ?,
                                    `home_visit_date`= ?,
                                    `home_visit_notes`= ?,
                                    `home_visit_passed`= ?,
                                    `last_updated`= NOW()
                                WHERE application_id = ?");
        
        $stmt->bind_param("ssssii", $new_status, $notes, $home_visit_date, $home_visit_notes, $home_visit_passed, $application_id);
        $stmt->execute();
        
        if($stmt->affected_rows > 0 || $new_status != $previous_status) {
            // Log the status change
            $changed_by = isset($this->settings->userdata['username']) ? $this->settings->userdata['username'] : 'Admin';
            $log_stmt = $this->conn->prepare("INSERT INTO `application_status_log` 
                                           (`application_id`, `previous_status`, `new_status`, `changed_at`, `changed_by`, `notes`) 
                                           VALUES (?, ?, ?, NOW(), ?, ?)");
            $log_stmt->bind_param("issss", $application_id, $previous_status, $new_status, $changed_by, $notes);
            $log_stmt->execute();
            
            // If approved or completed, update animal status
            if($new_status == 'Approved' || $new_status == 'Completed'){
                // Update animal status to Pending or Adopted
                $animal_status = ($new_status == 'Approved') ? 'Pending' : 'Adopted';
                $animal_stmt = $this->conn->prepare("UPDATE `animals` SET `adoption_status` = ? WHERE `animal_id` = ?");
                $animal_stmt->bind_param("si", $animal_status, $animal_id);
                $animal_stmt->execute();
            }
            
            $this->conn->commit();
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success', "Application status successfully updated to {$new_status}.");
        } else {
            $this->conn->rollback();
            $resp['status'] = 'failed';
            $resp['msg'] = "No changes were made to the application status.";
        }
    } catch (Exception $e) {
        $this->conn->rollback();
        $resp['status'] = 'failed';
        $resp['msg'] = "Database error: " . $e->getMessage();
        error_log("Error in update_application_status: " . $e->getMessage());
    }
    
    return json_encode($resp);
}
// Function to update rescue operation status
function update_rescue_status(){
    extract($_POST);
    
    // Debug output to check received data
    error_log("Updating rescue status with data: " . json_encode($_POST));
    
    // Data validation
    if(!isset($rescue_id) || !isset($new_status) || !isset($previous_status)) {
        $resp['status'] = 'failed';
        $resp['msg'] = "Missing required fields";
        error_log("Missing required fields in update_rescue_status");
        return json_encode($resp);
    }
    
    try {
        // Start transaction
        $this->conn->begin_transaction();
        
        // Build update SQL based on available columns in rescue_operations table
        $sql = "UPDATE `rescue_operations` SET 
                `status` = ?,
                `updated_at` = NOW()";
        
        // Add parameters for the statement
        $params = [$new_status];
        $types = "s"; // string
        
        // If additional details were submitted, update that field too
        if(isset($notes) && !empty($notes)) {
            $sql .= ", `additional_details` = ?";
            $params[] = $notes;
            $types .= "s"; // string
        }
        
        // Complete the SQL statement
        $sql .= " WHERE id = ?";
        $params[] = intval($rescue_id);
        $types .= "i"; // integer
        
        // Prepare and execute the statement
        $stmt = $this->conn->prepare($sql);
        if(!$stmt) {
            throw new Exception("SQL preparation failed: " . $this->conn->error);
        }
        
        // Bind parameters dynamically
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        
        if($stmt->affected_rows > 0 || $new_status != $previous_status) {
            // Get rescue operation details
            $rescue_details = $this->conn->query("SELECT r.*, c.email, c.id as client_id FROM rescue_operations r INNER JOIN clients c ON r.email = c.email WHERE r.id = '{$rescue_id}'")->fetch_assoc();
            
            // Map status to notification messages
            $status_notifications = array(
                'Pending' => array(
                    'title' => 'Rescue Request Received',
                    'message' => "Your rescue request for {$rescue_details['animal_type']} has been received and is pending review. Our team will contact you shortly."
                ),
                'Under Review' => array(
                    'title' => 'Rescue Request Under Review',
                    'message' => "Your rescue request for {$rescue_details['animal_type']} is currently being reviewed by our team. We'll update you soon."
                ),
                'Team Dispatched' => array(
                    'title' => 'Rescue Team Dispatched',
                    'message' => "Our rescue team has been dispatched to your location for the {$rescue_details['animal_type']}. They will arrive shortly."
                ),
                'In Progress' => array(
                    'title' => 'Rescue Operation In Progress',
                    'message' => "The rescue operation for {$rescue_details['animal_type']} is currently in progress. Our team is working on the rescue."
                ),
                'Completed' => array(
                    'title' => 'Rescue Operation Completed',
                    'message' => "The rescue operation for {$rescue_details['animal_type']} has been completed successfully. Thank you for your concern and support."
                ),
                'On Hold' => array(
                    'title' => 'Rescue Operation On Hold',
                    'message' => "The rescue operation for {$rescue_details['animal_type']} has been put on hold. " . (isset($notes) ? "Reason: {$notes}" : "")
                ),
                'Cancelled' => array(
                    'title' => 'Rescue Operation Cancelled',
                    'message' => "The rescue operation for {$rescue_details['animal_type']} has been cancelled. " . (isset($notes) ? "Reason: {$notes}" : "")
                )
            );
            
            // Send notification if status has a corresponding message
            if(isset($status_notifications[$new_status])) {
                $notification = $status_notifications[$new_status];
                $notif_sql = "INSERT INTO notifications (user_id, title, message, type) VALUES (?, ?, ?, 'rescue_status')";
                $notif_stmt = $this->conn->prepare($notif_sql);
                $notif_stmt->bind_param("iss", $rescue_details['client_id'], $notification['title'], $notification['message']);
                $notif_stmt->execute();
            }
            
            // Log the status change
            $log_sql = "INSERT INTO `rescue_status_log` 
                       (`rescue_id`, `previous_status`, `new_status`, `notes`, `changed_by`, `changed_at`) 
                       VALUES (?, ?, ?, ?, ?, NOW())";
            
            // Get the username for the log
            $username = '';
            if(isset($_SESSION['userdata']) && isset($_SESSION['userdata']['username'])) {
                $username = $_SESSION['userdata']['username'];
            } else {
                $username = 'Admin';
            }
            
            $log_stmt = $this->conn->prepare($log_sql);
            $log_stmt->bind_param("issss", $rescue_id, $previous_status, $new_status, $notes, $username);
            $log_stmt->execute();
            
            $this->conn->commit();
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success', "Rescue status updated successfully to {$new_status}.");
        } else {
            $this->conn->rollback();
            $resp['status'] = 'failed';
            $resp['msg'] = "No changes were made to the rescue status.";
        }
    } catch (Exception $e) {
        $this->conn->rollback();
        $resp['status'] = 'failed';
        $resp['msg'] = "Database error: " . $e->getMessage();
        error_log("Error in update_rescue_status: " . $e->getMessage());
    }
    
    return json_encode($resp);
}

// Function to delete rescue operation
function delete_rescue(){
    extract($_POST);
    
    $delete = $this->conn->query("DELETE FROM `rescue_operations` WHERE id = '{$id}'");
    if($delete){
        $resp['status'] = 'success';
        $this->settings->set_flashdata('success', "Rescue operation deleted successfully.");
    } else {
        $resp['status'] = 'failed';
        $resp['error'] = $this->conn->error;
    }
    
    return json_encode($resp);
}

// Add these functions in your Master.php file

// Save or update donation
function save_donation(){
    global $_settings;
    $resp = array('status'=>'failed','msg'=>'An error occurred while saving the data');
    
    if(empty($_POST['id'])){
        // For new donations
        if(empty($_POST['donation_id'])) {
            $prefix = 'DON';
            $date = date('Ymd');
            $rand = sprintf("%04d", mt_rand(1, 9999));
            $_POST['donation_id'] = $prefix . $date . $rand;
        }
        
        // Set client_id to NULL if is_anonymous is 1
        if($_POST['is_anonymous'] == 1) {
            $_POST['client_id'] = NULL;
        }
        
        $sql = "INSERT INTO `donations` (donation_id, client_id, PayPal_payment_ID, amount, message, donation_type, payment_status, donation_date, purpose, is_anonymous, receipt_sent) VALUES ('{$_POST['donation_id']}', " . (empty($_POST['client_id']) ? "NULL" : "'{$_POST['client_id']}'") . ", '{$_POST['PayPal_payment_ID']}', '{$_POST['amount']}', '{$_POST['message']}', '{$_POST['donation_type']}', '{$_POST['payment_status']}', '{$_POST['donation_date']}', '{$_POST['purpose']}', '{$_POST['is_anonymous']}', '{$_POST['receipt_sent']}')";
    } else {
        // For updating donations
        // Set client_id to NULL if is_anonymous is 1
        if($_POST['is_anonymous'] == 1) {
            $_POST['client_id'] = NULL;
        }
        
        $sql = "UPDATE `donations` SET donation_id = '{$_POST['donation_id']}', client_id = " . (empty($_POST['client_id']) ? "NULL" : "'{$_POST['client_id']}'") . ", PayPal_payment_ID = '{$_POST['PayPal_payment_ID']}', amount = '{$_POST['amount']}', message = '{$_POST['message']}', donation_type = '{$_POST['donation_type']}', payment_status = '{$_POST['payment_status']}', donation_date = '{$_POST['donation_date']}', purpose = '{$_POST['purpose']}', is_anonymous = '{$_POST['is_anonymous']}', receipt_sent = '{$_POST['receipt_sent']}' WHERE id = '{$_POST['id']}'";
    }
    
    @$save = $this->conn->query($sql);
    
    if($save){
        $rid = empty($_POST['id']) ? $this->conn->insert_id : $_POST['id'];
        $resp['status'] = 'success';
        $resp['id'] = $rid;
        if(empty($_POST['id']))
            $resp['msg'] = "Donation has been added successfully.";
        else
            $resp['msg'] = "Donation has been updated successfully.";
    }else{
        $resp['msg'] = "Error: " . $this->conn->error;
        $resp['sql'] = $sql;
    }
    
    return json_encode($resp);
}

// Delete donation
function delete_donation(){
    extract($_POST);
    
    @$delete = $this->conn->query("DELETE FROM `donations` where id = '{$id}'");
    if($delete){
        $resp['status'] = 'success';
        $resp['msg'] = "Donation has been deleted successfully.";
    }else{
        $resp['status'] = 'failed';
        $resp['error'] = $this->conn->error;
    }
    
    return json_encode($resp);
}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function save_category(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id','description'))){
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(isset($_POST['description'])){
			if(!empty($data)) $data .=",";
				$data .= " `description`='".addslashes(htmlentities($description))."' ";
		}
		$check = $this->conn->query("SELECT * FROM `categories` where `category` = '{$category}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Category already exist.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `categories` set {$data} ";
			$save = $this->conn->query($sql);
		}else{
			$sql = "UPDATE `categories` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if($save){
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"New Category successfully saved.");
			else
				$this->settings->set_flashdata('success',"Category successfully updated.");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_category(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `categories` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Category successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_sub_category(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id','description'))){
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(isset($_POST['description'])){
			if(!empty($data)) $data .=",";
				$data .= " `description`='".addslashes(htmlentities($description))."' ";
		}
		$check = $this->conn->query("SELECT * FROM `sub_categories` where `sub_category` = '{$sub_category}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Sub Category already exist.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `sub_categories` set {$data} ";
			$save = $this->conn->query($sql);
		}else{
			$sql = "UPDATE `sub_categories` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if($save){
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"New Sub Category successfully saved.");
			else
				$this->settings->set_flashdata('success',"Sub Category successfully updated.");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_sub_category(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `sub_categories` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Sub Category successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_product(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id','description'))){
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(isset($_POST['description'])){
			if(!empty($data)) $data .=",";
				$data .= " `description`='".addslashes(htmlentities($description))."' ";
		}
		$check = $this->conn->query("SELECT * FROM `products` where `product_name` = '{$product_name}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Product already exist.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `products` set {$data} ";
			$save = $this->conn->query($sql);
			$id= $this->conn->insert_id;
		}else{
			$sql = "UPDATE `products` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if($save){
			$upload_path = "uploads/product_".$id;
			if(!is_dir(base_app.$upload_path))
				mkdir(base_app.$upload_path);
			if(isset($_FILES['img']) && count($_FILES['img']['tmp_name']) > 0){
				foreach($_FILES['img']['tmp_name'] as $k => $v){
					if(!empty($_FILES['img']['tmp_name'][$k])){
						move_uploaded_file($_FILES['img']['tmp_name'][$k],base_app.$upload_path.'/'.$_FILES['img']['name'][$k]);
					}
				}
			}
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"New Product successfully saved.");
			else
				$this->settings->set_flashdata('success',"Product successfully updated.");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_product(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `products` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Product successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function delete_img(){
		extract($_POST);
		if(is_file($path)){
			if(unlink($path)){
				$resp['status'] = 'success';
			}else{
				$resp['status'] = 'failed';
				$resp['error'] = 'failed to delete '.$path;
			}
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = 'Unkown '.$path.' path';
		}
		return json_encode($resp);
	}
	function save_inventory(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id','description'))){
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `inventory` where `product_id` = '{$product_id}' and `size` = '{$size}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
		if($this->capture_err())
			return $this->capture_err();
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Inventory already exist.";
			return json_encode($resp);
			exit;
		}
		if(empty($id)){
			$sql = "INSERT INTO `inventory` set {$data} ";
			$save = $this->conn->query($sql);
		}else{
			$sql = "UPDATE `inventory` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if($save){
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"New Inventory successfully saved.");
			else
				$this->settings->set_flashdata('success',"Inventory successfully updated.");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_inventory(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `inventory` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Invenory successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function register() {
		// Validation checks
		$validation_errors = $this->validate($_POST);
		if (!empty($validation_errors)) {
			$resp['status'] = 'failed';
			$resp['msg'] = $validation_errors;
			return json_encode($resp);
		}
		extract($_POST);
		$data = "";
		$_POST['password'] = md5($_POST['password']);
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id'))) {
				if (!empty($data)) $data .= ",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `clients` WHERE `email` = '{$email}' " . (!empty($id) ? " AND id != {$id} " : "") . " ")->num_rows;
		if ($this->capture_err())
			return $this->capture_err();
		if ($check > 0) {
			$resp['status'] = 'failed';
			$resp['msg'] = "Email already taken.";
			return json_encode($resp);
			exit;
		}
		
		// Check if email is verified before allowing registration
		$email_verification = $this->conn->query("SELECT * FROM email_verifications WHERE email = '{$email}' AND is_verified = 1");
		if ($email_verification->num_rows === 0) {
			$resp['status'] = 'failed';
			$resp['msg'] = "Please verify your email before registering.";
			return json_encode($resp);
		}
	
		if (empty($id)) {
			$sql = "INSERT INTO `clients` SET {$data} ";
			$save = $this->conn->query($sql);
			$id = $this->conn->insert_id;
		} else {
			$sql = "UPDATE `clients` SET {$data} WHERE id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if ($save) {
			$resp['status'] = 'success';
			if (empty($id))
				$this->settings->set_flashdata('success', "Account successfully created.");
			else
				$this->settings->set_flashdata('success', "Account successfully updated.");
			foreach ($_POST as $k => $v) {
				$this->settings->set_userdata($k, $v);
			}
			$this->settings->set_userdata('id', $id);
	
		} else {
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error . "[{$sql}]";
		}
		return json_encode($resp);
	}
	
	function validate($data) {
		$validation_errors = []; // Renamed variable
		
		// Validation for first name
		if (empty($data['firstname'])) {
			$validation_errors[] = "First name is required.";
		}
	
		// Validation for last name
		if (empty($data['lastname'])) {
			$validation_errors[] = "Last name is required.";
		}
	
		// Validation for contact number
		if (empty($data['contact'])) {
			$validation_errors[] = "Contact number is required.";
		} elseif (!preg_match("/^(\+977\d{10}|\d{10})$/", $data['contact'])) {
			$validation_errors[] = "Invalid contact number format.";
		}
	
		// Validation for delivery address
		if (empty($data['default_delivery_address'])) {
			$validation_errors[] = "Delivery address is required.";
		}
	
		// Validation for email
		if (empty($data['email'])) {
			$validation_errors[] = "Email is required.";
		} elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
			$validation_errors[] = "Invalid email address.";
		}
		
		return $validation_errors; // Return renamed variable
	}

	function add_to_cart(){
		extract($_POST);
		$data = " client_id = '".$this->settings->userdata('id')."' ";
		$_POST['price'] = str_replace(",","",$_POST['price']); 
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		$check = $this->conn->query("SELECT * FROM `cart` where `inventory_id` = '{$inventory_id}' and client_id = ".$this->settings->userdata('id'))->num_rows;
		if($this->capture_err())
			return $this->capture_err();
	
		// Check stock availability
		$stock_check = $this->conn->query("SELECT `quantity` FROM `inventory` WHERE `id` = '{$inventory_id}'")->fetch_assoc();
		if($stock_check['quantity'] < $quantity){
			$resp['status'] = 'failed';
			$resp['msg'] = "Insufficient stock for the requested quantity.";
			return json_encode($resp);
		}
	
		if($check > 0){
			$sql = "UPDATE `cart` set quantity = quantity + {$quantity} where `inventory_id` = '{$inventory_id}' and client_id = ".$this->settings->userdata('id');
		}else{
			$sql = "INSERT INTO `cart` set {$data} ";
		}
		
		$save = $this->conn->query($sql);
		if($this->capture_err())
			return $this->capture_err();
		if($save){
			$resp['status'] = 'success';
			$resp['cart_count'] = $this->conn->query("SELECT SUM(quantity) as items from `cart` where client_id =".$this->settings->userdata('id'))->fetch_assoc()['items'];
			
			// Add notification for cart update
			$product_name = $this->conn->query("SELECT p.product_name FROM products p INNER JOIN inventory i ON p.id = i.product_id WHERE i.id = '{$inventory_id}'")->fetch_assoc()['product_name'];
			$notification_title = "Cart Updated";
			$notification_message = "Added {$quantity} x {$product_name} to your cart.";
			$notif_sql = "INSERT INTO notifications (user_id, title, message, type) VALUES (?, ?, ?, 'cart')";
			$notif_stmt = $this->conn->prepare($notif_sql);
			$notif_stmt->bind_param("iss", $this->settings->userdata('id'), $notification_title, $notification_message);
			$notif_stmt->execute();
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	
	function update_cart_qty(){
		extract($_POST);
		
		$save = $this->conn->query("UPDATE `cart` set quantity = '{$quantity}' where id = '{$id}'");
		if($this->capture_err())
			return $this->capture_err();
		if($save){
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
		
	}
	function empty_cart(){
		$delete = $this->conn->query("DELETE FROM `cart` where client_id = ".$this->settings->userdata('id'));
		if($this->capture_err())
			return $this->capture_err();
		if($delete){
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_cart(){
		extract($_POST);
		$delete = $this->conn->query("DELETE FROM `cart` where id = '{$id}'");
		if($this->capture_err())
			return $this->capture_err();
		if($delete){
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function delete_order(){
		extract($_POST);
		$delete = $this->conn->query("DELETE FROM `orders` where id = '{$id}'");
		$delete2 = $this->conn->query("DELETE FROM `order_list` where order_id = '{$id}'");
		$delete3 = $this->conn->query("DELETE FROM `sales` where order_id = '{$id}'");
		if($this->capture_err())
			return $this->capture_err();
		if($delete){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Order successfully deleted");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	function place_order(){
		extract($_POST);
		$client_id = $this->settings->userdata('id');
		
		$data = " client_id = '{$client_id}' ";
		$data .= " ,payment_method = '{$payment_method}' ";
		$data .= " ,amount = '{$amount}' ";
		$data .= " ,paid = '{$paid}' ";
		$data .= " ,delivery_address = '{$delivery_address}' ";
		$order_sql = "INSERT INTO `orders` set $data";
		$save_order = $this->conn->query($order_sql);
		if($this->capture_err())
			return $this->capture_err();
		if($save_order){
			$order_id = $this->conn->insert_id;
			$data = '';
			$cart = $this->conn->query("SELECT c.*,p.product_name,i.size,i.price,p.id as pid,i.unit from `cart` c inner join `inventory` i on i.id=c.inventory_id inner join products p on p.id = i.product_id where c.client_id ='{$client_id}' ");
			while($row= $cart->fetch_assoc()):
				if(!empty($data)) $data .= ", ";
				$total = $row['price'] * $row['quantity'];
				$data .= "('{$order_id}','{$row['pid']}','{$row['size']}','{$row['unit']}','{$row['quantity']}','{$row['price']}', $total)";
				
				// Update inventory stock
				$update_inventory = $this->conn->query("UPDATE `inventory` SET `quantity` = `quantity` - {$row['quantity']} WHERE id = '{$row['inventory_id']}'");
				if($this->capture_err())
					return $this->capture_err();
			endwhile;
			$list_sql = "INSERT INTO `order_list` (order_id,product_id,size,unit,quantity,price,total) VALUES {$data} ";
			$save_olist = $this->conn->query($list_sql);
			if($this->capture_err())
				return $this->capture_err();
			if($save_olist){
				$empty_cart = $this->conn->query("DELETE FROM `cart` where client_id = '{$client_id}'");
				$data = " order_id = '{$order_id}'";
				$data .= " ,total_amount = '{$amount}'";
				$save_sales = $this->conn->query("INSERT INTO `sales` set $data");
				if($this->capture_err())
					return $this->capture_err();
				
				// Add single notification for admin/staff
				$notification_title = "New Order Received";
				$notification_message = "A new order #{$order_id} has been placed. Total amount: ₹{$amount}";
				$notif_sql = "INSERT INTO user_notifications (user_id, title, message, type) VALUES (1, ?, ?, 'order')";
				$notif_stmt = $this->conn->prepare($notif_sql);
				$notif_stmt->bind_param("ss", $notification_title, $notification_message);
				$notif_stmt->execute();
				
				// Add notification for client
				$notification_title = "Order Placed Successfully";
				$notification_message = "Your order #{$order_id} has been placed successfully. Total amount: ₹{$amount}";
				$notif_sql = "INSERT INTO notifications (user_id, title, message, type) VALUES (?, ?, ?, 'order')";
				$notif_stmt = $this->conn->prepare($notif_sql);
				$notif_stmt->bind_param("iss", $client_id, $notification_title, $notification_message);
				$notif_stmt->execute();
				
				$resp['status'] ='success';
			}else{
				$resp['status'] ='failed';
				$resp['err_sql'] =$save_olist;
			}
	
		}else{
			$resp['status'] ='failed';
			$resp['err_sql'] =$save_order;
		}
		return json_encode($resp);
	}
	
	function update_order_status(){
		extract($_POST);
		$update = $this->conn->query("UPDATE `orders` set `status` = '$status' where id = '{$id}' ");
		if($update){
			// Get order details and client info
			$order_details = $this->conn->query("SELECT o.*, c.email FROM orders o INNER JOIN clients c ON o.client_id = c.id WHERE o.id = '{$id}'")->fetch_assoc();
			
			// Map status codes to readable status
			$status_map = array(
				'0' => 'Pending',
				'1' => 'Packed',
				'2' => 'Out for Delivery',
				'3' => 'Delivered',
				'4' => 'Cancelled'
			);
			
			// Create notification
			$notification_title = "Order Status Updated";
			$notification_message = "Your order #{$id} status has been updated to: " . $status_map[$status];
			$notif_sql = "INSERT INTO notifications (user_id, title, message, type) VALUES (?, ?, ?, 'order_status')";
			$notif_stmt = $this->conn->prepare($notif_sql);
			$notif_stmt->bind_param("iss", $order_details['client_id'], $notification_title, $notification_message);
			$notif_stmt->execute();
			
			$resp['status'] ='success';
			$this->settings->set_flashdata("success"," Order status successfully updated.");
		}else{
			$resp['status'] ='failed';
			$resp['err'] =$this->conn->error;
		}
		return json_encode($resp);
	}
	function pay_order(){
		extract($_POST);
		$update = $this->conn->query("UPDATE `orders` set `paid` = '1' where id = '{$id}' ");
		if($update){
			// Add notification for payment
			$order_details = $this->conn->query("SELECT o.*, c.email FROM orders o INNER JOIN clients c ON o.client_id = c.id WHERE o.id = '{$id}'")->fetch_assoc();
			$notification_title = "Payment Received";
			$notification_message = "Payment of ₹{$order_details['amount']} for order #{$id} has been received successfully.";
			$notif_sql = "INSERT INTO notifications (user_id, title, message, type) VALUES (?, ?, ?, 'payment')";
			$notif_stmt = $this->conn->prepare($notif_sql);
			$notif_stmt->bind_param("iss", $order_details['client_id'], $notification_title, $notification_message);
			$notif_stmt->execute();
			
			$resp['status'] ='success';
			$this->settings->set_flashdata("success"," Order payment status successfully updated.");
		}else{
			$resp['status'] ='failed';
			$resp['err'] =$this->conn->error;
		}
		return json_encode($resp);
	}
	function update_account(){
		extract($_POST);
		$data = "";
		if(!empty($password)){
			$_POST['password'] = md5($password);
			if(md5($cpassword) != $this->settings->userdata('password')){
				$resp['status'] = 'failed';
				$resp['msg'] = "Current Password is Incorrect";
				return json_encode($resp);
				exit;
			}

		}
		$check = $this->conn->query("SELECT * FROM `clients`  where `email`='{$email}' and `id` != $id ")->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Email already taken.";
			return json_encode($resp);
			exit;
		}
		foreach($_POST as $k =>$v){
			if($k == 'cpassword' || ($k == 'password' && empty($v)))
				continue;
				if(!empty($data)) $data .=",";
					$data .= " `{$k}`='{$v}' ";
		}
		$save = $this->conn->query("UPDATE `clients` set $data where id = $id ");
		if($save){
			foreach($_POST as $k =>$v){
				if($k != 'cpassword')
				$this->settings->set_userdata($k,$v);
			}
			
			$this->settings->set_userdata('id',$this->conn->insert_id);
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_category':
		echo $Master->save_category();
	break;
	case 'delete_category':
		echo $Master->delete_category();
	break;
	case 'save_sub_category':
		echo $Master->save_sub_category();
	break;
	case 'delete_sub_category':
		echo $Master->delete_sub_category();
	break;
	case 'save_product':
		echo $Master->save_product();
	break;
	case 'delete_product':
		echo $Master->delete_product();
	break;
	
	case 'save_inventory':
		echo $Master->save_inventory();
	break;
	case 'delete_inventory':
		echo $Master->delete_inventory();
	break;
	case 'register':
		echo $Master->register();
	break;
	case 'add_to_cart':
		echo $Master->add_to_cart();
	break;
	case 'update_cart_qty':
		echo $Master->update_cart_qty();
	break;
	case 'delete_cart':
		echo $Master->delete_cart();
	break;
	case 'empty_cart':
		echo $Master->empty_cart();
	break;
	case 'delete_img':
		echo $Master->delete_img();
	break;
	case 'place_order':
		echo $Master->place_order();
	break;
	case 'update_order_status':
		echo $Master->update_order_status();
	break;
	case 'pay_order':
		echo $Master->pay_order();
	break;
	case 'update_account':
		echo $Master->update_account();
	break;
	case 'delete_order':
		echo $Master->delete_order();
	break;
	case 'save_animal':
		echo $Master->save_animal();
	break;
	case 'delete_animal':
		echo $Master->delete_animal();
	break;
	case 'update_application_status':
		echo $Master->update_application_status();
	break;
	case 'update_rescue_status':
		echo $Master->update_rescue_status();
	break;
	case 'delete_rescue':
		echo $Master->delete_rescue();
	break;
	case 'save_donation':
		echo $Master->save_donation();
	break;
	case 'delete_donation':
		echo $Master->delete_donation();
	break;
	default:
		// echo $sysset->index();
		break;
}