<!DOCTYPE html>
<html dir="ltr" lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Favicon icon -->
        <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url("third_party/assets/images/favicon.png"); ?>">
        <title><?php echo $this->config->item('project_name'); ?></title>


        <!-- All Jquery -->
        <!-- ============================================================== -->
        <script src="<?= base_url("third_party/files/jquery-3.4.1.js"); ?>"></script>
        <script src="<?= base_url("third_party/files/jquery-ui-1.12.1/jquery-ui.min.js"); ?>"></script>
        <script src="<?= base_url("third_party/files/popper.min.js"); ?>"></script>

        <!--    bootstrap-->
        <link href="<?= base_url("third_party/files/bootstrap-4.3.1/css/bootstrap.min.css"); ?>" rel="stylesheet">
        <script src="<?= base_url("third_party/files/bootstrap-4.3.1/js/bootstrap.min.js"); ?>"></script>

        <!-- Select2 -->
        <link rel="stylesheet" href="<?= base_url("third_party/files/select2/dist/css/select2.min.css"); ?>">

        <!-- alertify style -->
        <link rel="stylesheet" href="<?php echo base_url('third_party/files/alertifyjs/css/alertify.min.css'); ?>">
        
        <link rel="stylesheet" href="<?php echo base_url('third_party/files/font-awesome/css/fontawesome-all.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('third_party/files/themify-icons/themify-icons.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('third_party/files/material-design-iconic-font/css/material-design-iconic-font.min.css'); ?>">


        <script type="text/javaScript">
            var date_format	=	"<?php echo $this->config->item('js_date_format'); ?>";
            $site_url = "<?php echo site_url(); ?>";
        </script>

        <link href="<?php echo base_url('third_party/files/animate/animate.css'); ?>" rel="stylesheet" type="text/css">
        <script src="<?= base_url("third_party/files/animate/animate-plus.js"); ?>"></script> 


        <link rel="stylesheet" href="<?php echo base_url('third_party/files/cropping/css/cropper.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('third_party/files/cropping/css/main.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('third_party/files/cropping/demo.css'); ?>"> 

        <!-- Custom CSS -->
        <link href="<?= base_url("third_party/css/style.css"); ?>" rel="stylesheet">


    </head>

    <body>
        

            <div id="main-wrapper">
                <nav class="navbar navbar-expand-lg navbar-light bg-light">

                    <ul class="nav">
                        <li class="active">
                            <a class="navbar-brand" href="<?php echo site_url("dashboard/index"); ?>">
                                <!-- Logo icon -->
                                <b class="logo-icon navbar-brand">
                                    <!-- Dark Logo icon -->
                                    <img src="<?= base_url("third_party/images/handshake-logo.png"); ?>" alt="homepage" style="max-height:60px" class="light-logo image-responsive" />
                                </b>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="dropdown-item" href="<?php echo site_url("member/getUserProfile"); ?>"><i class="ti-user m-r-5 m-l-5"></i> My Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="dropdown-item" href="<?php echo site_url("member/getEditProfile"); ?>"><i class="ti-wallet m-r-5 m-l-5"></i> Edit Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="dropdown-item" href="<?php echo site_url("chathome/index"); ?>"><i class="ti-email m-r-5 m-l-5"></i> Inbox</a>
                        </li>
                        <li></li>
                        <li class="nav-item">
                            <a class="dropdown-item" href="<?php echo site_url('Authentication/logout'); ?>"><i class="fa fa-power-off m-r-5 m-l-5"></i> Logout</a>
                        </li>
                    </ul>
                    <form class="form-inline my-2 my-lg-0">
                        <input class="form-control mr-sm-2" type="search" placeholder="Search">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                    </form>
                </nav>
            </div>
                <div class="clearfix"></div>
                <div class="page-wrapper">
