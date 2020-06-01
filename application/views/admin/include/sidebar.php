<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
    <!-- Sidebar user panel -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <?php 
            $admin_id = $this->session->userdata('admin_id');
            $user_type = $this->session->userdata('user_type');
        ?>
        <ul class="sidebar-menu">
           
            <li class="treeview">
                <a href="<?php echo base_url(); ?>admin/dashboard">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <?php if($user_type=='Super Admin'){?>
            <li class="treeview">
                <a href="<?php echo base_url(); ?>admin/categories/list">
                    <i class="fa fa-folder"></i> <span>Category</span>  
                </a>
             </li>  
            <li class="treeview">
                <a href="<?php echo base_url(); ?>admin/media/list">
                   <i class="fa fa-video-camera" aria-hidden="true"></i> <span>Media </span>  
                </a>
            </li>  
            <li class="treeview">
                <a href="<?php echo base_url(); ?>admin/pages/list">
                    <i class="fa fa-file-text" aria-hidden="true"></i><span>Pages</span> 
                </a>
            </li>     
            <li class="treeview">
                <a href="<?php echo base_url(); ?>admin/subadmin/list"> 
                    <i class="fa fa-users"></i> <span>Sub Admins</span> 
                </a>
            </li> 
            <?php }?>  
            <li class="treeview">
                <a href="<?php echo base_url(); ?>admin/user/users_list/1">
                    <i class="fa fa-user-md"></i> <span>Sales List</span> 
                </a>
            </li>   
            <li class="treeview">
                <a href="<?php echo base_url(); ?>admin/user/users_list/2">
                    <i class="fa fa-users"></i> <span>Companies List</span> 
                </a>
            </li> 
            <li class="treeview">
                <a href="<?php echo base_url(); ?>admin/user/users_list/3">
                    <i class="fa fa-users"></i> <span>Inspectors List</span> 
                </a>
            </li>  
             <li class="treeview">
                <a href="<?php echo base_url(); ?>admin/jobs/list/">
                    <i class="fa fa-briefcase"></i> <span>Jobs</span> 
                </a>
            </li> 
            <li class="treeview">
                <a href="<?php echo base_url(); ?>admin/jobs/location_list/">
                    <i class="fa fa-map"></i> <span>Job Locations</span> 
                </a>
            </li> 
            <li class="treeview">
                <a href="<?php echo base_url(); ?>admin/booking/list/">
                    <i class="fa fa-map"></i> <span>Bookings</span> 
                </a>
            </li> 
            <?php if($user_type=='Super Admin'){?>  
            <li class="treeview">
                <a href="<?php echo base_url(); ?>admin/notification"> 
                    <i class="fa fa-globe"></i> <span>Notification</span> 
                </a>
            </li>    
            <?php }?>    
            <?php if($user_type=='Super Admin'){?>  
            <li class="treeview">
                <a href="<?php echo base_url(); ?>admin/advertisement"> 
                   <i class="fa fa-share-square-o" aria-hidden="true"></i> <span>Advertisement</span> 
                </a>
            </li>    
            <?php }?>  
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

           
         
            

         
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

     