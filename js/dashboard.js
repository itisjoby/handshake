/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    initializeFormValidation();
    alertify.set('notifier', 'position', 'top-right');
    alertify.set('notifier', 'delay', 5);


})

function shareThoughts(obj) {
    if ($.trim($("[name='caption']").val()) == '' && $("[name='file[]']").get(0).files.length == 0) {
        $("[name='caption']").focus();
        return false;
    }



    $(".loading-cover").show();

    $("button").attr('disabled', true);
    var params = $(".share-thoughts").serializeArray();

    var formData = new FormData();
    $.each(params, function (i, val) {
        formData.append(val.name, val.value);
    });


    var length = $(".share-thoughts").find('[name="file[]"]').length;
    for (var j = 0; j < length; j++) {
        if ($(".share-thoughts").find('[name="file[]"]')[j].files.length)
        {
            files1 = $(".share-thoughts").find('[name="file[]"]')[j].files;
            $.each(files1, function (i, file) {
                // Prefix the name of uploaded files with "uploadedFiles-"
                // Of course, you can change it to any string
                formData.append('file[' + j + ']', file);
            });
        }
    }

    $.ajax({
        url: $site_url + "/Member/" + "addPost",
        type: 'POST',
        dataType: 'json',
        async: true,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
//                xhr: function () {
//        var xhr = $.ajaxSettings.xhr();
//        xhr.onprogress = function e() {
//            // For downloads
//             console.log(e)
//            if (e.lengthComputable) {
//                console.log(e.loaded / e.total);
//            }
//        };
//        xhr.upload.onprogress = function (e) {
//            // For uploads
//            console.log(e)
//            if (e.lengthComputable) {
//                console.log(e.loaded / e.total);
//            }
//        };
//        return xhr;
//    },
        xhr: function () {
            var xhr = new window.XMLHttpRequest();

            xhr.upload.addEventListener("progress", function (evt) {
                if (evt.lengthComputable) {
                    var percentComplete = evt.loaded / evt.total;
                    percentComplete = parseInt(percentComplete * 100);
                    console.log(percentComplete);
                    str = percentComplete + "%";
                    $("div.progress-bar").css("width", str)
                    $("div.progress-bar").attr("aria-valuenow", str)
                    if (percentComplete === 100) {

                    }

                }
            }, false);

            return xhr;
        },
        success: function (data)
        {
            $("div.progress-bar").css("width", 0)
            $("div.progress-bar").attr("aria-valuenow", 0)
            if (data['status'] == 1) {
                alertify.success(data['message']);

            } else if ($.trim(data['status']) == '0') {
                alertify.error(data['message']);

            }
            $("button").attr('disabled', false);
            $(".loading-cover").hide();

        }
    });
}
