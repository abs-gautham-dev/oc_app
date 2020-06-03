
<!-- kendo UI -->
<link rel="stylesheet" href="bower_components/kendo-ui/styles/kendo.common-material.min.css"/>
<link rel="stylesheet" href="bower_components/kendo-ui/styles/kendo.material.min.css"/>
<!-- altair admin -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/main.min.css" media="all">
<!-- uikit functions -->
<script src="<?php echo base_url(); ?>assets/admin/js/uikit_custom.min.js"></script>
<!-- page specific plugins -->
<!-- kendo UI -->
<script src="assets/admin/js/kendoui_custom.min.js"></script>

<!--  kendoui functions -->
<script src="assets/admin/js/pages/kendoui.min.js"></script>

<!-- page specific plugins -->
<!-- ionrangeslider -->
<script src="bower_components/ion.rangeslider/js/ion.rangeSlider.min.js"></script>

<!--  help/faq functions -->
<script src="assets/admin/js/pages/page_help.min.js"></script>

<div class="whole_container">
    <section class="main_container">
        <div class="inner_main_container pdT-5 pdB-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12  discription_page  mrgB-20 mrgT-15">
                        <div class="uk-accordion uk-accordion-alt help_accordion" data-slide-children="h2" data-slide-context=".md-card-content">
                                <?php  
                                if(!empty($site_information)) { $count=0;
                                    foreach($site_information as $page_data) { 
                                        $count++; 
                                        if($count==1){ ?>
                                            <h2 class="page_title"><?php if(!empty($page_data['question'])) echo $page_data['question'];?></h2>
                                            <div >
                                                <?php if(!empty($page_data['answer'])) echo trim($page_data['answer']);?>
                                            </div>

                                        <?php } else { ?>
                                            <h2 class="uk-accordion-title"><?php if(!empty($page_data['question'])) echo $page_data['question'];?></h2>
                                            <div class="uk-accordion-content">
                                                <?php if(!empty($page_data['answer'])) echo trim($page_data['answer']);?>
                                            </div>
                                        <?php }  
                                    } 
                                } else { ?>
                                    <h2 class="page_title">Content not found !!</h2>
                                <?php }?>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php if(isset($video_data) && !empty($video_data)) { ?>
<div id="first_time_visitor" class="modal fade modal_box_design" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 ">
                        <div class="video_iframe">
                            <div class="video_img">
                                <div class="videoContainer" id="modelVideo">
                                    <video id="mvideo" controls preload="auto" src="<?php echo $video_data['media_name']; ?>" poster="<?php echo $video_data['large_image']; ?>" width="500" height="280">
                                       <!--  <source src="http://techslides.com/demos/sample-videos/small.mp4" type="video/mp4" />
                                        <source src="http://techslides.com/demos/sample-videos/small.webm" type="video/webM" />
                                        <source src="http://techslides.com/demos/sample-videos/small.ogg" type="video/ogg" /> -->
                                        <p>Your browser does not support the video tag.</p>
                                    </video>
                                    <div class="control">
                                        <div class="topControl">
                                            <div class="progress">
                                                <span class="bufferBar"></span>
                                                <span class="timeBar"></span>
                                            </div>
                                            <div class="time">
                                                <span class="current"></span>
                                                <span class="duration"></span>
                                            </div>
                                        </div>
                                        <div class="btmControl">
                                            <div class="btn">
                                                <button title="Rewind video" class="rewind_btn" type=button id='butStepBwd' value='' onclick='FrameStep(mvideo,this,-0.04)'></button>
                                            </div>
                                            <div class="btnPlay btn" title="Play/Pause video"></div>
                                            <div class="btn">
                                                <button title="forward video" class="frw_btn" type=button id='butStepFwd' value='' onclick='FrameStep(mvideo,this,+0.04)'></button>
                                            </div>
                                            <div class="btnStop btn" title="Stop video"></div>

                                            <div class="btnFS btn" title="Switch to full screen"></div>

                                            <div class="volume" title="Set volume">
                                                <span class="volumeBar"></span>
                                            </div>
                                            <div class="sound sound2 btn" title="Mute/Unmute sound"></div>
                                        </div>
                                    </div>
                                    <div class="loading"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<style>
    #first_time_visitor .modal-header {
        padding-bottom: 0px;
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {
        if(window.location.href.indexOf("first_time_visitor") > -1) {
            $("#first_time_visitor").modal('show');
        }
    });
</script>