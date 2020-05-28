$(function(){

	

    var path=window.location.href;

    path=path.replace(/\/$/,"");

    path=decodeURIComponent(path);

    //console.log(path.split("/")[5]);

    $('.sidebar-menu a').each(function(){

        var href=$(this).attr('href');

        

        if(path.substring(0,href.length)==href){

            $(this).parent('li').addClass('active');

        }

        //console.log(href.split("/")[5]);

        if(path.split("/")[5]==href.split("/")[5]){

        	$(this).parent('li').addClass('active');

        	$(this).parent('li').parent('ul').parent('li').addClass('active');

        }

    });



});

//side menu activation



var pid="";

$(document).ready(function() {



$(".select2").select2();

/*$('.delete_product').click(function(event) {

	var id=$(this).data('id');

	bootbox.confirm("Want to delete this?", function(result) {

	if(result)

		{

			$("#tr_"+id).hide('slow');

			$.ajax({

					type: 'POST',

					url: '<?php echo site_url("ajax_controller/delete_product/'+id+'")?>',

					data: { del_id:id }, 

					success:function(response){

					 }

			  });

          }

        });



}); Delete Product*/



/* delete_product_gallery */

$('.delete_product_gallery').click(function(event) {

	var id=$(this).data('id');

	var checkstr =  confirm('re you sure you want to delete this?');

	if(checkstr == true){

		$("#tr_"+id).hide('slow');

		$.ajax({

			type: 'POST',

			url: '<?php echo site_url("admin/ajax/delete_product_gallery/'+id+'")?>',

			data: { del_id:id }, 

			success:function(response){

			}

		});

	}

}); /*Delete delete_product_gallery */



// delete_banner 

$('.delete_banner').click(function(event) {

	var id=$(this).data('id');

	var checkstr =  confirm('Are you sure you want to delete this?');

		if(checkstr == true){

		$("#tr_"+id).hide('slow');

			$.ajax({

					type: 'POST',

					url: '<?php echo site_url("admin/ajax/delete_banner_image/'+id+'")?>',

					data: { del_id:id }, 

					success:function(response){

					 }

			  });

			  

			  }

	

}); /*Delete  delete_banner */



// delete_client  



$('.delete_client').click(function(event) {	

	var id=$(this).data('id');

	var checkstr =  confirm('Are you sure you want to delete this?');		

	if(checkstr == true){		

	$("#tr_"+id).hide('slow');			

		$.ajax({					

				type: 'POST',					

				url: '<?php echo site_url("admin/ajax/delete_client_image/'+id+'")?>',					

				data: { del_id:id }, 					

				success:function(response){					

				}			  

			});			  			  

		}	

	});



	/*Delete  delete_client */



	

// delete_testimonial  

$('.delete_testimonial').click(function(event) {

	var id=$(this).data('id');

	var checkstr =  confirm('are you sure you want to delete this?');

		if(checkstr == true){

		$("#tr_"+id).hide('slow');

			$.ajax({

					type: 'POST',

					url: '<?php echo site_url("admin/ajax/delete_testimonial_image/'+id+'")?>',

					data: { del_id:id }, 

					success:function(response){

					 }

			  });

			  

			  }

	

}); /*Delete  delete_testimonial */





// delete_product_attribute  

$('.delete_product_attribute').click(function(event) {

	var id=$(this).data('id');

	var checkstr =  confirm('are you sure you want to delete this?');

		if(checkstr == true){

		$("#tr_"+id).hide('slow');

			$.ajax({

					type: 'POST',

					url: '<?php echo site_url("admin/ajax/delete_product_attribute/'+id+'")?>',

					data: { del_id:id }, 

					success:function(response){

					 }

			  });

		}

	

}); /*Delete  delete_testimonial */



  //Profile pic upload

$(".upload_profile_pic").click(function(event) {

	

	$('#status_image').html('');

   	var file = document.getElementById("image").files[0];

	if(file)

	{  

		var file_type = file.type.toLowerCase(file.type);

		if(file_type=="image/jpeg" || file_type=="image/png" || file_type=="image/jpg")

		{

			// $(".btn-file span.fileupload-new").hide();

		 //    $(".btn-file .fileupload-exists").show();

			aid =$("#admin_id").val();

			var formdata= new FormData();

			formdata.append('aid',aid);

			formdata.append('image',file);

			var ajax = new XMLHttpRequest();

			ajax.upload.addEventListener("progress", img_progressHandler, false);

			ajax.addEventListener("load", img_completeHandler, false);

			ajax.addEventListener("error", img_errorHandler, false);

			ajax.addEventListener("abort", img_abortHandler, false);

			ajax.open("POST", site_url+'admin/Ajax/profile_pic_upload');

			ajax.send(formdata);



			ajax.onreadystatechange = function() {

		        if (this.readyState == 4 && this.status == 200) {

		        	var new_src=  $(".fileupload-preview img").attr('src');	

		        	$(".fileupload-new img").attr('src',new_src);	

		        	$(".fileupload").removeClass('fileupload-exists').addClass('fileupload-new');

		       }

		    };

		}

		else

		{

		 	$('#status_image').html('Only JPG,PNG allowed');

		  // alert("Please Select JPG/PNG Only");

		}/* File Type Matched*/

		 

	}/* If file selected */       

	else

	{

		  alert("Please Select Image");

	}

});	



	//upload_banner_image

$(".upload_banner_image").click(function(event) {

 
  	$('#status_image').html('');

   	var file = document.getElementById("image").files[0];

	if(file)

	{  

		var file_type = file.type.toLowerCase(file.type);

		//alert(file.type);

		if(file_type=="image/jpeg" || file_type=="image/png" || file_type=="image/jpg")

		{



			aid =$("#banner_id").val();

			

			var formdata= new FormData();

			formdata.append('aid',aid);

			formdata.append('image',file);

			var ajax = new XMLHttpRequest();

			ajax.upload.addEventListener("progress", img_progressHandler, false);

			ajax.addEventListener("load", img_completeHandler, false);

			ajax.addEventListener("error", img_errorHandler, false);

			ajax.addEventListener("abort", img_abortHandler, false);

			ajax.open("POST", site_url+'admin/Ajax/banner_image_upload');

			ajax.send(formdata);

		}

		else

		{

		 	$('#status_image').html('Only JPG,PNG allowed');

		}/* File Type Matched*/

		 

	}/* If file selected */       

	else

	{

		  alert("Please Select Image");

	}

});	



	//Profile pic upload

$(".upload_profile_user").click(function(event) {

	

	$('#status_image').html('');

   	var file = document.getElementById("image").files[0];

	if(file)

	{  

		var file_type = file.type.toLowerCase(file.type);

		if(file_type=="image/jpeg" || file_type=="image/png" || file_type=="image/jpg")

		{

			// $(".btn-file span.fileupload-new").hide();

		 //    $(".btn-file .fileupload-exists").show();

			aid =$("#user_id").val();

			var formdata= new FormData();

			formdata.append('aid',aid);

			formdata.append('image',file);

			var ajax = new XMLHttpRequest();

			ajax.upload.addEventListener("progress", img_progressHandler, false);

			ajax.addEventListener("load", img_completeHandler, false);

			ajax.addEventListener("error", img_errorHandler, false);

			ajax.addEventListener("abort", img_abortHandler, false);

			ajax.open("POST", site_url+'admin/Ajax/profile_pic_user');

			ajax.send(formdata);



			ajax.onreadystatechange = function() {

		        if (this.readyState == 4 && this.status == 200) {

		        	var new_src=  $(".fileupload-preview img").attr('src');	

		        	$(".fileupload-new img").attr('src',new_src);	

		        	$(".fileupload").removeClass('fileupload-exists').addClass('fileupload-new');

		       }

		    };

		}

		else

		{

		 	$('#status_image').html('Only JPG,PNG allowed');

		  // alert("Please Select JPG/PNG Only");

		}/* File Type Matched*/

		 

	}/* If file selected */       

	else

	{

		  alert("Please Select Image");

	}

});	



//upload_category_image

$(".upload_category_image").click(function(event) {

	$('#status_image').html('');

   	var file = document.getElementById("image").files[0];

	if(file)

	{  

		var file_type = file.type.toLowerCase(file.type);

		//alert(file.type);

		if(file_type=="image/jpeg" || file_type=="image/png" || file_type=="image/svg+xml" || file_type=="image/jpg")

		{



			aid =$("#category_id").val();

			var formdata= new FormData();

			formdata.append('aid',aid);

			formdata.append('image',file);

			var ajax = new XMLHttpRequest();

			ajax.upload.addEventListener("progress", img_progressHandler, false);

			ajax.addEventListener("load", img_completeHandler, false);

			ajax.addEventListener("error", img_errorHandler, false);

			ajax.addEventListener("abort", img_abortHandler, false);

			ajax.open("POST", site_url+'admin/Ajax/cat_image_upload');

			ajax.send(formdata);

		}

		else

		{

		 	$('#status_image').html('Only JPG,PNG,SVG allowed');

		}/* File Type Matched*/

		 

	}/* If file selected */       

	else

	{

		  alert("Please Select Image");

	}

});



	//upload  testimonial





	//upload_category_image

$(".upload_amenities_image").click(function(event) {

	$('#status_image').html('');

   	var file = document.getElementById("image").files[0];

	if(file)

	{  

		var file_type = file.type.toLowerCase(file.type);

		//alert(file.type);

		if(file_type=="image/jpeg" || file_type=="image/png" || file_type=="image/svg+xml" || file_type=="image/jpg")

		{



			aid =$("#category_id").val();

			var formdata= new FormData();

			formdata.append('aid',aid);

			formdata.append('image',file);

			var ajax = new XMLHttpRequest();

			ajax.upload.addEventListener("progress", img_progressHandler, false);

			ajax.addEventListener("load", img_completeHandler, false);

			ajax.addEventListener("error", img_errorHandler, false);

			ajax.addEventListener("abort", img_abortHandler, false);

			ajax.open("POST", site_url+'admin/Ajax/amenities_image_upload');

			ajax.send(formdata);

		}

		else

		{

		 	$('#status_image').html('Only JPG,PNG,SVG allowed');

		}/* File Type Matched*/

		 

	}/* If file selected */       

	else

	{

		  alert("Please Select Image");

	}

});



	//upload  testimonial









});/*document */





function img_progressHandler(event){

  	var percent = (event.loaded / event.total) * 100;

  	document.getElementById("progressBar_image").style.width = Math.round(percent)+"%";

}

function img_completeHandler(event){

  	document.getElementById("progressBar_image").style.width = "0%";

  	(event.target.responseText=="1")

	$("#image_container").html(event.target.responseText);

}



function detail_img_progressHandler(event){

  	var percent = (event.loaded / event.total) * 100;

  	document.getElementById("detail_progress_bar").style.width = Math.round(percent)+"%";

}

function detail_img_completeHandler(event){

  	document.getElementById("detail_progress_bar").style.width = "0%";

  	(event.target.responseText=="1")

	$("#image_container").html(event.target.responseText);

}



function fabric_img_progressHandler(event){

  	var percent = (event.loaded / event.total) * 100;

  	document.getElementById("fabric_progress_bar").style.width = Math.round(percent)+"%";

}

function fabric_img_completeHandler(event){

  	document.getElementById("fabric_progress_bar").style.width = "0%";

  	(event.target.responseText=="1")

	$("#image_container").html(event.target.responseText);

}



function img_errorHandler(event){

  bootbox.alert("Upload Failed");

}

function img_abortHandler(event){

  bootbox.alert("Upload Aborted");

}

