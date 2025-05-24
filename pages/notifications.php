<?php
if(!isset($_SESSION['userdata']['id'])){
    header("Location: ./");
    exit;
}
?>
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">My Notifications</h4>
                </div>
                <div class="card-body">
                    <div class="list-group" id="notifications-list">
                        <?php 
                        $notifications = $conn->query("SELECT * FROM notifications WHERE user_id = '{$_SESSION['userdata']['id']}' ORDER BY date_created DESC");
                        if($notifications->num_rows > 0):
                            while($row = $notifications->fetch_assoc()):
                        ?>
                        <div class="list-group-item list-group-item-action <?php echo $row['is_read'] == 0 ? 'bg-light' : '' ?>" data-id="<?php echo $row['id'] ?>">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1"><?php echo $row['title'] ?></h5>
                                <small><?php echo date("M d, Y h:i A", strtotime($row['date_created'])) ?></small>
                            </div>
                            <p class="mb-1"><?php echo $row['message'] ?></p>
                            <small class="text-muted"><?php echo ucfirst($row['type']) ?></small>
                        </div>
                        <?php 
                            endwhile;
                        else:
                        ?>
                        <div class="text-center py-3">
                            <p class="text-muted">No notifications found</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(function(){
    $('.list-group-item').click(function(){
        var id = $(this).attr('data-id');
        if($(this).hasClass('bg-light')){
            $.ajax({
                url:'ajax.php?action=mark_notification_read',
                method:'POST',
                data:{id:id},
                dataType:'json',
                error:err=>{
                    console.error(err);
                    alert_toast("An error occurred", 'error');
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        location.reload();
                    }
                }
            });
        }
    });
});
</script> 