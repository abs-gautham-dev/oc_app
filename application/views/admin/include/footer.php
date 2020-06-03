<footer class="main-footer">
    <strong>Copyright &copy; 2016-<?php echo date('Y');?> All rights reserved.
</footer>
      
<!-- Add the sidebar's background. This div must be placed
immediately after the control sidebar -->
<!-- <div class="control-sidebar-bg"></div>
 -->
   
   
 </div><!-- ./wrapper -->
 
   <!-- jQuery 2.1.4 -->
    <!--script src="<?php echo base_url(); ?>assets/admin/plugins/jQuery/jQuery-2.1.4.min.js"></script-->
    <!-- Bootstrap 3.3.5 -->
    <script src="<?php echo base_url(); ?>assets/admin/bootstrap/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="<?php echo base_url(); ?>assets/admin/plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url(); ?>assets/admin/dist/js/app.min.js"></script>
    <!-- Sparkline -->
    <script src="<?php echo base_url(); ?>assets/admin/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="<?php echo base_url(); ?>assets/admin/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/admin/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="<?php echo base_url(); ?>assets/admin/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- ChartJS 1.0.1 -->
    <!--<script src="<?php echo base_url(); ?>assets/admin/plugins/chartjs/Chart.min.js"></script>-->
    <script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/jquery.dataTables.min.js"></script>
   
    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo base_url(); ?>assets/admin/dist/js/demo.js"></script>
	
	   <script src="<?php echo base_url(); ?>assets/admin/plugins/select2/select2.full.min.js"></script>
	  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>

    <script src="<?php echo base_url(); ?>assets/admin/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/admin/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- datepicker -->
    <script src="<?php echo base_url(); ?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/js/bootstrap-fileupload.js"></script>
	 <script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/dataTables.bootstrap.min.js"></script> 
  <script type="text/javascript">
            $(function () {
                $('.datetimepicker1').datepicker({
                    format: "MM yyyy",
                      viewMode: "months", 
                      startDate: new Date('Jan 1, 2018')});
                });
            
        </script>










	 <script>
      $(function () {
        // $("#datatable1").DataTable();
        $('#datatable1').DataTable({
          "paging": false,
          "lengthChange": false,
          "searching": false,
          "ordering": true,
          "info": false,
          "autoWidth": false
          
        });
      });

      //Colorpicker
        $(".my-colorpicker1").colorpicker();
        //color picker with addon
        $(".my-colorpicker2").colorpicker();

      $(function () {
    $('#example1').DataTable();
});





function new_update_order(id,table,field)
{

    if(id!='' && table!='' && field!='') {
        $('#notification_msg').html('');
        var order = $("#order_"+id).val();

        var old_order = $("#order_"+id).attr('data-value');
        var r = confirm('Are you sure, you want to change ordering from '+old_order +' to '+ order + ', Rest ordering will be update accordingly ?');
        if (r == true) {
            //UIkit.modal.confirm('You are changing order from '+old_order +' to '+ order + ', Rest ordering will be update accordingly ?', function(){ 
            $("#order_"+id).next().hide();
            $("#order_"+id).after("<img style='padding-left:4px;' src='assets/images/loading1.gif'>");
            $.ajax({
                type:'POST',
                data:{ 
                    id:id,
                    order:order,
                    table:table,
                    field:field,
                },
                url: base_url+"admin/Ajax/update_order/",
                success:function(data)
                {   $("#order_"+id).next().remove();
                    $("#order_"+id).next().show();
                    var response = JSON.parse(data);
                    
                    if(response.status==1) {
                        $('#notification_msg').html('<div class="alert alert-success fade in"><button data-dismiss="alert" class="close" type="button">×</button><p>'+response.msg+'</p></div>');
                        location.reload(); 
                    }
                    else if(response.status==0)  {
                        $('#notification_msg').html('<div class="alert alert-danger fade in"><button data-dismiss="alert" class="close" type="button">×</button><p>'+response.msg+'</p></div>');
                        $("#order_"+id).val(old_order);
                    }
                    else if(response.status==2)  {
                        $('#notification_msg').html('<div class="alert alert-warning fade in"><button data-dismiss="alert" class="close" type="button">×</button><p>'+response.msg+'</p></div>');
                        $("#order_"+id).val(old_order);
                    }
                }
            });
        //});
        } else {
            $("#order_"+id).val(old_order);
        }
    }
}





    </script>
  </body>
</html> 