<?php
require_once('../config.php');
require_once('../inc/header.php');
?>

<div class="content py-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Send Notification</h4>
                </div>
                <div class="card-body">
                    <form id="notification-form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_id">Select Client</label>
                                    <select class="form-control" name="user_id" id="user_id" required>
                                        <option value="">Select Client</option>
                                        <?php 
                                        $clients = $conn->query("SELECT * FROM clients ORDER BY firstname ASC");
                                        while($row = $clients->fetch_assoc()):
                                        ?>
                                        <option value="<?php echo $row['id'] ?>"><?php echo $row['firstname'] . ' ' . $row['lastname'] ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Notification Type</label>
                                    <select class="form-control" name="type" id="type" required>
                                        <option value="general">General</option>
                                        <option value="order">Order Update</option>
                                        <option value="message">Message</option>
                                        <option value="system">System</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" id="title" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea class="form-control" name="message" id="message" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Send Notification</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Recent Notifications</h3>
                    <div class="card-tools">
                        <a href="./?page=notifications" class="btn btn-flat btn-primary btn-sm">View All</a>
                    </div>
                </div>
                <div class="card-body">
                    <?php 
                    $notifications = $conn->query("SELECT * FROM user_notifications WHERE user_id = ".$_settings->userdata('id')." ORDER BY date_created DESC LIMIT 5");
                    if($notifications->num_rows > 0):
                    ?>
                    <div class="list-group">
                        <?php while($row = $notifications->fetch_assoc()): ?>
                        <div class="list-group-item list-group-item-action<?php echo $row['is_read'] == 0 ? ' unread' : '' ?>">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1"><?php echo $row['title'] ?></h5>
                                <small class="text-muted"><?php echo date("M d, Y h:i A", strtotime($row['date_created'])) ?></small>
                            </div>
                            <p class="mb-1"><?php echo $row['message'] ?></p>
                            <small class="text-muted">Type: <?php echo ucfirst($row['type']) ?></small>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center text-muted">
                        <p class="mb-0">No notifications found</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(function(){
    $('#notification-form').submit(function(e){
        e.preventDefault();
        start_loader();
        
        $.ajax({
            url: '../classes/Users.php?f=send_notification',
            data: $(this).serialize(),
            method: 'POST',
            dataType: 'json',
            error: err => {
                console.error(err);
                alert_toast("An error occurred", 'error');
                end_loader();
            },
            success: function(resp){
                if(resp.status == 'success'){
                    alert_toast(resp.msg, 'success');
                    $('#notification-form')[0].reset();
                    setTimeout(function(){
                        location.reload();
                    }, 2000);
                }else if(!!resp.msg){
                    alert_toast(resp.msg, 'error');
                }else{
                    alert_toast("An error occurred", 'error');
                }
                end_loader();
            }
        });
    });
});
$(document).ready(function(){
    $('.list-group-item').click(function(){
        var id = $(this).data('id');
        if(id){
            $.ajax({
                url: 'classes/Users.php?f=mark_notification_read',
                method: 'POST',
                data: {id: id},
                dataType: 'json',
                error: err => {
                    console.error(err);
                },
                success: function(resp){
                    if(resp.status == 'success'){
                        $(this).removeClass('unread');
                        $(this).find('h5').removeClass('font-weight-bold');
                    }
                }
            });
        }
    });
});
</script>
<style>
.list-group-item.unread {
    background-color: #f8f9fa;
    border-left: 3px solid #007bff;
}
.list-group-item.unread h5 {
    font-weight: bold;
}
</style>
<?php require_once('../inc/footer.php') ?>
