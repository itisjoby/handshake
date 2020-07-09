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
        <title><?php echo $this->config->item('project_name'); ?></title>


        <!-- All Jquery -->
        <!-- ============================================================== -->
        <script src="<?= base_url("third_party/files/jquery-3.4.1.js"); ?>"></script>
        <script src="<?= base_url("third_party/files/jquery-ui-1.12.1/jquery-ui.min.js"); ?>"></script>
        <script src="<?= base_url("third_party/files/popper.min.js"); ?>"></script>

        <!--    bootstrap-->
        <link href="<?= base_url("third_party/files/bootstrap-4.3.1/css/bootstrap.min.css"); ?>" rel="stylesheet">
        <script src="<?= base_url("third_party/files/bootstrap-4.3.1/js/bootstrap.min.js"); ?>"></script>


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

        <!-- alertify style -->
        <link rel="stylesheet" href="<?php echo base_url('third_party/files/alertifyjs/css/alertify.min.css'); ?>">



        <link href="<?php echo base_url('third_party/files/animate/animate.css'); ?>" rel="stylesheet" type="text/css">
        <script src="<?= base_url("third_party/files/animate/animate-plus.js"); ?>"></script> 

        <script type="text/javaScript">
            var date_format	=	"<?php echo $this->config->item('js_date_format'); ?>";
            $site_url = "<?php echo site_url(); ?>";
        </script>

        <!-- Custom CSS -->
        <link href="<?= base_url("third_party/css/style.css"); ?>" rel="stylesheet">
        <link href="<?= base_url("third_party/css/slidetext.css"); ?>" rel="stylesheet">
        
        <script src="<?= base_url("third_party/files/html2canvas.min.js"); ?>"></script>
        <script src="<?= base_url("third_party/files/chance.js"); ?>"></script>
        

    </head>

    <body>
        <div class="container">
            <div class="row text-center content">
                <img src="<?= base_url("third_party/images/handshake-logo-orginal.png"); ?>" alt="logo" class="img-fluid mx-auto d-block main-logo-orginal"/>
            </div>
            <!--            loginform-->
            <div class="row" id="loginform">
                <div class="col-md-4 col-12">
                    <button id="start-btn">Try snap</button>
                </div>
                <div class="col-md-4 col-12">
                    <form method="post" id="frm_login" class="formValidation form-horizontal">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-lg" name="Username" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" required="">
                        </div>
                        <div class="form-group">  
                            <input type="password" autocomplete="off" class="form-control form-control-lg" name="Password" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1" required="">
                        </div>
                        <div class="form-group">
                            <button class="btn btn-info" id="to-recover" type="button"><i class="fa fa-lock m-r-5"></i> Lost password?</button>
                            <button class="btn btn-success float-right" type="button" onclick="submitActionSet('login', event)">Login</button>
                        </div>
                    </form>
                </div>


                <div class="col-md-4 col-12">
                    new to our page?
                    <button id="registerform" class="btn-info">Register</button>
                    <div class="tech-slideshow">
                        <div class="mover-1"></div>
                        <div class="mover-2"></div>
                      </div>
                    
                </div>
            </div>
            <!--            registartion-->
            <div class="row" id="registerform" style="display:none">
                <div class="col-md-4 col-12">
                </div>
                <div class="col-md-4 col-12" >
                    <form method="post" id="frm_registration" class="formValidation1 form-horizontal">
                        <div class="row p-b-30">
                            <div class="col-12">
                                <div class="form-group">

                                    <input type="text" class="form-control form-control-lg" placeholder="Username" name="uname" aria-label="Username" aria-describedby="basic-addon1" required>
                                </div>
                                <!-- email -->
                                <div class="form-group ">

                                    <input type="text" class="form-control form-control-lg" placeholder="Email Address" name="email" aria-label="Username" aria-describedby="basic-addon1" required>
                                </div>
                                <div class="form-group">

                                    <input type="text" class="form-control form-control-lg" placeholder="Password" name="password" aria-label="Password" aria-describedby="basic-addon1" required>
                                </div>
                                <div class="form-group">

                                    <input type="text" class="form-control form-control-lg" placeholder=" Confirm Password" name="confirm_password" aria-label="Password" aria-describedby="basic-addon1" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <a class="btn btn-success" href="#" id="to-login" name="action">Sign in</a>
                            <button class="btn btn-info float-right form_btn" type="button" onclick="submitActionSet('save', event)">Sign Up</button>
                        </div>
                    </form>
                </div>


                <div class="col-md-4 col-12">
                </div>
            </div>
            <!--password forgot-->
            <div class="row" id="recoverform" style="display:none">
                <div class="col-md-4 col-12">
                </div>
                <div class="col-md-4 col-12">
                    <!-- Form -->
                    <form method="post" id="frm_recover" class="form-horizontal">
                        <!-- email -->
                        <div class="form-group">
                            <label>Enter your e-mail address below and we will send you instructions how to recover your account.</label>
                            <input type="email" class="form-control form-control-lg" maxlength="100" name="recovry_email" placeholder="Email Address" aria-label="Email Address" aria-describedby="basic-addon1">
                        </div>
                        <!-- pwd -->
                        <div class="form-group">

                            <a class="btn btn-success" href="#" id="to-login" name="action">Back To Login</a>
                            <button class="btn btn-info float-right" type="button" name="action" onclick="return recoveryMail();">Recover</button>

                        </div>
                    </form>
                </div>
                <div class="col-md-4 col-12">
                </div>
            </div>

        </div>




        <!-- ============================================================== -->
        <!-- All Required js -->



        <script>

            $('[data-toggle="tooltip"]').tooltip();
            $(".preloader").fadeOut();
            // ============================================================== 
            // Login and Recover Password 
            // ============================================================== 
            $('#to-recover').on("click", function () {
                $("#loginform").slideUp();
                $("div#registerform").hide();
                $("#recoverform").fadeIn();
            });
            $('a#to-login').click(function () {

                $("#recoverform").hide();
                $("div#registerform").hide();
                $("#loginform").fadeIn();
            });
            $('button#registerform').click(function () {

                $("#recoverform").hide();
                $("#loginform").hide();
                $("div#registerform").fadeIn();
            });
        </script>
        <input type="hidden" name="action" id="action" value=""/>
    </body>
    <footer>

        <script src="<?= base_url("js/common.js"); ?>"></script> 
        <script src="<?= base_url("js/register.js"); ?>"></script> 
        <!-- FormValidation.io -->
        <script src="<?php echo base_url('third_party/files/formValidation/formValidation.min.js'); ?>"></script>
        <script src="<?php echo base_url('third_party/files/formValidation/framework/bootstrap.min.js'); ?>"></script>
        <!-- alertify -->
        <script src="<?php echo base_url('third_party/files/alertifyjs/alertify.min.js'); ?>"></script>
        <!--Custom JS-->
        <script type="text/javascript">
            var date_format = "<?php echo $this->config->item('js_date_format'); ?>";
        </script>
        <script> 
    
    var imageDataArray = [];
    var canvasCount = 35;
    $("#start-btn").click(function(){
      //https://redstapler.co/thanos-snap-effect-javascript-tutorial/
      html2canvas($(".content")[0]).then(canvas => {
        //capture all div data as image
        ctx = canvas.getContext("2d");
        var imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        var pixelArr = imageData.data;
        createBlankImageData(imageData);
        //put pixel info to imageDataArray (Weighted Distributed)
        for (let i = 0; i < pixelArr.length; i+=4) {
          //find the highest probability canvas the pixel should be in
          let p = Math.floor((i/pixelArr.length) *canvasCount);
          let a = imageDataArray[weightedRandomDistrib(p)];
          a[i] = pixelArr[i];
          a[i+1] = pixelArr[i+1];
          a[i+2] = pixelArr[i+2];
          a[i+3] = pixelArr[i+3]; 
        }
        //create canvas for each imageData and append to target element
        for (let i = 0; i < canvasCount; i++) {
          let c = newCanvasFromImageData(imageDataArray[i], canvas.width, canvas.height);
          c.classList.add("dust");
          $("body").append(c);
        }
        //clear all children except the canvas
        $(".content").children().not(".dust").fadeOut(3500);
        //apply animation
        $(".dust").each( function(index){
          animateBlur($(this),0.8,800);
          setTimeout(() => {
            animateTransform($(this),100,-100,chance.integer({ min: -15, max: 15 }),800+(110*index));
          }, 70*index); 
          //remove the canvas from DOM tree when faded
          $(this).delay(70*index).fadeOut((110*index)+800,"easeInQuint",()=> {$( this ).remove();});
        });
      });
    });
    function weightedRandomDistrib(peak) {
      var prob = [], seq = [];
      for(let i=0;i<canvasCount;i++) {
        prob.push(Math.pow(canvasCount-Math.abs(peak-i),3));
        seq.push(i);
      }
      return chance.weighted(seq, prob);
    }
    function animateBlur(elem,radius,duration) {
      var r =0;
      $({rad:0}).animate({rad:radius}, {
          duration: duration,
          easing: "easeOutQuad",
          step: function(now) {
            elem.css({
                  filter: 'blur(' + now + 'px)'
              });
          }
      });
    }
    function animateTransform(elem,sx,sy,angle,duration) {
      var td = tx = ty =0;
      $({x: 0, y:0, deg:0}).animate({x: sx, y:sy, deg:angle}, {
          duration: duration,
          easing: "easeInQuad",
          step: function(now, fx) {
            if (fx.prop == "x") 
              tx = now;
            else if (fx.prop == "y") 
              ty = now;
            else if (fx.prop == "deg") 
              td = now;
            elem.css({
                  transform: 'rotate(' + td + 'deg)' + 'translate(' + tx + 'px,'+ ty +'px)'
              });
          }
      });
    }
    function createBlankImageData(imageData) {
      for(let i=0;i<canvasCount;i++)
      {
        let arr = new Uint8ClampedArray(imageData.data);
        for (let j = 0; j < arr.length; j++) {
            arr[j] = 0;
        }
        imageDataArray.push(arr);
      }
    }
    function newCanvasFromImageData(imageDataArray ,w , h) {
      var canvas = document.createElement('canvas');
          canvas.width = w;
          canvas.height = h;
          tempCtx = canvas.getContext("2d");
          tempCtx.putImageData(new ImageData(imageDataArray, w , h), 0, 0);
          
      return canvas;
    }
    </script>
    </footer>
</html>