/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    
    initializeFormValidation();
    initializeFormValidation('.formValidation1');
    alertify.set('notifier', 'position', 'top-right');
    alertify.set('notifier', 'delay', 5);
   
   
   // Replace source
$('img').on("error", function() {
  $(this).attr('src', '/images/missing.png');
});

// Or, hide them
$("img").on("error", function() {
  $(this).hide();
});
   

})



/*
 * To send data to form validation
 */
function submitActionSet(action,e,id) {
	// Prevent form submission
	e.preventDefault();
	$("input#action").val(action);
        
        if($.trim(action)=='save'){
            var selector    =   '.formValidation1';
        }
        
        if (selector === undefined || selector == null || selector.length <= 0) {
            selector = '.formValidation';
        }
        
	$("form"+selector).submit();
}
/*
 * form validation returns controll to here on success
 */
function initiateAjaxCall(){
	 
	var action = $("#action").val();
        if($.trim(action)=='save'){
            saveUser();
        }
        else if($.trim(action)=='login'){
            login();
        }
        
	
	
}

/*
 * function to update Assessment
 */
function saveUser(){
	$(".loading-cover").show();
	
	$("button").attr('disabled', true);
	var params	=	$(".formValidation1").serializeArray();
	
	var formData	=	new FormData();
	$.each(params, function (i, val) {
		formData.append(val.name, val.value);
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
		url: $site_url+"/Registration/"+"saveUser",
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
				location.href=$site_url+"/Authentication/"+"index"
			}
			else if ($.trim(data['status']) == '0') {
				alertify.error(data['message']);
				
			}
			$("button").attr('disabled', false);
			$(".loading-cover").hide();
			
		}
	});
	
}
/*
 * function to update Assessment
 */
function login(){
	$(".loading-cover").show();
	
	$("button").attr('disabled', true);
	var params	=	$(".formValidation").serializeArray();
	
	var formData	=	new FormData();
	$.each(params, function (i, val) {
		formData.append(val.name, val.value);
	});


	$.ajax({
		url: $site_url+"/Authentication/"+"login",
		type: 'POST',
		dataType: 'json',
		async: true,
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		success: function (data)
		{
			location.href=$site_url+'/'+data['url'];
			$("button").attr('disabled', false);
			$(".loading-cover").hide();
			
		}
	});
	
}
/*
 * function to recovry_email
 */
function recoveryMail(){
	$(".loading-cover").show();
	
	
	var recovry_email   =   $("[name='recovry_email']").val();
        if($.trim(recovry_email) == ''){
            alertify.error("Please enter your mail address which you used to create this account");
            return false;
        }
        $("button").attr('disabled', true);
	$.ajax({
		url: $site_url+"/Registration/"+"ResendPassword",
		type: 'POST',
		dataType: 'json',
		async: true,
		data: "email="+recovry_email,
		success: function (data)
		{
                    $("button").attr('disabled', false);
                    if(data['status'] == 1){
			alertify.success(data['msg']);
                        return false;
                    }
                    else{
			alertify.error(data['msg']);
                        return false;
                    }
			
		}
	});
	
}
