<div class="whole_container">
    <section class="main_container">
        <div class="inner_main_container pdT-5 pdB-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12  discription_page">
                        <?php if(!empty($page_data)) { ?>
                            <h2 class="page_title"><?php if(!empty($page_data['title'])) echo $page_data['title']; ?></h2>
                            <?php if(!empty($page_data['content'])) echo $page_data['content']; 
                        } else { ?>
                            <h2 class="page_title">Content not found !!</h2>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

