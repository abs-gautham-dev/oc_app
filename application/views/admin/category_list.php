

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

                  		<a href="<?php echo $add_action;?>" title="" data-toggle="tooltip" data-original-title="Add Pattern" class="btn btn-default pull-right"><i class="fa fa-plus"></i></a>

                		<?php } ?>

                	</div>  

					<div class="box-body">

						<table id="example1" class="table table-bordered table-hover">

							<thead>

								<tr>

									<th>Sr.No.</th>

								<!-- 	<th>Image</th> -->

									<th>Name</th>
									<!-- <th>Image</th> 
									<th>Sub Categories</th> -->
									<th>Order</th>
									<th>Status</th>

								 

									<th>Actions</th>

 
								</tr>

							</thead>

							<tbody>

				

								<?php 

								if(!empty($records_result))

								{	

									$i = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

									$table="categories";

									$field = "category_id";

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

										<tr id="tr_<?php echo $row['category_id']; ?>">

											<td><?php echo $i; ?></td>


											<td><?php echo $row['name']; ?></td>
										 	<!-- <td class="user-block">

												<?php if(isset($row['image']) && !empty($row['image'])){ ?>

													<img src="<?php echo base_url().$row['image']; ?>" alt="user image" class="img-circle img-bordered-sm">

												<?php } ?>

											</td>  
											<td>   

												<a id="edit_product" href="<?php echo $subCategories.'/'.$row['category_id']; ?>" class="btn btn-xs btn-info" title="" data-toggle="tooltip" data-original-title="Sub Categories">
													Sub Categories
												</a> 
											 
											</td>  -->
											<td>
												<select data-value="<?php echo $row['orders']; ?>" name ="order" id="order_<?php echo $row[$field]; ?>" onchange="new_update_order(<?php echo $row[$field]; ?>,'<?php echo $table; ?>','<?php echo $field; ?>')">
												<?php for($j=1;$j<=$total_records;$j++) { ?>
													<option  value="<?php echo $j; ?>" <?php if($row['orders']==$j) echo "Selected"; ?>><?php echo $j; ?></option>
												<?php } ?>
												</select>
											</td>
											 
											<td>
											  
												<p id="status_<?php echo $row[$field]; ?>" onclick="change_status('<?php echo $field; ?>','<?php echo $row[$field]; ?>','<?php echo $table; ?>')" class="<?php echo $class; ?>" title="" data-toggle="tooltip" data-original-title="Change Status"><?php echo $status; ?></p>
											</td>
										 
 
											<td class="td-actions">
												<a id="edit_product" href="<?php echo base_url('admin/categories/edit/').'/'.$row[$field]; ?>" class="btn btn-xs btn-primary edit_product" title="" data-toggle="tooltip" data-original-title="Edit">
													<i class="fa fa-pencil"></i>
												</a>
												<a  href="javascript:void(0)"  class="btn btn-xs btn-danger"  onclick="delete_record('<?php echo $field; ?>','<?php echo $row[$field]; ?>','<?php echo $table; ?>')" data-toggle="tooltip" data-original-title="Delete">
													<i class="fa fa-trash-o"></i>
												</a>
											</td> 

										</tr>



									<?php }

								} else {

									echo "<tr><td colspan='5' align='center'> No Record Found</td></tr>";

								} ?>

							</tbody>

							<!-- <tfoot>						

								<tr>

									<td colspan="5" align="center">

										<div><?php echo $pagination; ?></div>

									</td>

								</tr>

							</tfoot>	 -->						

						</table>

					</div>			

				</div>			

			</div>

		</div>

	</section><!-- /.content -->

</div><!-- /.content-wrapper