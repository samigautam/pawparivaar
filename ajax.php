<?php

// ... existing code ...
    case 'mark_notification_read':
        $id = $_POST['id'];
        $update = $conn->query("UPDATE notifications set is_read = 1 where id = '{$id}' and user_id = '{$_SESSION['userdata']['id']}'");
        if($update){
            $resp['status'] = 'success';
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $conn->error;
        }
        echo json_encode($resp);
    break;
// ... existing code ... 