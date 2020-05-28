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

			<div class="col-xs-12">

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

				<?php } 
			//	array_unshift($used_country,"Country");
				//array_unshift($used_state,"State");
				//array_unshift($used_city,"City");

				?>
				<div id="error"></div>
				<div class="box">

				<div class="box-header with-border">
				 <form class="form-horizontal" method="POST"  action="<?php echo current_url(); ?>">
                        <div class="row">
                            <div class="col-md-12">
                                


                                 <div class="col-md-3">

                                     <?php $extra=array(
                                        'class'=>'column_filter form-control select2 reset',
                                        'data-placeholder'=>'Select Countries',
                                        //'onchange'=>"filter_records();",
                                        'id'=>'country',
                                                                   
                                    );
                                    echo form_dropdown('country', $used_country, set_value('country'),$extra);?>

                                </div>
                       
                                <div class="col-md-3">

                                     <?php $extra=array(
                                        'class'=>'column_filter form-control select2 reset',
                                        'data-placeholder'=>'Select States',
                                        //'onchange'=>"filter_records();",
                                        'id'=>'state',
                                                                   
                                    );
                                    echo form_dropdown('state', $used_state, set_value('state'),$extra);?>

                                </div>

                                <div class="col-md-3">

                                     <?php $extra=array(
                                        'class'=>'column_filter form-control select2 reset',
                                        'data-placeholder'=>'Select Cities',
                                        //'onchange'=>"filter_records();",
                                        'id'=>'city',
                                                                  
                                    );
                                    echo form_dropdown('city', $used_city, set_value('city'),$extra);?>

                                </div>

                            		
                                <div class="form-group col-md-3">
                                   
<!-- 
                                     <button type="reset" class="btn  btn-warning">
                                       <i class="fa fa-refresh" aria-hidden="true"></i>
                                    </button> -->
                                     <button type="submit" class="btn btn-primary" onclick="filter_records();">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                    </button>
                                    
                                </div>
                            </div>
                        </div>
                        	</form>
                        
                        <?php if(isset($add_action) && !empty($add_action)){ ?>
                            <a href="<?php echo $add_action;?>" title="" data-toggle="tooltip" data-original-title="<?php if($add_button_title) echo $add_button_title; ?>" class="btn btn-default pull-right"><i class="fa fa-plus"></i></a>
                        <?php } ?>
                     <!--  <div  class="serial"><input type="checkbox" id="select_all" class="chkbx" value="0" style="top:50px;"></div>  -->
                        
            	</div> 

					<div class="box-body">

						<table  <?php if(!empty($records_result))

								{	?> id="example1"  <?php 

							} ?> class="table table-bordered table-hover">

							<thead>

								<tr>

									<th>Sr.No.</th>
									<th>Page name</th> 
									<th>Business owner name</th> 
									<th>Category</th> 
									<th>Sub Category</th> 
										<?php if(isset($edit_action) && !empty($edit_action)){ ?>
									<th>Sponsored</th>
									<th>Status</th>
									<th>Actions</th>
								<?php } ?>

								</tr>

							</thead>

							<tbody>

				

								<?php 



								if(!empty($records_result))

								{	

									$i =0;

									$table="users";

									$field = "user_id";

									foreach ($records_result as $row) { 

										$i++; 

										if(isset($row['status'])) {

	                                        if($row['status']=="verified") {

	                                            $status = "Verified";

	                                            $class = "pointer badge bg-green";

	                                        } else {

	                                            $status = "Unverified";

	                                            $class = "pointer badge bg-red";

	                                        }

                                    	}?>

										<tr id="tr_<?php echo $row[$field]; ?>">

											<td><?php echo $i; ?></td> 


											<td><?php if(!empty($row['business_name'])) echo $row['business_name']; ?></td>
											
											<td> 
												<select name="username" id="<?php echo 'drop'.$row['business_page_id'];?>" class="form-control select2"> 
												<?php if(!empty($user_lists))
												
												foreach ($user_lists as $user_li) {
													$select ='';
													if($row['user_id']==$user_li['user_id'])
													{ 
														$select = 'selected';
													}
												echo '<option '.$select.' value='.$user_li['user_id'].'>'.$user_li['username'].'</option>';
												}
												?> 
												</select></td>
											<td><?php if(!empty($row['name'])) echo $row['name']; ?></td>

											<?php	$sub_cat_name = get_sub_categories_name($row['sub_category_id']);
												if(!empty($row['sub_category_id2']))
												{
													$sub_cat_name .=', '.get_sub_categories_name($row['sub_category_id2']);
												}
												if(!empty($pages['sub_category_id3']))
												{
													$sub_cat_name .=', '.get_sub_categories_name($row['sub_category_id3']);
												} 
												  ?>
		   
											<td><?php if(!empty($row['subname'])) echo $sub_cat_name; ?></td>

											<?php if(isset($edit_action) && !empty($edit_action)){ ?>
									
											<td>

											<?php 

											  if($row['sponsored']=="Yes") {

	                                         

	                                            $class2 = "pointer badge bg-green";

		                                        } else {

		                                            

		                                            $class2 = "pointer badge bg-red";

		                                        }

											?>

										 		<p id="sponsored_<?php echo $row['business_page_id']; ?>" onclick="sponsored('sponsored','<?php echo $row['business_page_id']; ?>','business_page')" class="<?php echo $class2; ?>" title="" data-toggle="tooltip" data-original-title="Change Sponsored"><?php echo ucfirst($row['sponsored']); ?></p>

											</td>

											<td>



												<?php if($row['type']!='not_business'){?> 

													<p id="status_<?php echo $row['business_page_id']; ?>" onclick="verified('status','<?php echo $row['business_page_id']; ?>','business_page')" class="<?php echo $class; ?>" title="" data-toggle="tooltip" data-original-title="Change Status"><?php echo ucfirst($row['status']); ?></p>

												<?php }else{ ?>

													<p id="status_<?php echo $row['business_page_id']; ?>"  class="<?php echo $class; ?>" title="" data-toggle="tooltip" data-original-title="Change Status"><?php echo 'Not Required' ?></p>

												<?php }?>

											</td>

											<td class="td-actions">

												<a id="edit_product" href="javascript:void(0)" class="btn btn-xs btn-primary edit_product" 
												onclick="change_user('<?php echo $row['business_page_id'];?>','<?php echo 'drop'.$row['business_page_id'];?>')" title="" data-toggle="tooltip" data-original-title="Change User">

													<i class="fa fa-refresh"></i>

												</a>

											
												<a id="edit_product" href="<?php echo base_url().'admin/user/edit_page/'.$row['business_page_id'] ?>" class="btn btn-xs btn-primary edit_product" title="" data-toggle="tooltip" data-original-title="Edit">

													<i class="fa fa-edit"></i>

												</a>

												<a id="edit_product" href="<?php echo base_url().'admin/user/page_details/'.$row['business_page_id'] ?>" class="btn btn-xs btn-success edit_product" title="" data-toggle="tooltip" data-original-title="Details">

													<i class="fa fa-eye"></i>

												</a>



												<a id="document" href="<?php echo site_url().'admin/user/documents/'.$row['business_page_id']; ?>" class="btn btn-xs btn-warning " title="" data-toggle="tooltip" data-original-title="Documents">

													<i class="fa fa-file"></i>

												</a> 

												<?php

												 if($row['is_report']=='yes'){?>

												<a id="edit_productd" href="<?php echo site_url().'admin/user/report_page/'.$row['business_page_id']; ?>" class="btn btn-xs btn-primary " title="" data-toggle="tooltip" data-original-title="Report page">

													<i class="fa fa-file-text-o"></i>

												</a> 

												<?php }

												if($row['is_subscription']=='yes'){

												?>

												<a href="<?php echo site_url().'admin/membership_details/'.$row['mem_id']; ?>"  class="btn btn-xs btn-primary " title="" data-toggle="tooltip" data-original-title="View Membership">

													<i class="fa fa-user-plus"></i>

												</a> 

												<?php 

												}

											 /*	if($row['free_page']=='0'){

													 if($row['status']=="verified") { ?>

													<a href="<?php echo site_url().'admin/User/free_subscription/'.$row['business_page_id']; ?>"  onclick="return confirm('You are gifting free subscription to <?php if(!empty($row['business_name'])) echo $row['business_name']; ?>')" class="btn btn-xs btn-primary " title="" data-toggle="tooltip" data-original-title="Free Subscription">

														<i class="fa fa-usd"></i>

													</a> 

													<?php 

													}

												} */



												

												if($row['is_offers']=='yes'){

												?>

												<a href="<?php echo site_url().'admin/user/offers_list/'.$row['business_page_id']; ?>"  class="btn btn-xs btn-success " title="" data-toggle="tooltip" data-original-title="View Offers">

													<i class="fa fa-tags"></i>

												</a> 

												<?php 

												} ?>



												<a href="<?php echo site_url().'admin/user/review/'.$row['business_page_id']; ?>"  class="btn btn-xs btn-info " title="" data-toggle="tooltip" data-original-title="View Review">

													<i class="fa fa-star" aria-hidden="true"></i>

												</a> 



											</td>

											<?php } ?>


										 

										</tr>



									<?php }

								} else {

									echo "<tr><td colspan='7' align='center'> No Record Found</td></tr>";

								} ?>

							</tbody>

						</table>

							<!-- <div class="box-body"><?php echo $pagination; ?></div> -->

					</div>			

				</div>			

			</div>

		</div>

	</section><!-- /.content -->

</div><!-- /.content-wrapper -->

<script type="text/javascript">

	

function verified(field,id,table)

{ 

	if(id) {

		var cstatus = $("#status_"+id).html();

		var check= true;

		if(cstatus=='Unverified')

		{

			if(!confirm("You checked documents about this page ?")) {

	  			check= false;

	  		}

		}

		if(check == true){

	        $.ajax({

	            type:'POST',

	            data:{ 

	                id:id,

	                table_name:table,

	                field:field 

	            },

	            url: base_url+"admin/ajax/verified/",

	            success:function(data)

	            {

	        	   	var response = JSON.parse(data);

	                if(response.msg=="success") {

	                     if(response.status == 'verified') {

	                      	$("#status_"+id).html('Verified');

	                        $("#status_"+id).removeClass('bg-red');

	                        $("#status_"+id).addClass('bg-green');

	                    }

	                    else {

	                        

	                        $("#status_"+id).html('Unverified');

	                        $("#status_"+id).removeClass('bg-green');

	                        $("#status_"+id).addClass('bg-red');

	                        

	                    }

	                }

	                else {

	                    alert("Some error occured. Please try again !!");

	                }

	               

	            }

	        });

      }

    }

}



function change_varification(value,id)

{ 

	

	if(value) {

	

	        $.ajax({

	            type:'POST',

	            data:{ 

	               value :value,

	               id :id

	            },

	            url: base_url+"admin/ajax/change_varification/",

	            success:function(data)

	            {

	        	 

                }

	           

	        });

      }

  }



function sponsored(field,id,table)

{ 

	if(id) {

		

	        $.ajax({

	            type:'POST',

	            data:{ 

	                id:id,

	                table_name:table,

	                field:field 

	            },

	            url: base_url+"admin/ajax/sponsored/",

	            success:function(data)

	            {

	        	   	var response = JSON.parse(data);

	                if(response.msg=="success") {

	                     if(response.status == 'Yes') {

	                      	$("#sponsored_"+id).html('Yes');

	                        $("#sponsored_"+id).removeClass('bg-red');

	                        $("#sponsored_"+id).addClass('bg-green');

	                    }

	                    else {

	                        

	                        $("#sponsored_"+id).html('No');

	                        $("#sponsored_"+id).removeClass('bg-green');

	                        $("#sponsored_"+id).addClass('bg-red');

	                        

	                    }

	                }

	                else {

	                    alert("Some error occured. Please try again !!");

	                }

	               

	            }

	        });

        }

}






function change_user(page_id,dropdown_id)
{
	if (confirm("Are you sure?")) {
		
 	var user_id = $('#drop'+page_id+' :selected').val();
 
 	  $.ajax({
	            type:'POST',
	          
	            data:{ 
	                page_id:page_id,
	                user_id:user_id,
	            },
	              dataType:'json',
	            url: base_url+"admin/Page/change_user/",
	            success:function(respo)
	            {
	            //	var result = jQuery.parseJSON(respo);
	             	 $("#error").html(respo.data.msg);
	             	 if(respo.data.status==1)
	             	 {
	             	 	 setTimeout(function(){ 
						        location.reload();
						    }, 3000)  
	             	 }
	             	    
	            }

	        });

 	}

}

</script>

