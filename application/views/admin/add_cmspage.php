<div class="row">
<div class="col-lg-6  col-lg-offset-3">
<div class="panel panel-default">
	<div class="panel-heading">
	  <h3 class="panel-title">Add cms page</h3>
	</div>
	<div class="panel-body">
	  <?php 
	  if(isset($err_msg)){ echo $err_msg;}
	   form_open(); 
	  echo validation_errors();  ?>
	 <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="<?php echo site_url("admin/cmspage/add_cmspage"); ?>" role="form">
		  <div class="form-group">
			<label for="title" class="col-sm-3 control-label">Title</label>
			<div class="col-sm-9">
			  <input type="text" class="form-control" name="title" id="title" placeholder="Title">
			</div>
		  </div> 
		  <div class="form-group">
			<label for="description" class="col-sm-3 control-label">description</label>
			<div class="col-sm-9">
			  <input type="text" class="form-control" name="description" id="description" placeholder="description">
			</div>
		  </div>
		  <div class="form-group">
			<label for="content" class="col-sm-3 control-label">content</label>
			<div class="col-sm-9">
			  <input type="text" class="form-control" id="content" name="content" placeholder="content">
			</div>
		  </div>
		  <div class="form-group">
			<label for="seo_title" class="col-sm-3 control-label">seo title</label>
			<div class="col-sm-9">
			  <input type="text" class="form-control" id="seo_title" name="seo_title" placeholder="seo title">
			</div>
		  </div>		  
		  <div class="form-group">
			<label for="seo_description" class="col-sm-3 control-label">seo Description</label>
			<div class="col-sm-9">
			  <input type="text" class="form-control" id="seo_description" name="seo_description" placeholder="seo_description">
			</div>
		  </div>	 
		  <div class="form-group">
			<label for="seo_keywords" class="col-sm-3 control-label">seo keywords</label>
			<div class="col-sm-9">
			  <input type="text" class="form-control" id="seo_keywords" name="seo_keywords" placeholder="seo_keywords">
			</div>
		  </div>		  
		  
		  <div class="form-group">
			<label for="status" class="col-sm-3 control-label">Status</label>
			<div class="col-sm-9">
			  <input type="text" class="form-control" id="status" name="status" placeholder="Status">
			</div>
		  </div>

		  <div class="form-group">
			<div class="col-sm-offset-3 col-sm-9">
			  <button type="submit" class="btn btn-success">Save</button>
			</div>
		  </div>
</form>
<?php form_close(); ?>
            </div><!-- panel body-->
          </div><!-- end panel -->

</div><!-- col-6-->
</div><!-- row-->

