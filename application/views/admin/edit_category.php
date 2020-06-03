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
	                <form method="POST" action="<?php echo $from_action; ?>" role="form"  enctype="multipart/form-data"  data-parsley-validate>
	                <?php } ?>
	                  	<div class="box-body">
	                  		<div class="box-body">
			                    <div class="form-group">
									<label for="name">Name *</label>
									  	<input type="text" class="form-control" name="name" id="title" value="<?php echo !set_value('name') ? $category['name'] : set_value('name'); ?>" placeholder="Name" maxlength="50"   data-parsley-required data-parsley-required-message="Please enter name.">
										<?php echo form_error('name');?>
								</div>
								<!--  <div class="form-group">
                                            <label class="">Image *</label>
                                            <!-- <div class="col-sm-4"> --
                                                <div data-provides="fileupload" class="fileupload fileupload-new"><input type="hidden">
                                                    <div style="width: 150px; height: 120px;" class="fileupload-new thumbnail">
                                                        <img alt="No Image" src="<?php echo $category['image'];?>">
                                                    </div>
                                                    <div style="max-width: 150px; line-height: 5px;" class="fileupload-preview fileupload-exists thumbnail"></div>
                                                    <div>
                                                        <span class="btn btn-file"><span class="fileupload-new btn btn-default">Select image</span>
                                                        <span class="fileupload-exists">Change</span>
                                                        <input type="file" name="image" class="default" accept="image/*"  data-parsley-errors-container='#image_error'></span>
                                                        <a data-dismiss="fileupload" class="btn fileupload-exists error v-align-middle" href="#">Remove</a>
                                                    </div>
                                                    <div id="image_error" class="error"></div>
                                                    <?php echo isset($upload_error)?$upload_error:'';?>
                                                </div>
                                            <!-- </div>
                                        </div> -->
								
			                    <div class="form-group">
									<label for="status">Status *</label>
										<?php
										$status=!set_value('status') ? $category['status'] : set_value('status'); 
										$options_status = array('Active'  => 'Active','Inactive'  => 'Inactive');
										echo form_dropdown('status', $options_status, $status,'class="form-control"');
										?>
									<?php echo form_error('status');?>
							  	</div>
							</div><!-- /.box-body -->
	                  	</div><!-- /.box-body -->
						<div class="box-footer text-center">
							<?php if(isset($from_action) && !empty($from_action)){ ?>
							<button type="submit" class="btn btn-primary">Update</button>
							<?php } ?>
							<a href="<?php echo $back_action;?>" class="btn btn-default">Back</a>
						</div>
	                </form>
            	</div><!-- /.box -->
			</div><!-- col-12-->
		<!-- 	<div class="col-md-6">
				<div class="box box-primary">
					<div class="box-body">
               			<form class="form-horizontal">
               				<div class="col-md-6 text-center">
	                			<input type="hidden" id="category_id" name="category_id" value="<?php echo $category['category_id']; ?>" />
	                			<div class="control-group">
	                    			<label class="control-label">Image *</label>
	                    			<div class="controls">
				                        <div data-provides="fileupload" class="fileupload fileupload-new">
				                            <div  class="fileupload-new thumbnail">
				                                <img alt="No Image"src="<?php echo !empty($category['image']) ? base_url().$category['image']:'';?>" >
				                            </div>
				                            <div style="max-width: 100px; max-height: 100px; line-height: 5px;" class="fileupload-preview fileupload-exists thumbnail"></div>
				                            <div>
				                            	<?php if(isset($from_action) && !empty($from_action)){ ?>
				                                <span class="btn btn-file"><span class="fileupload-new btn btn-default">Change image</span>
				                                <span class="fileupload-exists">Change</span>
				                                <input type="file" name="image" id="image" class="default" accept="image/*"></span>
				                                <a data-dismiss="fileupload" class="btn fileupload-exists" href="#">Remove</a>
				                                <?php } ?>
				                            </div>
				                        </div>
	                    			</div>
				                </div>
			                </div>
			        <!--         <div class="col-md-6">
			                	 <div class="form-group"></div>
			                	 <div class="form-group"></div>
				                <div class="form-group">
				                    <div class="col-md-4">
				                     <button type="button" id="img_upload_button" class="btn btn-info upload_category_image">Upload</button>
				                    </div>
				                </div>
	                			<div class="form-group" >
									<div class="col-sm-8">
										<div class="progress">
										<div class="progress-bar" role="progressbar" id="progressBar_image" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
										</div>
									</div>
	                  				<div class="col-sm-12" id="status_image"></div>
								</div>
							</div> -->
                		</form>
              
            		</div><!-- panel body-->
				</div>
			</div><!-- col-12--> -->
		</div><!-- row-->
	</section>
</div><!-- row-->
