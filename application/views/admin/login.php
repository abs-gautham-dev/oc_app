<?php 
error_reporting(0);
?>
<style type="text/css">
 
body {
    min-height: 0 !important;
}
</style>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $title; ?></title>
        <base href="<?php base_url(); ?>">
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="icon" type="image/x-icon" href="<?php echo base_url();?>resources/ItunesArtwork.png" >  
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/bootstrap/css/bootstrap.min.css">
    	
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/bootstrap/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/bootstrap/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/dist/css/AdminLTE.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/plugins/iCheck/square/blue.css">
    </head>

    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">  
    		<a href="<?php echo base_url(); ?>">
    			<!-- <img src="<?php echo base_url();?>resources/ItunesArtwork1.png"    style=" border-radius: 20px;" width="100px"> -->
    		</a>
      </div>
      <?php if ($this->session->flashdata('error')) { ?>
        <div class="alert alert-block alert-danger fade in">
            <button data-dismiss="alert" class="close" type="button">×</button>
            <?php echo $this->session->flashdata('error') ?>
        </div>
      <?php } ?>
      <?php if ($this->session->flashdata('success')) { ?>
        <div class="alert alert-block alert-success fade in">
            <button data-dismiss="alert" class="close" type="button">×</button>
            <?php echo $this->session->flashdata('success') ?>
        </div>
      <?php } ?>
	  <!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg"><b>Admin Login</b></p>
       
		<form role="form" action="<?php echo base_url(); ?>admin/login" method="post">
          <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="Username" name="username"  autofocus value="<?php echo set_value('username'); ?>">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Password" name="password" value="<?php echo set_value('password'); ?>">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">
              <!--<div class="checkbox icheck">
                <label>
                  <input type="checkbox"> Remember Me
                </label>
              </div> -->
            </div><!-- /.col -->
              <a href="<?php echo site_url().'admin/forgot_password';?>">Forgot Password</a>
            <div class="col-xs-4">
			
            
              <button type="submit" name="login" value="Login" class="btn btn-primary btn-block btn-flat" >Sign In</button>
            </div><!-- /.col -->
          </div>
        </form>

       <!--  <a href="#">I forgot my password</a><br>
        <a href="register.html" class="text-center">Register a new membership</a> -->
		
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    <script src="<?php echo base_url(); ?>assets/admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="<?php echo base_url(); ?>assets/admin/bootstrap/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="<?php echo base_url(); ?>assets/admin/plugins/iCheck/icheck.min.js"></script>
    
	<script>
   
    </script>
  </body>

</html>