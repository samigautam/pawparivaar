<?php require_once('../config.php') ?>
<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
 <?php require_once('inc/header.php') ?>
<body class="hold-transition login-page  dark-mode">
  <script>
    start_loader()
  </script>
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="./" class="h1"><b>Login</b></a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form id="login-frm" action="" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="username" placeholder="Username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <a href="<?php echo base_url ?>">Go to Website</a>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <!-- /.social-auth-links -->

      <!-- <p class="mb-1">
        <a href="forgot-password.html">I forgot my password</a>
      </p> -->
      
          </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
  $(document).ready(function(){
    end_loader();
  })
</script>

<?php


// Fetch user data from the database
$query = "SELECT id, username, type FROM users WHERE username = ? AND password = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['userdata'] = [
        'id' => $user['id'],
        'username' => $user['username'],
        'type' => $user['type'], // Use 'type' from the database
    ];
    header("Location: admin/dashboard.php");
    exit;
} 

// Handle avatar update logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_avatar'])) {
    $userId = $_SESSION['userdata']['id'];
    $username = trim($_POST['username']); // Trim to avoid unnecessary spaces
    $newAvatar = $_FILES['avatar']['name'];
    $avatarTmpPath = $_FILES['avatar']['tmp_name'];
    $uploadDir = '../uploads/avatars/';
    $avatarPath = $uploadDir . basename($newAvatar);

    // Check if username already exists for other users
    $query = "SELECT id FROM users WHERE username = ? AND id != ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $username, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Username already exists.";
    } else {
        // Move uploaded file to the target directory
        if (!empty($newAvatar)) {
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Create directory if it doesn't exist
            }
            if (move_uploaded_file($avatarTmpPath, $avatarPath)) {
                // Update avatar and username
                $updateQuery = "UPDATE users SET username = ?, avatar = ? WHERE id = ?";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param("ssi", $username, $newAvatar, $userId);
                if ($stmt->execute()) {
                    echo "Profile updated successfully.";
                    // Update session data
                    $_SESSION['userdata']['username'] = $username;
                    $_SESSION['userdata']['avatar'] = $newAvatar;
                } else {
                    echo "Error updating profile.";
                }
            } else {
                echo "Error uploading avatar.";
            }
        } else {
            // Update only the username if no avatar is uploaded
            $updateQuery = "UPDATE users SET username = ? WHERE id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("si", $username, $userId);
            if ($stmt->execute()) {
                echo "Profile updated successfully.";
                // Update session data
                $_SESSION['userdata']['username'] = $username;
            } else {
                echo "Error updating profile.";
            }
        }
    }
}
?>

</body>
</html>