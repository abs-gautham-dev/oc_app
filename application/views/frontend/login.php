<div class="bredcum">
  <div class="container">
    <div class="col-md-6">
      <ul class="breadcrumb">
        <li><a href="#" class="page-name">Login</a></li>
      </ul>
    </div>
  <div class="col-md-6">
    <ul class="breadcrumb">
    <li><a href="#">Home</a></li>
    <li><a href="#">Login</a></li>
    </ul>
  </div>
  </div>
</div>
<section class="main-section">
  <div class="container">
    
      <div class="col-xs-12 col-md-6">
      <div class="login-page">
        <h3> Login as Company </h3>
        <small> You are just a step away from your dream job. </small>
        <form id="form" method="POST" action="<?php echo $from_action; ?>" enctype="multipart/form-data" role="form"  data-parsley-validate>
        <input type="email" placeholder="Enter Email" maxlength="20" name="email" id="email"  data-parsley-required data-parsley-required-message="<?php echo $this->lang->line('email_error') ?>">
        <input type="password" placeholder="Enter Password" maxlength="20" name="password" id="password"  placeholder="<?php echo $this->lang->line('password');?>"  data-parsley-required data-parsley-required-message="<?php echo $this->lang->line('password_error');?>">
          <a href="#"> Forgot Password </a>
          <input type="button" value="<?php echo $this->lang->line('login');?>"  onclick="submit_form()" class="submit" name="submit" id="submit" />
          <button class="submit-signbup"><a href="<?=base_url()?>welcome/signup">New to Office Checkers? Sign Up</a> </button>
        </form>
      </div>
      </div>
      <div class="col-xs-12 col-md-6">
      <div class="card-login">
        <h3> Are You Looking for Someone to Hire? </h3>
        <a href="#" class="cell-phone card"><i class="fa fa-phone"></i> +447774018889  </a>
        <button class="submit card-btn"> Post a Job Now </button>
      </div>
      </div>
    
  </div>
</section>
<script>
  function submit_form(){

  $("#form").parsley().validate();
   if ( $("#form").parsley().isValid() ) {

        var form = $('#form')[0];

        var data = new FormData(form);
      
        $.ajax({
            url: '<?php echo base_url()?>',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST', 
            dataType:'json',   
            success: function(data){
                if(data.data.status==2){
                    alert('login done');
                }else{
                  alert(data.data.msg);
                    $("#message_show").html(data.data.msg);
                    if(data.data.status==1){
                          //setTimeout(function() { location.reload();}, 2000);
                    }
                }
            }
          }) 
        
    }   
}
</script>

