<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

        <h1><?php if(isset($page_title)) echo $page_title; ?></h1>
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
        	<div class="col-lg-12">
              <form class="" id="change_password" method="POST" enctype="multipart/form-data"  action="<?php echo current_url(); ?>" role="form" data-parsley-validate>
        		<div class="col-lg-6">
    	        	<div class="box box-primary">
    	            	<!-- /.box-header -->
    	            	<div class="box-body">
    	                	<div class="row">
                                <div class="panel-body">
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
                                    <!-- ajax error msg -->
                                    <div id= 'notification_msg'> </div>

                                  
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label for="fullname" >Full Name *</label>
                                                <input type="text" class="form-control"  name="fullname" id="fullname" value="<?php if(isset($admin_data['fullname'])) echo $admin_data['fullname']; ?>" placeholder="Full Name" data-parsley-pattern="^[a-z A-Z 0-9 ]+$" data-parsley-required data-parsley-required-message="Please enter Full Name.">
                                                <?php echo form_error('fullname'); ?>
                                            </div>

                                            <div class="form-group">
                                                <label for="username">Username *</label>
                                                <input type="text"  class="form-control" id="username" name="username" placeholder="Username"  data-parsley-type="alphanum"  value="<?php if(isset($admin_data['username'])) echo $admin_data['username']; ?>" data-parsley-minlength="6" data-parsley-maxlength="12" data-parsley-required data-parsley-required-message="Please enter username." data-parsley-minlength-message="Username must be at least 6 characters long." data-parsley-maxlength-message="Username must be at most 12 characters long.">
                                                <p class="error" id="un_error" style="display:none;">Username already used.</p>
                                                <?php echo form_error('username'); ?>
                                            </div>
          
                                            <div class="form-group">
                                                <label for="email">Email *</label>
                                                <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="<?php if(isset($admin_data['email'])) echo $admin_data['email']; ?>" data-parsley-required data-parsley-required-message="Please enter email." data-parsley-type="email" data-parsley-type-message="Please enter valid email." >
                                                <p class="error" id="email_error" style="display:none;">Email already used.</p>
                                                <?php echo form_error('email'); ?>
                                            </div>

                                            <div class="form-group">
                                                <label for="email">Password *</label>
                                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="<?php if(isset($admin_data['password'])) echo $admin_data['password']; ?>"  minlength="6" maxlength="12" data-parsley-required data-parsley-required-message="Please enter password." >
                                               
                                                <?php echo form_error('email'); ?>
                                            </div>

                                            <div class="form-group">
                                                <label for="mobile">Mobile *</label>
                                                <input type="text" class="form-control" id="mobile" name="mobile" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" placeholder="Mobile" value="<?php if(!empty($admin_data['mobile'])) echo $admin_data['mobile']; ?>" data-parsley-required data-parsley-required-message="Please enter mobile number." data-parsley-type="number" data-parsley-minlength="10" data-parsley-minlength-message="Mobile number must be at least 10 digits long." data-parsley-maxlength-message="Mobile number must be at most 12 digits long." data-parsley-maxlength="12" data-parsley-type-message="Please enter valid mobile number">
                                                <?php echo form_error('mobile'); ?>
                                            </div>
                                          
                                            <div class="form-group">
                                                    <label class="">Image *</label>
                                                    <!-- <div class="col-sm-4"> -->
                                                        <div data-provides="fileupload" class="fileupload fileupload-new"><input type="hidden">
                                                            <div style="width: 150px; height: 120px;" class="fileupload-new thumbnail">
                                                                <!-- <img alt="No Image" src=""> -->
                                                            </div>
                                                            <div style="max-width: 150px; line-height: 5px;" class="fileupload-preview fileupload-exists thumbnail"></div>
                                                            <div>
                                                                <span class="btn btn-file"><span class="fileupload-new btn btn-default">Select image</span>
                                                                <span class="fileupload-exists">Change</span>
                                                                <input type="file" name="image" class="default" accept="image/*" data-parsley-required data-parsley-required-message="Please upload image." data-parsley-errors-container='#image_error'></span>
                                                                <a data-dismiss="fileupload" class="btn fileupload-exists error v-align-middle" href="#">Remove</a>
                                                            </div>
                                                            <div id="image_error" class="error"></div>
                                                            <?php echo isset($upload_error)?$upload_error:'';?>
                                                        </div>
                                                    <!-- </div> -->
                                                </div>
                                                 <div class="form-group">
                                                    <div class="col-sm-offset-4 col-sm-9">
                                                        <button type="submit" class="btn btn-primary">Add</button>
                                                        <a href="admin/subadmin/list" class="btn btn-default">Back</a>
                                                    </div>
                                                </div>
                                            
                                           
                                        </div>
                                   
                                </div><!-- panel body-->
                            </div>
                        </div>
                    </div><!-- row-->
                </div><!-- col-6-->

              </form>
        </div>
        </div>
        <!-- /.box -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>

<script>


$(document).ready(function(){
    $('.p_check').click(function(event) {
        var p_class = '.'+$(this).attr('name');
        if(this.checked) {
            // Iterate each checkbox
            $(p_class).each(function() {
                this.checked = true;
            });
        } else {
            $(p_class).each(function() {
                this.checked = false;
            });
        }
    });

});

$('#country').change(function(e) {
    var id = $("#country").val();
    if(id!='') {
        $('#state option').slice(1).remove();
        $('#city option').slice(1).remove();
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
var error_msg1 ='';
//check username 
$('#username').change(function(e) {
    var id = "A"; 
    var username = $("#username").val();
    
    if(id!='') {
        $.ajax({
            type:'POST',
            url: "<?php echo base_url(); ?>admin/ajax/check_username/",
            data: {id:id,username:username},
            
            success:function(data)
            {
                 if(data==1) {
                    error_msg = "Username already used." ;
                    $("#un_error").show();
                } else{
                    error_msg = "" ;
                    $("#un_error").hide();
                }
            }
        });
    }
});

//check email 
$('#email').change(function(e) {
    var id = "A"; 
    var email = $("#email").val();
    
    if(id!='') {

        $.ajax({
            type:'POST',
            url: "<?php echo base_url(); ?>admin/ajax/check_admin_email/",
            data: {id:id,email:email},
            
            success:function(data)
            {
                if(data==1) {
                    error_msg1 = "Email already used." ;
                    $("#email_error").show();
                }else{
                    error_msg1 = "" ;
                    $("#email_error").hide();
                }
            }
        });
    }
});
    
</script>
<script>
$(document).ready(function(){
    $("form").submit(function(){
      
        if(error_msg !=''){
            return false;
        }
         if(error_msg1 !=''){
            return false;
        }
    });
});
</script>



















