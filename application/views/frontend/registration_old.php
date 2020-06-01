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
        <li><a href="#" class="page-name">Registration</a></li>
      </ul>
    </div>
  <div class="col-md-6">
    <ul class="breadcrumb">
    <li><a href="#">Home</a></li>
    <li><a href="#">Registration</a></li>
    </ul>
  </div>
  </div>
</div>
<section class="main-section">
  <div class="container">
    
      <div class="col-xs-12 col-md-12">
      <div class="login-page registration">
      <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home">Inspector</a></li>
    <li><a data-toggle="tab" href="#menu1">Company</a></li>
    <li><a data-toggle="tab" href="#menu2">Sales</a></li>
    <li class="pull-right up-res">Already registered ? <a  href=""> Login Now</a></li>
  </ul>

  <div class="tab-content">
    <div id="home" class="tab-pane fade in active">
      <form>
      <div class="row">
        <div class="col-md-4">
        <input type="text" placeholder="Company Name">
        </div>
        <div class="col-md-4">
        <input type="email" placeholder="Official Email">
        </div>
        <div class="col-md-4">
        <input type="password" placeholder="Password">
        </div>
        <div class="col-md-4">
        <input type="number" placeholder="Mobile Number">
        </div>
        <div class="col-md-4">
        <input type="text" placeholder="Current Location">
        </div>
        <div class="col-md-4">
        <input type="text" placeholder="Pin Code">
        </div>
        <div class="col-md-8">
        <input type="file" placeholder="Upload Resume (*.doc, *.docx, *.rtf, *.txt, *.pdf) Max. 6 MB">
        <span> <i class="fa fa-upload" aria-hidden="true"></i> Choose File </span>
        </div>
        <div class="col-md-4">
        <button class="submit"> Sign Up </button>
        </div>
      </div>

</form>
    </div>
    <div id="menu1" class="tab-pane fade">
    <form>
      <div class="row">
        <div class="col-md-4">
        <input type="text" placeholder="Company Name">
        </div>
        <div class="col-md-4">
        <input type="email" placeholder="Official Email">
        </div>
        <div class="col-md-4">
        <input type="password" placeholder="Password">
        </div>
        <div class="col-md-4">
        <input type="number" placeholder="Mobile Number">
        </div>
        <div class="col-md-4">
        <input type="text" placeholder="Current Location">
        </div>
        <div class="col-md-4">
        <input type="text" placeholder="Pin Code">
        </div>
        <div class="col-md-8">
        <input type="file" placeholder="Upload Resume (*.doc, *.docx, *.rtf, *.txt, *.pdf) Max. 6 MB">
        <span> <i class="fa fa-upload" aria-hidden="true"></i> Choose File </span>
        </div>
        <div class="col-md-4">
        <button class="submit"> Sign Up </button>
        </div>
      </div>

</form>
    </div>
    <div id="menu2" class="tab-pane fade">
    <form>
      <div class="row">
        <div class="col-md-4">
        <input type="text" placeholder="Company Name">
        </div>
        <div class="col-md-4">
        <input type="email" placeholder="Official Email">
        </div>
        <div class="col-md-4">
        <input type="password" placeholder="Password">
        </div>
        <div class="col-md-4">
        <input type="number" placeholder="Mobile Number">
        </div>
        <div class="col-md-4">
        <input type="text" placeholder="Current Location">
        </div>
        <div class="col-md-4">
        <input type="text" placeholder="Pin Code">
        </div>
        <div class="col-md-8">
        <input type="file" placeholder="Upload Resume (*.doc, *.docx, *.rtf, *.txt, *.pdf) Max. 6 MB">
        <span> <i class="fa fa-upload" aria-hidden="true"></i> Choose File </span>
        </div>
        <div class="col-md-4">
        <button class="submit"> Sign Up </button>
        </div>
      </div>

</form>
    </div>
  </div>


      </div>
      </div>
  </div>
</section>
<section class="above-footer">
  <div class="container">
    <h3> Are You Looking for Someone to Hire? </h3>
    <p> 24000 companies have found the employer that they were Searching for. Post your job advert now and reach to Millions of job seekers fast and easy. </p>
    <button class="submit card-btn"> Post a Job Now </button> <a href="#" class="cell-phone card"><i class="fa fa-phone"></i> +447774018889  </a>
  </div>
</section>
<?php include 'footer.php';?>
</body>
</html>
