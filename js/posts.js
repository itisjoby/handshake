/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    initializeFormValidation();
    alertify.set('notifier', 'position', 'top-right');
    alertify.set('notifier', 'delay', 5);
$(document).on('click','button.post_comment_btn',function(e){
    e.preventDefault();
    commentPost(e.target);
});

})

function commentPost(obj) {
    
    var comment =   $.trim($(obj).closest("div.comment_box_wrapper").find("textarea[name='comment']").val());
    
    var post_id =   $("div.more_wrapper").find("input[name='postid']").val();
    if (comment == '') {
        $(obj).closest("div.comment_box_wrapper").find("textarea[name='comment']").focus();
        return false;
    }

    
    $(".loading-cover").show();

    $("button").attr('disabled', true);
   

    $.ajax({
        url: $site_url + "/posts/" + "addPostComment",
        type: 'POST',
        dataType: 'json',
        async: true,
        data: {comment:comment,post_id:post_id},
       

//        xhr: function () {
//            var xhr = new window.XMLHttpRequest();
//
//            xhr.upload.addEventListener("progress", function (evt) {
//                if (evt.lengthComputable) {
//                    var percentComplete = evt.loaded / evt.total;
//                    percentComplete = parseInt(percentComplete * 100);
//                    console.log(percentComplete);
//                    str = percentComplete + "%";
//                    $("div.progress-bar").css("width", str)
//                    $("div.progress-bar").attr("aria-valuenow", str)
//                    if (percentComplete === 100) {
//
//                    }
//
//                }
//            }, false);
//
//            return xhr;
//        },
        success: function (data)
        {
//            $("div.progress-bar").css("width", 0)
//            $("div.progress-bar").attr("aria-valuenow", 0)
            if (data['status'] == 1) {
                alertify.success(data['message']);
                $(obj).closest("div.comment_box_wrapper").find("textarea[name='comment']").val("");
                $("div.comment_section").load(location.href + " div.comment_section>*", "");

            } else if ($.trim(data['status']) == '0') {
                alertify.error(data['message']);

            }
            $("button").attr('disabled', false);
            $(".loading-cover").hide();

        }
    });
}
