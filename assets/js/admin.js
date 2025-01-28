jQuery(function($){

    // Check plugin update

    if(typeof(wmcfa_update_param)!=="undefined" && !wmcfa_update_param.checked){
        $.ajax({
            url: wmcfa_update_param.action_check,
            context: document.body
        });
    }

    // Run plugin update

    $.fn.wmcfa_showUpdateError = function(){

        var $this = $(this),

            $update_message = $this.find(".update-message");
            $update_message.attr("class", "update-message notice inline notice-warning notice-alt notice-error")
                .html("<p class='notice-text update-text'>" + wmcfa_language.update_error + "</p>");

        $this.removeClass("updating-message");

    };

    $(document).on("click", ".wmcfa-update-link", function(){

        var $plugin_update_row = $(this).parents(".plugin-update-tr"),
            $notice = $plugin_update_row.find(".notice");

        $plugin_update_row.addClass("updating-message");

        $.ajax({
            type: "POST",
            dataType: "json",
            url: wmcfa_update_param.action_run,
            context: document.body
        }).done(function(data){
            if(typeof(data)!=="undefined" && typeof(data.error)!=="undefined" && data.error===0) {
                $plugin_update_row.removeClass("updating-message").addClass("updated");
                $notice.removeClass("notice-warning").addClass("updated-message notice-success");
            }else{
                $plugin_update_row.wmcfa_showUpdateError();
            }
        }).fail(function(){
            $plugin_update_row.wmcfa_showUpdateError();
        });

        return false;

    });

});