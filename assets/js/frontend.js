jQuery(function(){

    // Base variables

    let msg_hide_t = 0;

    // Show loading

    $(document).on("submit", ".wpcf7-form", function(){
        $(".wpcf7-form").removeClass("-curr");
        $(this).addClass("-curr");
        $(".wmcfa-popup").addClass("show loading");
    });

    // Functions

    $.fn.wmcfa_showError = function(){

        let $popup = $(".wmcfa-popup"),
            $form = $(this),
            $output = $form.find(".wpcf7-response-output"),
            output_text = "",
            trim_err = 0;

        if(!$form.length || !window.wmcfa_settings.validate){
            return false;
        }

        $popup.removeClass("show loading");
        $popup.addClass("error");

        // Check fields

        $form.find("input:not([type='checkbox']):not([type='radio']):not([type='submit']),textarea").each(function(){

            let $this = $(this);

            if($this.hasClass("wpcf7-validates-as-required")){

                if(!$this.wmcfa_trim_val()){

                    let placeholder = "";

                    if($this.attr("placeholder")!=="undefined"){
                        placeholder = $this.attr("placeholder");
                    }

                    if(!placeholder && typeof($this.data("placeholder"))!=="undefined"){
                        placeholder = $this.data("placeholder");
                    }

                    if(!placeholder){
                        placeholder = $this.parents(".field").find(".label,.fl-label,label").text();
                    }

                    if(!placeholder){
                        placeholder = $this.parent().find(".label,.fl-label,label").text();
                    }

                    placeholder = placeholder.replace(/\*+/g, "").replace(/:+/g, "");
                    output_text += (!trim_err ? wmcfa_settings.messages.field_error : "") + "<div class='error-fl'>" + wmcfa_settings.messages.field_error_prefix + " " + placeholder + "</div>";

                    trim_err++;

                }

                // Add custom notices

                if(!trim_err){
                    if($this.attr("type")==="email" && wmcfa_settings.validate_email && !$this.wmcfa_validate_email()){
                        output_text = wmcfa_settings.messages.email_error;
                    }
                    if($this.attr("type")==="tel" && wmcfa_settings.validate_tel && !$this.wmcfa_validate_tel()){
                        output_text = wmcfa_settings.messages.tel_error;
                    }
                }

            }

        });

        if(output_text===""){
            output_text = $output.text();
        }

        $popup.wmcfa_afterShow(output_text);

    };

    $.fn.wmcfa_showMsg = function(){

        let $popup = $(".wmcfa-popup"),
            $form = $(this);

        $popup.removeClass("error show loading");
        $popup.addClass("show-msg");

        window.setTimeout(function(){
            $popup.wmcfa_afterShow($form.find(".wpcf7-response-output").text());
        });

    };

    $.fn.wmcfa_afterShow = function(output_text){

        let $this = $(this);

        $this.addClass("show-msg").find(".msg-text > span").html(output_text);

        window.clearTimeout(msg_hide_t);
        msg_hide_t = window.setTimeout(function(){
            if($this.hasClass("show-msg") && !$this.hasClass("error") && window.wmcfa_settings.redirect_to!==""){
                document.location.href = window.wmcfa_settings.redirect_to;
            }
            $this.removeClass("show-msg error");
        }, 5000);

    };

    $.fn.wmcfa_init = function(){

        let $popup = $(".wmcfa-popup");

        // Show message

        document.addEventListener("wpcf7mailsent", function(event){
            $(".wpcf7-form.-curr").wmcfa_showMsg();
        }, false);

        // Show error

        document.addEventListener("wpcf7mailfailed", function(event){
            $(".wpcf7-form.-curr").wmcfa_showError();
        }, false);
        document.addEventListener("wpcf7spam", function(event){
            $(".wpcf7-form.-curr").wmcfa_showError();
        }, false);
        document.addEventListener("wpcf7invalid", function(event){
            $(".wpcf7-form.-curr").wmcfa_showError();
        }, false);

        // LTR mode

        if(window.wmcfa_settings.ltr_mode){
            $popup.addClass("ltr-mode");
        }

    };

    window.setTimeout(function(){
        $(document).wmcfa_init();
    });

    // Popup triggers

    $(document).on("click", ".wmcfa-popup-bg", function(e){

        let $popup = $(".wmcfa-popup");

        if($(this).hasClass("loading")){
            return false;
        }

        if(!$popup.hasClass("error") && window.wmcfa_settings.redirect_to!==""){
            document.location.href = window.wmcfa_settings.redirect_to;
        }

        $(this).removeClass("show-msg error");

        e.stopPropagation();

    });

    $(document).on("click", ".wmcfa-popup-msg", function(e){
        e.stopPropagation();
    });

    // Reset forms

    $(document).on("reset", "form.wpcf7-form", function(){
        $(this).find(".field").removeClass("focused");
    });

});

$.fn.wmcfa_trim = function(){
    var s = $(this).text();
    if(!s){
        return false;
    }
    return s.replace(/ +/g, "");
};

$.fn.wmcfa_trim_val = function(){
    var s = $(this).val();
    if(!s){
        return false;
    }
    return s.replace(/ +/g, "");
};

$.fn.wmcfa_validate_email = function(){
    var s = $(this).val(),
        filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    if(filter.test(s)){
        return true;
    }
    return false;
};

$.fn.wmcfa_validate_tel = function(){
    var s = $(this).val();
    s = s.replace(/[^0-9]/gim,'');
    if(s.length < 9 || s.length > 10){
        return false;
    }
    return true;
};