<?php
require 'authentication.php'; // admin authentication check 
// auth check
if(isset($_SESSION['admin_id'])){
  $user_id = $_SESSION['admin_id'];
  $user_name = $_SESSION['admin_name'];
  $security_key = $_SESSION['security_key'];
  if ($user_id != NULL && $security_key != NULL) {
    header('Location: dashboard.php');
  }
}

if(isset($_POST['login_btn'])){
 $info = $obj_admin->admin_login_check($_POST);
}

$page_name="Login";
include("include/login_header.php");
?>
<link href="assets/css/style.css" rel="stylesheet" type="text/css">

<div class="row">
	<div class="col-md-4 col-md-offset-3">
		<div class="well" style="position:relative;top:20vh;">
		<center>
		<h2 style="color:#00208a;font-weight:bold;font-family:poppins;margin:10px;">ETMS</h2></center>
		<form class="form-horizontal form-custom-login" action="" method="POST">
		<p class="text-center text-success">Please login to your account</p> 
			  <!-- <div class="login-gap"></div> -->
			  <?php if(isset($info)){ ?>
			  <h5 class="alert alert-danger"><?php echo $info; ?></h5>
			  <?php } ?>
			  <div class="form-group">
			  <label for="username">Username</label>
			    <input type="text" class="form-control" placeholder="Username" name="username" required/>
			  </div>
			  <div class="form-group" ng-class="{'has-error': loginForm.password.$invalid && loginForm.password.$dirty, 'has-success': loginForm.password.$valid}">
			  <label for="password">Password</label>
			  <input type="password" class="form-control" placeholder="Password" name="admin_password" required/>
			  </div>
			  <div class="form-group">
			  <button type="submit" name="login_btn" class="btn btn-primary btn-block" style="margin-top:15px; border-radius: 5px;text-align: center;">Login</button>
			  </div>
			</form>
		</div>
	</div>
</div>

<script>
        const togglePassword = document
            .querySelector('#togglePassword');
        const password = document.querySelector('#password');
        togglePassword.addEventListener('click', () => {
            // Toggle the type attribute using
            // getAttribure() method
            const type = password
                .getAttribute('type') === 'password' ?
                'text' : 'password';
            password.setAttribute('type', type);
            // Toggle the eye and bi-eye icon
            this.classList.toggle('bi-eye');
        });
    </script>
<?php

include("include/footer.php");

?>
