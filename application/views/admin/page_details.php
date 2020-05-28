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
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
            <div class="col-md-6">
              <!-- general form elements -->
                <div class="box box-primary">
                  <div class="box-header">
                  <?php 
          
                  if($page['is_subscription']=='yes'){?>
                      <a target="_" class="btn btn-primary" href="<?php echo base_url().'admin/membership_details/'.$page['mem_id'];?>">View Membership</a>
                  <?php }?>
                  </div>
                        <div class="box-body">
                        <div class="box-body">
                             
                               <div class="form-group">
                                    <label for="status">Business Name</label><br>
                                       <span class="busniess_details"><?php echo $page['business_name'];  ?></span>
                                </div> 

                                <div class="form-group">
                                    <label for="status">Page Owner Name</label><br>
                                       <span class="busniess_details"><?php echo $page['user_name'];  ?></span>
                                </div> 

                                <div class="form-group">
                                    <label for="status">Category</label><br>
                                       <span class="busniess_details"><?php echo $page['category_name'];  ?></span>
                                </div> 
                                <div class="form-group">
                                    <label for="status">Sub Category</label><br>
                                       <span class="busniess_details"><?php echo $page['sub_category_name'];  ?></span>
                                </div> 
                                
                                <div class="form-group">
                                    <label for="status">Email</label><br>
                                       <span class="busniess_details"><?php echo $page['email'];  ?></span>
                                </div> 
                                
                                 <div class="form-group">
                                    <label for="status">Mobile</label><br>
                                       <span class="busniess_details"><?php echo $page['mobile'];  ?></span>
                                </div> 
                        
                                <div class="form-group">
                                    <label for="status">Address 1</label><br>
                                       <span class="busniess_details"><?php echo $page['address_1'];  ?></span>
                                </div> 

                                 <div class="form-group">
                                    <label for="status">Address 2</label><br>
                                       <span class="busniess_details"><?php echo $page['address_2'];  ?></span>
                                </div> 
                                 <div class="form-group">
                                    <label for="status">Status</label><br>
                                       <span class="busniess_details"><?php echo ucfirst($page['status']);  ?></span>
                                </div>  
                                <div class="form-group">
                                    <label for="status">Notification</label><br>
                                       <span class="busniess_details"><?php echo $page['push_notification']; ?></span>
                                </div>  
                                <div class="form-group">
                                    <label for="status">Display Rating</label><br>
                                       <span class="busniess_details"><?php echo $page['disply_rating'];  ?></span>
                                </div>  
                                 <div class="form-group">
                                    <label for="status">Type</label><br>
                                       <span class="busniess_details"><?php echo $page['type'];  ?></span>
                                </div>
                             
                                <div class="form-group">
                                    <label for="status">Country name</label><br>
                                       <span class="busniess_details"><?php echo $page['country_name'];  ?></span>
                                </div> 
                                <div class="form-group">
                                    <label for="status">State name</label><br>
                                       <span class="busniess_details"><?php echo $page['state_name'];  ?></span>
                                </div> 
                                 <div class="form-group">
                                    <label for="status">City name</label><br>
                                       <span class="busniess_details"><?php echo $page['city_name'];  ?></span>
                                </div> 
                                 <div class="form-group">
                                    <label for="status">Pin</label><br>
                                       <span class="busniess_details"><?php if($page['page_pin'] > 0){ echo 'Yes';}else{ echo 'No';}   ?></span>
                                </div> 
                               
              </div><!-- /.box-body -->
                      </div><!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="btn btn-default">Back</a>
            </div>
                  </form>
              </div><!-- /.box -->
      </div><!-- col-12-->

      <div class="col-md-6">
        <div class="box box-primary">
          <div class="box-body">
                    <form class="form-horizontal">
                      <div class="col-md-12 text-center">
                      
                        <div class="control-group">
                            <div class="controls">
                                <div data-provides="fileupload" class="fileupload fileupload-new">
                                    <?php  if(!empty($page['business_image'])){ ?>
                                    <div  class="fileupload-new thumbnail">
                                      <img alt="No Image"src="<?php echo base_url().$page['business_image'];?>" >
                                    </div>
                                        <?php } 
                                            $st=0;
                                            if(!empty($page['star_images'])){
                                              $st = 1;
                                                    foreach($page['star_images'] as $images)
                                                    { 
                                                      if($st < 5 ){
                                                      if(!empty($images['file_path'])){ ?>
                                                                <div  class="fileupload-new thumbnail">
                                                                  <img width="150px" height="150px" alt="No Image"src="<?php echo base_url().$images['file_path'];?>" >
                                                                </div>
                                                    <?php }
                                                    }

                                                  $st++;
                                                     }
                                                  }
                                             if($st < 5){

                                                if(!empty($page['images'])){
                                                  $im = 1;
                                                        foreach($page['images'] as $images)
                                                        {  
                                                      $total =  $st+$im;
                                                         if($total < 5){
                                                          if(!empty($images['file_path'])){ ?>
                                                                    <div  class="fileupload-new thumbnail">
                                                                      <img width="150px" height="150px" alt="No Image"src="<?php echo base_url().$images['file_path'];?>" >
                                                                    </div>
                                                        <?php }
                                                        }

                                                      $im++;
                                                         }
                                                      }
                                                }
                                            ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                </div><!-- panel body-->
        </div>
      </div><!-- col-12-->
    </div><!-- row-->
  </section>
</div><!-- row-->

