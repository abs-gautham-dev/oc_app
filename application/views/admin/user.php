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
	                  <!-- <h3 class="box-title">Example</h3> -->
	                  <?php if ($this->session->flashdata('success')) { ?>
	                	<div class="alert alert-success fade in">
	                        <button data-dismiss="alert" class="close" type="button">×</button>
	                      	<p><?php echo $this->session->flashdata('success') ?></p>
	                  	</div>
	                  	<?php } ?>   
	                    <?php if ($this->session->flashdata('error')) { ?>
	                    <div class="alert alert-error fade in">
	                        <button data-dismiss="alert" class="close" type="button">×</button>
	                        <p><?php echo $this->session->flashdata('error') ?></p>
	                  	</div>
	                    <?php } ?>
	                </div>
	                <!-- /.box-header -->
	                <!-- form start -->
	               
                        <div class="box-body">
	                  		<div class="box-body">
	                  			<!-- <div class="form-group">
                                    <label for="interest" class="">Interest </label>
                                    <?php
                                        $extra=array(
                                            'class'=>'form-control',
                                            'data-parsley-required-message'=>'Please select interest.',
                                        );
                                        $interest=!set_value('interest') ? $user['interest_id'] : set_value('interest'); 
                                        echo form_dropdown('interest', $interest_list,$interest,$extra);
                                        echo form_error('interest');
                                    ?>
                                </div> -->
                                <div class="form-group">
                                    <label for="status">User Type </label>
                                     </br> <?php echo $user['user_type'] ;?>
                   
                                </div>  
			                    <div class="form-group">
									<label for="name">Username </label>
                    </br> <?php echo $user['username'] ;?>
									 
								</div>
								
								<div class="form-group">
                                    <label for="email">Email </label>
                                    </br>
                                  <?php echo $user['email'] ;?>
                                   
                                </div>
								<div class="form-group">
									<label>About</label>
                   </br>
                   <?php if(!empty($user['about'])){
                     echo $user['about'] ;}else{ echo "N/A"; }?>
									   </div>
								
                                <div class="form-group">
                                    <label for="address">Address</label>
                                     </br>
                                     <?php if(!empty($user['address'])){  echo $user['address'] ;}else{ echo "N/A"; }?>
                                   
                                </div>
						
								<div class="form-group">
									<label for="country">Country</label>
                    </br>             <?php if(!empty($user['country_id'])){
									 foreach($countries as  $key => $value) { ?>
                          <?php if(!empty($user['country_id']) && ($user['country_id']==$key)) {echo $value;}  ?>
                        <?php  }} else{ echo "N/A"; } ?>
								</div>

								<div class="form-group">

									<label for="state">State</label>
                                        </br>
                                        <?php  if(!empty($user['state_id'])) {
                                        if(!empty($user['country_id'])) {
                                            if($states = getStatesList($user['country_id'])) {
                                                foreach($states as $state) { ?>
                                                  <?php if(!empty($user['state_id']) && ($user['state_id']==$state['id'])) {echo $state['name'];} ?>
                                                <?php }
                                            } 
                                        } else {
                                           
                                        }}else{ echo "N/A"; } ?>
										
								
								</div>
                                
                                <div class="form-group">
                                    <label for="state">City</label>
                                        </br>
                                        <?php if(!empty($user['city_id'])) {
                                            if(!empty($user['state_id'])) {
                                            $cities = getCitiesList($user['state_id']);
                                            //echo "<pre>";print_r($states);exit;
                                            if($cities) {
                                                foreach($cities as $city) { ?>
                                                    <?php if(!empty($user['city_id']) && ($user['city_id']==$city['id'])) {echo  $city['name'];} ?>
                                                <?php }
                                            } 
                                        } else {
                                           
                                        } }else{ echo "N/A"; }?>
                                        
                                  
                                </div>
                               
                                <div class="form-group">
                                    <label for="zip_code">Zip Code </label>
                                     </br>
                                    <?php if(!empty($user['zipcode'])) { echo $user['zipcode'] ;}else{ echo "N/A"; }?>
                                   
                                </div>
                                <div class="form-group">
                                
                                <div class="row">
                                    <div class="col-sm-5"><label for="country_code">Country Code</label>
                                     </br>
                                    <?php  if(!empty($user['country_code'])) {  echo $user['country_code'];}else{ echo "N/A"; }  ?>
                                    </div>
                                    <div class="col-sm-7"><label for="mobile">Mobile </label>

                                     </br>
                                    <?php if(!empty($user['mobile'])) {  echo $user['mobile'] ; }else{ echo "N/A"; }?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="status">Account Type </label>
                                    </br>
                                    <?php echo $user['account_type'] ;?>
                                      
                                </div>  
                                <div class="form-group">
                                    <label for="status">Notification </label>
                                    </br>
                                    <?php echo $user['notification'] ;?>
                                       
                                     
                                </div> 
                                <div class="form-group">
                                    <label for="status">Adv. Notification </label>
                                    </br>
                                    <?php echo $user['adv_notification'] ;?>
                                       
                                </div>      
			                    <div class="form-group">
									<label for="status">Status</label>
									</br>
									<?php echo $user['status'] ;?>
							  	</div>
							</div><!-- /.box-body -->
	                  	</div><!-- /.box-body -->
						<div class="box-footer text-center">
						
							<a href="<?php echo $back_action;?>" class="btn btn-default">Back</a>
						</div>

	              
            	</div><!-- /.box -->
			</div><!-- col-12-->


		</div><!-- row-->
        <div class="col-md-6">
        <div class="box box-primary">
          <div class="box-body">
                   
                      <div class="col-md-6 text-center">
                      
                        <div class="control-group">
                            <label class="control-label">Image </label>
                            <div class="controls">
                                <div data-provides="fileupload" class="fileupload fileupload-new">
                                    <div  class="fileupload-new thumbnail">
                                      <img alt="No Image"src="<?php echo !empty($user['profile_pic']) ? base_url().$user['profile_pic']:'';?>" >
                                    </div>
                                    <div style="max-width: 100px; max-height: 100px; line-height: 5px;" class="fileupload-preview fileupload-exists thumbnail"></div>
                                    <div>
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                
              
                </div><!-- panel body-->
        </div>
      </div><!-- col-12-->
	</section>
</div><!-- row-->
