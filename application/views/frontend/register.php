<div class="bredcum">
  <div class="container">
    <div class="col-md-6">
      <ul class="breadcrumb">
        <li><a href="#" class="page-name">Registration</a></li>
      </ul>
    </div>
  <div class="col-md-6">
    <ul class="breadcrumb">
    <li><a href="#">Home</a></li>
    <li><a href="#">Registration</a></li>
    </ul>
  </div>
  </div>
</div>
<section class="main-section">
  <div class="container">
    
      <div class="col-xs-12 col-md-12">
      <div class="login-page registration">
      <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home">Inspector</a></li>
    <li><a data-toggle="tab" href="#menu1">Company</a></li>
    <li><a data-toggle="tab" href="#menu2">Sales</a></li>
    <li class="pull-right up-res">Already registered ? <a  href="<?=base_url()?>welcome"> Login Now</a></li>
  </ul>

  <div class="tab-content">
    <span class="success_new"></span>
    <div id="home" class="tab-pane fade in active">
      
      <form class="" id="form" method="POST" action="<?php echo $from_action; ?>" enctype="multipart/form-data" role="form"  data-parsley-validate>
      <div class="row">
        <input type="hidden" name="user_type" value="Inspectors">
        <div class="col-md-4">
        <input type="text" name="name" placeholder="Company Name" data-parsley-pattern="^[a-z A-Z 0-9 ]+$" data-parsley-required data-parsley-required-message="Enter Company Name">
        </div>
        <div class="col-md-4">
        <input type="email" id="email" name="email" placeholder="Official Email" data-parsley-required data-parsley-required-message="<?php echo $this->lang->line('email_error');?>" maxlength="20" />
        <span class="parsley-required already_email_error" style="color: red" id="already_email_error"><?php echo $this->lang->line('already_email_error');?></span>
        </div>
        <div class="col-md-4">
        <input type="password" name="password" id="password"  placeholder="<?php echo $this->lang->line('password');?>"  data-parsley-required data-parsley-required-message="<?php echo $this->lang->line('password_error');?>" maxlength="20" />
        </div>
        <div class="col-md-4">
        <input type="text" name="mobile" oninput="mobile_check()" id="mobile" placeholder="<?php echo $this->lang->line('mobile');?>"  maxlength="20"   data-parsley-required data-parsley-required-message="<?php echo $this->lang->line('mobile_error');?>">
          <span class="parsley-required" style="color: red" id="already_mobile_error"></span>
        </div>
        <div class="col-md-4">
        <input type="text" name="address" placeholder="Current Location" data-parsley-required data-parsley-required-message="Enter Current Location">
        </div>
        <div class="col-md-4">
        <input type="text" placeholder="Pin Code" name="pin_code" data-parsley-required data-parsley-required-message="Enter Pin Code">
        </div>
        <div class="col-md-8">
        <input type="file" name="profile_pic" placeholder="Upload Resume (*.doc, *.docx, *.rtf, *.txt, *.pdf) Max. 6 MB">
        <span> <i class="fa fa-upload" aria-hidden="true"></i> Choose File </span>
        </div>
        <div class="col-md-4">
        <input type="button" class="submit" value="Sign Up" name="submit" id="submit" onclick="submit_form('form')"/> 
        </div>
      </div>

</form>
    </div>
    <div id="menu1" class="tab-pane fade">
 <form class="" id="form1" method="POST" action="<?php echo $from_action; ?>" enctype="multipart/form-data" role="form"  data-parsley-validate>
      <div class="row">
        <input type="hidden" name="user_type" value="Companies">
        <div class="col-md-4">
        <input type="text" name="name" placeholder="Company Name" data-parsley-pattern="^[a-z A-Z 0-9 ]+$" data-parsley-required data-parsley-required-message="Enter Company Name">
        </div>
        <div class="col-md-4">
        <input type="email" name="email" id="email1" placeholder="Official Email" data-parsley-required data-parsley-required-message="<?php echo $this->lang->line('email_error');?>" maxlength="20" />
        <span class="parsley-required already_email_error" style="color: red" id="already_email_error"><?php echo $this->lang->line('already_email_error');?></span>
        </div>
        <div class="col-md-4">
        <input type="password" name="password" id="password"  placeholder="<?php echo $this->lang->line('password');?>"  data-parsley-required data-parsley-required-message="<?php echo $this->lang->line('password_error');?>" maxlength="20" />
        </div>
        <div class="col-md-4">
        <input type="text" name="mobile" oninput="mobile_check()" id="mobile1" placeholder="<?php echo $this->lang->line('mobile');?>"  maxlength="20"   data-parsley-required data-parsley-required-message="<?php echo $this->lang->line('mobile_error');?>">
          <span class="parsley-required" style="color: red" id="already_mobile_error"></span>
        </div>
        <div class="col-md-4">
        <input type="text" name="address" placeholder="Current Location" data-parsley-required data-parsley-required-message="Enter Current Location">
        </div>
        <div class="col-md-4">
        <input type="text" placeholder="Pin Code" name="pin_code" data-parsley-required data-parsley-required-message="Enter Pin Code">
        </div>
        <div class="col-md-8">
        <input type="file" name="profile_pic" placeholder="Upload Resume (*.doc, *.docx, *.rtf, *.txt, *.pdf) Max. 6 MB">
        <span> <i class="fa fa-upload" aria-hidden="true"></i> Choose File </span>
        </div>
        <div class="col-md-4">
        <input type="button" class="submit" value="Sign Up" name="submit" id="submit" onclick="submit_form('form1')"/> 
        </div>
      </div>

</form>
    </div>
    <div id="menu2" class="tab-pane fade">
<form class="" id="form2" method="POST" action="<?php echo $from_action; ?>" enctype="multipart/form-data" role="form"  data-parsley-validate>
  <input type="hidden" name="user_type" value="Sales">
      <div class="row">
        <div class="col-md-4">
        <input type="text" name="name" placeholder="Company Name" data-parsley-pattern="^[a-z A-Z 0-9 ]+$" data-parsley-required data-parsley-required-message="Enter Company Name">
        </div>
        <div class="col-md-4">
        <input type="email" id="email2" name="email" placeholder="Official Email" data-parsley-required data-parsley-required-message="<?php echo $this->lang->line('email_error');?>" maxlength="20" />
        <span class="parsley-required already_email_error" style="color: red" id="already_email_error"><?php echo $this->lang->line('already_email_error');?></span>
        </div>
        <div class="col-md-4">
        <input type="password" name="password" id="password"  placeholder="<?php echo $this->lang->line('password');?>"  data-parsley-required data-parsley-required-message="<?php echo $this->lang->line('password_error');?>" maxlength="20" />
        </div>
        <div class="col-md-4">
        <input type="text" name="mobile" oninput="mobile_check()" id="mobile2" placeholder="<?php echo $this->lang->line('mobile');?>"  maxlength="20"   data-parsley-required data-parsley-required-message="<?php echo $this->lang->line('mobile_error');?>">
          <span class="parsley-required" style="color: red" id="already_mobile_error"></span>
        </div>
        <div class="col-md-4">
        <input type="text" name="address" placeholder="Current Location" data-parsley-required data-parsley-required-message="Enter Current Location">
        </div>
        <div class="col-md-4">
        <input type="text" placeholder="Pin Code" name="pin_code" data-parsley-required data-parsley-required-message="Enter Pin Code">
        </div>
        <div class="col-md-8">
        <input type="file" name="profile_pic" placeholder="Upload Resume (*.doc, *.docx, *.rtf, *.txt, *.pdf) Max. 6 MB">
        <span> <i class="fa fa-upload" aria-hidden="true"></i> Choose File </span>
        </div>
        <div class="col-md-4">
        <input type="button" class="submit" value="Sign Up" name="submit" id="submit" onclick="submit_form('form2')"/> 
        </div>
      </div>

</form>
    </div>
  </div>


      </div>
      </div>
  </div>
</section>
<section class="above-footer">
  <div class="container">
    <h3> Are You Looking for Someone to Hire? </h3>
    <p> 24000 companies have found the employer that they were Searching for. Post your job advert now and reach to Millions of job seekers fast and easy. </p>
    <button class="submit card-btn"> Post a Job Now </button> <a href="#" class="cell-phone card"><i class="fa fa-phone"></i> +447774018889  </a>
  </div>
</section>
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
 $(".already_email_error").hide();
//check email 
$('#email,#email1,#email2').change(function(e) {

    var email = $('#email,#email1,#email2').val();
    
        $.ajax({
            type:'POST',
            url: "<?php echo base_url(); ?>admin/ajax/check_user_email/",
            data: {email:email},
            
            success:function(data)
            {
                if(data==1) {
                    error_msg = "Email already used." ;
                    $(".already_email_error").show();
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

    var mobile = '';
    if($("#mobile").val()!=''){
      mobile = $("#mobile").val();
    }else if($("#mobile1").val()!=''){
      mobile = $("#mobile1").val();
    }else{
      mobile = $("#mobile2").val();
    }
    
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

function submit_form(id){

  $("#"+id).parsley().validate();
   if ( $("#"+id).parsley().isValid() ) {

        var form = $("#"+id)[0];

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
                   jQuery("#exampleModal").modal('show');
                   alert(data.data.msg);
                   jQuery(".success_new").html(data.data.msg);
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
