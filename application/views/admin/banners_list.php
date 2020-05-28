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
                  		<?php if(isset($add_action) && !empty($add_action)){ ?>
                  		<!-- <a href="<?php echo $add_action;?>" title="" data-toggle="tooltip" data-original-title="Add Banner" class="btn btn-default pull-right"><i class="fa fa-plus"></i></a> -->
                		<?php } ?>
                	</div>
					<div class="box-body">
						<table id="" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>Sr.No.</th>
								
								
									<th>Type</th>
									<th>Image</th>
									<th>Status</th>
									<?php if(isset($edit_action) && !empty($edit_action)){ ?>
									<th>Actions</th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
				
								<?php 
								if(!empty($records_result))
								{	
									$i = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
									$table="banners";
									$field = "banner_id";
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
										<tr id="tr_<?php echo $row['banner_id']; ?>">
											<td><?php echo $i; ?></td>
										
										
											<td><?php echo $row['type']; ?></td>
											<td><img  src="<?php echo base_url()."/".$row['image']; ?>" width="170px" height="100px" ></td>
											<td>
												<p id="status_<?php echo $row['banner_id']; ?>" onclick="change_status('<?php echo $field; ?>','<?php echo $row['banner_id']; ?>','<?php echo $table; ?>')" class="<?php echo $class; ?>" title="" data-toggle="tooltip" data-original-title="Change Status"><?php echo $status; ?></p>
											</td>
											<?php if(isset($edit_action) && !empty($edit_action)){ ?>
											<td class="td-actions">
												<a id="edit_product" href="<?php echo $edit_action.'/'.$row['banner_id']; ?>" class="btn btn-xs btn-primary edit_product" title="" data-toggle="tooltip" data-original-title="Edit">
													<i class="fa fa-pencil"></i>
												</a>
											<!-- 	<a  href="javascript:void(0)"  class="btn btn-xs btn-danger"  onclick="delete_record('<?php echo $field; ?>','<?php echo $row[$field]; ?>','<?php echo $table; ?>')" data-toggle="tooltip" data-original-title="Delete">
													<i class="fa fa-trash-o"></i>
												</a> -->
											</td>
											<?php } ?>
										</tr>
									<?php }
								} else {
									echo "<tr><td colspan='5' align='center'> No Record Found</td></tr>";
								} ?>
							</tbody>
							<tfoot>						
								<tr>
									<td colspan="5" align="center">
										<div><?php echo $pagination; ?></div>
									</td>
								</tr>
							</tfoot>							
						</table>
					</div>			
				</div>			
			</div>
		</div>
	</section><!-- /.content -->
</div><!-- /.content-wrapper -->