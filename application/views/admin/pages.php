<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<div id="page_content">
    <div id="page_content_inner">
        <?php if($this->session->flashdata('success_msg'))  { ?>
            <div class="uk-alert uk-alert-success" data-uk-alert="">
                <a href="#" class="uk-alert-close uk-close"></a>
                <?php echo $this->session->flashdata('success_msg'); ?>
            </div>
        <?php } ?>
        <?php if($this->session->flashdata('error_msg'))  { ?>
            <div class="uk-alert uk-alert-danger" data-uk-alert="">
                <a href="#" class="uk-alert-close uk-close"></a>
                <?php echo $this->session->flashdata('error_msg'); ?>
            </div>
        <?php } ?>
        <div class="md-card">
            <div class="md-card-content">
                <div class="uk-grid" data-uk-grid-margin="">
                    <form id="search_form" name = "filter_form" class="" method="" action = "" >
                        <div class="uk-grid" data-uk-grid-margin="">
                            <div class="uk-width-medium-2-6">
                                <label for="page_name">Page Name</label>
                                <input type="text" class="md-input" id ="page_name" name = "page_name" value="" onkeyup="filter_records();">
                            </div>
                            <div class="uk-width-medium-2-6">
                                <div class="uk-margin-small-top">
                                    <select id="orderby" class="kUI_dropdown_basic_select selectpicker uk-form-width-medium" name = "orderby" onchange="filter_records();">
                                        <option value="DESC" >Desc</option>
                                        <option value="ASC" >Asc</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
         <div class="md-card">
            <div class="md-card-toolbar">
                <div class="md-card-toolbar-actions">
                    <a class="green" href="admin/add_page" data-uk-tooltip title="Add New Page"><i class="md-icon clndr_add_event material-icons">&#xE145;</i></a>
                </div>
                <h3 class="md-card-toolbar-heading-text">
                    Pages
                </h3>
            </div>
            <div class="md-card-content" id="ajax_data">
                <?php $this->load->view('admin/pages_data');?>
            </div>
        </div>
    </div>
</div>
<div class="loading-info title_center_desing error" id="loader" style="display:none;"><img src="assets/images/loading.gif" /></div>

<script>
var base_url = '<?php echo base_url();?>';
    function filter_records(page)
    {
        $('.uk-alert').addClass("display-none");
        $("#loader").show();
        if(page==null) {
            page=0;
        }
        
        var page_name = $("#page_name").val();
        var orderby = $("#orderby").val();

        $.ajax({
            type:'GET',
            data:"page_name="+page_name+"&orderby="+orderby,
            url: base_url+"admin/post/pages_data/"+page,
            success:function(data)
            {alert(data);
                $("#loader").hide();
               $("#ajax_data").html(data);
            }
        });
    }

</script>