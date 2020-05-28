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
        <div class="box box-primary">
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-6  col-lg-offset-3">
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
                        <div class="panel panel-primary">
                            <div class="panel-body">
                            	<?php if(isset($from_action) && !empty($from_action)){ ?>
	                 			<form class="" method="POST" action="<?php echo $from_action; ?>" enctype="multipart/form-data" role="form"  data-parsley-validate>
	                  			<?php } ?>
	                  				<div class="box-body">
				                  		<div class="form-group">
											<label for="name">Document Name *</label>
											  	<input type="text" class="form-control" name="name" id="title" value="<?php echo  $doc_name; ?>" placeholder="Document Name" maxlength="50" data-parsley-required data-parsley-required-message="Please Enter Document Name.">
												<?php echo form_error('name');?>
												<input type="hidden"  name="user_id"  value="<?php echo $user_id;?>">
												<input type="hidden"  name="page_id"  value="<?php echo $page_id;?>">
										</div>
										<div class="form-group">
			                                <label class="">Document Files *</label>
			                             
			                                      
			                                            <span class="btn btn-file"><span class="fileupload-new btn btn-default">Select Document</span>
			                                           
			                                            <input type="file" multiple name="doc[]" class="default" accept="" data-parsley-required data-parsley-required-message="Please Upload Document." data-parsley-errors-container='#image_error'></span>
			                                        
			                                        </div>
			                                        <div id="image_error" class="error"></div>
			                                        <?php echo isset($upload_error)?$upload_error:'';?>
			                                    </div>
			                                <!-- </div> -->
			                            </div>
              						</div><!-- /.box-body -->
									<div class="box-footer text-center">
										<?php if(isset($from_action) && !empty($from_action)){ ?>
										<button type="submit" class="btn btn-primary">Add</button>
										<?php } ?>
										<a href="<?php echo $back_action;?>" class="btn btn-default">Back</a>
									</div>
								</form>
		               		</div>
		            	</div>
	                </div>
            	</div><!-- /.box -->
			</div><!-- col-12-->
		</div><!-- row-->
	</section>
</div><!-- row-->
