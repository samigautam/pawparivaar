<?php
require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    // Update query to use firstname and lastname instead of name
    $qry = $conn->query("SELECT d.*, CONCAT(c.firstname, ' ', c.lastname) as client_name FROM `donations` d LEFT JOIN `clients` c ON c.id = d.client_id WHERE d.id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k = $v;
        }
    }
}
?>
<style>
    #uni_modal .modal-footer{
        display:none;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered">
                <tr>
                    <th width="30%">Donation ID</th>
                    <td><?php echo $donation_id ?></td>
                </tr>
                <tr>
                    <th>Donor</th>
                    <td>
                        <?php if($is_anonymous == 1): ?>
                            <i>Anonymous</i>
                        <?php else: ?>
                            <?php echo $client_name ? $client_name : 'N/A' ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Amount</th>
                    <td><?php echo number_format($amount, 2) ?></td>
                </tr>
                <tr>
                    <th>Donation Type</th>
                    <td><?php echo $donation_type ?></td>
                </tr>
                <tr>
                    <th>Purpose</th>
                    <td><?php echo $purpose ? $purpose : 'General' ?></td>
                </tr>
                <tr>
                    <th>Message</th>
                    <td><?php echo $message ? nl2br($message) : 'N/A' ?></td>
                </tr>
                <tr>
                    <th>Payment Status</th>
                    <td>
                        <?php 
                        if($payment_status == 'completed'):
                            echo "<span class='badge badge-success'>Completed</span>";
                        elseif($payment_status == 'pending'):
                            echo "<span class='badge badge-warning'>Pending</span>";
                        else:
                            echo "<span class='badge badge-danger'>Failed</span>";
                        endif;
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>PayPal Payment ID</th>
                    <td><?php echo $PayPal_payment_ID ? $PayPal_payment_ID : 'N/A' ?></td>
                </tr>
                <tr>
                    <th>Donation Date</th>
                    <td><?php echo date("F d, Y h:i A", strtotime($donation_date)) ?></td>
                </tr>
                <tr>
                    <th>Receipt Sent</th>
                    <td><?php echo $receipt_sent == 1 ? 'Yes' : 'No' ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-right">
            <button class="btn btn-flat btn-dark" type="button" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>