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
                            	<?php if(isset($from_action) && !empty($from_action)){ ?>
	                 			<form class="" method="POST" action="<?php echo $from_action; ?>" enctype="multipart/form-data" role="form"  data-parsley-validate>
	                  			<?php } ?>
	                  				<div class="box-body">
				                  		<div class="form-group">
											<label for="title">Title *</label>
										  	<input type="text" class="form-control" name="title" id="title" value="<?php echo set_value('title'); ?>" placeholder="Title" maxlength="150" data-parsley-required data-parsley-required-message="Please enter title.">
											<?php echo form_error('name');?>
										</div>
										<div class="form-group">
											<label for="content" >Content *</label>
											<textarea name="content" placeholder="Content Here" class="form-control" id="ckeditor" rows="3" ><?php echo set_value('content'); ?></textarea>
											<?php echo form_error('content');?>
										</div>

										<div class="form-group">
											<label for="seo_title">Seo Title</label>
										  	<input type="text" class="form-control" name="seo_title" id="seo_title" value="<?php echo set_value('seo_title'); ?>" placeholder="Seo Title" maxlength="150">
											<?php echo form_error('seo_title');?>
										</div>
										
										<div class="form-group">
											<label for="seo_description" >Seo Description</label>
											<textarea name="seo_description" placeholder="Seo Description" class="form-control" rows="3"><?php echo set_value('seo_description'); ?></textarea>
											<?php echo form_error('seo_description');?>
										</div>
										
										<div class="form-group">
											<label for="seo_keywords" >Seo Keyword</label>
											<textarea name="seo_keywords" placeholder="Seo Keyword" class="form-control" rows="3"><?php echo set_value('seo_keywords'); ?></textarea>
											<?php echo form_error('seo_keywords');?>
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

<!-- CK Editor -->
<script src="<?php echo base_url(); ?>assets/admin/js/ckeditor/ckeditor.js"></script>
<script>
    $(function () {
        // Replace the <textarea id="ckeditor"> with a CKEditor
        CKEDITOR.replace('ckeditor');
    });
</script>
