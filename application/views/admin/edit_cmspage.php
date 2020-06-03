<?php
$r=$cmspage_data->result();
$row=$r[0];

?>
<div class="row">
<div class="col-lg-6  col-lg-offset-3">
<div class="panel panel-default">
		<div class="panel-heading">
		  <h3 class="panel-title"><?php echo $row->title; ?></h3>
		</div>
    <div class="panel-body">
	  <?php 
	  if(isset($err_msg)){ echo $err_msg;}
	   form_open(); 
	  echo validation_errors();  ?>
             <form class="form-horizontal" method="POST" action="<?php echo site_url("admin/cmspage/edit_cmspage/".$row->cmspage_id); ?>" role="form">
  <div class="form-group">
    <label for="title" class="col-sm-3 control-label">title</label>
    <div class="col-sm-9">
      <input type="text" class="form-control"  name="title" id="title" value="<?php echo $row->title; ?>" placeholder="Name">
    </div>
  </div>
  <div class="form-group">
    <label for="description" class="col-sm-3 control-label">description</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="description"  name="description" value="<?php echo  $row->description; ?>" placeholder="description">
    </div>
  </div>
  <div class="form-group">
    <label for="content" class="col-sm-3 control-label">content</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="content"  name="content" value="<?php echo $row->content; ?>" placeholder="content">
    </div>
  </div>
  
    <div class="form-group">
    <label for="seo_title" class="col-sm-3 control-label">seo title</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="seo_title"  name="seo_title" value="<?php echo $row->seo_title; ?>" placeholder="seo title">
    </div>
  </div>
  
    <div class="form-group">
    <label for="seo_description" class="col-sm-3 control-label">seo description</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="seo_description"  name="seo_description" value="<?php echo $row->seo_description; ?>" placeholder="seo description">
    </div>
  </div>
  
  
    <div class="form-group">
    <label for="seo_keywords" class="col-sm-3 control-label">seo keywords</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="seo_keywords"  name="seo_keywords" value="<?php echo $row->seo_keywords; ?>" placeholder="seo keywords">
    </div>
  </div>    
  
  <div class="form-group">
    <label for="status" class="col-sm-3 control-label">status</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="status"  name="status" value="<?php echo $row->status; ?>" placeholder="status">
    </div>
  </div>
  
  <input type="hidden" id="cmspage_id" name="cmspage_id" value="<?php echo $row->cmspage_id; ?>" />
 
  <div class="form-group">
    <div class="col-sm-offset-3 col-sm-9">
      <button type="submit" class="btn btn-success">Update</button>
    </div>
  </div>
</form>

            </div><!-- panel body-->
          </div><!-- end panel -->

</div><!-- col-6-->
</div><!-- row-->






<?php form_close(); ?>



