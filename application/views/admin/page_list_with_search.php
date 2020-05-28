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
                  		 
                	</div>
                	</div>
					<div class="box-body">
						<form name="frm" id="frm" method="post"  onsubmit="return checkdetails('business')";  action="<?php echo base_url().'admin/user/page_list_with_search';?>">
						 		  		<div class="col-md-2">
				                      
				                         </div>
				                       <div class="col-md-4">
				                        <input type="text" class="start_date form-control" name="start_date" placeholder="Start date" id="start_date" readonly value="<?php echo $this->input->post('start_date');?>">
				                         </div>
				                         <div class="col-md-4">
				                        <input type="text" class="start_date form-control" name="end_date" placeholder="End date" id="end_date" readonly value="<?php echo $this->input->post('end_date');?>">
				                         </div>
				                        <div class="col-md-1">
				                          <input type="submit" name="submit" class="btn btn-warning" id="submits"  value="Search">
				                        </div>
			              	</form>
			              		 <a href="<?php echo base_url().'admin/dashboard';?>"><button class="btn btn-danger" style=" float: right;margin-right: 10px;"> Back</button></a>
			              		<br></br>

						<table  <?php if(!empty($records_result))
								{	?> id="example1"  <?php 
							} ?> class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>Sr.No.</th>
									<th>Page Image</th>
									<th>Page name</th>
									<th>Address</th>
									<th>Offer Created</th>
									<th>Total Offer</th>
									<th>Is Subscription</th>
									<th>Total Subscription</th>
									 
									<?php if(isset($edit_action) && !empty($edit_action)){ ?>
									<th>Details</th>
									<?php } ?>
								</tr>
							</thead>
							<tbody>
				
								<?php 

								if(!empty($records_result))
								{	
									$i =0;
									$table="users";
									$field = "user_id";
									foreach ($records_result as $row) { 
										$i++; 
										if(isset($row['status'])) {
	                                        if($row['status']=="verified") {
	                                            $status = "Verified";
	                                            $class = "pointer badge bg-green";
	                                        } else {
	                                            $status = "Unverified";
	                                            $class = "pointer badge bg-red";
	                                        }
                                    	}?>
										<tr id="tr_<?php echo $row[$field]; ?>">
											<td><?php echo $i; ?></td>
											<td class="user-block">
												<?php if(isset($row['business_image']) && !empty($row['business_image'])){ ?>
													<img src="<?php echo base_url().$row['business_image']; ?>" alt="user image" class="img-circle img-bordered-sm">
												<?php }else{ ?>
												<img src="<?php echo base_url().'resources/images/profile/default_image.png'; ?>" alt="user image" class="img-circle img-bordered-sm">

												<?php 	} ?>
											</td>
												
											<td><?php if(!empty($row['business_name'])) echo $row['business_name']; ?></td>
											<td><?php if(!empty($row['address_1'])) echo $row['address_1']; ?></td>
											<td><?php if(!empty($row['is_offers'])) echo $row['is_offers']; ?></td>
											<td><?php if(!empty($row['total_offer'])) { echo $row['total_offer']; } else {echo 'N/A';}  ?></td>
											
											 <td>
											 <?php if($row['is_subscribe']=='Yes'){?>
											 <a href="<?php echo base_url().'admin/membership';?>"> <?php if(!empty($row['is_subscribe'])) echo $row['is_subscribe']; ?></a>
											 <?php }else{?>
											 <?php if(!empty($row['is_subscribe'])) echo $row['is_subscribe'];?>
											 	<?php }?>
											 </td>
											<td><?php if(!empty($row['total_subscribe'])) {echo $row['total_subscribe'];} else {echo 'N/A';} ?></td>
											
											 
						
											
											<td class="td-actions">
												<a id="edit_product" href="<?php echo base_url().'admin/user/page_details/'.$row['business_page_id'] ?>" class="btn btn-xs btn-success edit_product" title="" data-toggle="tooltip" data-original-title="Details">
													<i class="fa fa-eye"></i>
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
   <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />

<script type="text/javascript">
$(document).ready(function() {

    $('.start_date')
    .datepicker({
        format: 'yyyy-mm-dd',
           endDate: '+0d',
            autoclose: true
    })
    
});


function checkdetails(val)
{ 
    if(val=='offer')
    {
     
      var start_date = $("#start_dates").val();
      var end_date =   $("#end_dates").val();
    }else
    {
      
      var start_date = $("#start_date").val();
      var end_date =   $("#end_date").val();
    }
    if(start_date!='' && end_date!='')
    {
      if(new Date(end_date) < new Date(start_date))
      {
        alert('End date must be greater than Start date');
         return false;
      }else
      {
        return true;
      }
    }else
    {
      alert('Please enter Start date And end date');
       return false;
    } 
}
 
 
</script>