<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2 class="text-center">Reset Password</h2>
                <form method="post" action="<?php echo htmlspecialchars(base_url."classes/ResetPassword.php"); ?>">
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" class="form-control" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" class="form-control" name="confirm_new_password" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>