
<div class="container">


    <form class="share-thoughts" enctype='multipart/form-data'>
        <div class="row">
            <!-- column -->
            <div class="col-md-8 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Share Something</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-10 form-group">

                            <textarea id="textarea1" name="caption" placeholder="Type and enter" class="form-control" maxlength="20000" rows="3"></textarea>

                        </div>
                        <div class="col-2">
                            <a class="btn-circle btn-lg btn-cyan float-right share-thoughts-btn" onclick="shareThoughts(this);" href="javascript:void(0)"><i class="fas fa-paper-plane"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-footer ">
                    <div class="row">
                        <div class="col-12" id="fileshare">
                            <input name="file[]" type="file" multiple />

                        </div>
                    </div>
                </div>
                <div class="progress" style="height:10px">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>


    </form>



    <div class="row">
        <!-- column -->
        <div class="col-md-8 col-12">

            <?php
            if (!empty($latest_posts) && is_array($latest_posts) && count($latest_posts) > 0) {
                foreach ($latest_posts as $key => $value) {

                    $propic = $value['propic'];
                    if ((!isset($propic) && $propic == NULL) || (!file_exists(FCPATH . $this->config->item("_UPLOAD_PATH") . "/photos/" . $propic))) {
                        $propic = base_url("third_party/images/nopropic.jpg");
                    } else {
                        $propic = base_url() . $this->config->item("_UPLOAD_PATH") . "/photos/" . $propic;
                    }
                    $profile_name = ucfirst(trim($value['first_name'])) . " " . ucfirst(trim($value['last_name']));
                    $caption      = isset($value['caption']) ? Ucfirst(htmlentities(trim($value['caption']), ENT_QUOTES, "UTF-8")) : "";
                    ?>
                    <!-- Comment Row -->
                    <div class="card">

                        <div class="card-body">
                            <div class="propic_wrapper">
                                <a href="<?php echo site_url("member/getUserProfile/" . $value['uid']); ?>">
                                    <img src="<?php echo $propic; ?>" alt="profile image" style="height:40px;width:40px" class="rounded-circle card-img img-fluid ">
                                </a>
                            </div>

                            <h6 class="card-title">
                                <a href="<?php echo site_url("member/getUserProfile/" . $value['uid']); ?>" class="name_wrapper">
                                    <?php echo (isset($value['first_name']) && isset($value['last_name'])) ? htmlentities($profile_name, ENT_QUOTES, "UTF-8") : "Anonymous User"; ?>
                                </a>
                            </h6>
                            <p class="card-text time_container">
                                <small class="text-muted time_wrapper"><?php echo time_ago_in_php($value['created_at']); ?></small>
                            </p>


                            <?php
                            if (trim($caption) != '') {
                                ?>
                                <p class="caption lead"><?php echo $caption; ?></p>
                                <?php
                            }
                            if (isset($value['type']) && trim($value['type']) == 'photo') {
                                $pics = $value['link'];
                                if ((!isset($pics) && $pics == NULL) || (!file_exists(FCPATH . $this->config->item("_UPLOAD_PATH") . "/photos/" . $pics))) {
                                    $pics = base_url("third_party/images/download.jpg");
                                } else {
                                    $pics = base_url() . $this->config->item("_UPLOAD_PATH") . "/photos/" . $pics;
                                }
                                echo '<img src="' . $pics . '" class="img-fluid img-thumbnail resizer time_line_pictures" alt="image">';
                            }
                            ?>

                        </div>


                        <div class="more_wrapper card-footer">
                            <input type="hidden" name="postid" value="<?php echo $value['id']; ?>">
                            <span class="like_counter"> 
                                <a href="javascript:void(0);" class="react">
                                    <?php
                                    if ($value['liked'] == '') {
                                        ?>
                                        <span class="glyphicon glyphicon-thumbs-up"></span>like
                                        <?php
                                    } else {
                                        ?>
                                        <span class="glyphicon glyphicon-thumbs-down"></span>unlike
                                        <?php
                                    }
                                    ?>
                                </a>
                                <span class="likecount"><?php echo $value['likes']; ?></span> likes
                            </span>
                            <span class="comment_counter">
                                <a href="<?php echo site_url("posts/index/" . $value['id']); ?>"><span class="cmntcount"><?php echo $value['comments']; ?></span> comments</a>
                            </span>
                        </div>
                    </div>

                    <?php
                }
            } else {
                echo '<div class="alert alert-danger text-center">No posts to show...</div>';
            }
            ?>

        </div>




        <!-- column -->
        <?php
        if (!empty($people_you_may_know)) {
            ?>
            <div class="col-md-4 col-12 friend-suggetions-div">
                <!-- Card -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">People You May Know</h4>
                        <div class="suggestions-div scrollable" style="height:475px;">
                            <ul class="suggestions-ul">
                                <!--chat Row -->
                                <?php
                                foreach ($people_you_may_know as $key => $val) {
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
                                            <button type="button" class="btn btn-success btn-sm" onclick="sentFriendRequest('<?php echo $val['id']; ?>')">Add Friend</button>
                                            <button type="button" class="btn btn-danger btn-sm">Message</button>
                                        </div>
                                    </li>


                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>



                <!-- Tabs -->

            </div>
            <?php
        }

        if (!empty($friend_requests)) {
            ?>
            <div class="col-md-4 col-12 friend-requests-div">
                <!-- Card -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Friend Requests</h4>
                        <div class="suggestions-div scrollable" style="height:475px;">
                            <ul class="suggestions-ul">
                                <!--chat Row -->
                                <?php
                                foreach ($friend_requests as $key => $val) {
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
                                            <button type="button" class="btn btn-success btn-sm" onclick="respondFriendRequest('<?php echo $val['request_id']; ?>', 'accepted')">Accept</button>
                                            <button type="button" class="btn btn-danger btn-sm" onclick="respondFriendRequest('<?php echo $val['request_id']; ?>', 'remove')">Remove</button>
                                        </div>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <div class="col-md-4 col-12 shout_box_wrapper">
            <!-- Card -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Global chat</h4>
                    <div class="shout_box_div scrollable" style="height:475px;">
                        <ul class="shout_box_ul">
                            <!--chat Row -->


                        </ul>
                        <textarea class="message_poster" maxlength='1000'placeholder='Join the discussion'></textarea>
                    </div>
                </div>
            </div>
            <script src='<?php echo base_url('/js/global_chat.js'); ?>'></script>
        </div>
    </div>
</div>