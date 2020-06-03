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
               <div class="col-md-2">
               </div>
            <div class="col-md-8">
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
	                <form method="POST" action="<?php echo $from_action; ?>" role="form"  data-parsley-validate>
	                <?php } ?>
	                  	<div class="box-body">
	                  		<div class="box-body">
	                  		<span style=" color: red;" class="danger">*Please DO NOT remove text which are placed between {{text}}. </span>
			                    <div class="form-group">
									<label for="name">Title </label>
									  	<input type="text" class="form-control" name="title" id="title" value="<?php echo $templete['title'];?>" placeholder="Title"  data-parsley-required data-parsley-required-message="Please enter title.">
										 
								</div>
								 <div class="form-group">
									<label for="name">Subject </label>
									  	<input type="text"  class="form-control" name="subject" id="Subject" value="<?php echo $templete['subject'];?>" placeholder="Subject"  data-parsley-required data-parsley-required-message="Please enter Subject.">
										 
								</div>
								<input type="hidden" id="temp_id" name="temp_id" value="<?php echo $templete['id'];?>">
			                    <div class="form-group">
										
			                    <textarea id="ckeditor" name="message"><?php echo $templete['message'];?></textarea>
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
		
		</div><!-- row-->
	</section>
</div><!-- row-->
<script src="<?php echo base_url(); ?>assets/admin/js/ckeditor/ckeditor.js"></script>
<script>
    $(function () {
        // Replace the <textarea id="ckeditor"> with a CKEditor
        CKEDITOR.replace('ckeditor');
    });
</script>
