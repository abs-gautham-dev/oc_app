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
            <!-- left column -->
            <div class="col-md-12">
            <div class="col-md-6">
              <!-- general form elements -->
              	<div class="box box-primary">
	                <div class="box-header">
	                  <!-- <h3 class="box-title">Example</h3> -->
	                  <?php if ($this->session->flashdata('success')) { ?>
	                	<div class="alert alert-success fade in">
	                        <button data-dismiss="alert" class="close" type="button">×</button>
	                      	<p><?php echo $this->session->flashdata('success') ?></p>
	                  	</div>
	                  	<?php } ?>   
	                    <?php if ($this->session->flashdata('error')) { ?>
	                    <div class="alert alert-error fade in">
	                        <button data-dismiss="alert" class="close" type="button">×</button>
	                        <p><?php echo $this->session->flashdata('error') ?></p>
	                  	</div>
	                    <?php } ?>
	                </div>
	                <!-- /.box-header -->
	                <!-- form start -->
	                <?php if(isset($from_action) && !empty($from_action)){ ?>
	                <form method="POST" action="<?php echo $from_action; ?>" onsubmit="return fromsubmit()" role="form"  data-parsley-validate>
	                <?php } ?>
                        <div class="box-body">
	                  		<div class="box-body">
	                  			<!-- <div class="form-group">
                                    <label for="interest" class="">Interest </label>
                                    <?php
                                        $extra=array(
                                            'class'=>'form-control',
                                            'data-parsley-required-message'=>'Please select interest.',
                                        );
                                        $interest=!set_value('interest') ? $user['interest_id'] : set_value('interest'); 
                                        echo form_dropdown('interest', $interest_list,$interest,$extra);
                                        echo form_error('interest');
                                    ?>
                                </div> -->
                                
                               
                                <div class="form-group">
                                    <label for="fullname" >Full Name *</label>
                                    <input type="text" class="form-control" name="fullname" id="fullname"  data-parsley-pattern="^[a-z A-Z 0-9 ]+$"  value="<?php if(isset($user['full_name'])) echo $user['full_name']; ?>" placeholder="Fullname" data-parsley-required data-parsley-required-message="Please enter fullname.">
                                    <?php echo form_error('fullname'); ?>
                                </div> 
								
								<div class="form-group">
                                    <label for="email">Email *</label>
                                    <input type="email"  readonly class="form-control" id="email" name="email" placeholder="Email" value="<?php if(isset($user['email'])) echo $user['email']; ?>" data-parsley-required data-parsley-required-message="Please enter valid email." data-parsley-type="email" data-parsley-type-message="Please enter valid email." >
                                    <p class="error" id="email_error" style="display:none;">Email already used.</p>
                                    <?php echo form_error('email'); ?>
                                </div>

                                
                                <div class="form-group">
                                    <label for="email">Password</label>
                                    <input type="text"  readonly class="form-control"  placeholder="Password" value="<?php if(isset($user['password'])) echo base64_decode( $user['password']); ?>" >
                                </div>
								<div class="form-group">
									<label>About</label>
									<textarea class="form-control" rows="3" name="about" placeholder="Enter here ..." maxlength="1500"  ><?php if(!empty($user['about'])) echo $user['about']; ?></textarea>
			                    </div>
								
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="<?php if(!empty($user['address'])) echo $user['address']; ?>">
                                    <?php echo form_error('address'); ?>
                                </div>
						
								<div class="form-group">
									<label for="country">Country</label>
									<?php
                                        $extra=array(
                                            'class'=>'form-control',
                                            'id'=>"country"
                                        );
                                        $country=!set_value('country') ? $user['country_id'] : set_value('country'); 
                                        echo form_dropdown('country', $countries,$country,$extra);
                                        echo form_error('country');
                                    ?>
								</div>

								<div class="form-group">
									<label for="state">State</label>
									<select class="form-control" id="state" name="state">
                                        <option value='' id='first'>Select State</option>
                                        <?php if(!empty($user['country_id'])) {
                                            if($states = getStatesList($user['country_id'])) {
                                                foreach($states as $state) { ?>
                                                    <option value=<?php echo $state['id'];?> <?php if(!empty($user['state_id']) && ($user['state_id']==$state['id'])) {echo "selected";} ?>><?php echo $state['name'];?></option>
                                                <?php }
                                            } 
                                        } else {
                                            echo "<option value='' id='first'>Select State</option>";
                                        } ?>
										
									</select>
								</div>
                                
                                <div class="form-group">
                                    <label for="state">City</label>
                                    <select class="form-control" id="city" name="city">
                                        <option value='' id='city_first'>Select City</option>
                                        <?php if(!empty($user['state_id'])) {
                                            $cities = getCitiesList($user['state_id']);
                                            //echo "<pre>";print_r($states);exit;
                                            if($cities) {
                                                foreach($cities as $city) { ?>
                                                    <option value=<?php echo $city['id'];?> <?php if(!empty($user['city_id']) && ($user['city_id']==$city['id'])) {echo "selected";} ?>><?php echo $city['name'];?></option>
                                                <?php }
                                            } 
                                        } else {
                                            echo "<option value='' id='city_first'>Select City</option>";
                                        } ?>
                                        
                                    </select>
                                </div>
                                <div class="form-group">
                                
                                <div class="row">
                                   
                                    <div class="col-sm-12"><label for="mobile">Mobile </label>
                                    <input type="text" class="form-control" id="mobile" name="mobile" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" placeholder="Mobile" value="<?php if(!empty($user['mobile'])) echo $user['mobile']; ?>"  data-parsley-minlength="10" data-parsley-minlength-message="Mobile number must be at least 10 digits long." data-parsley-maxlength-message="Mobile number must be at most 12 digits long." data-parsley-maxlength="12" data-parsley-type-message="Please enter valid mobile number">
                                    <p class="error" id="mobile_error" style="display:none;">mobile no already used.</p>
                                    <?php echo form_error('mobile'); ?></div>
                                    </div>
                                </div>
			                    <div class="form-group">
									<label for="status">Status *</label>
										<?php
										$status=!set_value('status') ? $user['status'] : set_value('status'); 
										$options_status = array('Active'  => 'Active','Inactive'  => 'Inactive');
										echo form_dropdown('status', $options_status, $status,'class="form-control"');
										?>
									<?php echo form_error('status');?>
							  	</div>
							</div><!-- /.box-body -->
	                  	</div><!-- /.box-body -->
                        <input type="hidden" name="user_type" value="<?php if(!empty($user['user_type'])) echo $user['user_type']; ?>">
						<div class="box-footer text-center">
							<?php if(isset($from_action) && !empty($from_action)){ ?>
							<button type="submit" class="btn btn-primary">Update</button>
							<?php } ?>
							<a href="<?php echo $back_action;?>" class="btn btn-default">Back</a>
						</div>
	                </form>
            	</div><!-- /.box -->
			</div><!-- col-12-->

			<div class="col-md-6">
				<div class="box box-primary">
					<div class="box-body">
               			<form class="form-horizontal">
               				<div class="col-md-6 text-center">
	                			<input type="hidden" id="user_id" name="user_id" value="<?php echo $user['user_id']; ?>" />
	                			<div class="control-group">
	                    			<label class="control-label">Image *</label>
	                    			<div class="controls">
				                        <div data-provides="fileupload" class="fileupload fileupload-new">
				                            <div  class="fileupload-new thumbnail">
				                              <img alt="No Image"src="<?php echo !empty($user['profile_pic']) ? base_url().$user['profile_pic']:'';?>" >
				                            </div>
				                            <div style="max-width: 100px; max-height: 100px; line-height: 5px;" class="fileupload-preview fileupload-exists thumbnail"></div>
				                            <div>
				                            	<?php if(isset($from_action) && !empty($from_action)){ ?>
				                                <span class="btn btn-file"><span class="fileupload-new btn btn-default">Change image</span>
				                                <span class="fileupload-exists">Change</span>
				                                <input type="file" name="image" id="image" class="default" accept="image/*"></span>
				                                <a data-dismiss="fileupload" class="btn fileupload-exists" href="#">Remove</a>
				                                <?php } ?>
				                            </div>
				                        </div>
	                    			</div>
				                </div>
			                </div>
			                <div class="col-md-6">
			                	 <div class="form-group"></div>
			                	 <div class="form-group"></div>
				                <div class="form-group">
				                    <div class="col-md-4">
				                     <button type="button" id="img_upload_button" class="btn btn-info upload_profile_user">Upload</button>
				                    </div>
				                </div>
	                			<div class="form-group" >
									<div class="col-sm-8">
										<div class="progress">
										<div class="progress-bar" role="progressbar" id="progressBar_image" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
										</div>
									</div>
	                  				<div class="col-sm-12" id="status_image"></div>
								</div>
							</div>
                		</form>
              
            		</div><!-- panel body-->
				</div>
			</div><!-- col-12-->
		</div><!-- row-->
	</section>
</div><!-- row-->

<script>
$('#country').change(function(e) {
    var id = $("#country").val();
   
    if(id!='') {
        $('#state option').slice(1).remove();

        $.ajax({
            type:'POST',
            url: "<?php echo base_url(); ?>admin/ajax/get_states/",
            data: {id:id},
            
            success:function(data)
            {
                if(data) { 
                    var states="";
                    data = JSON.parse(data);
                    
                    //sub_outlets += "<option value=''>Select Sub Outlet</option>";
                    for(var i=0;i<data.length;i++) {
                        states += "<option value="+data[i].id+">"+data[i].name+"</option>";
                    }
                    
                    $("#state").find("#first").after(states);
                } else {
                    error_msg = "Some Error occured. Please try again !!" ;
                    error = '<div class="alert alert-block alert-danger fade in"><button data-dismiss="alert" class="close" type="button">×</button>'+error_msg+'</div>';
                    $('#notification_msg').html(error).fadeIn(250).fadeOut(10000);
                }
                //console.log(data[0].sub_outlet_id);
            }
        });
    }
});

$('#state').change(function(e) {
    var id = $("#state").val();
    if(id!='') {
        $('#city option').slice(1).remove();

        $.ajax({
            type:'POST',
            url: "<?php echo base_url(); ?>admin/ajax/get_cities/",
            data: {id:id},
            
            success:function(data)
            {
                if(data) {
                    var cities="";
                    data = JSON.parse(data);
                    
                    //sub_outlets += "<option value=''>Select Sub Outlet</option>";
                    for(var i=0;i<data.length;i++) {
                        cities += "<option value="+data[i].id+">"+data[i].name+"</option>";
                    }
                    $("#city").find("#city_first").after(cities);
                } else {
                    error_msg = "Some Error occured. Please try again !!" ;
                    error = '<div class="alert alert-block alert-danger fade in"><button data-dismiss="alert" class="close" type="button">×</button>'+error_msg+'</div>';
                    $('#notification_msg').html(error).fadeIn(250).fadeOut(10000);
                }
            }
        });
    }
});
var error_msg ='';
//check email 
$('#email').change(function(e) {
    error_msg ='';
     $("#email_error").hide();
    var id = "<?php echo $user['user_id']; ?>"; 
    var email = $("#email").val();
    if(id!='') {

        $.ajax({
            type:'POST',
            url: "<?php echo base_url(); ?>admin/ajax/check_user_email/",
            data: {id:id,email:email},
            
            success:function(data)
            { 
                if(data==1) {
                    error_msg = "Email already used." ;
                    $("#email_error").show();
                }
            }
        });
    }
});
//check username 
$('#username').change(function(e) {
     error_msg1 ='';
    var id ="<?php echo $user['user_id']; ?>";
    var username = $("#username").val();
    
    if(id!='') {
        $.ajax({
            type:'POST',
            url: "<?php echo base_url(); ?>admin/ajax/check_username_user/",
            data: {id:id,username:username},
            
            success:function(data)
            {
                 if(data==1) {
                    error_msg1 = "Username already used." ;
                    $("#un_error").show();
                } else{
                    error_msg1 = "" ;
                    $("#un_error").hide();
                }
            }
        });
    }
});

//check email 
$('#mobile').change(function(e) {
     error_msg ='';
     $("#mobile_error").hide();
    var id = "<?php echo $user['user_id']; ?>"; 
    var mobile = $("#mobile").val();
    if(id!='') {

        $.ajax({
            type:'POST',
            url: "<?php echo base_url(); ?>admin/ajax/check_user_mobile/",
            data: {id:id,mobile:mobile},
            
            success:function(data)
            { 
                if(data==1) {
                    error_msg = "mobile no already used." ;
                    $("#mobile_error").show();
                }
            }
        });
    }
});
function fromsubmit() {
 if(error_msg !=''){
    return false;}
 if(error_msg1 !=''){
    return false;}
}


user_types();
function user_types(){
var user_type = $("#user_type").val();
// alert(user_type);
if(user_type=='Doctor'){
    $(".doctor_div").hide();
    $(".category_div").show();
    $("#category_id").attr('data-parsley-required',true);
    $("#dr_id").attr('data-parsley-required',false);
}else{
    $(".category_div").hide();
    $(".doctor_div").show();
    $("#category_id").attr('data-parsley-required',false);
    $("#dr_id").attr('data-parsley-required',true);
}

}


</script>
