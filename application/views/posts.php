
<div class="container">
    <div class="row">
        <!-- column -->
        <div class="col-md-9 col-12">
            <div class="post_content">
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
                        <div class="card">

                            <div class="card-body">
                                <div class="propic_wrapper">
                                    <a href="<?php echo site_url("member/getUserProfile"); ?>"><img src="<?php echo $propic; ?>" alt="profile image" style="height:40px;width:40px" class="rounded-circle thumbnail"></a>
                                </div>
                                <h6 class="card-title">
                                    <a href="<?php echo site_url("member/getUserProfile"); ?>"><?php echo (isset($value['first_name']) && isset($value['last_name'])) ? htmlentities($profile_name, ENT_QUOTES, "UTF-8") : "Anonymous User"; ?></a>
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
                                    echo '<img src="' . $pics . '" class="img-fluid img-thumbnail" alt="image" style="min-width:100%">';
                                }
                                ?>

                            </div>
                            <div class="comment_section ">
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
                                        <span class="cmntcount"><?php echo $value['comments']; ?></span> comments
                                    </span>
                                </div>
                                <div class="comments_container card-body">

                                    <?php
                                    if (!empty($latest_cmnts) && is_array($latest_cmnts) && count($latest_cmnts) > 0) {
                                        foreach (array_reverse($latest_cmnts) as $ckey => $cvalue) {

                                            $cpropic = $cvalue['propic'];
                                            if ((!isset($cpropic) && $cpropic == NULL) || (!file_exists(FCPATH . $this->config->item("_UPLOAD_PATH") . "/photos/" . $cpropic))) {
                                                $cpropic = base_url("third_party/images/nopropic.jpg");
                                            } else {
                                                $cpropic = base_url() . $this->config->item("_UPLOAD_PATH") . "/photos/" . $cpropic;
                                            }
                                            $cprofile_name = ucfirst(trim($cvalue['first_name'])) . " " . ucfirst(trim($cvalue['last_name']));
                                            $comment       = isset($cvalue['comment']) ? Ucfirst(htmlentities(trim($cvalue['comment']), ENT_QUOTES, "UTF-8")) : "";
                                            ?>
                                            <div class="propic_wrapper">

                                                <a href="<?php echo site_url("member/getUserProfile"); ?>"><img src="<?php echo $cpropic; ?>" alt="profile image" style="height:40px;width:40px" class="rounded-circle thumbnail"></a>
                                            </div>
                                            <h6 class="card-title">
                                                <a href="<?php echo site_url("member/getUserProfile"); ?>"><?php echo (isset($cvalue['first_name']) && isset($cvalue['last_name'])) ? htmlentities($cprofile_name, ENT_QUOTES, "UTF-8") : "Anonymous User"; ?></a>
                                            </h6>

                                            <p class="caption"><?php echo $comment; ?></p>
                                            <p class="card-text time_container">
                                                <small class="text-muted time_wrapper"><?php echo time_ago_in_php($cvalue['created_at']); ?></small>
                                            </p>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="comment_box_wrapper form-group">
                                <span class="comment_box">
                                    <textarea class="form-control" name="comment"></textarea>
                                </span>
                                <span>
                                    <button type="button" class="btn btn-primary post_comment_btn">Comment</button>
                                </span>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    redirect("dashboard/notfound");
                }
                ?>

            </div>
        </div>
    </div>
</div>

