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
                  		
                  		<?php if(isset($add_action) && !empty($add_action)){ ?>
                  		<a href="<?php echo $add_action;?>" title="" data-toggle="tooltip" data-original-title="Add Admin" class="btn btn-default pull-right"><i class="fa fa-plus"></i></a>
                		<?php } ?>
                	</div>
					<div class="box-body">
						<table <?php  if(!empty($records_results))
								{	?> id="example1"  <?php } ?> class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>Sr.No.</th>
									<th>Profile Pic.</th>
									<th>Username</th>
									<th>Email</th>
									<th>Account Type</th>
									<?php if(isset($edit_action) && !empty($edit_action)){ ?>
									<th>Status</th>
									
									<th>Actions</th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
	

								<?php 
								if(!empty($records_results))
								{	
									$i = 0;
									$table="admin";
									$field = "admin_id";
									foreach ($records_results as $row) { $i++; 
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
											<td class="user-block">
												<?php if(isset($row['profile_pic']) && !empty($row['profile_pic'])){ ?>
													<img src="<?php echo base_url().$row['profile_pic']; ?>" alt="user image" class="img-circle img-bordered-sm">
												<?php } ?>
											</td>
												
											<td><?php if(!empty($row['username'])) echo $row['username']; ?></td>
											<td><?php if(!empty($row['email'])) echo $row['email']; ?></td>
											<td><?php if(!empty($row['user_type'])) echo $row['user_type']; ?></td>
											<?php if(isset($edit_action) && !empty($edit_action)){ ?>
											<td>
											<?php if($row['user_type']!='Super Admin') { ?>
												<p id="status_<?php echo $row[$field]; ?>" onclick="change_status('<?php echo $field; ?>','<?php echo $row[$field]; ?>','<?php echo $table; ?>')" class="<?php echo $class; ?>" title="" data-toggle="tooltip" data-original-title="Change Status"><?php echo $status; ?></p>
											<?php } ?>
											</td>
											
											<td class="td-actions">
											<?php $user_type = $this->session->userdata('user_type');
											if($user_type =='Admin') { 
												if($row['user_type']!='Super Admin') { ?>
													<a id="edit_product" href="<?php echo $edit_action.'/'.$row[$field]; ?>" class="btn btn-xs btn-primary edit_product" title="" data-toggle="tooltip" data-original-title="Edit">
														<i class="fa fa-pencil"></i>
													</a>&nbsp;&nbsp;
												<?php }
											} else { ?>
												<a id="edit_product" href="<?php echo $edit_action.'/'.$row[$field]; ?>" class="btn btn-xs btn-primary edit_product" title="" data-toggle="tooltip" data-original-title="Edit">
													<i class="fa fa-pencil"></i>
												</a>&nbsp;&nbsp;

											<?php }  ?>	
											<?php if($row['user_type']!='Super Admin') { ?>
												<?php if(isset($delete_action) && !empty($delete_action)){ ?>
												<a  href="javascript:void(0)" class="btn btn-xs btn-danger"  
												onclick="delete_report_page_list('admin_id','<?php echo $row['admin_id'];?>','admin')" data-toggle="tooltip" data-original-title="Delete">
													<i class="fa fa-trash-o"></i>
												</a>&nbsp;&nbsp;
											<?php }} ?>	

												
										
											</td>
											<?php 
											
											} ?> 	
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

<script>
function delete_report_page_list(field,id,table)
{ 
    if(id) {
         var a = confirm("Are you sure to delete this record?");
		if(a) {
	         $.ajax({
            type:'POST',
            data:{ 
                id:id,
                table_name:table,
                field:field 
            },
            url: base_url+"admin/ajax/delete_record/",
            success:function(data)
            {
             location.reload(); 
            }
        });
		}else{
		  return false;
		}

       
    }
}
</script>