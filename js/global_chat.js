var conn = new WebSocket('ws://localhost:8080');

$(document).ready(function () {

    conn.onopen = function (e) {
        let msg = "Connection established!";
        showMessage(msg, 2)
    };

    conn.onmessage = function (e) {

        showMessage(e.data, 0);
    };
//    conn.onerror = function (e) {
//
//        showMessage(e.data);
//    };

    $(document).on('keyup', 'textarea.message_poster', function (e) {
        if (e.keyCode === 13) {
            // Cancel the default action, if needed
            e.preventDefault();
            let msg = $(this).val();
            sendMessage(msg, 1);
            $(this).val('');
        }
    });
});
/**
 * 
 * @param {type} msg
 * @returns {undefined}
 */
function sendMessage(msg) {
    if (msg.length < 1000) {
        if (conn.readyState == 1) {
            conn.send(msg);
            showMessage(msg, true);
        } else {
            showMessage('network error', 2);
        }
    }
}
/**
 * 
 * @param {type} msg
 * @param {type} my_msg
 * @returns {undefined}
 */
function showMessage(msg, msg_type) {
    if ($(".shout_box_ul").find("li").length > 10) {
        /*REMOVE PREVIOUS MESSAGES*/
        $(".shout_box_ul").find("li").eq(0).remove();
    }
    /*APPENDING ADDED MESSAGES*/
    if (msg_type == 1) {
        let html = `<li>
                    <div class="text-left my_gm">
        <i class="fa fa-bullhorn" aria-hidden="true"></i>
                        ${msg}
                    </div>
                </li>`;
        $(".shout_box_ul").append(html);
    } else if (msg_type == 0) {
        let html = `<li>
                    <div class="text-right other_gm">
                        ${msg}
                        <i class="fa fa-globe" aria-hidden="true"></i>
                    </div>
                </li>`;
        $(".shout_box_ul").append(html);

    } else if (msg_type == 2) {
        let html = `<li>
                    <div class="text-center common_warning">
        <i class="fa fa-exclamation" aria-hidden="true"></i>
                        ${msg}
                        
                    </div>
                </li>`;
        $(".shout_box_ul").append(html);
    }


}
