<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="./assets/css/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<?php include 'header.php';?>
<div class="bredcum">
  <div class="container">
    <div class="col-md-6">
      <ul class="breadcrumb">
        <li><a href="#" class="page-name">Login</a></li>
      </ul>
    </div>
  <div class="col-md-6">
    <ul class="breadcrumb">
    <li><a href="#">Home</a></li>
    <li><a href="#">Login</a></li>
    </ul>
  </div>
  </div>
</div>
<section class="main-section">
  <div class="container">
    
      <div class="col-xs-12 col-md-6">
      <div class="login-page">
        <h3> Login as Company </h3>
        <small> You are just a step away from your dream job. </small>
        <form>
        <input type="email" placeholder="Enter Email">
        <input type="password" placeholder="Enter Password">
          <a href="#"> Forgot Password </a>
          <button class="submit"> Login </button>
          <button class="submit-signbup"> New to Office Checkers? Sign Up </button>
        </form>
      </div>
      </div>
      <div class="col-xs-12 col-md-6">
      <div class="card-login">
        <h3> Are You Looking for Someone to Hire? </h3>
        <a href="#" class="cell-phone card"><i class="fa fa-phone"></i> +447774018889  </a>
        <button class="submit card-btn"> Post a Job Now </button>
      </div>
      </div>
    
  </div>
</section>
<?php include 'footer.php';?>
</body>
</html>
