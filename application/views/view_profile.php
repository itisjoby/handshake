

<?php
if ($action == 'home') {
    ?>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php
                $img = $content['basic_details'][0]['pro_pic_url'];
                if ((!isset($img) && $img == NULL) || (!file_exists(FCPATH . $this->config->item("_UPLOAD_PATH") . "/photos/" . $img))) {
                    $img = base_url("third_party/images/nopropic.jpg");
                } else {
                    $img = base_url() . $this->config->item("_UPLOAD_PATH") . "/photos/" . $img;
                }
                $cover_img = $content['basic_details'][0]['coverpic_url'];
                if ((!isset($cover_img) && $cover_img == NULL) || (!file_exists(FCPATH . $this->config->item("_UPLOAD_PATH") . "/photos/" . $cover_img))) {
                    $cover_img = base_url("third_party/images/nopropic.jpg");
                } else {
                    $cover_img = base_url() . $this->config->item("_UPLOAD_PATH") . "/photos/" . $cover_img;
                }
                ?>

                <div class="fb-profile">
                    <img align="left" class="fb-image-lg img-fluid resizer" src="<?php echo $cover_img; ?>" alt="Profile image example"/>
                    <img align="left" class="fb-image-profile thumbnail img-fluid" src="<?php echo $img; ?>" alt="Profile image"/>
                    <div class="fb-profile-text">
                        <h1 class="name-view"><?php echo isset($content['basic_details'][0]['first_name']) ? Ucfirst(htmlentities(trim($content['basic_details'][0]['first_name']), ENT_QUOTES, "UTF-8")) : "" ?> <?php echo isset($content['basic_details'][0]['last_name']) ? Ucfirst(htmlentities(trim($content['basic_details'][0]['last_name']), ENT_QUOTES, "UTF-8")) : "" ?></h1>
                        <p class="about-view"><?php echo isset($content['basic_details'][0]['about_me']) ? Ucfirst(htmlentities(trim($content['basic_details'][0]['about_me']), ENT_QUOTES, "UTF-8")) : "" ?>.</p>
                    </div>
                </div>
            </div>

            <!-- column -->
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">Basic Informations</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                Date of Birth  
                            </div>
                            <div class="col">
                                <?php
                                echo isset($content['basic_details'][0]['dob']) ? date("d-m-Y", strtotime(htmlentities(trim($content['basic_details'][0]['dob']), ENT_QUOTES, "UTF-8"))) : "";
                                ?>
                            </div>
                        </div>

                        <div class="row">                  
                            <div class="col">
                                <label>Email  </label>
                            </div>
                            <div class="col">
                                <?php
                                echo isset($content['basic_details'][0]['email']) ? htmlentities(trim($content['basic_details'][0]['email']), ENT_QUOTES, "UTF-8") : "";
                                ?>

                            </div>
                        </div>

                        <div class="row">        
                            <div class="col">
                                <label>Phone Number  </label>
                            </div>
                            <div class="col">
                                <?php
                                echo isset($content['basic_details'][0]['phone_number']) ? htmlentities(trim($content['basic_details'][0]['phone_number']), ENT_QUOTES, "UTF-8") : "";
                                ?>

                            </div>
                        </div>

                        <div class="row">  
                            <div class="col">
                                <label>Religion  </label>
                            </div>
                            <div class="col">
                                <?php
                                echo isset($content['basic_details'][0]['religion']) ? htmlentities(trim($content['basic_details'][0]['religion']), ENT_QUOTES, "UTF-8") : "";
                                ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label>Relationship Status</label>
                            </div>
                            <div class="col">
                                <?php
                                echo isset($content['basic_details'][0]['relationship_status']) ? htmlentities(trim($content['basic_details'][0]['relationship_status']), ENT_QUOTES, "UTF-8") : "";
                                ?>
                            </div>
                        </div>

                        <div class="row">    
                            <div class="col">
                                <label>Current City</label>
                            </div>
                            <div class="col">
                                <?php
                                echo isset($content['basic_details'][0]['current_city']) ? htmlentities(trim($content['basic_details'][0]['current_city']), ENT_QUOTES, "UTF-8") : "";
                                ?>
                            </div>
                        </div>

                        <div class="row">       
                            <div class="col">
                                <label>Home Town</label>
                            </div>
                            <div class="col">
                                <?php
                                echo isset($content['basic_details'][0]['home_town']) ? htmlentities(trim($content['basic_details'][0]['home_town']), ENT_QUOTES, "UTF-8") : "";
                                ?>
                            </div>
                        </div>

                        <div class="row">   
                            <div class="col">
                                <label>Bio</label>
                            </div>
                            <div class="col">
                                <?php
                                echo isset($content['basic_details'][0]['bio']) ? htmlentities(trim($content['basic_details'][0]['bio']), ENT_QUOTES, "UTF-8") : "";
                                ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col">
                                <label>About Me</label>
                            </div>
                            <div class="col">
                                <?php
                                echo isset($content['basic_details'][0]['about_me']) ? htmlentities(trim($content['basic_details'][0]['about_me']), ENT_QUOTES, "UTF-8") : "";
                                ?>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">Education Details</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                BCA
                            </div>
                            <div class="col">
                                eTTUMANOORAPAN COLLEGE
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">Work Details</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                Software  developer
                            </div>
                            <div class="col">
                                codepoint softwares
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                Software  developer
                            </div>
                            <div class="col">
                                bharathi information  softwares
                            </div>
                        </div>


                    </div>
                </div>

            </div>
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">Family Details</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <?php
                                echo isset($content['basic_details'][0]['dob']) ? date("d-m-Y", strtotime(htmlentities(trim($content['basic_details'][0]['dob']), ENT_QUOTES, "UTF-8"))) : "";
                                ?>
                            </div>
                            <div class="col">
                                Father
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <?php
                                echo isset($content['basic_details'][0]['dob']) ? date("d-m-Y", strtotime(htmlentities(trim($content['basic_details'][0]['dob']), ENT_QUOTES, "UTF-8"))) : "";
                                ?>
                            </div>
                            <div class="col">
                                Wife
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <?php
                                echo isset($content['basic_details'][0]['dob']) ? date("d-m-Y", strtotime(htmlentities(trim($content['basic_details'][0]['dob']), ENT_QUOTES, "UTF-8"))) : "";
                                ?>
                            </div>
                            <div class="col">
                                Uncle
                            </div>
                        </div>


                    </div>
                </div>

            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-header">Photos</div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            foreach ($files as $key => $val) {
                                ?>

                                <div class="col">
                                    <img src="<?php echo base_url('uploads/photos/' . $val['link']); ?>" alt=""/>

                                </div>



                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- column -->
        </div>
    </div>



    <?php
}
?>