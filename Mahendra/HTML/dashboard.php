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
<?php include 'breadcrumb.php';?>

<section class="dashboard-section py-3">
  <div class="container">
    <div class="search-section">
        <input type="text" placeholder="Design UX" class="search-input">
        <input type="text" placeholder="City,State or Pincode" class="location-input">
        <a href="#" class="find-job-button buttonH">Find Jobs</a>
        <div class="invoice buttonH">
            <span>Post a Job</span>
            <span><i class="fa fa-paper-plane-o" aria-hidden="true"></i></span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <div class="celender">
                <b>Celender</b>
            </div>
            <div class="invoice">
                <span>View Invoice</span>
                <span><i class="fa fa-id-card" aria-hidden="true"></i></span>
            </div>
        </div>
        <div class="col-md-10">
            <div class="tab-container">
                <div role="tabpanel">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-tabs-dropdown mb-2 d-flex" role="tablist">
                        <li role="presentation" class="active"><a href="#current-bobs" aria-controls="current-bobs" role="tab" data-toggle="tab">Current Jobs</a></li>
                        <li role="presentation"><a href="#upcoming-jobs" aria-controls="upcoming-jobs" role="tab" data-toggle="tab">Upcoming Jobs</a></li>
                        <li role="presentation" class="tab-li"><a href="#past-jobs" aria-controls="past-jobs" role="tab" data-toggle="tab">Past Jobs</a></li>
                        <li class="filter">
                            <span class="filter-label color3">1-9 pages</span>
                            <div class="dropdown">
                                <button class="btn dropdown-toggle color3" type="button" data-toggle="dropdown">Sort by : Relevance
                                <span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                  <li><a href="#">Option 1</a></li>
                                  <li><a href="#">Option 2</a></li>
                                  <li><a href="#">Option 3t</a></li>
                                </ul>
                              </div>
                        </li> 
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="current-bobs">
                            <div class="dlist-box-container">
                                <div class="dsingle-list-box my-2">
                                    <a href="javascript:void(0)">
                                        <div class="statusMob">
                                            <h5>3 days ago</h5>
                                            <p class="price color2 mobile">30,000 - 40,000 a month</p>
                                        </div>
                                        <div class="block1">
                                            <h4 class="color1">UI UX Designer Want for Product Design</h4>
                                            <p class="price color2 desktop">30,000 - 40,000 a month</p>
                                        </div>
                                        <div class="block2">
                                            <ul class="price-list">
                                                <li><i class="fa fa-calendar" aria-hidden="true"></i> Start Date May 22, 2020 - <span class="color2">End</span>  June 22, 2020</li>
                                            </ul>
                                            <ul class="option-list desktop">
                                                <li><a href="javascript:void(0)" class="clam-button status ">Clam Now</a></li>
                                            </ul>
                                        </div>
                                        <p class="address color3"><i class="fa fa-map-marker" aria-hidden="true"></i> 1st Block 1st Cross, Rammurthy nagar, Bangalore-560016</p>
                                        <p class="description color3">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vel placerat eros, ac auctor ipsum. Nulla eu semper felis, quis malesuada tortor. Ut cursus eros ac lacinia aliquet.</p>
                                        <div class="clamButtonb-container mobile">
                                            <a href="javascript:void(0)" class="clam-button status ">Clam Now</a>
                                        </div>
                                    </a>
                                </div>
                                <div class="dsingle-list-box my-2">
                                    <a href="javascript:void(0)">
                                        <div class="statusMob">
                                            <h5>3 days ago</h5>
                                            <p class="price color2 mobile">30,000 - 40,000 a month</p>
                                        </div>
                                        <div class="block1">
                                            <h4 class="color1">UI UX Designer Want for Product Design</h4>
                                            <p class="price color2 desktop">30,000 - 40,000 a month</p>
                                        </div>
                                        <div class="block2">
                                            <ul class="price-list">
                                                <li><i class="fa fa-calendar" aria-hidden="true"></i> Start Date May 22, 2020 - <span class="color2">End</span>  June 22, 2020</li>
                                            </ul>
                                            <ul class="option-list desktop">
                                                <li><a href="javascript:void(0)" class="clam-button status ">Clam Now</a></li>
                                            </ul>
                                        </div>
                                        <p class="address color3"><i class="fa fa-map-marker" aria-hidden="true"></i> 1st Block 1st Cross, Rammurthy nagar, Bangalore-560016</p>
                                        <p class="description color3">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vel placerat eros, ac auctor ipsum. Nulla eu semper felis, quis malesuada tortor. Ut cursus eros ac lacinia aliquet.</p>
                                        <div class="clamButtonb-container mobile">
                                            <a href="javascript:void(0)" class="clam-button status ">Clam Now</a>
                                        </div>
                                    </a>
                                </div>
                                <div class="dsingle-list-box my-2">
                                    <a href="javascript:void(0)">
                                        <div class="statusMob">
                                            <h5>3 days ago</h5>
                                            <p class="price color2 mobile">30,000 - 40,000 a month</p>
                                        </div>
                                        <div class="block1">
                                            <h4 class="color1">UI UX Designer Want for Product Design</h4>
                                            <p class="price color2 desktop">30,000 - 40,000 a month</p>
                                        </div>
                                        <div class="block2">
                                            <ul class="price-list">
                                                <li><i class="fa fa-calendar" aria-hidden="true"></i> Start Date May 22, 2020 - <span class="color2">End</span>  June 22, 2020</li>
                                            </ul>
                                            <ul class="option-list desktop">
                                                <li><a href="javascript:void(0)" class="clam-button status ">Clam Now</a></li>
                                            </ul>
                                        </div>
                                        <p class="address color3"><i class="fa fa-map-marker" aria-hidden="true"></i> 1st Block 1st Cross, Rammurthy nagar, Bangalore-560016</p>
                                        <p class="description color3">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vel placerat eros, ac auctor ipsum. Nulla eu semper felis, quis malesuada tortor. Ut cursus eros ac lacinia aliquet.</p>
                                        <div class="clamButtonb-container mobile">
                                            <a href="javascript:void(0)" class="clam-button status ">Clam Now</a>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="upcoming-jobs">
                            <div class="dsingle-list-box my-2">
                                <a href="javascript:void(0)">
                                    <div class="statusMob">
                                        <h5>3 days ago</h5>
                                        <p class="price color2 mobile">30,000 - 40,000 a month</p>
                                    </div>
                                    <div class="block1">
                                        <h4 class="color1">UI UX Designer Want for Product Design</h4>
                                        <p class="price color2 desktop">30,000 - 40,000 a month</p>
                                    </div>
                                    <div class="block2">
                                        <ul class="price-list">
                                            <li><i class="fa fa-calendar" aria-hidden="true"></i> Start Date May 22, 2020 - <span class="color2">End</span>  June 22, 2020</li>
                                        </ul>
                                        <ul class="option-list desktop">
                                            <li><a href="javascript:void(0)" class="clam-button status ">Clam Now</a></li>
                                        </ul>
                                    </div>
                                    <p class="address color3"><i class="fa fa-map-marker" aria-hidden="true"></i> 1st Block 1st Cross, Rammurthy nagar, Bangalore-560016</p>
                                    <p class="description color3">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vel placerat eros, ac auctor ipsum. Nulla eu semper felis, quis malesuada tortor. Ut cursus eros ac lacinia aliquet.</p>
                                    <div class="clamButtonb-container mobile">
                                        <a href="javascript:void(0)" class="clam-button status ">Clam Now</a>
                                    </div>
                                </a>
                            </div>
                            <div class="dsingle-list-box my-2">
                                <a href="javascript:void(0)">
                                    <div class="statusMob">
                                        <h5>3 days ago</h5>
                                        <p class="price color2 mobile">30,000 - 40,000 a month</p>
                                    </div>
                                    <div class="block1">
                                        <h4 class="color1">UI UX Designer Want for Product Design</h4>
                                        <p class="price color2 desktop">30,000 - 40,000 a month</p>
                                    </div>
                                    <div class="block2">
                                        <ul class="price-list">
                                            <li><i class="fa fa-calendar" aria-hidden="true"></i> Start Date May 22, 2020 - <span class="color2">End</span>  June 22, 2020</li>
                                        </ul>
                                        <ul class="option-list desktop">
                                            <li><a href="javascript:void(0)" class="clam-button status ">Clam Now</a></li>
                                        </ul>
                                    </div>
                                    <p class="address color3"><i class="fa fa-map-marker" aria-hidden="true"></i> 1st Block 1st Cross, Rammurthy nagar, Bangalore-560016</p>
                                    <p class="description color3">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vel placerat eros, ac auctor ipsum. Nulla eu semper felis, quis malesuada tortor. Ut cursus eros ac lacinia aliquet.</p>
                                    <div class="clamButtonb-container mobile">
                                        <a href="javascript:void(0)" class="clam-button status ">Clam Now</a>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="past-jobs">
                            <div class="dsingle-list-box my-2">
                                <a href="javascript:void(0)">
                                    <div class="statusMob">
                                        <h5>3 days ago</h5>
                                        <p class="price color2 mobile">30,000 - 40,000 a month</p>
                                    </div>
                                    <div class="block1">
                                        <h4 class="color1">UI UX Designer Want for Product Design</h4>
                                        <p class="price color2 desktop">30,000 - 40,000 a month</p>
                                    </div>
                                    <div class="block2">
                                        <ul class="price-list">
                                            <li><i class="fa fa-calendar" aria-hidden="true"></i> Start Date May 22, 2020 - <span class="color2">End</span>  June 22, 2020</li>
                                        </ul>
                                        <ul class="option-list desktop">
                                            <li><a href="javascript:void(0)" class="clam-button status ">Clam Now</a></li>
                                        </ul>
                                    </div>
                                    <p class="address color3"><i class="fa fa-map-marker" aria-hidden="true"></i> 1st Block 1st Cross, Rammurthy nagar, Bangalore-560016</p>
                                    <p class="description color3">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vel placerat eros, ac auctor ipsum. Nulla eu semper felis, quis malesuada tortor. Ut cursus eros ac lacinia aliquet.</p>
                                    <div class="clamButtonb-container mobile">
                                        <a href="javascript:void(0)" class="clam-button status ">Clam Now</a>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</section>
<?php include 'footer2.php';?>
</body>
</html>
