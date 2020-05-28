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
			<?php } 
			$doctor_id = $this->session->userdata('doctor_id');?>
  		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
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
				<div class="box">
					<div class="box-header with-border">
                  		<h3 class="box-title">Filter Here</h3>
                  		<?php if(empty($doctor_id)){?>
                  			<a href="<?php echo base_url().'admin/jobs/add/'?>" title="" data-toggle="tooltip" data-original-title="Add Job" class="btn btn-default pull-right"><i class="fa fa-plus"></i></a>
                  		<?php }?>
                	</div>
					<div class="box-body table-responsive">
						<table <?php  
								if(!empty($records_result))
								{ ?> id="example1" <?php }?> class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>Sr.No.</th>		
									<th>Title</th>
									<th>Short Description</th>
									<th>Start Date</th>
									<th>Status</th>
									
									<th class="no-sort">Actions</th>
									
								</tr>
							</thead>
							<tbody>
				
								<?php  
								 $dd_id = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
								if(!empty($records_result))
								{	
									$i = 0;
									$table="users";
									$field = "user_id";
									foreach ($records_result as $row) { $i++; 
										if(isset($row['status'])) {
	                                        if($row['status']=="Active") {
	                                            $status = "Active";
	                                            $class = "pointer badge bg-green";
	                                        } else {
	                                            $status = "Inactive";
	                                            $class = "pointer badge bg-red";
	                                        }
                                    	}?>
										<tr id="tr_<?php echo $row[$field]; ?>">
											<td><?php echo $i; ?></td>
											
											
												
											<td><?php if(!empty($row['title'])) echo $row['title']; ?></td>
											<td><?php if(!empty($row['short_desc'])) echo $row['short_desc']; ?></td>
											<td><?php if(!empty($row['start_date'])) echo $row['start_date']; ?></td>
											<?php if(isset($edit_action) && !empty($edit_action)){ ?>
											<td>
												<p id="status_<?php echo $row[$field]; ?>" onclick="change_status('<?php echo $field; ?>','<?php echo $row[$field]; ?>','<?php echo $table; ?>')" class="<?php echo $class; ?>" title="" data-toggle="tooltip" data-original-title="Change Status"><?php echo $status; ?></p>
											</td>
											<?php } ?>
											<td class="td-actions"> 
												<?php if(empty($doctor_id)){ ?>
													<a id="edit_product" href="<?php echo $edit_action.'/'.$row[$field]; ?>" class="btn btn-xs btn-primary edit_product" title="" data-toggle="tooltip" data-original-title="Edit">
													<i class="fa fa-pencil"></i>
													</a>
													<?php if($dd_id==1){ ?>
													
												<?php }?>
												<?php }else{ 
													if($doctor_type==1){
													?> 
													<a id="edit_product" href="<?php echo base_url().'admin/user/appointment/'.$row[$field]; ?>" class="btn btn-xs btn-danger" title="" data-toggle="tooltip" data-original-title="Appointment List">
														<i class="fa fa-stethoscope" aria-hidden="true"></i>
													</a>&nbsp;&nbsp;
													<a id="edit_product" href="<?php echo base_url().'admin/user/feedback/'.$row[$field]; ?>" class="btn btn-xs btn-warning" title="" data-toggle="tooltip" data-original-title="Feedback List">
														<i class="fa fa-envelope-o" aria-hidden="true"></i>
													</a>&nbsp;&nbsp;

												<?php }else{ ?>

													<a id="edit_product" onclick="assign_dr('<?php echo $row[$field] ?>')"  href="javascript:void(0)" class="btn btn-xs btn-warning" title="" data-toggle="tooltip" data-original-title="Assign patient to your doctor">
														<i class="fa fa-plus" aria-hidden="true"></i>
													</a>&nbsp; 

												<?php	} 
												} ?>
											 
											</td>	
										</tr>

									<?php }
								} else {
									echo "<tr><td colspan='7' align='center'> No Record Found</td></tr>";
								} ?>
							</tbody>
						</table>
							<!-- <div class="box-body"><?php echo $pagination; ?></div> -->
					</div>			
				</div>			
			</div>
		</div>
	</section><!-- /.content -->
</div><!-- /.content-wrapper -->
<script type="text/javascript">
	function assign_dr(dr_id){
		if (confirm("Are you sure want to add ?")) {
		  window.location.href='<?php echo base_url().'admin/User/add_doctor/'; ?>'+dr_id;
		} else {
		  txt = "You pressed Cancel!";
		}
	}


</script>>