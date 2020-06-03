

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

					<div class="box-body table-responsive">

						<table <?php 

								if(!empty($records_result))

								{	?> id="example1"<?php }?> class="table table-bordered table-hover">

							<thead>

								<tr>

									<th width="10%">Sr.No.</th>
									<th width="15%">Media Type</th>
									<th>Doctor Name</th>
									<th>Title</th>
									<th>Media</th>  
									<th>Like </th>
									<th>Status</th> 
									<th>Actions</th>

 
								</tr>

							</thead>

							<tbody>

				

								<?php 

								if(!empty($records_result))

								{	

									$i = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

									$table="media";

									$field = "id";

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
											<td><?php echo ucfirst($row['media_type']); ?></td>
											<td><?php echo $row['title']; ?></td>
											<td><?php echo $row['full_name']; ?></td>
										 	  <td class="user-block">

												<?php if(isset($row['file']) && !empty($row['file'])){ 
													if($row['media_type']=='audio'){ ?>

													<a target="_" href="<?php echo base_url().$row['file']?>">	
														<img src="<?php echo base_url().'resources/images/category/Audio.png'; ?>" alt="Audio" class=""> 
													</a>

													<?php }elseif($row['media_type']=='video')
													{ ?>
													<a target="_" href="<?php echo base_url().$row['file']?>">	
														<img src="<?php echo base_url().'resources/images/category/video.png'; ?>" alt="Video" class=""> 
													</a>
													<?php 
													}else{ ?>
													<a target="_" href="<?php echo base_url().$row['file']?>">	
														<img src="<?php echo base_url().'resources/images/category/image.png'; ?>" alt="Image" class=""> 
													</a>
													<?php }
 													 } ?> 
											</td>  
											<td>
												 <?php echo $row['total_like'];?>	
											</td> 
											<td> 
												<p id="status_<?php echo $row[$field]; ?>" onclick="change_status('<?php echo $field; ?>','<?php echo $row[$field]; ?>','<?php echo $table; ?>')" class="<?php echo $class; ?>" title="" data-toggle="tooltip" data-original-title="Change Status"><?php echo $status; ?></p>
											</td>
										 
 
											<td class="td-actions"> 
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