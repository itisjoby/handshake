timer = 10000;
request = false;
lastseen = '';
$(window).on('load', function () {
    getLastSeen();
    ///setTimeout(function(){ seekMessages(); }, timer);
})
$(document).ready(function () {

    findChatDetails();

});


function sendMessage(obj) {
    if (request !== false) {
        request.abort();
    }
    if ($.trim($("[name='message']").val()) == '' && $("[name='file[]']").get(0).files.length == 0) {
        $("[name='message']").focus();
        return false;
    }


    var $form = $(".frmChat");
    $(".loading-cover").show();

    $("button").attr('disabled', true);
    var params = $form.serializeArray();

    var formData = new FormData();
    $.each(params, function (i, val) {
        formData.append(val.name, val.value);
    });


    var length = $form.find('[name="file[]"]').length;
    for (var j = 0; j < length; j++) {
        if ($form.find('[name="file[]"]')[j].files.length)
        {
            files1 = $form.find('[name="file[]"]')[j].files;
            $.each(files1, function (i, file) {
                // Prefix the name of uploaded files with "uploadedFiles-"
                // Of course, you can change it to any string
                formData.append('file[' + j + ']', file);
            });
        }
    }

    $.ajax({
        url: $site_url + "/Chat/" + "saveMessages",
        type: 'POST',
        dataType: 'json',
        async: true,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data)
        {

            if (data['status'] == 1) {
                alertify.success(data['message']);

            } else if ($.trim(data['status']) == '0') {
                alertify.error(data['message']);

            }
            $("button").attr('disabled', false);
            $(".loading-cover").hide();

        }
    }).done(function () {
        setTimeout(seekMessages, timer);
    });
}

function seekMessages() {
    $(".backload-cover").show();
    findChatDetails();

    if (sessionStorage.getItem('personal_recipients') === null) {
        //...
        var personal_recipients = [];
    } else {
        var personal_recipients = JSON.parse(sessionStorage.getItem('personal_recipients'));
    }
    if (sessionStorage.getItem('group_recipients') === null) {
        //...
        var group_recipients = [];
    } else {
        var group_recipients = JSON.parse(sessionStorage.getItem('group_recipients'));
    }





    
    request = $.ajax({
        url: $site_url + "/Chat/" + "checkNewMessages",
        type: 'POST',
        dataType: 'json',
        data: {'personal_recipients': personal_recipients, 'group_recipients': group_recipients, 'lastseen': lastseen},
        async: true,
        success: function (data)
        {
            if (data['status'] == 1) {
                //found something new


                $("#msg_area").load(location.href + " #msg_area>*", "");
                $('textarea').animate({scrollTop: 10000}, 'slow');
                sessionStorage.clear();

            }
        }
    });
    request.done(function () {
        $(".backload-cover").hide();
        getLastSeen();
        setTimeout(seekMessages, timer);
    });
}

function findChatDetails() {
    if ($('input#is_private').val() == 1) {
        //personal
        var value = $('input#reciever_id').val();
        var key_name = 'personal_recipients';
        SaveDataToLocalStorage(value, key_name);
    } else if ($('input#is_private').val() == 0) {
        //groupchat
        var value = $('input#reciever_id').val();
        var key_name = 'group_recipients';
        SaveDataToLocalStorage(value, key_name);
    }
}

function SaveDataToLocalStorage(value, key_name)
{
    var a = [];
    // Parse the serialized data back into an aray of objects
    if (sessionStorage.getItem(key_name) === null) {
        //...
        a = [];
    } else {
        a = JSON.parse(sessionStorage.getItem(key_name));
    }

    // Push the new data (whether it be an object or anything else) onto the array
    a.push(value);
    // Alert the array value

    // Re-serialize the array back into a string and store it in localStorage
    sessionStorage.setItem(key_name, JSON.stringify(a));
}
function getLastSeen() {
    var today = new Date();
    var date = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    lastseen = date + ' ' + time;
    return true;
}


function loadAddForm() {
    $(".loading-cover").show();
    
    $.ajax({
        url: $site_url + "/Chat/" + "loadcreateGroupChat",
        type: 'POST',
        dataType: 'json',

        async: true,
        success: function (data)
        {
            if (data['status'] == 1) {
                //found something new

                
                $("#third_modal_div").html(data['html']).modal("show");
                initializeFormValidation();
                $(".loading-cover").hide();

            }
        }
    });
}
/*
 * To send data to form validation
 */
function submitActionSet(action, e, id,selector) {
    if(selector === undefined || selector == null || selector.length <= 0){
        selector = '.formValidation';
    }
    
    // Prevent form submission
    e.preventDefault();
    $("input#action").val(action);
    $("form"+selector).submit();
}
/*
 * form validation returns controll to here on success
 */
function initiateAjaxCall() {

    var action = $("#action").val();
    if(action=='change_group_picture'){
        changeGroupPic();
    }
    else{
        createGroupChat();
    }
    

}
function createGroupChat() {

    $(".loading-cover").show();

    $("button").attr('disabled', true);
    var params = $(".formValidation").serializeArray();

    var formData = new FormData();
    $.each(params, function (i, val) {
        formData.append(val.name, val.value);
    });
    $.ajax({
        url: $site_url + "/Chat/" + "createGroupChat",
        type: 'POST',
        dataType: 'json',
        async: true,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data)
        {
            if (data['status'] == 1) {
                //found something new


                location.href = $site_url + "/Chat/" + "index/" + data['groupid'] + "/group";
                //$("button").attr('disabled', false);
                //$(".loading-cover").hide();
            }
        }
    });
}
function loadAddGroupMember(group_id){
    
   if(group_id==''){
       return true;
   }
    
    $(".loading-cover").show();
    request = $.ajax({
        url: $site_url + "/Chat/" + "loadAddGroupMember",
        type: 'POST',
        dataType: 'json',
        data: {'group_id':group_id},
        async: true,
        success: function (data)
        {
            if (data['status'] == 1) {
                //found something new
                $("div#first_modal_div").html(data['html']).modal("show");
                $('.datatable').DataTable();
                $(".loading-cover").hide();
            }
        }
    });
}

function addGroupmembers(){
    $(".loading-cover").show();
    var $form       =   $("form#addmembers");
    $("button").attr('disabled', true);
    var params = $form.serializeArray();

    var formData = new FormData();
    $.each(params, function (i, val) {
        formData.append(val.name, val.value);
    });

    $.ajax({
        url: $site_url + "/Chat/" + "addGroupMember",
        type: 'POST',
        dataType: 'json',
        async: true,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data)
        {

            if (data['status'] == 1) {
                alertify.success(data['message']);
                $("div#first_modal_div").modal("hide");

            } else if ($.trim(data['status']) == '0') {
                alertify.error(data['message']);

            }
            $("button").attr('disabled', false);
            $(".loading-cover").hide();

        }
    })
}

function leave_group(id) {
    $.ajax({
        type: 'POST',
        url: $site_url + "/chat/" + "leaveMsgGroup",
        dataType:"json",
        data: {'id': id},
        success: function (data) {
           
             if (data['status'] == 1) {
                alertify.success(data['message']);
                $("#modal-danger").modal('hide');
                location.reload();

            } else if ($.trim(data['status']) == '0') {
                alertify.error(data['message']);

            }
        },
        error: function (data) {
            alertify.set('notifier', 'position', 'top-right');
            alertify.set('notifier', 'delay', 5);
            alertify.error('An error occurred.');
            console.log(data);
        },
    });
}

function leave_group_warning(id) {
    $("#modal-danger").remove();
    modal = '<div class="modal modal-danger fade" id="modal-danger">\
                        <div class="modal-dialog modal-lg">\
                          <div class="modal-content">\
                            <div class="modal-header">\
                              \
                              <h4 class="modal-title">Leave Confirmation</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>\
                            </div>\
                            <div class="modal-body">\
                              <p>Are you sure want to leave this group ? </p>\
                            </div>\
                            <div class="modal-footer">\
                              <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Close</button>\
                              <button type="button" class="btn btn-danger" onclick="return leave_group(' + id + ');">Yes Proceed</button>\
                            </div>\
                          </div>\
                        </div>\
                      </div>';
    $('body').append(modal);
    $("#modal-danger").modal('show');
}
function changePicture() {
    $(".loading-cover").show();
    var group_id=$("input#reciever_id").val();
    $.ajax({
        url: $site_url + "/Chat/" + "changePicture",
        type: 'POST',
        dataType: 'json',
        data:{'group_id':group_id},
        async: true,
        success: function (data)
        {
            if (data['status'] == 1) {
                //found something new

                
                $("#third_modal_div").html(data['html']).modal("show");
                initializeFormValidation();
                $(".loading-cover").hide();

            }
        }
    });
}
function changeGroupPic() {

    $(".loading-cover").show();

    $("button").attr('disabled', true);
    var $form   =   $("#changeGrouppic");
    var params = $("#changeGrouppic").serializeArray();

    var formData = new FormData();
    $.each(params, function (i, val) {
        formData.append(val.name, val.value);
    });
     var length = $form.find('[name="group_pic[]"]').length;
    for (var j = 0; j < length; j++) {
        if ($form.find('[name="group_pic[]"]')[j].files.length)
        {
            files1 = $form.find('[name="group_pic[]"]')[j].files;
            $.each(files1, function (i, file) {
                // Prefix the name of uploaded files with "uploadedFiles-"
                // Of course, you can change it to any string
                formData.append('file', file);
            });
        }
    }
    $.ajax({
        url: $site_url + "/Chat/" + "changeGroupPic",
        type: 'POST',
        dataType: 'json',
        async: true,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data)
        {
            if (data['status'] == 1) {
                //found something new

                alertify.success(data['message']);
                $("#msg_area").load(location.href + " #msg_area>*", "");
                $("#third_modal_div").modal("hide");
                
            }
            else{
                alertify.error(data['message']);
            }
            $("button").attr('disabled', false);
            $(".loading-cover").hide();
        }
    });
}
function delete_group() {
    $(".loading-cover").show();
    var group_id=$("input#reciever_id").val();
    $.ajax({
        url: $site_url + "/Chat/" + "deleteGroup",
        type: 'POST',
        dataType: 'json',
        data:{'group_id':group_id},
        async: true,
        success: function (data)
        {
            if (data['status'] == 1) {
                //found something new

                alertify.success("messages cleared successfully")
                location.reload();
                $(".loading-cover").hide();

            }
        }
    });
}
