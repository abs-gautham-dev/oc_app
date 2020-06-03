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
                  		<a href="<?php echo $add_action;?>" title="" data-toggle="tooltip" data-original-title="Add Faq" class="btn btn-default pull-right"><i class="fa fa-plus"></i></a>
                		<?php } ?>
                	</div>
					<div class="box-body">
						<table id="" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>Sr.No.</th>
									<th>Title</th>
									<th>Order</th>
									<?php if(isset($edit_action) && !empty($edit_action)){ ?>
									<th>Status</th>
										<th>Actions</th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
				
								<?php 
								if(!empty($records_result))
								{	
									$i = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
									$table="faq";
									$field = "faq_id";
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
										 
											<td><?php echo $row['question']; ?></td>
											<td>
												<select data-value="<?php echo $row['orders']; ?>" name ="order" id="order_<?php echo $row[$field]; ?>" onchange="new_update_order(<?php echo $row[$field]; ?>,'<?php echo $table; ?>','<?php echo $field; ?>')">

												<?php 
												$data['records_result']=$this->Common_model->getRecords('faq', '*', "","faq_id asc", false);
												for($j=1;$j<=count($data['records_result']);$j++) { ?>
													<option  value="<?php echo $j; ?>" <?php if($row['orders']==$j) echo "Selected"; ?>><?php echo $j; ?></option>
												<?php } ?>
												</select>
											</td>
											 
											<?php if(isset($edit_action) && !empty($edit_action)){ ?>
											<td>
												<p id="status_<?php echo $row[$field]; ?>" onclick="change_status('<?php echo $field; ?>','<?php echo $row[$field]; ?>','<?php echo $table; ?>')" class="<?php echo $class; ?>" title="" data-toggle="tooltip" data-original-title="Change Status"><?php echo $status; ?></p>
											</td>

											
											<td class="td-actions">
												<a id="edit_product" href="<?php echo $edit_action.'/'.$row[$field]; ?>" class="btn btn-xs btn-primary edit_product" title="" data-toggle="tooltip" data-original-title="Edit">
													<i class="fa fa-pencil"></i>
												</a>
											<?php if(isset($delete_action) && !empty($delete_action)){ ?>
												<a  href="javascript:void(0)" class="btn btn-xs btn-danger"  onclick="delete_record_faq('<?php echo $field; ?>','<?php echo $row[$field]; ?>','<?php echo $table; ?>')" data-toggle="tooltip" data-original-title="delete">
													<i class="fa fa-trash-o"></i>
												</a>
											</td>
											<?php } }?>
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
<script type="text/javascript">
	
function delete_record_faq(field,id,table)
{ 
    if(id) {
       
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
               $("#tr_"+id).hide();
               
            }
        });
    }
}

function check_box(id)
{
	if(id) 
	{		 
    	$.ajax({

        	type:'POST',

        	data:{ 
            	id:id,
        	},

        	url: base_url+"admin/Other/change_approved_faq/",

        	success:function(data)
			{

			}

		});

	}

}	


</script>

 