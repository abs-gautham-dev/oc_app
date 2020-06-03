 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<section class="why-chose-section py-3">
  <div class="container">
    <div class="form-section">
         <form class="" id="form" method="POST" action="" enctype="multipart/form-data" role="form"  data-parsley-validate>
          
          <select  name="company_id"  class="js-example-basic-single" data-parsley-required data-parsley-required-message="Select Company Name">
            <option value="">Select Company</option>
            <?php if(!empty($company_list)){ 
              foreach ($company_list as $key => $list) { ?>
               <option value="<?php echo $list['user_id']?>"><?php echo ucwords($list['full_name'])?></option>
            <?php  }

            }?>
          </select>

            <input type="text" data-parsley-pattern="^[a-z A-Z 0-9 ]+$" name="contact_name" data-parsley-required data-parsley-required-message="Enter Contact Name"  placeholder="Contact Name"  maxlength="30" >
            <input data-parsley-required data-parsley-required-message="Enter Email" name="email" type="email" maxlength="30"  placeholder="Email"> 
            <input oninput ="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" maxlength="12"  data-parsley-required data-parsley-required-message="Enter Telephone Number" name="mobile" type="text" placeholder="Telephone Number">
            <textarea data-parsley-required data-parsley-required-message="Enter Address" name="address" placeholder="Address" row="10"></textarea>

            <a href="#" class="find-job-button"  onclick="submit_form('form')">Send Order</a>
        </form>
    </div>
  </div>
</section> 
</body>
</html>
<script type="text/javascript">
  $(document).ready(function() {
    $('.js-example-basic-single').select2();
});
  function submit_form(id){

  $("#form").parsley().validate();
   if ( $("#form").parsley().isValid() ) {

        var form = $("#form")[0];

        var data = new FormData(form);
        $.ajax({
            url: '<?php echo base_url().'sales/create_order'?>',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST', 
            dataType:'json',   
            success: function(data){
                if(data.status==1){
                    $("#message_show").html(data.msg);
                    setTimeout(function(){
                      window. location="<?php echo base_url().'sales/order-list'?>";}, 3000);
                }else{
                    $("#message_show").html(data.msg);
                }
            }
          }) 
    }   
}
</script>