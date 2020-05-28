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
        <div class="box box-primary">
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-6  col-lg-offset-3">
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
                                <input type="hidden" class="form-control" name="current_password" id="current_password" value="<?php if(isset($current_password)) echo $current_password; ?>">
                                <form class="" id="change_password" method="POST" action="<?php echo base_url("admin/change_password"); ?>" role="form">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label for="old_password">Old Password *</label>
                                            <input type="password" class="form-control" name="old_password" id="old_password" placeholder="Old Password" required>
                                            <?php echo form_error('old_password'); ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="new_password" >New Password *</label>
                                            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password" minlength="6" maxlength="12" required>
                                            <?php echo form_error('new_password'); ?>
                                        </div>
              
                                        <div class="form-group">
                                            <label for="confirm_password">Confirm Password *</label>
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                                            <?php echo form_error('confirm_password'); ?>
                                        </div>

                                        <div class="box-footer text-center">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                            <a href="admin/dashboard" class="btn btn-default">Back</a>
                                        </div>
                                    </div>
                                </form>
                            </div><!-- panel body-->
                        </div><!-- end panel -->
                    </div><!-- col-6-->
                </div><!-- row-->
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
<script>
    $(document).ready(function () {
        $("#change_password").validate({
            // Specify the validation rules
            rules: {
                old_password: {
                    required: true,
                    equalTo: "#current_password"
                },
                new_password: {
                  required: true,
                  minlength: 6,
                  maxlength: 12
                },
                confirm_password: {
                    required: true,
                    equalTo: "#new_password"
                },
            },
      
            // Specify the validation error messages
            messages: {
                old_password: {
                    required: "Please enter old password.",
                    equalTo: "Old Password is incorrect."
                },
                new_password: {
                    required: "Please enter new password.",
                    minlength: "Password must be at least 6 characters long.",
                    maxlength: "Password must be at most 12 characters long."
                },
                confirm_password: {
                    required: "Please enter confirm password.",
                    equalTo: "New Password and Confirm Password must match."
                },
            },
    
            submitHandler: function(form) {
                //$("button").button('loading');
                ("#change_password").submit();
            }
        });
    });
  
</script>



















