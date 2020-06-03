<!DOCTYPE html>
<html lang="en">
<head>
  <title>Office checkers</title>
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
<?php include 'header2.php';?>
<?php include 'breadcrumb.php';?>

<section class="why-chose-section py-3">
  <div class="container">
    <div class="form-section">
        <form action="/action_page.php">
            <input type="text" placeholder="Post Title">
            <input type="text" placeholder="Location">
            <input type="text" placeholder="Post Code">
            <input type="text" placeholder="Telephone Number">
            <textarea placeholder="Job Description" row="10"></textarea>
            <a href="#" class="find-job-button">Post Job Now</a>
        </form>
    </div>
  </div>
</section>
<?php include 'footer2.php';?>
</body>
</html>