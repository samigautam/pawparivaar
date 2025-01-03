
<section class="py-2">
    <div class="container">
        <div class="card rounded-0">
            <div class="card-body">
                <div class="w-100 justify-content-between d-flex">
                    <h4><b>Orders</b></h4>
                    <a href="./?p=edit_account" class="btn btn btn-dark btn-flat"><div class="fa fa-user-cog"></div> Manage Account</a>
                </div>
                <hr class="border-warning">
                <table class="table table-stripped text-dark">
                    <colgroup>
                        <col width="10%">
                        <col width="15">
                        <col width="25">
                        <col width="25">
                        <col width="15">
                        <col width="10%"> <!-- Added this column for the cancel order button -->
                    </colgroup>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>DateTime</th>
                            <th>Transaction ID</th>
                            <th>Amount</th>
                            <th>Order Status</th>
                            <th>Action</th> <!-- Add this column for action buttons -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $i = 1;
                            $qry = $conn->query("SELECT o.*,concat(c.firstname,' ',c.lastname) as client from `orders` o inner join clients c on c.id = o.client_id where o.client_id = '".$_settings->userdata('id')."' order by unix_timestamp(o.date_created) desc ");
                            while($row = $qry->fetch_assoc()):
                        ?>
                            <tr>
                                <td><?php echo $i++ ?></td>
                                <td><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
                                <td><a href="javascript:void(0)" class="view_order" data-id="<?php echo $row['id'] ?>"><?php echo md5($row['id']); ?></a></td>
                                <td><?php echo number_format($row['amount']) ?> </td>
                                <td class="text-center">
                                    <?php 
                                        if($row['status'] == 0): 
                                            echo '<span class="badge badge-light text-dark">Pending</span>';
                                        elseif($row['status'] == 1): 
                                            echo '<span class="badge badge-primary">Packed</span>';
                                        elseif($row['status'] == 2): 
                                            echo '<span class="badge badge-warning">Out for Delivery</span>';
                                        elseif($row['status'] == 3): 
                                            echo '<span class="badge badge-success">Delivered</span>';
                                        else: 
                                            echo '<span class="badge badge-danger">Cancelled</span>';
                                        endif; 
                                    ?>
                                </td>
                                <td>
                                    <?php if($row['status'] != 4): ?>
                                        <!-- Button to cancel order -->
                                        <button class="btn btn-sm btn-danger cancel-order" data-id="<?php echo $row['id'] ?>">Cancel</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<script>
    $(function(){
        $('.view_order').click(function(){
            uni_modal("Order Details","./admin/orders/view_order.php?view=user&id="+$(this).attr('data-id'),'large')
        })

        // Handle cancel order button click
        $('.cancel-order').click(function(){
            var orderID = $(this).data('id');
            // Show confirmation dialog
            if (confirm("Are you sure you want to cancel this order?")) {
                cancelOrder(orderID);
            }
        });

        // Function to cancel order via AJAX
        function cancelOrder(orderID) {
            $.ajax({
                url: _base_url_ + "classes/Master.php?f=update_order_status",
                method: "POST",
                data: { id: orderID, status: 4 }, // Set status to 4 (cancelled)
                dataType: "json",
                error: function(err) {
                    console.log(err);
                    alert_toast("An error occurred", 'error');
                },
                success: function(resp) {
                    if (resp.status == 'success') {
                        alert_toast("Order cancelled successfully", 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        console.log(resp);
                        alert_toast("An error occurred", 'error');
                    }
                }
            });
        }
    });
</script>
