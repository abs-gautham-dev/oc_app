<table id="" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>Sr.No.</th>
									<th>Post Title.</th>
									<th>User Name.</th>
									<th>Post Date</th>
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
									$table="user_post";
									$field = "post_id";

									foreach ($records_result as $row) { 
										$i++; 
										if(isset($row['status'])) {
	                                        if($row['status']=="Active") {
	                                            $status = "Active";
	                                            $class = "pointer badge bg-green";
	                                        } else {
	                                            $status = "Deactive";
	                                            $class = "pointer badge bg-red";
	                                        }
                                    	} ?>
										<tr id="tr_<?php echo $row[$field]; ?>">
											<td><?php echo $i; ?></td>
											
											<td><?php if(!empty($row['post_title'])) echo substr($row['post_title'],'0','20'); ?></td>	
											<td><?php if(!empty($row['username'])) echo $row['username']; ?></td>	
										
											<td><?php if(!empty($row['post_date'])) echo $row['post_date']; ?></td>
											<td>
												<p id="status_<?php echo $row[$field]; ?>" onclick="change_status_post('<?php echo $field; ?>','<?php echo $row[$field]; ?>','<?php echo $table; ?>')" class="<?php echo $class; ?>" title="" data-toggle="tooltip" data-original-title="Change Status"><?php echo $status; ?></p>
											</td>
											<?php if(isset($edit_action) && !empty($edit_action)){ ?>
											<td class="td-actions">
												
												<a href="admin/post/media/<?php echo $row['post_id'];?>" class="btn btn-xs btn-warning edit_product"  title="" data-toggle="tooltip" data-original-title="media">
													<i class="fa fa-picture-o"></i>
												</a>
												<a href="admin/post/details/<?php echo $row['post_id'];?>"class="btn btn-xs btn-info edit_product" title="" data-toggle="tooltip" data-original-title="View">
													<i class="fa fa-eye"></i>
												</a>
												<a  href="javascript:void(0)"  class="btn btn-xs btn-danger"  onclick="delete_record('<?php echo $field; ?>','<?php echo $row[$field]; ?>','<?php echo $table; ?>')" data-toggle="tooltip" data-original-title="delete">
													<i class="fa fa-trash-o"></i>
												</a>

												
											</td>
											<?php } ?>
										</tr>

									<?php }
								} else {
									echo "<tr><td colspan='7' align='center'> No Record Found</td></tr>";
								} ?>
							</tbody>
						</table>
							<div class="box-body"><?php echo $pagination; ?></div>

<script>

</script>