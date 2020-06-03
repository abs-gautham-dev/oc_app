<div class="breadcrumbContainer">
    <div class="container">
        <div class="breadcrumb">
            <div>
                <span><a href="#" class="page-name"><?php echo ucwords($page_title);?></a></span>
            </div>
            <div>
                <?php if(!empty($breadcrumbs)){
                    foreach ($breadcrumbs as $key => $list) { ?>
                        <span><a href="<?php if(!empty($list['link'])){ echo $list['link'];}else{ echo 'javascript:void(0)';}?>"><?php echo ucwords($list['title']);?></a></span>
                <?php  }
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<div id="message_show"></div>
<style type="text/css">
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 0px;
    text-align: center;
}
</style>