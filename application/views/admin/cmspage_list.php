
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
	  <h1> Cms page </h1>
	  <ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">CMS Page</li>
	  </ol>
	</section>


	<!-- Main content -->
	<section class="content">
	  <!-- Info boxes -->
	  <div class="row">
	  
		<div class="col-lg-12">   

					
		<table class="table table-striped table-bordered">
			<thead>
				<tr> 
					<th>Title</th>
					<th>Description</th>
					<th>Content</th>
					<th>Status</th>
					<th class="td-actions">Action</th>
				</tr>
			</thead>
			<tbody>
					
	<?php
	if(!empty($cmspage_result))
	{
		foreach ($cmspage_result->result() as $row)
		{
    ?>
			<tr id="tr_<?php echo $row->cmspage_id; ?>">
			<td><?php  echo $row->title ;?></td>
			<td><?php echo $row->description; ?></td>
			<td><?php echo $row->content; ?></td>
			<td><?php echo $row->status; ?></td>
			<td class="td-actions">
				<a  id="edit_product" href="<?php echo site_url("admin/cmspage/edit_cmspage/".$row->cmspage_id) ?>" class="btn btn-xs btn-primary edit_product">
					Edit
				</a>
				<a  id="edit_product" data-id="<?php echo $row->cmspage_id; ?>" class="btn btn-xs btn-danger delete_product">
					Delete
				</a>


			</td>
		</tr>

<?php }/* While Loop*/
}else
{
	echo "<tr><td colspan='5'> No Record Found</td></tr>";
}

  ?>							
<tr><td colspan="5"><ul class="pagination"><?php echo $p_link; ?></ul></td></tr>
							</tbody>
						</table>
</div>			
</div>

		
			<!-- Main row -->
         
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->