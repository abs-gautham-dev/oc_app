<?php
$r=$userprofile_data->result();
$row=$r[0];

?>
<div class="row">
<div class="col-lg-6  col-lg-offset-3">
<div class="panel panel-default">
	<div class="panel-heading">
	  <h3 class="panel-title"><?php echo $row->username; ?></h3>
	</div>
	<div class="panel-body">
	  <?php 
	  if(isset($err_msg)){ echo $err_msg;}
	   form_open(); 
	  echo validation_errors();  ?>
    <form class="form-horizontal" method="POST" action="<?php echo site_url("admin/login/edit_user/".$row->id); ?>" role="form">
	
	  <div class="form-group">
		<label for="user_type" class="col-sm-3 control-label">User Type</label>
		<div class="col-sm-9">
		  <!-- <input type="text" class="form-control"  name="user_type" id="user_type" value="<?php echo $row->user_type; ?>"> -->
		  <?php
				$options_user_type = array(
					  'admin'  => 'Admin',
					  'manager'  => 'Manager',
					  'customer'    => 'Customer',

					);

				echo form_dropdown('user_type', $options_user_type, $row->user_type);
			?>
		  
		</div>	 
		</div>	
		
	  <div class="form-group">
		<label for="first_name" class="col-sm-3 control-label">First Name</label>
		<div class="col-sm-9">
		  <input type="text" class="form-control"  name="first_name" id="first_name" value="<?php echo $row->first_name; ?>">
		</div>	 
		</div>	 

		<div class="form-group">
		<label for="last_name" class="col-sm-3 control-label">Last Name</label>
		<div class="col-sm-9">
		  <input type="text" class="form-control"  name="last_name" id="last_name" value="<?php echo $row->last_name; ?>"  >
		</div>
	  </div>
	  
	  <div class="form-group">
		<label for="username" class="col-sm-3 control-label">User Name</label>
		<div class="col-sm-9">
		  <input type="text" readonly class="form-control" id="username"  name="username" value="<?php echo  $row->username; ?>"  >
		 
		</div>
	  </div>	 
	  <div class="form-group">
		<label for="email" class="col-sm-3 control-label">Email</label>
		<div class="col-sm-9">
		  <input type="text" readonly class="form-control" id="email"  name="email" value="<?php echo  $row->email; ?>"  >
		 
		</div>
	  </div>
	  
	<!-- <div class="form-group">
		<label for="password" class="col-sm-3 control-label">Password</label>
		<div class="col-sm-9">
		  <input type="password"  class="form-control" id="password"  name="password" value="<?php echo  $row->password; ?>"  >
		 
		</div>
	  </div>-->

		
	  <div class="form-group">
		<label for="status" class="col-sm-3 control-label">Status</label>
			<div class="col-sm-9">
			
			<?php
			//echo $row->status;
			$options_status = array(
                  'active'  => 'Active',
                  'inactive'  => 'InActive',
                  'pending'    => 'Pending',
                );

			echo form_dropdown('status', $options_status, $row->status);
			  
			  
			  ?>
			</div>
		</div>	
		
	  <input type="hidden" id="user_id" name="user_id" value="<?php echo $row->id; ?>" />
 
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



