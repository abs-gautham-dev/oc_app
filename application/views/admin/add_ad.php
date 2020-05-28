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
        <div class="box box-primary">
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-8  col-lg-offset-2">
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
                        <div class="panel panel-primary">
                            <div class="panel-body">
                              
                                <form class="" method="POST" action="" enctype="multipart/form-data" role="form"  data-parsley-validate>
                                       
                                    <div class="box-body">
                                         <div class="form-group">
                                              
                                                <label for="Category">Category *</label>
                                                <select  class="form-control category" id="category" name="category_id" data-parsley-required data-parsley-required-message="Please Select Category."> 
                                                    <option value="">Please Select Category</option>
                                                    <?php if(isset($categories)) {
                                                        foreach($categories as $category) { 

                                                            ?>
                                                            <option  value=<?php echo $category['category_id'];?>><?php echo $category['name'];?></option>
                                                        <?php }
                                                    } ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="state">Sub Category *</label>
                                                <select class="form-control" id="sub_category" name="sub_category_id">
                                                    <option value='' id='first'>Select Sub Category</option> 
                                                   
                                                    
                                                </select>
                                            </div>
                                 
                                      <!--   <div class="form-group">
                                            <label for="name">Title *</label>
                                                <input type="text" class="form-control" name="title" id="title" value="<?php echo set_value('title'); ?>" placeholder="Title" maxlength="50"   data-parsley-required data-parsley-required-message="Please enter title.">
                                                <?php echo form_error('title');?>
                                        </div> -->
                                        <div class="form-group">
                                            <label for="name">Phone *</label>
                                                <input type="text" class="form-control" name="phone_number" id="phone_number" value="<?php echo set_value('phone'); ?>" placeholder="Phone" maxlength="50"   data-parsley-required data-parsley-required-message="Please enter phone.">
                                                <?php echo form_error('title');?>
                                        </div>
                                         <div class="form-group">
                                            <label for="content" >Detail *</label>
                                            <textarea name="detail" placeholder="Detail" class="form-control" id="ckeditor" rows="3" ></textarea>
                                            <?php echo form_error('content');?>
                                        </div>

                                        <div class="form-group">
                                            <label class="">Image </label>
                                          <div class="form-group">
                                                <label for="country">&nbsp;</label><br>
                                                <span class="btn btn-file">  
                                                 <span  class="fileupload-new btn btn-sm btn-success">Upload Image</span>
                                                <input multiple data-parsley-required-message="Please upload image." id="image0" name="image[]" class="default filepreview" onchange="checkFileUploadExt(this,0);" accept="image/*" type="file">
                                                            <br>
                                                </span>
                                                <span style=" color: red;" id="error0" ></span>
                                                <span id="prev_0"></span>              
                                            </div>
                                                           
                                        </div>
                                    </div><!-- /.box-body -->
                                        <div class="form-group">
                                            <label>Status *</label>
                                            <?php
                                                $options_status = array('Active'  => 'Active','Inactive'  => 'Inactive');
                                                echo form_dropdown('status', $options_status, set_value('status'),'class="form-control"');?>
                                            <?php echo form_error('status');?>
                                        </div>
                                    <div class="box-footer text-center">
                                         <button type="submit" class="btn btn-primary">Add</button>
                                        
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div><!-- /.box -->
            </div><!-- col-12-->
        </div><!-- row-->
    </section>
</div><!-- row-->
<!-- CK Editor -->
<script src="<?php echo base_url(); ?>assets/admin/js/ckeditor/ckeditor.js"></script>
<script>






      function checkFileUploadExt(fieldObj,id) {
      var control = document.getElementById("image"+id);
      var filelength = control.files.length;
        var error=0;
        for (var i = 0; i < control.files.length; i++) {
            var file = control.files[i];
            var FileName = file.name;
            var FileExt = FileName.substr(FileName.lastIndexOf('.') + 1);
                if ((FileExt.toUpperCase() != "JPG" && FileExt.toUpperCase() != "JEPG" && FileExt.toUpperCase() != "PNG")) {
                 // var error = "File type : " + FileExt + "\n\n";
                  error = "File Must be  png or jpeg or jpg files .\n\n";
                } 
        }
        if(error!='0')
        {
            $("#error"+id).html(error);
            $("#image"+id).val('');
        }else
        {
              $("#error"+id).html('');
        }
       
    }
    $(function () {
        // Replace the <textarea id="ckeditor"> with a CKEditor
        CKEDITOR.replace('ckeditor');
    });


$('#category').change(function(e) {
    var id = $("#category").val();
    if(id!='') {
        $('#sub_category option').slice(1).remove();

        $.ajax({
            type:'POST',
            url: "<?php echo base_url(); ?>admin/ads/get_sub_category/",
            data: {id:id},
            
            success:function(data)
            {
                if(data) {
                    var sub_category="";
                    data = JSON.parse(data);
                    
                    //sub_outlets += "<option value=''>Select Sub Outlet</option>";
                    for(var i=0;i<data.length;i++) {
                        sub_category += "<option value="+data[i].id+">"+data[i].name+"</option>";
                    }
                    $("#sub_category").find("#first").after(sub_category);
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
</script>
