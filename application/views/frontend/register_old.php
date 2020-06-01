<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

  <!-- ======= Hero Section ======= -->
  <div class="main">
    <div class="container">
        <div class="signup-content">
            <div class="signup-img">
                <img src="<?php echo base_url()?>assets/front/img/signup-img.jpg" alt="">
            </div>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <div class="signup-form " style="border: 2px solid #12c0dc; padding: 15px;">
                <form class="" id="form" method="POST" action="<?php echo $from_action; ?>" enctype="multipart/form-data" role="form"  data-parsley-validate>
              
                     <h2 class="main-heading" style="border-bottom:0!important;    font-weight: bold;"><span style="color: #1e500c!important"><?php echo $this->lang->line('registration');?></span> <span style="color: #12c0dc;"> <?php echo $this->lang->line('form');?></span> </h2>
                    
                        <div class="form-group">
                            <label for="name"><?php echo $this->lang->line('full_name');?> :</label>
                            <input type="text" placeholder="<?php echo $this->lang->line('full_name');?>"  name="name" id="name"  data-parsley-pattern="^[a-z A-Z 0-9 ]+$"  data-parsley-required data-parsley-required-message="<?php echo $this->lang->line('full_name_error');?>"  maxlength="20"/>
                        </div>
                         <div class="form-row">
                        <div class="form-group">
                          <label for="email"><?php echo $this->lang->line('email');?> :</label>
                          <input type="email"  placeholder="<?php echo $this->lang->line('email');?>"  name="email" id="email"  data-parsley-required data-parsley-required-message="<?php echo $this->lang->line('email_error');?>" maxlength="20" />
                          <span class="parsley-required" style="color: red" id="already_email_error"><?php echo $this->lang->line('already_email_error');?></span>
                      </div>
                       <div class="form-group">
                        <label for="address"><?php echo $this->lang->line('address');?> :</label>
                        <input type="text" name="address" id="address"  placeholder="<?php echo $this->lang->line('address');?>"   data-parsley-required data-parsley-required-message="<?php echo $this->lang->line('address_error');?>" maxlength="20" />
                    </div>
                        
                     </div>

                    <div class="form-row">
                        <div class="form-group"> 
                            <label for="birth_date"><?php echo $this->lang->line('country_code');?> :</label>
                            <select   name="country_code"  data-parsley-required data-parsley-required-message="<?php echo $this->lang->line('country_code_error');?>">
                            <option value=''><?php echo $this->lang->line('country_code');?></option>
                            <?php if(isset($countries)) {
                              foreach($countries as $country) { ?>
                                <option value=<?php echo $country['phonecode'];?>><?php echo $country['name'].' ('.$country['phonecode'].')';?></option>";
                              <?php }
                            } ?>
                          </select>
                        </div>
                        <div class="form-group"> 
                            <label for="birth_date"><?php echo $this->lang->line('mobile');?> :</label>
                            <input type="text" name="mobile" oninput="mobile_check()" id="mobile" placeholder="<?php echo $this->lang->line('mobile');?>"  maxlength="20"   data-parsley-required data-parsley-required-message="<?php echo $this->lang->line('mobile_error');?>">
                             <span class="parsley-required" style="color: red" id="already_mobile_error"><?php echo $this->lang->line('already_mobile_error');?></span>
                        </div>
                     </div> 

                    <div class="form-row">
                          <div class="form-group">
                            <label for="password"><?php echo $this->lang->line('password');?> :</label>
                            <input type="password" name="password" id="password"  placeholder="<?php echo $this->lang->line('password');?>"  data-parsley-required data-parsley-required-message="<?php echo $this->lang->line('password_error');?>" maxlength="20" />
                        </div>
                        <div class="form-group">
                          <label for="birth_date"><?php echo $this->lang->line('confirm_password');?> :</label>
                          <input type="password"  placeholder="<?php echo $this->lang->line('confirm_password');?>"   data-parsley-equalto="#password" data-parsley-equalto-message="<?php echo $this->lang->line('confirm_password_error_same');?>" maxlength="20"  data-parsley-required data-parsley-required-message="<?php echo $this->lang->line('confirm_password_error');?>" name="confirm_password" id="confirm_password">
                      </div>
                    </div>
                   
                  
                    <div class="form-row">
                        <div class="form-group">
                           <label for="state"><?php echo $this->lang->line('country');?> :</label>
                           <select  id="country" name="country"  data-parsley-required data-parsley-required-message="<?php echo $this->lang->line('country_error');?>">
                            <option value=''><?php echo $this->lang->line('select_country')?></option>
                            <?php if(isset($countries)) {
                              foreach($countries as $country) { ?>
                                <option value=<?php echo $country['id'];?> <?php if(!empty($admin_data['country']) && ($admin_data['country']==$country['id'])) {echo "selected";} ?>><?php echo $country['name'];?></option>";
                              <?php }
                            } ?>
                          </select>
                        </div> 

                        <div class="form-group">
                            <label for="state"><?php echo $this->lang->line('state');?> :</label>
                            <div class="form-select" >
                                <select name="state" id="state"  data-parsley-required data-parsley-required-message="<?php echo $this->lang->line('state_error');?>">
                                    <option value="" id='first'><?php echo $this->lang->line('select_state')?></option>
                                 
                                </select>
                                <span class="select-icon"><i class="zmdi zmdi-chevron-down"></i></span>
                            </div>
                        </div>
                    </div>
                   
                    <div class="form-row">
                        <div class="form-group">
                            <label for="city"><?php echo $this->lang->line('city');?> :</label>
                            <div class="form-select">
                                <select name="city" id="city"  data-parsley-required data-parsley-required-message="<?php echo $this->lang->line('city_error');?>">
                                    <option value="" id='city_first'><?php echo $this->lang->line('select_city')?></option>
                                  
                                </select>
                                <span class="select-icon"><i class="zmdi zmdi-chevron-down"></i></span>
                            </div>
                        </div>

                 
                    </div>
                   
                    <div class="form-group">
                        <label for="course"><?php echo $this->lang->line('user_type');?> :</label>
                        <div class="form-select">
                            <select onchange="change_user_type()" name="user_type" id="user_type"   data-parsley-required data-parsley-required-message="<?php echo $this->lang->line('user_type_error');?>">
                                <option value=""><?php echo $this->lang->line('select_user_type')?></option>
                                <option value="doctor"><?php echo $this->lang->line('doctor');?></option>
                                <option value="patient"><?php echo $this->lang->line('patient');?></option>
                                <!--<option value="mediacal_student"><?php echo $this->lang->line('mediacal_student');?></option>-->
                            </select>
                            <span class="select-icon"><i class="zmdi zmdi-chevron-down"></i></span>
                        </div>
                    </div>

                     <div class="form-group category_name" style="display: none;">
                           <label for="state"><?php echo $this->lang->line('category');?> :</label>
                           <select  id="category" name="category_id"  data-parsley-required-message="<?php echo $this->lang->line('category_error');?>">
                            <option value=''><?php echo $this->lang->line('category_select')?></option>
                            <?php if(isset($category_list)) {
                              foreach($category_list as $category) { ?>
                                <option value=<?php echo $category['category_id'];?> ><?php echo $category['name'];?></option>";
                              <?php }
                            } ?>
                          </select>
                        </div> 
                  
                    <div class="form-submit">
                        <input type="reset" value="<?php echo $this->lang->line('reset');?>" class="submit" name="reset" id="reset"  />
                        <input type="button" value="<?php echo $this->lang->line('submit');?>"  onclick="submit_form()" class="submit" name="submit" id="submit"  />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
 <br>
<style type="text/css">
    .digit {
    width: 25%;
    float: left;
}
</style>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"  aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">OTP</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
             <div class="form-group">

                <div class="col-sm-12 text-center otp_error" style="color: red">
                   
                </div>
                <div class="col-sm-12 text-center success_msg" style="color: green">
                    <input name="firstdigit" class="digit text-center" type="password" required id="one" size="1" maxlength="1" tabindex="0">
                    <input name="secondtdigit" class="digit text-center" type="password" required id="two" size="1" maxlength="1" tabindex="1">
                    <input name="thirddigit" class="digit text-center" type="password" required id="three" size="1" maxlength="1"  tabindex="2" >
                    <input name="fourthdigit" class="digit text-center" type="password" required id="four" size="1" maxlength="1" tabindex="3">
                    <input type="hidden" required id="user_id" >
                </div>
            </div>
      </div>
      <div class="modal-footer">
         <button type="submit" class="btn btn-success digit" onclick="otp_send()" tabindex="3">Confirm</button> 
      </div>
    </div>
  </div>
</div>
  
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
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
//check username 
 $("#already_email_error").hide();
//check email 
$('#email').change(function(e) {

    var email = $("#email").val();
    
        $.ajax({
            type:'POST',
            url: "<?php echo base_url(); ?>admin/ajax/check_user_email/",
            data: {email:email},
            
            success:function(data)
            {
                if(data==1) {
                    error_msg = "Email already used." ;
                    $("#already_email_error").show();
                } else{
                    error_msg = "" ;
                    $("#already_email_error").hide();
                }
            }
        });
});


//check Mail 
error_msg2 = '';
 $("#already_mobile_error").hide();
function mobile_check() {

    var mobile = $("#mobile").val();
    
        $.ajax({
            type:'POST',
            url: "<?php echo base_url(); ?>check_user_mobile/",
            data: {mobile:mobile}, 
            success:function(data)
            {
                if(data==1) {
                    error_msg2 = "Mobile already used." ;
                    $("#already_mobile_error").show();
                } else{
                    error_msg2 = "" ;
                    $("#already_mobile_error").hide();
                }
            }
        });
}


function otp_send(){

    var one = $("#one").val();
    var two = $("#two").val();
    var three = $("#three").val();
    var four = $("#four").val();

    if(one!='' && two!='' && three!='' && four!=''){
        $(".otp_error").html('');
        var user_id =$("#user_id").val();
        var code = one+two+three+four;
         $.ajax({
            url: '<?php echo base_url().'verify_account'?>',
            data:{'code':code,'user_id':user_id} ,
            type: 'POST', 
            dataType:'json',   
            success: function(data){
                    if(data.data.status==1){
                         $(".otp_error").html('');
                        $(".success_msg").html('<?php echo $this->lang->line('congratulations');?>'+' '+'<?php echo $this->lang->line('account_created');?> !');
                        setTimeout(function() { location.reload();}, 3000);

                    }else{
                         $(".otp_error").html('<?php echo $this->lang->line('otp_not_match');?>')
                    }
            }
          }) 
     }else{
        $(".otp_error").html('<?php echo $this->lang->line('otp_required');?>')
     }

}

function submit_form(){

  $("#form").parsley().validate();
   if ( $("#form").parsley().isValid() ) {

        var form = $('#form')[0];

        var data = new FormData(form);
        if(error_msg=='' && error_msg2==''){
        $.ajax({
            url: '<?php echo base_url().'signup'?>',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST', 
            dataType:'json',   
            success: function(data){

                if(data.data.status==2){
                    $("#user_id").val(data.data.user_id);
                   jQuery("#exampleModal").modal('show')
                }else{
                    $("#message_show").html(data.data.msg);
                }
            }
          }) 
        }
    }   
}


function change_user_type(){

    user_type = $("#user_type").val();
    
    if(user_type=='doctor'){
        $(".category_name").show();
        $("#category").attr(' data-parsley-required',true);
    }else{
         $(".category_name").hide();
           $("#category").attr(' data-parsley-required',false);
    }

}


</script>