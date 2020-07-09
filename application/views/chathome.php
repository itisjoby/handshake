<?php
if ($action == 'home') {
    ?>

    <link href="<?= base_url("third_party/dist/css/chat_styles.css"); ?>" rel="stylesheet">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title"><?php echo $page_title; ?></h4>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $page_title; ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <a class="add-data" href="#" onclick="loadAddForm()" title="create group chat" data-toggle="tooltip" data-placement="top">
        <i class="fa fa-plus" aria-hidden="true"></i>
    </a>
    <?php
    if ($this->session->flashdata('success_msg')) {
        echo "<div class='alert alert-success text-center'>" . $this->session->flashdata('success_msg') . "</div>";
    }

    if ($this->session->flashdata('error_msg')) {
        echo "<div class='alert alert-danger text-center'>" . $this->session->flashdata('error_msg') . "</div>";
    }
    ?>



    <div class="container-fluid h-100" id="msg_area">
        <div class="row justify-content-center h-100">
            <div class="col-md-4 col-xl-3 chat"><div class="card mb-sm-3 mb-md-0 contacts_card">
                    <div class="card-header">
                        <div class="input-group">
                            <input type="text" placeholder="Search..." name="" class="form-control search">
                            <div class="input-group-prepend">
                                <span class="input-group-text search_btn"><i class="fas fa-search"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body contacts_body">
                        <ui class="contacts">
                            <?php
                            if (!empty($chat_history) && is_array($chat_history) && count($chat_history) > 0) {
                                foreach ($chat_history as $index => $value) {

                                    if ($value['is_private'] == 0) {
                                        $url = site_url("chat/index/" . $value['id'] . "/group");
                                    } else {
                                        $url = site_url("chat/index/" . $value['id'] . "/personal");
                                    }


                                    $propic_history = $value['link'];
                                    if ((!isset($propic_history) && $propic_history == NULL) || (!file_exists(FCPATH . $this->config->item("_UPLOAD_PATH") . "/photos/" . $propic_history))) {
                                        $propic_history = base_url("third_party/images/nopropic.jpg");
                                    } else {
                                        $propic_history = base_url() . $this->config->item("_UPLOAD_PATH") . "/photos/" . $propic_history;
                                    }
                                    ?>
                                    <li class="active">
                                        <div class="d-flex bd-highlight">
                                            <div class="img_cont">
                                                <img src="<?php echo $propic_history; ?>" class="rounded-circle user_img">
                                                <span class="online_icon"></span>
                                            </div>
                                            <div class="user_info">
                                                <a href="<?php echo $url; ?>"><span><?php
                                                        echo (isset($value['chatname']) && trim($value['chatname']) != '' ) ? ucfirst(trim(htmlentities($value['chatname'], ENT_QUOTES, "UTF-8"))) : "***********";
                                                        echo (isset($value['unread']) && trim($value['unread']) > 0 ) ? "<strong class='unread_msg_count'>  (" . $value['unread'] . ")</strong>" : "";
                                                        ?></span></a>
                                                <p><?php echo (isset($value['last_msg']) && trim($value['last_msg']) != '' ) ? mb_substr(ucfirst(trim(htmlentities($value['last_msg'], ENT_QUOTES, "UTF-8"))), 0, 30) . "..." : ""; ?></p>
                                                <p><?php echo (isset($value['last_msg_date']) && trim($value['last_msg_date']) != '' ) ? time_ago_in_php($value['last_msg_date']) : ""; ?></p>
                                            </div>
                                        </div>
                                    </li>
                                    <?php
                                }
                            }
                            ?>

                        </ui>
                    </div>
                    <div class="card-footer"></div>
                </div>

            </div>
        </div>

    </div>




    <?php
}
?>
<script>
    $(document).ready(function () {
        $('[data-toggle="popover"]').popover();
    });
</script>
<div class="modal fade" id="first_modal_div"></div>
<div class="modal fade" id="second_modal_div"></div>
<div class="modal fade" id="third_modal_div"></div>