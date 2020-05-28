<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
    <!-- Sidebar user panel -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
      <?php  $admin_id = $this->session->userdata('admin_id');
        $where_page = array('admin_id'=>$admin_id);
        $access = $this->Common_model->getRecords('irg_user_access', 'section_id,add,edit,delete,view',$where_page,"", false);

        ?>
        <ul class="sidebar-menu">
            <li class="treeview">
                <a href="<?php echo base_url(); ?>admin/dashboard">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <?php if($access[0]['view']=='1'){?>
            <li class="treeview">
                <a href="<?php echo base_url(); ?>admin/interest/list">
                    <i class="fa fa-folder"></i> <span>Interest</span>
                      <i class="fa fa-angle-right pull-right"></i>
                </a>
            </li><?php
                }
          
            if($access[1]['view']=='1'){?>
               <li class="treeview">
                    <a href="<?php echo base_url(); ?>admin/categories/list">
                        <i class="fa fa-folder"></i> <span>Category</span>
                        <i class="fa fa-angle-right pull-right"></i>
                    </a>
                   
                </li>
            <?php  }   if($access[4]['view']=='1'){?> 
            
             <li class="treeview">
                <a href="<?php echo base_url(); ?>admin/amenities/list">
                    <i class="fa fa-folder"></i> <span>Amenities</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
               
            </li>
            <?php  }   if($access[5]['view']=='1'){?> 
            <li class="treeview">
                <a href="<?php echo base_url(); ?>admin/subscription/plan">
                    <i class="fa fa-folder"></i> <span>Subscription Plan</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
               
            </li>
          <?php  }   if($access[6]['view']=='1'){?> 
            <li class="treeview">
                <a href="<?php echo base_url(); ?>admin/membership">
                    <i class="fa fa-folder"></i> <span>Membership</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
               
            </li>
              <?php  }   if($access[7]['view']=='1'){?> 
             <li class="treeview">
                <a href="<?php echo base_url(); ?>admin/points/plan">
                    <i class="fa fa-folder"></i> <span>Points Plan</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
               
            </li>
            <?php  }   if($access[8]['view']=='1'){?> 
            <li class="treeview">
                <a href="<?php echo base_url(); ?>admin/user/list">
                    <i class="fa fa-user"></i> <span>User</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
               
            </li>
              <?php  }   if($access[9]['view']=='1'){?> 
              <li class="treeview">
                <a href="<?php echo base_url(); ?>admin/subadmin/list">
                    <i class="fa fa-user"></i> <span>Subadmin</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
               
            </li>
             <?php  }   if($access[14]['view']=='1'){?> 
              <li class="treeview">
                <a href="<?php echo base_url(); ?>admin/post">
                    <i class="fa fa-user"></i> <span>Report Post</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
               
            </li>
             <?php  }   if($access[15]['view']=='1'){ ?> 

             <li class="treeview">
                <a href="<?php echo base_url(); ?>admin/template">
                    <i class="fa fa-file-text-o"></i> <span>Template</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
               
            </li>
              <?php  }  if($access[10]['view']=='1'){  ?> 

              <li class="treeview">
                <a href="javascript:void(0)">
                    <i class="fa fa-folder"></i> <span>CMS Content</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <?php    if($access[11]['view']=='1'){  ?> 
                    <li><a href="<?php echo base_url(); ?>admin/pages/list"><i class="fa fa-circle-o"></i>Pages</a></li>
                     <?php  }  if($access[12]['view']=='1'){  ?> 
                    <li><a href="<?php echo base_url(); ?>admin/faq/list"><i class="fa fa-circle-o"></i>Faq</a></li>
                     <?php  }  ?> 
                   <!--  <li><a href="<?php echo base_url(); ?>admin/Tour/image"><i class="fa fa-circle-o"></i>Tour image</a></li> -->
                </ul>
            </li>     

            <?php  } ?>
            <?php  if($access[13]['view']=='1'){ ?> 

             <li class="treeview">
                <a href="<?php echo base_url(); ?>admin/settings">
                    <i class="fa fa-cog"></i> <span>Settings</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
               
            </li>
            <?php  } ?> 
            <li class="treeview">
                <a href="javascript:void(0)">
                    <i class="fa fa-folder"></i> <span>Profile</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url(); ?>admin/edit_profile"><i class="fa fa-circle-o"></i> Edit Profile</a></li>
                    <li><a href="<?php echo base_url(); ?>admin/change_password"><i class="fa fa-circle-o"></i> Change Password</a></li>
                </ul>
            </li>
           <!--  <li class="treeview">
                <a href="javascript:void(0)">
                    <i class="fa fa-user"></i> <span>Post</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url(); ?>admin/post/"><i class="fa fa-circle-o"></i>view Post</a></li>
                </ul>
            </li> -->
		
            <!-- <li class="treeview">
                <a href="#">
                    <i class="fa fa-folder"></i> <span>Product</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url(); ?>admin/product/product_list"><i class="fa fa-circle-o"></i>view Product</a></li>
                    <li><a href="<?php echo base_url(); ?>admin/product/add_product"><i class="fa fa-circle-o"></i>Add Product</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-folder"></i> <span>Products</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url(); ?>admin/product_type/list"><i class="fa fa-circle-o"></i>View Product Types</a></li>
                    <li><a href="<?php echo base_url(); ?>admin/products/list"><i class="fa fa-circle-o"></i>View Products</a></li>
                </ul>
            </li>
		
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-folder"></i> <span>Attributes</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url(); ?>admin/attribute/list"><i class="fa fa-circle-o"></i>Attributes</a></li>
                    <li><a href="<?php echo base_url(); ?>admin/attribute/options"><i class="fa fa-circle-o"></i>Attribute Options</a></li>
                </ul>
            </li>
		
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-folder"></i> <span>Fabrics</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url(); ?>admin/fabric/list"><i class="fa fa-circle-o"></i>View Fabrics</a></li>
                    <li><a href="<?php echo base_url(); ?>admin/fabric_type/list"><i class="fa fa-circle-o"></i>Fabric types</a></li>
                    
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-folder"></i> <span>Buttons</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url(); ?>admin/button_type/list"><i class="fa fa-circle-o"></i>Button Types</a></li>
                    <li><a href="<?php echo base_url(); ?>admin/buttons/list"><i class="fa fa-circle-o"></i>Buttons</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-folder"></i> <span>Other</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url(); ?>admin/color/list"><i class="fa fa-circle-o"></i>Colors</a></li>
                    <li><a href="<?php echo base_url(); ?>admin/patterns/list"><i class="fa fa-circle-o"></i>Patterns</a></li>
                    <li><a href="<?php echo base_url(); ?>admin/weaves/list"><i class="fa fa-circle-o"></i>Weaves</a></li>
                    <li><a href="<?php echo base_url(); ?>admin/yarns/list"><i class="fa fa-circle-o"></i>Yarns</a></li>
                    <li><a href="<?php echo base_url(); ?>admin/wash_care/list"><i class="fa fa-circle-o"></i>Wash Care</a></li>
                </ul>
            </li>  -->
            

            <!-- <li class="treeview">
                <a href="#">
                    <i class="fa fa-gears"></i> <span>Setting</span>
                    <i class="fa fa-angle-right pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url(); ?>admin/fabric_global_setting"><i class="fa fa-circle-o"></i>Global Fabric Setting</a></li>
                </ul>
            </li> -->
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

     