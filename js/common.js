/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    //initializeFormValidation(controller);
    $("#second_model_div").on("hidden.bs.modal", function () {
        if ($('#model_div').hasClass('in')) {
            onClosingMultiModel('model_div');
        }
    });

    $("#third_model_div").on("hidden.bs.modal", function () {
        if ($('#second_model_div').hasClass('in')) {
            onClosingMultiModel('second_model_div');
        } else {
            onClosingMultiModel('model_div');
        }
    });

    $('a').tooltip();
    alertify.set('notifier', 'position', 'top-right');
    alertify.set('notifier', 'delay', 5);

    $(document).on('click','a.react',function (e) {
        //Some code
        var post_id = $(e.target).closest(".more_wrapper").find("input[name='postid']").val();
        like(post_id, e.target);
    });
    
    
    
       // Replace source
        $('img').on("error", function() {
          $(this).attr('src', '/images/missing.png');
        });

        // Or, hide them
//        $("img").on("error", function() {
//          $(this).hide();
//        });
    

})

function initializeFormValidation(selector) {
    if (selector === undefined || selector == null || selector.length <= 0) {
        selector = '.formValidation';
    }

    return $(selector).formValidation({
        autofocus: true,
        framework: 'bootstrap'
    })
            .on('success.form.fv', function (e) {

                // Prevent form submission
                e.preventDefault();

                // Use Ajax to submit form data
                //saveData(controller);
                initiateAjaxCall();
            }).
            on('change', '.datepicker', function (evt) {
                // Revalidate the from date after on changing
                $('.formValidation').formValidation('revalidateField', $(this));
            });
}



function intializeDatatable(controller, alt_order, order) {
    if ($.fn.dataTable.isDataTable('.dataTable')) {
        table = $('.dataTable').DataTable();
        table.destroy();
    }

    if (order === undefined)
        order = 0;
    if (alt_order === undefined)
        alt_order = 0;
    $('.dataTable').DataTable({
        // Load data for the table's content from an Ajax source
        "ajax":
                {
                    "url": $site_url + "/" + controller + "/" + "getData",
                    "type": "POST"
                },
        //Set column definition initialisation properties.
        'aoColumnDefs':
                [
                    {//	To remove sorting for columns with class 'nosort'
                        'bSortable': false,
                        'aTargets': ['text-center nosort']
                    },
                    {//	To remove sorting for columns with class 'nosort'
                        'bSortable': false,
                        'aTargets': ['nosort']
                    },
                    {//	To remove 30px right-padding on non-sortable datatable fields
                        'sClass': 'text-center nosort',
                        'aTargets': ['text-center nosort']
                    },
                    {//	To remove 30px right-padding on non-sortable datatable fields
                        'sClass': 'dt_no_pad',
                        'aTargets': ['nosort']
                    },
                    {//	To right align all column cell with column class set to 'text-right'
                        'sClass': 'text-right',
                        'aTargets': ['text-right']
                    },
                    {//	To center align all column cell with column class set to 'text-center'
                        'sClass': 'text-center',
                        'aTargets': ['text-center']
                    },
                    {//	To center align all column cell with column class set to 'text-center'
                        'sClass': 'text-left',
                        'aTargets': ['text-left']
                    },
                    {type: 'alt-string', targets: alt_order}
                ],
        "order": [[order, "asc"]],
    });


    // Creating search text fields for every colunm, For avoiding search text field add class='nosearch'
    n = 0;
    ////$('.dataTable tfoot th').each( function () {
    $('.dataTable').find(' tfoot th').each(function () {
        var title = $(this).text();
        if (!$(this).hasClass("nosearch")) {
            var input_val = $('.dataTable').DataTable().columns(n).search()[0]; //updated by shine 24-01-2018 to retain search text
            if ($(this).hasClass("datepicker")) {
                $(this).removeClass("datepicker")
                $(this).html('<input type="text" data-column="' + n + '"  class="search-input-text datepicker"  value="' + input_val + '"   placeholder="' + title + '" />');
                initializePlugin('datepicker', '[data-column="' + n + '"]');   //updated by shine 16-02-2018 to avoid multiple datepicker on one input if call - initializePlugin('datepicker')
            } else {
                $(this).html('<input type="text" data-column="' + n + '"  class="search-input-text"  value="' + input_val + '"  placeholder="' + title + '" />');
            }
            n++;
        }
    });

    //columnswise Search functionality
    $('.dataTable').find('.search-input-text').on('keyup change', function () {   // for text boxes
        var i = $(this).attr('data-column');  // getting column index
        var v = $(this).val();  // getting search input value
        if (v != "")
            $(this).css({'background-color': '#F2FF68'});
        else
            $(this).css('background-color', '#FFF');
        //table.columns(i).search(v).draw();
        $('.dataTable').DataTable().columns(i).search(v).draw();
    });
}



function delete_model(controller, id) {
    $.ajax({
        type: 'POST',
        url: $site_url + "/" + controller + "/" + "deleteData",
        data: {'id': id},
        success: function (data) {
            var rtn = JSON.parse(data);
            if (rtn.status) {
                alertify.set('notifier', 'position', 'top-right');
                alertify.set('notifier', 'delay', 5);
                if (rtn.message == "")
                    alertify.success('Deleted successfully');
                else
                    alertify.success(rtn.message);
                $('.dataTable').DataTable().ajax.reload(null, false);
                $(".modal").modal("hide");
            } else {
                alertify.set('notifier', 'position', 'top-right');
                alertify.set('notifier', 'delay', 5);
                if (rtn.message == "")
                    alertify.success('Failed');
                else
                    alertify.error(rtn.message);
                initializeFormValidation();
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

function delete_record(controller, id) {
    $("#modal-danger").remove();
    modal = '<div class="modal modal-danger fade" id="modal-danger">\
                        <div class="modal-dialog modal-lg">\
                          <div class="modal-content">\
                            <div class="modal-header">\
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>\
                              <h4 class="modal-title">Delete Confirmation</h4>\
                            </div>\
                            <div class="modal-body">\
                              <p>Are you sure want to delete this record ? </p>\
                            </div>\
                            <div class="modal-footer">\
                              <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>\
                              <button type="button" class="btn btn-outline" onclick="return delete_model(\'' + controller + '\',' + id + ');">Delete</button>\
                            </div>\
                          </div>\
                        </div>\
                      </div>';
    $('body').append(modal);
    $("#modal-danger").modal('show');
}

function change_status(obj, id, controller) {
    $.ajax({
        type: 'POST',
        url: $site_url + "/" + controller + "/" + "statusChange",
        data: {'id': id},
        success: function (data) {
            var rtn = JSON.parse(data);
            if (rtn.status) {
                if (rtn.class != "") {
                    $(obj).attr('class', rtn.class);
                    $(obj).attr('data-original-title', rtn.class);
                    alertify.set('notifier', 'position', 'top-right');
                    alertify.set('notifier', 'delay', 5);
                    alertify.success(rtn.message);
                }
            } else {
                alertify.set('notifier', 'position', 'top-right');
                alertify.set('notifier', 'delay', 5);
                alertify.error(rtn.message);
                initializeFormValidation();
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
function change_notificationStatus(id) {
    $.ajax({
        url: $site_url + "/Notifications/" + "changeStat",
        method: "POST",
        dataType: 'json',
        data: {'id': id},
        success: function (data)
        {//alert(data['status'])
            if (data['status'] == "1") {
//          $(".alert-danger").hide();
//          alertify.set('notifier', 'position', 'top-right');
//            alertify.set('notifier', 'delay', 5);
//            alertify.success(data['message']);
//          $(".alert-success ").show();
            } else {
//          $(".alert-success").hide();
//          $(".alert-danger").show();
            }
        }
    });
}
function isNumberKey(evt, object)
{
    var charCode = (evt.which) ? evt.which : event.keyCode;

    if ((String.fromCharCode(charCode) == "." && object.value.indexOf(".") == -1) || (/\d/g).test(String.fromCharCode(charCode)) || charCode == 8)
    {
        return true;
    } else
    {
        return false;
    }
}

function sentFriendRequest(id) {
    $.ajax({
        type: 'POST',
        url: $site_url + "/member/" + "sendFriendRequest",
        data: {'target_user': id},
        dataType: "json",
        success: function (data) {

            if (data['status'] == 1) {
                $(".friend-suggetions-div").load(location.href + " .friend-suggetions-div>*", "");
                alertify.success(data['message']);
            } else {
                alertify.error(data['message']);
            }
        },
        error: function (data) {

            alertify.error('An error occurred.');

        },
    });
}
function respondFriendRequest(request_id, response) {
    $.ajax({
        type: 'POST',
        url: $site_url + "/member/respondFriendRequest",
        data: {'request_id': request_id, 'response': response},
        dataType: "json",
        success: function (data) {

            if (data['status'] == 1) {
                alertify.success(data['message']);
                $(".friend-requests-div").load(location.href + " .friend-requests-div>*", "");
            } else {
                alertify.error(data['message']);
            }
        },
        error: function (data) {

            alertify.error('An error occurred.');

        },
    });
}
function unFriend(user_id) {
    $.ajax({
        type: 'POST',
        url: $site_url + "/member/unFriend",
        data: {'request_id': user_id},
        dataType: "json",
        success: function (data) {

            if (data['status'] == 1) {
                alertify.success(data['message']);
                $(".friend-suggetions-div").load(location.href + " .friend-suggetions-div>*", "");
            } else {
                alertify.error(data['message']);
            }
        },
        error: function (data) {

            alertify.error('An error occurred.');

        },
    });
}
function removeSentFriendRequest(user_id) {
    $.ajax({
        type: 'POST',
        url: $site_url + "/member/removeSentFriendRequest",
        data: {'request_id': user_id},
        dataType: "json",
        success: function (data) {

            if (data['status'] == 1) {
                alertify.success(data['message']);
                $(".friend-suggetions-div").load(location.href + " .friend-suggetions-div>*", "");
            } else {
                alertify.error(data['message']);
            }
        },
        error: function (data) {

            alertify.error('An error occurred.');

        },
    });
}
function like(post_id, obj) {
    if (post_id === undefined || post_id == null || post_id.length <= 0) {
        console.log("error when tried to like this post");
        return false;
    }
    $.ajax({
        type: 'POST',
        url: $site_url + "/member/like",
        data: {'post_id': post_id},
        dataType: "json",
        success: function (data) {

            if (data['status'] == 1) {
                alertify.success(data['message']);
                var total_likes = $(obj).closest("span.like_counter").find("span.likecount").html();
               
                if (data['action'] == 'like') {
                    total_likes = parseInt($.trim(total_likes)) + 1;
                    var liked = '<a href="javascript:void(0);" class="react"><span class="glyphicon glyphicon-thumbs-down"></span>unlike</a><span class="likecount">' + total_likes + '</span> likes';
                   $(obj).closest("span.like_counter").html(liked);
                } else {
                    total_likes = parseInt($.trim(total_likes)) - 1;
                    var unliked = '<a href="javascript:void(0);" class="react"><span class="glyphicon glyphicon-thumbs-up"></span>like</a><span class="likecount">' + total_likes + '</span> likes';
                     $(obj).closest("span.like_counter").html(unliked);
                }
                
                

            } else {
                alertify.error(data['message']);
            }
        },
        error: function (data) {

            alertify.error('An error occurred.');

        },
    });
}