
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" type="text/css">

        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>
  		
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
                    <div class="col-lg-10  col-lg-offset-1">
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
	                 			<form class="" method="POST" action="<?php echo $from_action; ?>" enctype="multipart/form-data" role="form"  data-parsley-validate> 
	                  				<div class="box-body"> 
	                  					<div class="form-group">
											<label for="noficiation" >Select Users</label><br>
		                  					 <select data-parsley-required="" data-parsley-required-message="Please select users."  id="option-droup-demo" name="users[]" multiple="multiple">
										       	<optgroup label="Doctors">
										  		<?php 
										  		if(!empty($dr_list)){
										  			foreach ($dr_list as $key => $list) {
										  				 echo '<option value="'.$list['user_id'].'">'.$list['full_name'].' ('.$list['email'].')'.'</option>';
										  			}
										  		}
										  		?>
										        </optgroup>
										        <optgroup label="Patient">
										       	<?php 
											  		if(!empty($patient_list)){
											  			foreach ($patient_list as $key => $list) {
											  				 echo '<option value="'.$list['user_id'].'">'.$list['full_name'].' ('.$list['email'].')'.'</option>';
											  			}
											  		}
										  		?>
										        </optgroup>
										    </select>
										</div>
										<div class="form-group">
                                            <label for="title">Notification *</label>
                                            <input type="text" class="form-control" name="title" id="title" value="" placeholder="Title" maxlength="150" data-parsley-required="" data-parsley-required-message="Please enter notification.">
                                        </div>
                                        <div class="form-group"> 
			                    			<label class="control-label">Image *</label>
			                    			<div class="controls">
						                        <div data-provides="fileupload" class="fileupload fileupload-new"><input type="hidden">
			                                        <div style="width: 150px; height: 120px;" class="fileupload-new thumbnail">
			                                            <!-- <img alt="No Image" src=""> -->
			                                        </div>
			                                        <div style="max-width: 150px; line-height: 5px;" class="fileupload-preview fileupload-exists thumbnail"></div>
			                                        <div>
			                                            <span class="btn btn-file"><span class="fileupload-new btn btn-default">Select image</span>
			                                            <span class="fileupload-exists">Change</span>
			                                            <input type="file" name="image" class="default" accept="image/*" data-parsley-required data-parsley-required-message="Please upload image." data-parsley-errors-container='#image_error'></span>
			                                            <a data-dismiss="fileupload" class="btn fileupload-exists error v-align-middle" href="#">Remove</a>
			                                        </div>
			                                        <div id="image_error" class="error"></div>
			                                        <?php echo isset($upload_error)?$upload_error:'';?>
			                                    </div> 
				                          </div>
							  			</div>
										<div class="form-group">
											<label for="noficiation" >Detail</label>
											<textarea   id="ckeditor" data-parsley-required-message="Please enter notification." name="detail" placeholder="Notification" class="form-control" rows="3"><?php echo set_value('noficiation'); ?></textarea>
											<?php echo form_error('noficiation');?>
										</div>
										
					               
										
              						</div><!-- /.box-body -->
									<div class="box-footer text-center">
										<button type="submit" class="btn btn-primary">Send</button>
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

<!-- CK Editor -->
 <script type="text/javascript">
        $(document).ready(function() {
            $('#option-droup-demo').multiselect({
            enableClickableOptGroups: true	,
            enableCollapsibleOptGroups: true
        });
        });
    </script>
    <!-- CK Editor -->
<script src="<?php echo base_url(); ?>assets/admin/js/ckeditor/ckeditor.js"></script>
<script>
    $(function () {
        // Replace the <textarea id="ckeditor"> with a CKEditor
        CKEDITOR.replace('ckeditor');
    });
</script>
