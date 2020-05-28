<?php 
//echo '<pre>';print_r($apply_status_data);
 function searchForId($id, $array) {

  if($array['job_id']==$id)
  {
    return 'yes';
  }

  return null; 

}

if(!empty($designation_list)){
	require_once('includes/alert.php'); 
	foreach($designation_list as $row){
	 	$user_id=$this->session->userdata('user_id');
	    $member_id=$this->session->userdata('member_id');
	    $rq_user_id=$this->session->userdata('rq_user_id');
   // $user_id='462';?>
	<div class="box_style" style="display: block;">
		
		<h3>
			<a href="<?php echo base_url();?>home/designation_details/<?php if(!empty($row['id']))echo base64_encode($row['id']);?>">
				<?php if(!empty($row['job_title'])){echo ucfirst($row['job_title']);}else{echo 'N/A';}?>
			</a>
			
		</h3>
		<?php if((isset($member_id) && !empty($member_id)) || (isset($rq_user_id) && !empty($rq_user_id))){ ?>
			<strong class="gray_text">
				<?php if(!empty($row['company'])){echo $row['company'];}else{echo 'N/A';}?>
			</strong>
		<?php } ?>
		<ul class="price_listing">
			<li class="price_li"><span class="price_icon"></span>
				<?php if(!empty($row['min_salary_in'])){echo $row['min_salary_in'];}?>-<?php if(!empty($row['max_salary_in'])){echo $row['max_salary_in'];}?>
			</li>
			<li class="work_li"><span class="work_icon"></span>
				<?php if(isset($row['min_experience_year']) && $row['min_experience_year']!=''){echo $row['min_experience_year'];}?>-<?php if(isset($row['max_experience_year']) && $row['max_experience_year']!=''){echo $row['max_experience_year'];}?> Year’s
			</li>
		</ul>
	 	<?php if((isset($member_id) && !empty($member_id)) || (isset($rq_user_id) && !empty($rq_user_id))){ ?>
			<strong class="text_address"><span class="map_icon"></span>
				<?php if(!empty($row['location'])){echo $row['location'];}else{echo 'N/A';}?>
			</strong>
		<?php } ?>
      	<p>
			<?php if(!empty($row['job_description'])){echo mb_strimwidth($row['job_description'],0, 50,'...');}else{echo 'N/A';}?>
      	</p>
		
		<div class="apl_btn">
			<?php


				if(!isset($user_id) && empty($user_id))
				{
					echo'<a class="btn btn-default" onclick="return login();">Apply</a>';
				}
				else
				{
					$applied=0;
					if(!empty($apply_status_data)){
						foreach($apply_status_data as $apply_data){//echo '<pre>';print_r($apply_data);die;
						$status= searchForId($row['id'],$apply_data);
							if($status=='yes')
							{
								$applied=1;
								$button_value='Applied';
								$class='active';
								//echo'<a class="btn btn-default'.' '.$class.'" href="javascript:void(0)">'.$button_value.'</a>';
							}


					 	}
					 	//echo $status;die;
					 	if(empty($applied)){
					 	?>

						 <span id="apply_status_<?php if(!empty($row['id'])) echo $row['id'].$user_id; ?>"><a class="btn btn-default" id="<?php if(!empty($row['id'])) echo $row['id'].$user_id; ?>" onclick="return job_apply('<?php if(!empty($row['subcategory_id'])) echo $row['subcategory_id']; ?>','<?php if(!empty($row['rq_id'])) echo $row['rq_id']; ?>','<?php if(!empty($row['id'])) echo $row['id']; ?>','<?php echo $user_id; ?>');">Apply</a></span>
					<?php 	}
					else
					{
						echo'<a class="btn btn-default'.' '.$class.'" href="javascript:void(0)">'.$button_value.'</a>';
					}
				}

				}
			//}
			?>
	      <strong>
			<?php 
				$now = time(); // or your date as well
				$your_date = strtotime($row['created_date']);
				$datediff = $now - $your_date;
				$day= floor($datediff/(60*60*24));
					if($day == 0){
						echo 'Today';
					}else if($day == 1){
						echo $day. ' day ago';
					}
					else
					{
						echo $day. ' days ago';
					}
			?>
	      </strong>
    	 </div>
		
  	</div>
   <!--  -->
	<?php  
 	} 
}

?>


        
        
    