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
                  		<a href="<?php echo $add_action;?>" title="" data-toggle="tooltip" data-original-title="Send Notification" class="btn btn-default pull-right"><i class="fa fa-plus"></i></a>
                	</div>
					<div class="box-body box-body table-responsive">
						<table <?php  if(!empty($records_results))
								{	?> id="example1"  <?php } ?>  class="table table-bordered table-hover">
							<thead>
								<tr>
									<th width="5%">Sr.No.</th>
									<th>Image</th>
									<th>Advertisement Title</th>
									<th>Details</th>
									<th>Send By</th>
									<th>Created</th>
									<th width="25%">Notification Sent</th>
								</tr>
							</thead>
							<tbody>

								<?php 
								if(!empty($records_results))
								{	
									$i = 0;
									foreach ($records_results as $row) { $i++; 
									?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><a href="<?php echo base_url().$row['image']; ?>"><img width="50px" height="50px"  src="<?php echo $row['image']; ?>"></td>
											<td><?php echo $row['notification_title']; ?></td>
											<td><?php echo $row['notification_description']; ?></td>
											<td><?php echo $row['send_by']; ?></td>
											<td><?php echo date('d-M-Y h:i a',strtotime($row['created'])); ?></td>
											<td><?php echo ucwords($row['notification_sent']); ?></td>
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