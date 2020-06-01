<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

  <!-- ======= Hero Section ======= -->
  <div class="main">
    <div class="container">
        <div class="signup-content"  style="border: 2px solid #12c0dc; padding: 15px;">
            <div class="signup-form">
                <form class="" id="form" method="POST" action="<?php echo $from_action; ?>" enctype="multipart/form-data" role="form"  data-parsley-validate>
                     <h2 class="main-heading" style="border-bottom:0!important;    font-weight: bold;"><span style="color: #1e500c!important"><?php echo $this->lang->line('login');?></span> <span style="color: #12c0dc;"> <?php echo $this->lang->line('form');?></span> </h2>
                    <div class="form-row col-sm-12">
                        <div class="form-row col-sm-3"></div>
                        <div class="form-row col-sm-8">
                            <div class="form-row col-sm-12">
                                <div class="form-group">
                                  <label for="email"><?php echo $this->lang->line('email');?> / <?php echo $this->lang->line('mobile');?></label>
                                  <input type="text" maxlength="20" placeholder="<?php echo $this->lang->line('email');?> / <?php echo $this->lang->line('mobile');?>"  name="email" id="email"  data-parsley-required data-parsley-required-message="<?php echo $this->lang->line('email_error').' / '.$this->lang->line('mobile');?>" />
                                  
                                </div>

                                <div class="form-group">
                                        <label for="password"><?php echo $this->lang->line('password');?> :</label>
                                        <input type="password"  maxlength="20" name="password" id="password"  placeholder="<?php echo $this->lang->line('password');?>"  data-parsley-required data-parsley-required-message="<?php echo $this->lang->line('password_error');?>" />
                                </div>
                            </div>
                             <div class="form-row col-sm-12">
                                   <div class="form-row col-sm-4"></div>
                                 <div class="">
                                    <input type="reset" value="<?php echo $this->lang->line('reset');?>" class="submit" name="reset" id="reset" />
                                    <input type="button" value="<?php echo $this->lang->line('login');?>"  onclick="submit_form()" class="submit" name="submit" id="submit" />
                                </div>
                            </div>

                             <div class="form-row col-sm-12">
                                   <div class="form-row col-sm-4"></div>
                                 <div class="">
                                   <a style="font-size: 13px;" href="<?php echo base_url().'forgot_password'?>"><?php echo $this->lang->line('forgot').' '.$this->lang->line('password');?></span></a>
                                </div>
                            </div>
                         </div>
                     </div> 
                </form>
            </div>
        </div>
    </div>
</div> 
 
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
                        setTimeout(function() { location.reload();}, 5000);

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
                    $("#user_id").val(data.data.user_id);
                   jQuery("#exampleModal").modal('show')
                }else{

                    $("#message_show").html(data.data.msg);
                    if(data.data.status==1){
                          setTimeout(function() { location.reload();}, 2000);
                    }
                }
            }
          }) 
        
    }   
}


    
</script>