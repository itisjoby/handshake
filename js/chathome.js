
$(window).on('load', function () {
   
})
$(document).ready(function () {

    
});

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