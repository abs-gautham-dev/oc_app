<section class="invoice-list-section py-3">
  <div class="container">
        <?php 
            if(!empty($sales_order)){ ?>
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Sales Name</th>
                        <th>Sales Number</th>
                        <th>Sales Email</th>
                        <th>Received On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
               <?php  foreach ($sales_order as $key => $list) {
                    $sales_name = $this->Common_model->getRecords('users','full_name,email,mobile',array('user_id'=>$list['sender_id']),'',true); ?>
                    <tr>
                        <td><?php echo ucwords($sales_name['full_name']);?></td>
                        <td><?php echo $sales_name['mobile'];?></td>
                        <td><?php echo $sales_name['email'];?></td>
                        <td><?php echo date('d M Y',strtotime($list['created']));?></td>
                        <td><a href="<?php echo base_url().'company/send_job_request/'.$list['id'];?>"><button class="find-job-button buttonH"><i class="fa fa-paper-plane" aria-hidden="true"></i></button></a></td>
                    </tr>          
                <?php  } ?>

                </tbody>
                </table>

                <?php  }else{ ?>

                    <div class="list-box-container">
                         <div class="single-list-box" style="text-align: center;">
                            No Record Found
                        </div>      
                    </div>     
                <?php }
            ?>
  </div>
</section>
</body>
</html>
<script type="text/javascript">
    $(document).ready(function() {
    $('#example').DataTable();
} );
</script>