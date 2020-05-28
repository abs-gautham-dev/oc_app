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
                                <?php if(isset($from_action) && !empty($from_action)){ ?>
                                <form method="POST" action="<?php echo $from_action; ?>" role="form"  data-parsley-validate>
                                <?php } ?>
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label for="name">Name *</label>
                                            <input type="text" class="form-control" name="name" id="name" value="<?php echo !set_value('name') ? $subcategory['name'] : set_value('name'); ?>" placeholder="Name" maxlength="100" data-parsley-pattern="^[a-z A-Z&]+$"  data-parsley-required data-parsley-required-message="Please enter name.">
                                            <?php echo form_error('name'); ?>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="code" class="">Category *</label>
                                            <?php
                                                $extra=array(
                                                    'class'=>'form-control',
                                                    'data-parsley-required'=>'required',
                                                    'data-parsley-required-message'=>'Please select category.',
                                                );
                                                $subcategory_list=!set_value('category') ? $subcategory['category_id'] : set_value('category'); 
                                                echo form_dropdown('category', $categories,$subcategory_list,$extra);
                                                echo form_error('category');
                                            ?>
                                        </div>

                                       
                                        <div class="form-group">
                                            <label for="status">Status *</label>
                                            <?php $options_status = array('Active'  => 'Active','Inactive'  => 'Inactive');
                                            $status=!set_value('status') ? $subcategory['status'] : set_value('status'); 
                                            echo form_dropdown('status', $options_status, $status,'class="form-control"');?>
                                            <?php echo form_error('status');?>
                                        </div>
              
                                        <div class="box-footer">
                                            <div class="form-group">
                                                <div class="col-sm-offset-3 col-sm-9">
                                                    <?php if(isset($from_action) && !empty($from_action)){ ?>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                    <?php } ?>
                                                    <a href="<?php echo $back_action;?>" class="btn btn-default">Back</a>
                                                </div>
                                            </div>
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