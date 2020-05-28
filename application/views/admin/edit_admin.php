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

                                    <form class="" id="" method="POST" action="<?php echo current_url(); ?>" role="form" data-parsley-validate>
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label for="fullname" >Full Name *</label>
                                                <input type="text" class="form-control" name="fullname" id="fullname"  data-parsley-pattern="^[a-z A-Z 0-9 ]+$"  value="<?php if(isset($admin_data['fullname'])) echo $admin_data['fullname']; ?>" placeholder="Fullname" data-parsley-required data-parsley-required-message="Please enter fullname.">
                                                <?php echo form_error('fullname'); ?>
                                            </div>

                                            <div class="form-group">
                                                <label for="username">Username *</label>
                                                <input type="text" readonly class="form-control" id="username" name="username" data-parsley-required data-parsley-type="alphanum" placeholder="Username" value="<?php if(isset($admin_data['username'])) echo $admin_data['username']; ?>" data-parsley-minlength="6" data-parsley-maxlength="12" data-parsley-required data-parsley-required-message="Please enter username." data-parsley-minlength-message="Username must be at least 6 characters long." data-parsley-maxlength-message="Username must be at most 12 characters long.">
                                                <p class="error" id="un_error" style="display:none;">Username already used.</p>
                                                <?php echo form_error('username'); ?>
                                            </div>
          
                                            <div class="form-group">
                                                <label for="email">Email *</label>
                                                <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="<?php if(isset($admin_data['email'])) echo $admin_data['email']; ?>" data-parsley-required data-parsley-required-message="Please enter valid email." data-parsley-type="email" data-parsley-type-message="Please enter valid email." >
                                                <p class="error" id="email_error" style="display:none;">Email already used.</p>
                                                <?php echo form_error('email'); ?>
                                            </div>

                                            <div class="form-group">
                                                <label for="email">Password *</label>
                                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="<?php if(isset($admin_data['password'])) echo  base64_decode($admin_data['password']); ?>"  minlength="6" maxlength="12" data-parsley-required data-parsley-required-message="Please enter password." >
                                               
                                                <?php echo form_error('email'); ?>
                                            </div>

                                            <div class="form-group">
                                                <label for="mobile">Mobile *</label>
                                                <input type="text" class="form-control" id="mobile" name="mobile" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" placeholder="Mobile" value="<?php if(!empty($admin_data['mobile'])) echo $admin_data['mobile']; ?>" data-parsley-required data-parsley-required-message="Please enter mobile number." data-parsley-type="number" data-parsley-minlength="10" data-parsley-minlength-message="Mobile number must be at least 10 digits long." data-parsley-maxlength-message="Mobile number must be at most 12 digits long." data-parsley-maxlength="12" data-parsley-type-message="Please enter valid mobile number">
                                                <?php echo form_error('mobile'); ?>
                                            </div>
                                           
                                            <div class="box-footer">
                                                <div class="form-group">
                                                    <div class="col-sm-offset-3 col-sm-9">
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                        <a href="admin/subadmin/list" class="btn btn-default">Back</a>
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
                                    </form>
                                </div><!-- panel body-->
                            </div>
                        </div>
                    </div><!-- row-->
                </div><!-- col-6-->

                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-body">
                            <form class="form-horizontal">
                                <div class="col-md-6 text-center">
                                    <input type="hidden" id="admin_id" name="admin_id" value="<?php echo $admin_data['admin_id']; ?>" />
                                    <div class="control-group">
                                        <div class="box-body">
                                            <label class="">Profile Image *</label>
                                            <div class="controls">
                                                <div data-provides="fileupload" class="fileupload fileupload-new">
                                                    <div class="fileupload-new thumbnail" style="width: auto;max-width: 150px;">
                                                        <img alt="No Image"src="<?php echo !empty($admin_data['profile_pic'])?base_url().$admin_data['profile_pic']:'';?>" style="width: auto;">
                                                    </div>
                                                    <div style="max-width: 150px; max-height: 150px; line-height: 5px;" class="fileupload-preview fileupload-exists thumbnail"></div>
                                                    <div>
                                                        <span class="btn btn-file">
                                                            <span class="fileupload-new btn btn-default">Change Image</span>
                                                                <span class="btn fileupload-exists">Change</span>
                                                                <input type="file" name="image" id="image" class="default" accept="image/*">
                                                            </span>
                                                            <a data-dismiss="fileupload" class="btn remove fileupload-exists" href="#">Remove</a>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group"></div>
                                    <div class="form-group"></div>
                                    <div class="form-group"></div>
                                    <div class="form-group">
                                        <div class="col-md-4">
                                         <button type="button" id="img_upload_button" class="btn btn-info upload_profile_pic">Upload</button>
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
                     <?php $admin_id = $this->session->userdata('admin_id'); 
                                if($admin_id == '1'){
                                ?>
                    <form  method="POST" action="<?php echo site_url(); ?>admin/user/user_access/<?php if(isset($admin_data['admin_id'])) echo $admin_data['admin_id']; ?>">
                    <div class="box box-primary">
                        <div class="box-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Sr.No.</th>
                                    <th>Section</th>
                                    <th><input class="align-right p_check" type="checkbox" name="view" value="1"> View</th>
                                    <th><input class="align-right p_check" type="checkbox" id="add" name="add" value="1"> Add </th>
                                    <th><input class="align-right p_check" type="checkbox" id="edit" name="edit" value="1"> Edit </th>
                                    <th><input class="align-right p_check" type="checkbox" id="delete" name="delete" value="1"> Delete </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if(!empty($permission))
                                {   
                                    $i = ($this->uri->segment(6)) ? $this->uri->segment(6) : 0;
                                    foreach ($permission as $row) { $i++; ?>
                                        <tr id="tr_<?php echo $row['id']; ?>">
                                            <td><?php echo $i; ?></td> 
                                            <td class="align-right"><?php if(!empty($row['name'])) echo $row['name']; ?></td>
                                            <input class="align-right" type="hidden" name="sections[]" value="<?php echo  $row['section_id']; ?>">
                                            <td>
                                                <?php if($row['view']==1) { ?>
                                                    <input class="align-right view" type="checkbox" name="permission[<?php echo  $row['section_id']; ?>][view]" checked>
                                                <?php } else { ?>
                                                    <input class="align-right view" type="checkbox" name="permission[<?php echo  $row['section_id']; ?>][view]" >
                                                <?php }?>
                                            </td>
                                            <td>
                                                <?php if($row['add']==1) { ?>
                                                    <input class="align-right add" type="checkbox" name="permission[<?php echo  $row['section_id']; ?>][add]" checked>
                                                <?php } else { ?>
                                                    <input class="align-right add" type="checkbox" name="permission[<?php echo  $row['section_id']; ?>][add]">
                                                <?php }?>
                                            </td>
                                            <td>
                                                <?php if($row['edit']==1) { ?>
                                                    <input class="align-right edit" type="checkbox" name="permission[<?php echo  $row['section_id']; ?>][edit]" checked>
                                                <?php } else { ?>
                                                    <input class="align-right edit" type="checkbox" name="permission[<?php echo  $row['section_id']; ?>][edit]">
                                                <?php }?>
                                            </td>
                                            <td>
                                                <?php if($row['delete']==1) { ?>
                                                    <input class="align-right delete" type="checkbox" name="permission[<?php echo  $row['section_id']; ?>][delete]" checked>
                                                <?php } else { ?>
                                                    <input class="align-right delete" type="checkbox" name="permission[<?php echo  $row['section_id']; ?>][delete]">
                                                <?php }?>
                                            </td>
                                            <!-- <td><input class="align-right view" type="checkbox" name="permission[<?php //echo  $row['id']; ?>][view]" value="1"></td>
                                            <td><input class="align-right add" type="checkbox" name="permission[<?php //echo  $row['id']; ?>][add]" value="1"></td>
                                            <td><input class="align-right edit" type="checkbox" name="permission[<?php //echo  $row['id']; ?>][edit]" value="1"></td>
                                            <td><input class="align-right delete" type="checkbox" name="permission[<?php //echo  $row['id']; ?>][delete]" value="1"></td> -->
                                        </tr>
                                    <?php } ?>    
                                <?php } else {
                                    echo "<tr><td colspan='7' align='center'> No Record Found</td></tr>";
                                } ?>
                            </tbody>
                        </table>
                       <div class="box-footer">
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <a href="admin/subadmin/list" class="btn btn-default">Back</a>
                                </div>
                            </div>
                        </div>    
                    </form>
                        </div><!-- panel body-->
                    </div>
                    <?php  } ?>
                </div><!-- col-12-->
                <!-- /.box-body -->
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
//check username 
$('#username').change(function(e) {
    var id = "<?php echo $admin_data['admin_id']; ?>"; 
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
                }
            }
        });
    }
});

//check email 
$('#email').change(function(e) {
    var id = "<?php echo $admin_data['admin_id']; ?>"; 
    var email = $("#email").val();
    if(id!='') {

        $.ajax({
            type:'POST',
            url: "<?php echo base_url(); ?>admin/ajax/check_admin_email/",
            data: {id:id,email:email},
            
            success:function(data)
            {
                if(data==1) {
                    error_msg = "Email already used." ;
                    $("#email_error").show();
                } else{
                    error_msg = "" ;
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
      
        if(error_msg!=''){
            return false;
        }
    });
});
</script>



















