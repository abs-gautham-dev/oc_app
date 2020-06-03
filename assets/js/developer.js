function change_status(field,id,table)

{

    if(id) {

        $("#status_"+id).html('Wait...');

        $.ajax({

            type:'POST',

            data:{ 

                id:id,

                table_name:table,

                field:field 

            },

            url: base_url+"admin/ajax/change_status/",

            success:function(data)

            {

                var response = JSON.parse(data);

                if(response.msg=="success") {

                     if(response.status == 'Inactive') {

                        $("#status_"+id).html(response.status);

                        $("#status_"+id).removeClass('bg-green');

                        $("#status_"+id).addClass('bg-red');

                    }

                    else {

                        $("#status_"+id).html(response.status);

                        $("#status_"+id).removeClass('bg-red');

                        $("#status_"+id).addClass('bg-green');

                        

                    }

                }

                else {

                    alert("Some error occured. Please try again !!");

                }

               

            }

        });

    }

}
function change_status_delete(field,id,table,table_delete,status)
{   if(table == 'interest'){
        if(status != 'Active'){
        var t =  confirm('Are you sure you want to activate this intrest? A user will need to select interest again in order to show under their interest list');
        }else{
        var t =  confirm('Are you sure you want to deactive the intrest ? Please note: This interest might be used by user in the application & deactivating will remove from their existing intrest list');
        }
    }else{
         if(status != 'Active'){
        var t =  confirm('Are you sure you want to activate this amenity? A user will need to select amenity again in order to show under their business list.');
        }else{
        var t =  confirm('Are you sure you want to deactive the amenitiy ? Please note: This amenity might be used by business owner in the application & deactivating will remove from their existing amenity list.');
        }

    }
  
    if(t == true){
        if(id) {
            $("#status_"+id).html('Wait...');
            $.ajax({
                type:'POST',
                data:{ 
                    id:id,
                    table_name:table,
                    table_delete:table_delete,
                    field:field 
                },
                url: base_url+"admin/ajax/change_status_delete/",
                success:function(data)
                {
                    var response = JSON.parse(data);
                    if(response.msg=="success") {
                         if(response.status == 'Inactive') {
                            $("#status_"+id).html(response.status);
                            $("#status_"+id).removeClass('bg-green');
                            $("#status_"+id).addClass('bg-red');
                        }
                        else {
                            $("#status_"+id).html(response.status);
                            $("#status_"+id).removeClass('bg-red');
                            $("#status_"+id).addClass('bg-green');
                            
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

function change_status_review(field,id,table)

{

    if(id) {

        $("#status_"+id).html('Wait...');

        $.ajax({

            type:'POST',

            data:{ 

                id:id,

                table_name:table,

                field:field 

            },

            url: base_url+"admin/ajax/change_status_review/",

            success:function(data)

            {

                var response = JSON.parse(data);

                if(response.msg=="success") {

                     if(response.status == 'Inactive') {

                        $("#status_"+id).html(response.status);

                        $("#status_"+id).removeClass('bg-green');

                        $("#status_"+id).addClass('bg-red');

                    }

                    else {

                        $("#status_"+id).html(response.status);

                        $("#status_"+id).removeClass('bg-red');

                        $("#status_"+id).addClass('bg-green');

                        

                    }

                }

                else {

                    alert("Some error occured. Please try again !!");

                }

               

            }

        });

    }

}



function change_status_post(field,id,table)

{

    if(id) {

        $("#status_"+id).html('Wait...');

        $.ajax({

            type:'POST',

            data:{ 

                id:id,

                table_name:table,

                field:field 

            },

            url: base_url+"admin/ajax/change_status_post/",

            success:function(data)

            { 

                var response = JSON.parse(data);

                if(response.msg=="success") {

                     if(response.status == 'Deactive') {

                        $("#status_"+id).html(response.status);

                        $("#status_"+id).removeClass('bg-green');

                        $("#status_"+id).addClass('bg-red');

                    }

                    else {

                        $("#status_"+id).html(response.status);

                        $("#status_"+id).removeClass('bg-red');

                        $("#status_"+id).addClass('bg-green');

                        

                    }

                }

                else {

                    alert("Some error occured. Please try again !!");

                }

               

            }

        });

    }

}



function delete_record(field,id,table)

{ 

    if(id) {

       

        $.ajax({

            type:'POST',

            data:{ 

                id:id,

                table_name:table,

                field:field 

            },

            url: base_url+"admin/ajax/delete_post/",

            success:function(data)

            {

               $("#tr_"+id).hide();

               

            }

        });

    }

}



//To update the help questions order

function update_order(id,table,type)

{

    if(id!='' && table!='') {

        $("#order_"+id).next().hide();

        $("#order_"+id).after("<img style='padding:4px;' src='assets/images/loading.gif'>");

        $('#notification_msg').html('');

        var order = $("#order_"+id).val();

       

        $.ajax({

            type:'POST',

            data:{ 

                id:id,

                order:order,

                table:table,

                type:type

            },

            url: base_url+"admin/ajax/update_order/",

            success:function(data)

            {   

                $("#order_"+id).next().remove();

                $("#order_"+id).next().show();

                var response = JSON.parse(data);

               

                if(response.status==true) {

                    $('#notification_msg').html('<div class="alert alert-success fade in"><button data-dismiss="alert" class="close" type="button">×</button><p>'+response.msg+'</p></div>');

                }

                else {

                    $('#notification_msg').html('<div class="alert alert-danger fade in"><button data-dismiss="alert" class="close" type="button">×</button><p>'+response.msg+'</p></div>');

                }

            }

        });

    }

}