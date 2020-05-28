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
	                <form method="POST" action="<?php echo $from_action; ?>" role="form"  data-parsley-validate>
	                <?php } ?>
	                  	<div class="box-body">
	                  		<div class="box-body">
	                  		<?php if(empty($subscription)){$subscription['amount']='';$subscription['points']='';} ?>

			                    <div class="form-group">
									<label for="name">Amount *</label>
									  	<input type="number" max="999" min="1"  class="form-control"  name="amount" id="title" value="<?php echo !set_value('amount') ? $subscription['amount']: set_value('amount'); ?>" placeholder="Amount" maxlength="50" data-parsley-required data-parsley-required-message="Please enter amount.">
										<?php echo form_error('amount');?>
								</div>

								

								<div class="form-group">
									<label for="name">Points *</label>
									  	<input type="number"max="9999" min="1" class="form-control" name="points" id="title" value="<?php echo !set_value('points') ? $subscription['points'] : set_value('points'); ?>" placeholder="Points" maxlength="50" data-parsley-required data-parsley-required-message="Please enter points.">
										<?php echo form_error('points');?>
								</div>
								
			                 
							</div><!-- /.box-body -->
	                  	</div><!-- /.box-body -->
						<div class="box-footer text-center">
							<?php if(isset($from_action) && !empty($from_action)){ ?>
							<button type="submit" class="btn btn-primary"><?php if($this->uri->segment(3)=='add'){echo 'Add'; }else { echo 'Update';  }?></button>
							<?php } ?>
							<a href="<?php echo $back_action;?>" class="btn btn-default">Back</a>
						</div>
	                </form>
            	</div><!-- /.box -->
			</div><!-- col-12-->
			
		</div><!-- row-->
	</section>
</div><!-- row-->
