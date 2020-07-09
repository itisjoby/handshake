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
    <?php
    if ($is_private == 1) {
        ?>
        <form class="frmChat" id="frmChat" enctype='multipart/form-data'>

            <?php
            if (!empty($friend_info) && is_array($friend_info) && count($friend_info) > 0) {

                $val     = $friend_info[0];
                $my_info = $my_info[0];
                $propic  = $val['link'];
                if ((!isset($propic) && $propic == NULL) || (!file_exists(FCPATH . $this->config->item("_UPLOAD_PATH") . "/photos/" . $propic))) {
                    $propic = base_url("third_party/images/nopropic.jpg");
                } else {
                    $propic = base_url() . $this->config->item("_UPLOAD_PATH") . "/photos/" . $propic;
                }
                $mypropic = $my_info['link'];
                if ((!isset($mypropic) && $mypropic == NULL) || (!file_exists(FCPATH . $this->config->item("_UPLOAD_PATH") . "/photos/" . $mypropic))) {
                    $mypropic = base_url("third_party/images/nopropic.jpg");
                } else {
                    $mypropic = base_url() . $this->config->item("_UPLOAD_PATH") . "/photos/" . $mypropic;
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
                            </div></div>
                        <div class="col-md-8 col-xl-6 chat">
                            <div class="card">
                                <div class="card-header msg_head">
                                    <div class="d-flex bd-highlight">
                                        <div class="img_cont">
                                            <a href="<?php echo site_url("member/getUserProfile/" . $val['id']); ?>"><img src="<?php echo $propic; ?>" class="rounded-circle user_img"></a>

                                            <span class="online_icon"></span>
                                        </div>
                                        <div class="user_info">
                                            <span>Chat with <?php echo (isset($val['first_name']) && isset($val['last_name']) && trim($val['first_name']) != '' && trim($val['last_name']) != '') ? ucfirst(trim(htmlentities($val['first_name'], ENT_QUOTES, "UTF-8"))) . " " . ucfirst(trim(htmlentities($val['last_name'], ENT_QUOTES, "UTF-8"))) : "Mr. Anonymous"; ?></span>
                                            <p>1767 Messages</p>
                                        </div>
                                        <div class="video_cam">
                                            <span><i class="fas fa-video"></i></span>
                                            <span><i class="fas fa-phone"></i></span>
                                        </div>
                                    </div>
                                    <span id="action_menu_btn"><i class="fas fa-ellipsis-v"></i></span>
                                    <div class="action_menu">
                                        <ul>
                                            <li><i class="fas fa-user-circle"></i> View profile</li>
                                            <li><i class="fas fa-users"></i> Add to close friends</li>
                                            <li><i class="fas fa-plus"></i> Add to group</li>
                                            <li><i class="fas fa-ban"></i> Block</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body msg_card_body">
                                    <?php
                                    if (!empty($msg_history) && is_array($msg_history) && count($msg_history) > 0) {
                                        $msg_history = array_reverse($msg_history, true);
                                        foreach ($msg_history as $mkey => $mval) {
                                            if ($mval['sender_id'] == $user_id) {
                                                ?>
                                                <div class="d-flex justify-content-end mb-4">
                                                    <div class="msg_cotainer_send">
                                                        <?php
                                                        if ($mval['msgtype'] == 'file') {

                                                            $pics = $mval['filelink'];
                                                            if ((!isset($pics) && $pics == NULL) || (!file_exists(FCPATH . $this->config->item("_UPLOAD_PATH") . "/photos/" . $pics))) {
                                                                $pics = base_url("third_party/images/download.jpg");
                                                            } else {
                                                                $pics = base_url() . $this->config->item("_UPLOAD_PATH") . "/photos/" . $pics;
                                                            }
                                                            echo '<div class="image-responsive thumbnail"><img src="' . $pics . '" class="img-fluid" alt="image" style="min-width:100%"></div>';
                                                        }
                                                        ?>

                                                        <input type="hidden" name="msg_id" value="<?php echo $mval['id']; ?>">

                                                        <?php echo htmlentities(trim($mval['message']), ENT_QUOTES, "UTF-8"); ?>
                                                        <span class="msg_time_send"><?php echo time_ago_in_php($mval['sent_time']); ?></span>
                                                    </div>
                                                    <div class="img_cont_msg">
                                                        <a href="<?php echo site_url("member/getUserProfile/" . $user_id); ?>"><img src="<?php echo $mypropic; ?>" class="rounded-circle user_img_msg"></a>
                                                    </div>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="d-flex justify-content-start mb-4">
                                                    <div class="img_cont_msg">
                                                        <a href="<?php echo site_url("member/getUserProfile/" . $mval['sender_id']); ?>"><img src="<?php echo $propic; ?>" class="rounded-circle user_img_msg"></a>
                                                    </div>
                                                    <div class="msg_cotainer">
                                                        <?php
                                                        if ($mval['msgtype'] == 'file') {

                                                            $pics = $mval['filelink'];
                                                            if ((!isset($pics) && $pics == NULL) || (!file_exists(FCPATH . $this->config->item("_UPLOAD_PATH") . "/photos/" . $pics))) {
                                                                $pics = base_url("third_party/images/download.jpg");
                                                            } else {
                                                                $pics = base_url() . $this->config->item("_UPLOAD_PATH") . "/photos/" . $pics;
                                                            }
                                                            echo '<div class="image-responsive thumbnail"><img src="' . $pics . '" class="img-fluid" alt="image" style="min-width:100%"></div>';
                                                        }
                                                        ?>
                                                        <input type="hidden" name="msg_id" value="<?php echo $mval['id']; ?>">

                                                        <?php echo htmlentities(trim($mval['message']), ENT_QUOTES, "UTF-8"); ?>
                                                        <span class="msg_time_send"><?php echo time_ago_in_php($mval['sent_time']); ?></span>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            ?>



                                            <?php
                                        }
                                    }
                                    ?>




                                </div>

                            </div>
                        </div>
                    </div></div>
                <div class="container-fluid h-100">
                    <div class="row justify-content-center h-100">
                        <div class="col-md-4 col-xl-3 chat"></div><div class="col-md-8 col-xl-6 chat">

                            <div class="card-footer" style="margin-top: -25px;">
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text attach_btn"><i class="fas fa-paperclip"></i></span>
            <!--                            <input name="file[]" type="file" multiple />-->
                                    </div>
                                    <input type="hidden" name="reciever_id" id="reciever_id" value="<?php echo $reciever_id; ?>"/>
                                    <input type="hidden" name="action" id="action" value=""/>
                                    <input type="hidden" name="quoted_msg" id="quoted_msg" value=""/>
                                    <input type="hidden" name="forwarded_from" id="forwarded_from" value=""/>
                                    <input type="hidden" name="is_private" id="is_private" value="<?php echo $is_private; ?>"/>
                                    <textarea name="message" class="form-control type_msg" placeholder="Type your message..." maxlength="20000"></textarea>
                                    <div class="input-group-append">
                                        <span class="input-group-text send_btn" onclick="sendMessage(this);"><i class="fas fa-location-arrow"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <?php
            }
            ?>


        </form>
        <?php
    } else if ($is_private == 0) {
        if($left_group!=1){
        $group_action_btn_content = '<ul>
                                                <li><a href=&#39;' . site_url("chat/viewMembers/" . $reciever_id) . '&#39;><i class="fas fa-user-circle"></i> View Members</a></li>
                                                <li><a href=&#39;javascript:void(0);&#39; onclick=&#39;loadAddGroupMember(' . $reciever_id . ');&#39;><i class="fas fa-users"></i> Add Members</a></li>
                                                <li><i class="fas fa-plus"></i> Customise</li>
                                                <li><i class="fas fa-plus"></i> Group stats</li>
                                                <li><i class="fas fa-ban"></i> Mute</li>
                                                <li><a href=&#39;javascript:void(0);&#39; onclick=&#39;delete_group(' . $reciever_id . ');&#39;><i class="fas fa-ban"></i> Clear Messages</a></li>
                                                <li><a href=&#39;javascript:void(0);&#39; onclick=&#39;leave_group_warning(' . $reciever_id . ');&#39;><i class="fas fa-ban"></i> Leave</a></li>
                                            </ul>';
        }
        else{
            $group_action_btn_content = '<ul>
                                                <li><a href=&#39;' . site_url("chat/viewMembers/" . $reciever_id) . '&#39;><i class="fas fa-user-circle"></i> View Members</a></li>
                                                
                                                <li><a href=&#39;javascript:void(0);&#39; onclick=&#39;delete_group(' . $reciever_id . ');&#39;><i class="fas fa-ban"></i> Delete</a></li>
                                            </ul>';
        }
        ?>
        <form class="frmChat" id="frmChat" enctype='multipart/form-data'>

            <?php
            if (!empty($group_data) && is_array($group_data) && count($group_data) > 0) {

                $group_data = $group_data[0];


                $group_pic = $group_data['icon'];
                if ((!isset($group_pic) && $group_pic == NULL) || (!file_exists(FCPATH . $this->config->item("_UPLOAD_PATH") . "/photos/" . $group_pic))) {
                    $group_pic = base_url("third_party/images/nopropic.jpg");
                } else {
                    $group_pic = base_url() . $this->config->item("_UPLOAD_PATH") . "/photos/" . $group_pic;
                }

                if($left_group!=1){
                $group_dp_click = '<ul>
                                                <li><a target=&#39;_blank&#39; href=&#39;' . $group_pic . '&#39;><i class="fas fa-user-circle"></i> View Picture</a></li>
                                                <li><a href=&#39;' . site_url("chat/savePicture/" . url_title($group_data['icon'])) . '&#39; ><i class="fas fa-users"></i> Save Picture</a></li>
                                                <li><a href=&#39;javascript:void(0)&#39; onclick=&#39;changePicture()&#39;><i class="fas fa-users"></i> Change Picture</a></li>
                                            </ul>';
                }
            else{
                 $group_dp_click = '<ul>
                                                <li><a target=&#39;_blank&#39; href=&#39;' . $group_pic . '&#39;><i class="fas fa-user-circle"></i> View Picture</a></li>
                                                <li><a href=&#39;' . site_url("chat/savePicture/" . url_title($group_data['icon'])) . '&#39; ><i class="fas fa-users"></i> Save Picture</a></li>
                                                
                                            </ul>';
            }
                ?>

                <div class="container-fluid h-100" id="msg_area">
                    <div class="row justify-content-center h-100">
                        <div class="col-md-4 col-xl-3 chat">
                            <div class="card mb-sm-3 mb-md-0 contacts_card">
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
                                                                    echo (isset($value['unread']) && trim($value['unread']) > 0 ) ? "<strong class='unread_msg_count'> (" . $value['unread'] . ")</strong>" : "";
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

                                                <!--                                      <span class="online_icon offline"></span> data-trigger="focus"-->

                                    </ui>
                                </div>
                                <div class="card-footer"></div>
                            </div></div>
                        <div class="col-md-8 col-xl-6 chat">
                            <div class="card">
                                <div class="card-header msg_head">
                                    <div class="d-flex bd-highlight">
                                        <div tabindex="0" class="img_cont" data-toggle="popover"  data-placement="left" data-html="true" data-content='<?php echo $group_dp_click; ?>'>
                                            <img src="<?php echo $group_pic; ?>" class="rounded-circle user_img">

                                            <span class="online_icon"></span>
                                        </div>
                                        <div class="user_info">
                                            <span>Chat with <?php echo (isset($group_data['name']) && trim($group_data['name']) != '' ) ? ucfirst(trim(htmlentities($group_data['name'], ENT_QUOTES, "UTF-8"))) : "***********"; ?></span>
                                            <p>1767 Messages</p>
                                        </div>
                                        <div class="video_cam">
                                            <span><i class="fas fa-video"></i></span>
                                            <span><i class="fas fa-phone"></i></span>
                                        </div>
                                    </div>
                                    <span id="action_menu_btn" tabindex="-1" data-toggle="popover"  data-placement="left" data-html="true" data-content='<?php echo $group_action_btn_content; ?>'><i class="fas fa-ellipsis-v"></i></span>
                                    <div class="action_menu">
                                        <ul>
                                            <li><i class="fas fa-user-circle"></i> View profile</li>
                                            <li><i class="fas fa-users"></i> Add to close friends</li>
                                            <li><i class="fas fa-plus"></i> Add to group</li>
                                            <li><i class="fas fa-ban"></i> Block</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body msg_card_body">
                                    <?php
                                    if (!empty($msg_history) && is_array($msg_history) && count($msg_history) > 0) {
                                        $msg_history = array_reverse($msg_history, true);
                                        foreach ($msg_history as $mkey => $mval) {


                                            $propic = $friend_info[$mval['sender_id']]['link'];
                                            if ((!isset($propic) && $propic == NULL) || (!file_exists(FCPATH . $this->config->item("_UPLOAD_PATH") . "/photos/" . $propic))) {
                                                $propic = base_url("third_party/images/nopropic.jpg");
                                            } else {
                                                $propic = base_url() . $this->config->item("_UPLOAD_PATH") . "/photos/" . $propic;
                                            }


                                            if ($mval['sender_id'] == $user_id) {
                                                ?>
                                                <div class="d-flex justify-content-end mb-4">
                                                    <div class="msg_cotainer_send">
                                                        <?php
                                                        if ($mval['msgtype'] == 'file') {

                                                            $pics = $mval['filelink'];
                                                            if ((!isset($pics) && $pics == NULL) || (!file_exists(FCPATH . $this->config->item("_UPLOAD_PATH") . "/photos/" . $pics))) {
                                                                $pics = base_url("third_party/images/download.jpg");
                                                            } else {
                                                                $pics = base_url() . $this->config->item("_UPLOAD_PATH") . "/photos/" . $pics;
                                                            }
                                                            echo '<div class="image-responsive thumbnail"><img src="' . $pics . '" class="img-fluid" alt="image" style="min-width:100%"></div>';
                                                        }
                                                        ?>

                                                        <input type="hidden" name="msg_id" value="<?php echo $mval['id']; ?>">

                                                        <?php echo htmlentities(trim($mval['message']), ENT_QUOTES, "UTF-8"); ?>
                                                        <span class="msg_time_send"><?php echo time_ago_in_php($mval['sent_time']); ?></span>
                                                    </div>
                                                    <div class="img_cont_msg">
                                                        <a href="<?php echo site_url("member/getUserProfile/" . $mval['sender_id']); ?>"><img src="<?php echo $propic; ?>" class="rounded-circle user_img_msg"></a>
                                                    </div>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="d-flex justify-content-start mb-4">
                                                    <div class="img_cont_msg">
                                                        <a href="<?php echo site_url("member/getUserProfile/" . $mval['sender_id']); ?>"><img src="<?php echo $propic; ?>" class="rounded-circle user_img_msg"></a>
                                                    </div>
                                                    <div class="msg_cotainer">
                                                        <?php
                                                        if ($mval['msgtype'] == 'file') {

                                                            $pics = $mval['filelink'];
                                                            if ((!isset($pics) && $pics == NULL) || (!file_exists(FCPATH . $this->config->item("_UPLOAD_PATH") . "/photos/" . $pics))) {
                                                                $pics = base_url("third_party/images/download.jpg");
                                                            } else {
                                                                $pics = base_url() . $this->config->item("_UPLOAD_PATH") . "/photos/" . $pics;
                                                            }
                                                            echo '<div class="image-responsive thumbnail"><img src="' . $pics . '" class="img-fluid" alt="image" style="min-width:100%"></div>';
                                                        }
                                                        ?>
                                                        <input type="hidden" name="msg_id" value="<?php echo $mval['id']; ?>">

                                                        <?php echo htmlentities(trim($mval['message']), ENT_QUOTES, "UTF-8"); ?>
                                                        <span class="msg_time_send"><?php echo time_ago_in_php($mval['sent_time']); ?></span>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            ?>



                                            <?php
                                        }
                                    }
                                    ?>




                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            <?php
            if($left_group!=1){
            ?>
                <div class="container-fluid h-100">
                    <div class="row justify-content-center h-100">
                        <div class="col-md-4 col-xl-3 chat"></div>
                        <div class="col-md-8 col-xl-6 chat">
                            <div class="card-footer" style="margin-top: -25px;">
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text attach_btn"><i class="fas fa-paperclip"></i></span>
            <!--                            <input name="file[]" type="file" multiple />-->
                                    </div>
                                    <input type="hidden" name="reciever_id" id="reciever_id" value="<?php echo $reciever_id; ?>"/>
                                    <input type="hidden" name="action" id="action" value=""/>
                                    <input type="hidden" name="quoted_msg" id="quoted_msg" value=""/>
                                    <input type="hidden" name="forwarded_from" id="forwarded_from" value=""/>
                                    <input type="hidden" name="is_private" id="is_private" value="<?php echo $is_private; ?>"/>
                                    <textarea name="message" class="form-control type_msg" placeholder="Type your message..." maxlength="20000"></textarea>
                                    <div class="input-group-append">
                                        <span class="input-group-text send_btn" onclick="sendMessage(this);"><i class="fas fa-location-arrow"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            else{
                ?>
            
            <div class="container-fluid h-100">
                    <div class="row justify-content-center h-100">
                        <div class="col-md-4 col-xl-3 chat"></div>
                        <div class="col-md-8 col-xl-6 chat">
                            <div class="card-footer" style="margin-top: -25px;">
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text attach_btn"><i class="fas fa-paperclip"></i></span>
            <!--                            <input name="file[]" type="file" multiple />-->
                                    </div>
                                    <input type="hidden" name="reciever_id" id="reciever_id" value="<?php echo $reciever_id; ?>"/>
                                    <input type="hidden" name="action" id="action" value=""/>
                                    <input type="hidden" name="quoted_msg" id="quoted_msg" value=""/>
                                    <input type="hidden" name="forwarded_from" id="forwarded_from" value=""/>
                                    <input type="hidden" name="is_private" id="is_private" value="<?php echo $is_private; ?>"/>
                                    <textarea disabled name="message" class="form-control type_msg" placeholder="You cannot reply to this conversation since you are not part of this group" maxlength="20000"></textarea>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            }
            ?>


        </form>
        <?php
    }
    ?>



    <style>
        .unread_msg_count{
            color:yellow;
        }
    </style>




    <div class="modal fade" id="first_modal_div"></div>
    <div class="modal fade" id="second_modal_div"></div>
    <div class="modal fade" id="third_modal_div"></div>

    <?php
} elseif ($action == 'create_group') {
    ?>
    <div class="modal-dialog" style="max-width:70%">

        <div class="modal-content">
            <form class="formValidation" id="createGroup">
                <div class="modal-header">
                    <h4 class="modal-title">Create Group Chat</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>

                </div>


                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Group Name <span style='color:red'> *</span></label>
                                <input type="text" name="groupname" value="" class="form-control" maxlength="250" required data-fv-notempty-message="Group name cannot be empty"/>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-footer" >
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success pull-right" onclick="submitActionSet('create_group', event)">Create</button>

                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <?php
} else if ($action == 'view_member') {
    ?>


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
    <div class="col-lg-6 friend-suggetions-div">
        <!-- Card -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Members (<?php echo count($group_member_arr) ?>)</h4>
                <?php
                if (!empty($group_member_arr) && is_array($group_member_arr)) {
                    if (!empty($friend_list) && is_array($friend_list)) {
                        $friend_list_arr = array_column($friend_list, 'id');
                    } else {
                        $friend_list_arr = array();
                    }
                    if (!empty($pending_friend_list) && is_array($pending_friend_list)) {
                        $pfriend_list_arr = array_column($pending_friend_list, 'id');
                    } else {
                        $pfriend_list_arr = array();
                    }
                    ?>
                    <div class="suggestions-div scrollable" style="height:475px;">
                        <ul class="suggestions-ul">
                            <!--chat Row -->
                            <?php
                            foreach ($group_member_arr as $key => $val) {
                                $propic = $val['link'];
                                if ((!isset($propic) && $propic == NULL) || (!file_exists(FCPATH . $this->config->item("_UPLOAD_PATH") . "/photos/" . $propic))) {
                                    $propic = base_url("third_party/images/nopropic.jpg");
                                } else {
                                    $propic = base_url() . $this->config->item("_UPLOAD_PATH") . "/photos/" . $propic;
                                }
                                ?>

                                <li>
                                    <span class="profile-userpic">
                                        <a href="<?php echo site_url("member/getUserProfile/" . $val['id']); ?>"><img src="<?php echo $propic; ?>" alt="profile image" style="height:100px;width:100px" class="thumbnail"></a>

                                    </span>
                                    <span class="profile-usertitle-name">
                                        <a href="<?php echo site_url("member/getUserProfile/" . $val['id']); ?>"><?php echo (isset($val['first_name']) && isset($val['last_name']) && trim($val['first_name']) != '' && trim($val['last_name']) != '') ? ucfirst(trim(htmlentities($val['first_name'], ENT_QUOTES, "UTF-8"))) . " " . ucfirst(trim(htmlentities($val['last_name'], ENT_QUOTES, "UTF-8"))) : "Mr. Anonymous"; ?></a>
                                    </span>
                                    <div class="profile-userbuttons">
                                        <?php
                                        if ($val['id'] != $user_id) {
                                            if (!in_array($val['id'], $friend_list_arr)) {
                                                if (in_array($val['id'], $pfriend_list_arr)) {
                                                    ?>
                                                    <button type="button" class="btn btn-warning btn-sm" onclick="removeSentFriendRequest('<?php echo $val['id']; ?>')">Request Sent <i class="fa fa-check"></i></button>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <button type="button" class="btn btn-success btn-sm" onclick="sentFriendRequest('<?php echo $val['id']; ?>')">Add Friend</button>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <button type="button" class="btn btn-info btn-sm" onclick="unFriend('<?php echo $val['id']; ?>')">Friends <i class="fa fa-check"></i></button>
                                                <?php
                                            }
                                            ?>
                                            <a href="<?php echo site_url("chat/index/" . $val['id'] . "/1"); ?>"><button type="button" class="btn btn-danger btn-sm">Message</button></a>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </li>

                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                <?php } ?>



            </div>
        </div>
        <div class="clearfix"></div>
    </div>

    <?php
} elseif ($action == 'add_group_member') {
    ?>
    <div class="modal-dialog" style="max-width:70%">

        <div class="modal-content">
            <form class="formValidation" id="addmembers">
                <div class="modal-header">
                    <h4 class="modal-title">Add Members</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>

                </div>
                <?php
                if (!empty($group_member_arr) && is_array($group_member_arr)) {
                    $group_existing_member = array_column($group_member_arr, "id");
                } else {
                    $group_existing_member = [];
                }
                ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-striped datatable">
                                    <thead>
                                        <tr>
                                            <th class="text-left"></th>
                                            <th class="text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($friend_list as $key => $val) {
                                            if (in_array($val['id'], $group_existing_member)) {
                                                continue;
                                            }
                                            $propic = $val['link'];
                                            if ((!isset($propic) && $propic == NULL) || (!file_exists(FCPATH . $this->config->item("_UPLOAD_PATH") . "/photos/" . $propic))) {
                                                $propic = base_url("third_party/images/nopropic.jpg");
                                            } else {
                                                $propic = base_url() . $this->config->item("_UPLOAD_PATH") . "/photos/" . $propic;
                                            }
                                            ?>
                                            <tr>
                                                <td class="text-left"> 
                                                    <span class="profile-userpic">
                                                        <a href="<?php echo site_url("member/getUserProfile/" . $val['id']); ?>"><img src="<?php echo $propic; ?>" alt="profile image" style="height:100px;width:100px" class="thumbnail"></a>

                                                    </span>
                                                    <span class="profile-usertitle-name">
                                                        <a href="<?php echo site_url("member/getUserProfile/" . $val['id']); ?>"><?php echo (isset($val['first_name']) && isset($val['last_name']) && trim($val['first_name']) != '' && trim($val['last_name']) != '') ? ucfirst(trim(htmlentities($val['first_name'], ENT_QUOTES, "UTF-8"))) . " " . ucfirst(trim(htmlentities($val['last_name'], ENT_QUOTES, "UTF-8"))) : "Mr. Anonymous"; ?></a>
                                                    </span>
                                                </td>
                                                <td class="text-center"><input type="checkbox" name="friendlist[]" class="form-control" value="<?php echo $val['id']; ?>"></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-footer" >
                    <input type="hidden" name="group_id" id="group_id" value="<?php echo $group_id; ?>">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success pull-right" onclick="addGroupmembers()">Add Selected Members</button>

                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <?php
} else if ($action == 'photo_upload') {
    ?>
    <div class="modal-dialog" style="max-width:70%">

        <div class="modal-content">
            <form class="formValidation" id="changeGrouppic">
                <div class="modal-header">
                    <h4 class="modal-title">Change Group Picture</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>

                </div>


                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">


                            <legend class='civil-id'>Upload Group Picture</legend>

                            <div class="clearfix"></div>
                            <div class="row">
                                <div class="demo-wrap upload-demo">
                                    <div class="col-sm-12 col-md-6 margin-10-mobile">
                                        <div class="actions text-center">
                                            <a class="btn btn-primary file-btn">
                                                <span>Choose Image</span>
                                                <input type="file" id="upload" name="group_pic[]" onchange="return readURL(this)" value="Choose a file" accept="image/*" data-fv-notempty="true" data-fv-notempty-message="Civil ID image cannot be empty" data-fv-file-maxsize="<?php echo ($this->config->item("_MAX_SIZE") * 1024); ?>" data-fv-file-message="Please select a file with specified requirement" data-fv-file-type="<?php echo $this->config->item("_FILE_UPLOAD_ALLOWED_TYPES"); ?>" data-fv-file-extension="<?php echo $this->config->item("_FILE_UPLOAD_ALLOWED_EXTENSION"); ?>" />
                                            </a>

                                        </div>
                                        <div class="img-wrap" id="front_image">

                                        </div>
                                        <input type="hidden" name="x" id="dataX">
                                        <input type="hidden" name="y" id="dataY">
                                        <input type="hidden" name="width" id="dataWidth">
                                        <input type="hidden" name="height" id="dataHeight">
                                        <input type="hidden" id="dataRotate">
                                        <input type="hidden" id="dataScaleX">
                                        <input type="hidden" id="dataScaleY">
                                    </div>
                                </div>


                                <div class="clearfix"></div>

                            </div>
                            <div class="clearfix"></div>
                            <div>
                                <div class="result_img"></div>

                            </div>
                            <br><br><br>
                            <script>

                                var console = window.console || {log: function () {}};
                                var URL = window.URL || window.webkitURL;
                                var $image = $('#image');
                                var $download = $('#download');
                                var $dataX = $('#dataX');
                                var $dataY = $('#dataY');
                                var $dataHeight = $('#dataHeight');
                                var $dataWidth = $('#dataWidth');
                                var $dataRotate = $('#dataRotate');
                                var $dataScaleX = $('#dataScaleX');
                                var $dataScaleY = $('#dataScaleY');
                                var options = {
                                    aspectRatio: 'free',
                                    dragMode: 'move',
                                    preview: '.img-preview',
                                    responsive: true,
                                    modal: true,
                                    highlight: true,
                                    background: false,
                                    movable: true,
                                    rotatable: true,
                                    scalable: true,
                                    zoomable: true,
                                    zoomOnWheel: true,
                                    minContainerWidth: 250,
                                    minContainerHeight: 250,
                                    minCropBoxWidth: 50,
                                    minCropBoxHeight: 50,
                                    crop: function (e) {
                                        $dataX.val(Math.round(e.detail.x));
                                        $dataY.val(Math.round(e.detail.y));
                                        $dataHeight.val(Math.round(e.detail.height));
                                        $dataWidth.val(Math.round(e.detail.width));
                                        $dataRotate.val(e.detail.rotate);
                                        $dataScaleX.val(e.detail.scaleX);
                                        $dataScaleY.val(e.detail.scaleY);
                                    }
                                };
                                function readURL(input) {
                                    $('.loading-div').show();
                                    var url = input.value;
                                    var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                                    if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                                        var reader = new FileReader();
                                        reader.onload = function (e) {
                                            //$('#image').attr('src', e.target.result);
                                            console.log(options);
                                            load_civil_id = 0;
                                            $("#front_image").html('<img id="image" src="" alt="Font Image">');
                                            $('#image').cropper('destroy').attr('src', e.target.result).cropper(options);
                                            $('.loading-div').hide();
                                        }
                                        reader.readAsDataURL(input.files[0]);
                                        //$('.loading-div').hide();
                                    } else {
                                        $(this).attr('src', '');
                                        $("#front_image").html('<h4 style="display:block;line-height:20vh;">Please upload front side image of Civil ID</h4><img src="<?= base_url('third_party/images/font_side_sample.png'); ?>" style="width:400px;" />');
                                        $('#image').cropper('destroy');
                                        $('.loading-div').hide();
                                    }
                                }



                            </script>

                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-footer" >
                    <input type="hidden" name="action" value=""/>
                    <input type="hidden" name="group_id" value="<?php echo $group_id; ?>"/>
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success pull-right" onclick="submitActionSet('change_group_picture', event, '', '#changeGrouppic')">Change Picture</button>

                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <?php
}
?>
<script>
    $(document).ready(function () {
        $('[data-toggle="popover"]').popover();
    });
</script>
<style>
    .suggestions-div{
        width:100%;
        height:100%;
    }
    .suggestions-ul{
        display: flex;
        flex-direction: column;
        margin: 0;
        padding: 0;
    }
    .suggestions-ul li {
        list-style-type:none;
        border: 1px solid black;

        flex-grow: 1;
        text-align: left;

    }

    .profile-userpic{
        float:left;
        padding: 5px;
    }
    span.profile-usertitle-name{
        padding: 5px;
    }
    ul li {
        list-style-type:none;
    }
</style>