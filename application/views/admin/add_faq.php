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
										  	<input type="text" class="form-control" name="question" id="question" value="<?php echo set_value('question'); ?>" placeholder="Title" maxlength="150" data-parsley-required data-parsley-required-message="Please enter title.">
											<?php echo form_error('question');?>
										</div>
										<div class="form-group">
											<label for="content" >Description *</label>
											<textarea name="answer" placeholder="Answer Here" id="ckeditor" class="form-control"  rows="3" ><?php echo set_value('answer'); ?></textarea>
											<?php echo form_error('answer');?>
										</div>
									
	                                		
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
