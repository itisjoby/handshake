<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url("third_party/assets/images/favicon.png"); ?>">
    <title>Matrix Template - The Ultimate Multipurpose admin template</title>
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="<?= base_url("third_party/assets/libs/jquery/dist/jquery.min.js"); ?>"></script>
    <!-- Custom CSS -->
    <link href="<?= base_url("third_party/dist/css/style.min.css"); ?>" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    
    <!-- Select2 -->
        <link rel="stylesheet" href="<?= base_url("third_party/bower_components/select2/dist/css/select2.min.css"); ?>">
        <!-- alertify style -->
        <link rel="stylesheet" href="<?php echo base_url('third_party/bower_components/alertifyjs/css/alertify.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('third_party/bower_components/alertifyjs/css/themes/bootstrap.min.css'); ?>">
        <script type="text/javaScript">
            var date_format	=	"<?php echo $this->config->item('js_date_format'); ?>";
            $site_url = "<?php echo site_url(); ?>";
        </script>
        <link href="<?php echo base_url('third_party/bower_components/animate/animate.css'); ?>" rel="stylesheet" type="text/css">
        <script src="<?= base_url("third_party/bower_components/animate/animate-plus.js"); ?>"></script> 
    
</head>

<body>
    <div class="main-wrapper">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center bg-dark">
            <div class="auth-box bg-dark border-top border-secondary">
                <div>
                    <div class="text-center p-t-20 p-b-20">
                        <span class="db"><img src="<?= base_url("third_party/"); ?>assets/images/logo.png" alt="logo" /></span>
                    </div>
                    <!-- Form -->
                    
                    <form method="post" id="frm_registration" class="formValidation form-horizontal m-t-20" enctype="multipart/form-data" >
                        <div class="row p-b-30">
                            <div class="col-12">
                                <div class="input-group mb-3 form-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-success text-white" id="basic-addon1"><i class="ti-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control form-control-lg" placeholder="Username" name="uname" aria-label="Username" aria-describedby="basic-addon1" required>
                                </div>
                                <!-- email -->
                                <div class="input-group mb-3 form-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-danger text-white" id="basic-addon1"><i class="ti-email"></i></span>
                                    </div>
                                    <input type="text" class="form-control form-control-lg" placeholder="Email Address" name="email" aria-label="Username" aria-describedby="basic-addon1" required>
                                </div>
                                <div class="input-group mb-3 form-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-warning text-white" id="basic-addon2"><i class="ti-pencil"></i></span>
                                    </div>
                                    <input type="text" class="form-control form-control-lg" placeholder="Password" name="password" aria-label="Password" aria-describedby="basic-addon1" required>
                                </div>
                                <div class="input-group mb-3 form-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-info text-white" id="basic-addon2"><i class="ti-pencil"></i></span>
                                    </div>
                                    <input type="text" class="form-control form-control-lg" placeholder=" Confirm Password" name="confirm_password" aria-label="Password" aria-describedby="basic-addon1" required>
                                </div>
                            </div>
                        </div>
                        <div class="row border-top border-secondary">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="p-t-20">
                                        <button class="btn btn-block btn-lg btn-info form_btn" type="button" onclick="submitActionSet('save',event)">Sign Up</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper scss in scafholding.scss -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper scss in scafholding.scss -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Right Sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Right Sidebar -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- All Required js -->
    
    <!-- Bootstrap tether Core JavaScript -->
    <script src="<?= base_url("third_party/assets/libs/popper.js/dist/umd/popper.min.js"); ?>"></script>
    <script src="<?= base_url("third_party/assets/libs/bootstrap/dist/js/bootstrap.min.js"); ?>"></script>
    <!-- ============================================================== -->
    <!-- This page plugin js -->
    <!-- ============================================================== -->
    <script>
    $('[data-toggle="tooltip"]').tooltip();
    $(".preloader").fadeOut();
    </script>
    <input type="hidden" name="action" id="action" value=""/>
</body>
 <!-- DataTables --> 
<script src="<?= base_url("third_party/bower_components/datatables.net/js/jquery.dataTables.min.js"); ?>"></script> 
<!-- <script src="<?= base_url("third_party/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"); ?>"></script> -->
<!-- Select2 --> 
<script src="<?= base_url("third_party/bower_components/select2/dist/js/select2.full.min.js"); ?>"></script> 
<!-- bootstrap datepicker --> 

<script src="<?= base_url("third_party/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"); ?>"></script> 

<script src="<?= base_url("js/common.js"); ?>"></script> 
<script src="<?= base_url("js/register.js"); ?>"></script> 
<!-- FormValidation.io -->
<script src="<?php echo base_url('third_party/bower_components/formValidation/formValidation.min.js'); ?>"></script>
<script src="<?php echo base_url('third_party/bower_components/formValidation/framework/bootstrap.min.js'); ?>"></script>
<!-- alertify -->
<script src="<?php echo base_url('third_party/bower_components/alertifyjs/alertify.min.js');?>"></script>
<!--Custom JS-->
    <script type="text/javascript">
        var date_format	=	"<?php echo $this->config->item('js_date_format'); ?>";
    </script>
       

</html>