<section class="invoice-list-section py-3">
  <div class="container">
        <?php 
            if(!empty($sales_order)){ ?>
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Company Name</th>
                        <th>Contact Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Sent On</th>
                        <!-- <th>Action</th> -->
                    </tr>
                </thead>
                <tbody>
               <?php  foreach ($sales_order as $key => $list) {
                    $company_name = $this->Common_model->getRecords('users','full_name',array('user_id'=>$list['company_id']),'',true); ?>
                    <tr>
                        <td><?php echo ucwords($company_name['full_name']);?></td>
                        <td><?php echo ucwords($list['contact_name']);?></td>
                        <td><?php echo $list['email'];?></td>
                        <td><?php echo $list['address'];?></td>
                        <td><?php echo date('d M Y',strtotime($list['created']));?></td>
                        <!-- <td>Action</td> -->
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