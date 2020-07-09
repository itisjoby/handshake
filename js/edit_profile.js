/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    
    initializeFormValidation();
    alertify.set('notifier', 'position', 'top-right');
    alertify.set('notifier', 'delay', 5);
   $('.datepicker').datepicker({
            autoclose: true,
            format: date_format
    });

})



/*
 * To send data to form validation
 */
function submitActionSet(action,e,id) {
    
	// Prevent form submission
	e.preventDefault();
	$("input#action").val(action);
	$("form.formValidation").submit();
}
/*
 * form validation returns controll to here on success
 */
function initiateAjaxCall(){
	  
	var action = $("#action").val();
        if($.trim(action)=='update'){
            updateUser();
        }
        
	
	
}

/*
 * function to update Assessment
 */
function updateUser(){
   
	$(".loading-cover").show();
	
	$("button").attr('disabled', true);
	var params	=	$(".formValidation").serializeArray();
	
	var formData	=	new FormData();
	$.each(params, function (i, val) {
		formData.append(val.name, val.value);
	});
        files1 = $(".formValidation").find('[name="profile_pic"]')[0].files;
        $.each(files1, function (i, file) {
            formData.append('file', file);
        });

//	var length=$(".formValidation").find('[name="documentfile[]"]').length;
//	for(var j=0;j<length;j++){
//		if ($(".formValidation").find('[name="documentfile[]"]')[j].files.length)
//		{
//			files1 = $(".formValidation").find('[name="documentfile[]"]')[j].files;
//			$.each(files1, function (i, file) {
//				// Prefix the name of uploaded files with "uploadedFiles-"
//				// Of course, you can change it to any string
//				formData.append('documentfile['+j+']', file);
//			});
//		}
//	}

	$.ajax({
		url: $site_url+"/Member/"+"updateUser",
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
				
			}
			else if ($.trim(data['status']) == '0') {
				alertify.error(data['message']);
				
			}
			$("button").attr('disabled', false);
			$(".loading-cover").hide();
			
		}
	});
	
}

