<div class="container">

    <form method="post" id="frm_registration" class="formValidation" enctype="multipart/form-data" >
        <div class="row">
            

                <div class="col-6">

                    <div class="card">
                        <div class="card-header">Basic Information</div>
                        <div class="card-body">
                            <div class="col form-group">
                                <label>First Name</label>
                                <input type="text" class="form-control form-control-lg" placeholder="First Name" name="first_name" value="<?php echo isset($content['basic_details'][0]['first_name']) ? htmlentities(trim($content['basic_details'][0]['first_name']), ENT_QUOTES, "UTF-8") : "" ?>">
                            </div>
                            <div class="col form-group">
                                <label>Last Name</label>
                                <input type="text" class="form-control form-control-lg" placeholder="Last Name" name="last_name" value="<?php echo isset($content['basic_details'][0]['last_name']) ? htmlentities(trim($content['basic_details'][0]['last_name']), ENT_QUOTES, "UTF-8") : "" ?>">
                            </div>

                            <div class="col form-group">
                                <label>Date of Birth</label>
                                <input type="text" class="form-control form-control-lg datepicker" readonly placeholder="dd-mm-yyyy" name="dob" value="<?php echo isset($content['basic_details'][0]['dob']) ? date("d-m-Y", strtotime(htmlentities(trim($content['basic_details'][0]['dob']), ENT_QUOTES, "UTF-8"))) : "" ?>">
                            </div>
                            <div class="col form-group">
                                <label>Email</label>
                                <input type="email" class="form-control form-control-lg" placeholder="Email" name="email" value="<?php echo isset($content['basic_details'][0]['email']) ? htmlentities(trim($content['basic_details'][0]['email']), ENT_QUOTES, "UTF-8") : "" ?>">
                            </div>
                            <div class="col form-group">
                                <label>Phone Number</label>
                                <input type="tel" class="form-control form-control-lg" placeholder="Phone Number" name="phone_number" value="<?php echo isset($content['basic_details'][0]['phone_number']) ? htmlentities(trim($content['basic_details'][0]['phone_number']), ENT_QUOTES, "UTF-8") : "" ?>">
                            </div>
                            <div class="col form-group">
                                <label>Religion</label>
                                <input type="text" class="form-control form-control-lg" placeholder="Religion" name="religion" value="<?php echo isset($content['basic_details'][0]['religion']) ? htmlentities(trim($content['basic_details'][0]['religion']), ENT_QUOTES, "UTF-8") : "" ?>">
                            </div>
                            <div class="col form-group">
                                <label>Relationship Status</label>
                                <select name="relation_ship_status" class="form-control form-control-lg">
                                    <option value="">--select--</option>
                                </select>
                            </div>
                            <div class="col form-group">
                                <label>Current City</label>
                                <input type="text" class="form-control form-control-lg" placeholder="Current City" name="current_city" value="<?php echo isset($content['basic_details'][0]['current_city']) ? htmlentities(trim($content['basic_details'][0]['current_city']), ENT_QUOTES, "UTF-8") : "" ?>">
                            </div>
                            <div class="col form-group">
                                <label>Home Town</label>
                                <input type="text" class="form-control form-control-lg" placeholder="Home Town" name="home_town" value="<?php echo isset($content['basic_details'][0]['home_town']) ? htmlentities(trim($content['basic_details'][0]['home_town']), ENT_QUOTES, "UTF-8") : "" ?>">
                            </div>
                            <div class="col form-group">
                                <label>Bio</label>
                                <textarea class="form-control form-control-lg" placeholder="Bio" name="bio"><?php echo isset($content['basic_details'][0]['bio']) ? htmlentities(trim($content['basic_details'][0]['bio']), ENT_QUOTES, "UTF-8") : "" ?></textarea>
                            </div>
                            <div class="col form-group">
                                <label>About Me</label>
                                <textarea class="form-control form-control-lg" placeholder="About Me" name="about_me"><?php echo isset($content['basic_details'][0]['about_me']) ? htmlentities(trim($content['basic_details'][0]['about_me']), ENT_QUOTES, "UTF-8") : "" ?></textarea>
                            </div>

                            <div class="card-footer form-group" style="text-right">
                                <button type="button" class="btn btn-sm btn-primary" onclick="submitActionSet('update', event)">Update Profile</button>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">Upload Profile Photo</div>
                        <div class="card-body">

                            <div class="demo-wrap upload-demo">
                                <div class="col-sm-12 col-md-6 margin-10-mobile">
                                    <div class="actions text-center">
                                        <a class="btn btn-primary file-btn">
                                            <span>Choose Profile Image</span>
                                            <input type="file" id="upload" name="profile_pic" onchange="return readURL(this)" value="Choose a file" accept="image/*" data-fv-file-maxsize="<?php echo ($this->config->item("_MAX_SIZE") * 1024); ?>" data-fv-file-message="Please select a file with specified requirement" data-fv-file-type="<?php echo $this->config->item("_FILE_UPLOAD_ALLOWED_TYPES"); ?>" data-fv-file-extension="<?php echo $this->config->item("_FILE_UPLOAD_ALLOWED_EXTENSION"); ?>" />
                                        </a>

                                    </div>
                                    <div class="img-wrap" id="front_image">
                                        
                                        <img src="<?= base_url('third_party/images/font_side_sample.png'); ?>" style="width:400px;" />
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
                                        $("#front_image").html('<img src="<?= base_url('third_party/images/font_side_sample.png'); ?>" style="width:400px;" />');
                                        $('#image').cropper('destroy');
                                        $('.loading-div').hide();
                                    }
                                }



                            </script>

                        </div>

                        <!-- column -->
                    </div>
                

            </div>
        </div>
    </form>

</div>
<input type="hidden" name="action" id="action" value=""/>