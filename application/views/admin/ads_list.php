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

                  		<div class="box-header with-border">

                  		

	                  		<a href="admin/ads/add" title="" data-toggle="tooltip" data-original-title="Add Ad" class="btn btn-default pull-right"><i class="fa fa-plus"></i></a>

                	</div>

                	</div>

					<div class="box-body">

						<table  <?php if(!empty($records_result))

								{	?> id="example1"  <?php 

							} ?> class="table table-bordered table-hover">

							<thead>
 								<tr>
 									<th>Sr.No.</th>
 									<th>Approved</th>
 									<th>Ad Desc</th> 
 									<th>Phone</th> 
 									<th>Status</th>
 									<th>Action</th>
 								</tr>

							</thead>

							<tbody>

				

								<?php 



								if(!empty($records_result))

								{	

									$i =0;

									$table="ads";

									$field = "id";

									foreach ($records_result as $row) { 

										$i++; 

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
											<td><input type="checkbox" <?php if($row['approved']==1){ echo 'checked';}?> onclick="check_box(<?php echo $row['id']?>)" name="checkbox" id="checkbox" value="1"></td>

											<td><?php if(!empty($row['detail'])) echo substr($row['detail'], 0,50); ?></td>
										 
											<td><?php if(!empty($row['phone_number'])) echo $row['phone_number']; ?></td> 
											<td>
													<p id="status_<?php echo $row[$field]; ?>" onclick="change_status('<?php echo $field; ?>','<?php echo $row[$field]; ?>','<?php echo $table; ?>')" class="<?php echo $class; ?>" title="" data-toggle="tooltip" data-original-title="Change Status"><?php echo $status; ?></p>

											</td> 
											<td class="td-actions">

												<a id="edit_product" href="<?php echo base_url().'admin/ads/ad_edit/'.$row[$field];?>" class="btn btn-xs btn-primary edit_product" title="" data-toggle="tooltip" data-original-title="Edit"> 
													<i class="fa fa-edit"></i> 
												</a>  
												<a  href="javascript:void(0)"  class="btn btn-xs btn-danger"  onclick="delete_record('<?php echo $field; ?>','<?php echo $row[$field]; ?>','<?php echo $table; ?>')" data-toggle="tooltip" data-original-title="Delete">
													<i class="fa fa-trash-o"></i>
												</a>
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

function check_box(id)
{
	if(id) 
	{	
			 
	        	$.ajax({

	            	type:'POST',

	            	data:{ 
	                	id:id,
	            	},

	            	url: base_url+"admin/Other/change_approved/",

	            	success:function(data)
					{

					}

        		});

	}

}	



 

</script>

