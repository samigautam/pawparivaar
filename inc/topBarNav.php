<nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container px-4 px-lg-5 ">
                <button class="navbar-toggler btn btn-sm" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <a class="navbar-brand" href="./">
                <img src="<?php echo validate_image($_settings->info('logo')) ?>" width="30" height="30" class="d-inline-block align-top" alt="" loading="lazy">
                <?php echo $_settings->info('short_name') ?>
                </a>

                <form class="form-inline" id="search-form">
                  <div class="input-group">
                    <input class="form-control form-control-sm form " type="search" placeholder="Search" aria-label="Search" name="search"  value="<?php echo isset($_GET['search']) ? $_GET['search'] : "" ?>"  aria-describedby="button-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-outline-success btn-sm m-0" type="submit" id="button-addon2"><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                </form>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
    <li class="nav-item"><a class="nav-link" aria-current="page" href="./">Home</a></li>
    <?php 
    $cat_qry = $conn->query("SELECT * FROM categories where status = 1 ");
    while($crow = $cat_qry->fetch_assoc()):
        $sub_qry = $conn->query("SELECT * FROM sub_categories where status = 1 and parent_id = '{$crow['id']}' ");
        if($sub_qry->num_rows <= 0):
    ?>
    <li class="nav-item"><a class="nav-link" aria-current="page" href="./?p=products&c=<?php echo md5($crow['id']) ?>"><?php echo $crow['category'] ?></a></li>

    <?php else: ?>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdown<?php echo $crow['id'] ?>" href="#" role="button" data-toggle="dropdown" aria-expanded="false"><?php echo $crow['category'] ?></a>
        <ul class="dropdown-menu p-0" aria-labelledby="navbarDropdown<?php echo $crow['id'] ?>">
            <?php while($srow = $sub_qry->fetch_assoc()): ?>
            <li><a class="dropdown-item border-bottom" href="./?p=products&c=<?php echo md5($crow['id']) ?>&s=<?php echo md5($srow['id']) ?>"><?php echo $srow['sub_category'] ?></a></li>
            <?php endwhile; ?>
        </ul>
    </li>
    <?php endif; ?>
    <?php endwhile; ?>
    
    <li class="nav-item"><a class="nav-link" href="./?p=about">About</a></li>
    <li class="nav-item"><a class="nav-link" href="http://localhost/pawparivaar/ovas">VET Appointment</a></li>
    <li class="nav-item"><a class="nav-link" href="http://localhost/pawparivaar/map/index.html">Rescue</a></li>
    <li class="nav-item"><a class="nav-link" href="http://localhost/pawparivaar/donation_form.php">Donate</a></li>
    <li class="nav-item"><a class="nav-link" href="http://localhost/pawparivaar/adoption/browse_animals.php">Adopt</a></li>
</ul>

                    <div class="d-flex align-items-center">
                      <?php if(!isset($_SESSION['userdata']['id'])): ?>
                        <button class="btn btn-outline-dark ml-2" id="login-btn" type="button">Login</button>
                        <?php else: ?>
                        <a class="text-dark mr-2 nav-link" href="./?p=cart">
                            <i class="bi-cart-fill me-1"></i>
                            Cart
                            <span class="badge bg-dark text-white ms-1 rounded-pill" id="cart-count">
                              <?php 
                              if(isset($_SESSION['userdata']['id'])):
                                $count = $conn->query("SELECT SUM(quantity) as items from `cart` where client_id =".$_settings->userdata('id'))->fetch_assoc()['items'];
                                echo ($count > 0 ? $count : 0);
                              else:
                                echo "0";
                              endif;
                              ?>
                            </span>
                        </a>
                        
                        <?php if(isset($_SESSION['userdata']['id'])): ?>
                        <a class="text-dark mr-2 nav-link" href="./?p=notifications">
                            <i class="bi-bell-fill me-1"></i>
                            Notifications
                            <span class="badge bg-danger text-white ms-1 rounded-pill" id="notification-count">
                              <?php 
                                $notif_count = $conn->query("SELECT COUNT(*) as count from `notifications` where user_id =".$_settings->userdata('id')." AND is_read = 0")->fetch_assoc()['count'];
                                echo ($notif_count > 0 ? $notif_count : 0);
                              ?>
                            </span>
                        </a>
                        <?php endif; ?>
                        
                            <a href="./?p=my_account" class="text-dark  nav-link"><b><?php echo $_settings->userdata('firstname')?></b></a>
                            <a href="logout.php" class="text-dark  nav-link"><i class="fa fa-sign-out-alt"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
<script>
  $(function(){
    $('#login-btn').click(function(){
      uni_modal("","login.php")
    })
    $('#navbarResponsive').on('show.bs.collapse', function () {
        $('#mainNav').addClass('navbar-shrink')
    })
    $('#navbarResponsive').on('hidden.bs.collapse', function () {
        if($('body').offset.top == 0)
          $('#mainNav').removeClass('navbar-shrink')
    })
  })

  $('#search-form').submit(function(e){
    e.preventDefault()
     var sTxt = $('[name="search"]').val()
     if(sTxt != '')
      location.href = './?p=products&search='+sTxt;
  })
</script>