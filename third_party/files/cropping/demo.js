var Demo = (function () {
    function demoUpload() {
        var $uploadCrop;

        function readFile(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('.upload-demo').addClass('ready');
                    $uploadCrop.croppie('bind', {
                        url: e.target.result
                    }).then(function () {
                        console.log('jQuery bind complete');
                    });

                }

                reader.readAsDataURL(input.files[0]);
            } else {
                swal("Sorry - you're browser doesn't support the FileReader API");
            }
        }

        $uploadCrop = $('#upload-demo').croppie({
            viewport: {
                width: 450,
                height: 300,
                type: 'square'
            },
            enableExif: true
        });

        $('#upload').on('change', function () {
            readFile(this);
            load_civil_id = 0;
        });
        /*$('.basic-result').on('click', function (ev) {
            $uploadCrop.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function (resp) {
                popupResult({
                    src: resp
                });
            });
        });*/
        
    }
    function popupResult(result) {
		var html;
		if (result.html) {
			html = result.html;
		}
		if (result.src) {
                    //console.log(result.src);
			html = '<img src="' + result.src + '" />';
                        $(".result_img").append(html);
		}
		
	}
    function demoUpload1() {
        var $uploadCrop1;

        function readFile(input1) {
            if (input1.files && input1.files[0]) {
                var reader1 = new FileReader();

                reader1.onload = function (e) {
                    $('.upload-demo1').addClass('ready');
                    $uploadCrop1.croppie('bind', {
                        url: e.target.result
                    }).then(function () {
                        console.log('jQuery bind complete');
                    });

                }

                reader1.readAsDataURL(input1.files[0]);
            } else {
                swal("Sorry - you're browser doesn't support the FileReader API");
            }
        }

        $uploadCrop1 = $('#upload-demo1').croppie({
            viewport: {
                width: 450,
                height: 300,
                type: 'square'
            },
            enableExif: true
        });

        $('#upload1').on('change', function () {
            readFile(this);
            load_civil_id = 0;
        });
        /*$('.basic-result').on('click', function (ev) {
            $uploadCrop1.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function (resp) {
                popupResult({
                    src: resp
                });
            });
        });*/
    }


    function init() {
        demoUpload();
        demoUpload1();
    }

    return {
        init: init
    };
})();
