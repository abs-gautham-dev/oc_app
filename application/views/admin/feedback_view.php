<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1> <?php echo $page_title;?> </h1>
	  	<ol class="breadcrumb">
			<?php foreach ($breadcrumbs as  $breadcrumb) { ?>
				<li class="<?php echo $breadcrumb['class'];?>"> 
					<?php if(!empty($breadcrumb['link'])) { ?>
						<a href="<?php echo $breadcrumb['link'];?>"><?php echo $breadcrumb['icon'].$breadcrumb['title'];?></a>
					<?php } else {
						echo $breadcrumb['icon'].$breadcrumb['title'];
					} ?>
				</li>
			<?php }?>
	  	</ol>
	</section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
            <div class="col-md-6">
              <!-- general form elements -->
              	<div class="box box-primary">
	                <div class="box-header">
	                  <!-- <h3 class="box-title">Example</h3> -->
	                  <?php if ($this->session->flashdata('success')) { ?>
	                	<div class="alert alert-success fade in">
	                        <button data-dismiss="alert" class="close" type="button">×</button>
	                      	<p><?php echo $this->session->flashdata('success') ?></p>
	                  	</div>
	                  	<?php } ?>   
	                    <?php if ($this->session->flashdata('error')) { ?>
	                    <div class="alert alert-error fade in">
	                        <button data-dismiss="alert" class="close" type="button">×</button>
	                        <p><?php echo $this->session->flashdata('error') ?></p>
	                  	</div>
	                    <?php } ?>
	                </div>
	                <!-- /.box-header -->
	                <!-- form start -->
	                <?php if(isset($from_action) && !empty($from_action)){ ?>
	                <form method="POST" action="<?php echo $from_action; ?>" onsubmit="return fromsubmit()" role="form"  data-parsley-validate>
	                <?php } ?>
                        <div class="box-body">
	                  		<div class="box-body">
	                  		  
                                <div class="form-group">
                                    <label>1. Have you been answered adequately</label><br>
                                    <label>Answer:</label> <?php echo $records_result['question1'];   ?>
                                </div> 
                                <div class="form-group">
                                    <label>2. Did doctor provide you with full information about medical conditions and how to deal with medication</label><br>
                                    <label>Answer:</label> <?php echo $records_result['question2'];   ?>
                                </div> 
                                <div class="form-group">
                                    <label>3. Level of cleanliness of clinics and establishment in general</label><br>
                                    <label>Answer:</label> <?php echo $records_result['question3'];   ?>
                                </div>
                                <div class="form-group">
                                    <label>4. Level of waiting pleases for patients in terms of comfort and trancquility</label><br>
                                    <label>Answer:</label> <?php echo $records_result['question4'];   ?>
                                </div> 
                                <div class="form-group">
                                    <label>5. In case of inquiry about the use of certain devices or medications, would you like to use this application with your direct doctor</label><br>
                                    <label>Answer:</label> <?php echo $records_result['question5'];   ?>
                                </div> 
                                <hr>
                                <label>We hope that you will kindly evaluate our clinics after your generous visit to avoid any mistakes in the future </label>&nbsp;&nbsp;

                                <div class="form-group">
                                    <label>1. Receptions</label><br>
                                    <label>Answer:</label> <?php echo $records_result['question6'];   ?>
                                </div> 
                                <div class="form-group">
                                    <label>2. The level of the doctor ‘s dealing with you</label><br>
                                    <label>Answer:</label> <?php echo $records_result['question7'];   ?>
                                </div> 
                                <div class="form-group">
                                    <label>3. Level of reception staff and nurses</label><br>
                                    <label>Answer:</label> <?php echo $records_result['question8'];   ?>
                                </div> 
                                <div class="form-group">
                                    <label>4. Do you recommend that acquaintance deal with our clinics</label><br>
                                    <label>Answer:</label> <?php echo $records_result['question9'];   ?>
                                </div>  								
								
	                  	</div><!-- /.box-body -->
						<div class="box-footer text-center">
						 
							<a href="<?php echo base_url().'admin/user/feedback/'.$records_result['dr_id'];?>" class="btn btn-default">Back</a>
						</div>
	                </form>
            	</div><!-- /.box -->
			</div><!-- col-12-->
 
		</div><!-- row-->
	</section>
</div><!-- row-->

