var Login = function () {
    
    return {
        //main function to initiate the module
        init: function () {
            
           $('.login-form').validate({
                errorElement: 'label', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                rules: {
                    username: {
                        required: true
                    },
                    password: {
                        required: true
                    },
                    remember: {
                        required: false
                    }
                },

                messages: {
                    username: {
                        required: "用户为必填."
                    },
                    password: {
                        required: "密码为必填."
                    }
                },

                invalidHandler: function (event, validator) { //display error alert on form submit   
                    //$('.alert-error', $('.login-form')).show();
                },

                highlight: function (element) { // hightlight error inputs
                    $(element)
                        .closest('.control-group').addClass('error'); // set error class to the control group
                },

                success: function (label) {
                    label.closest('.control-group').removeClass('error');
                    label.remove();
                },

                errorPlacement: function (error, element) {
                    error.addClass('help-small no-left-padding').insertAfter(element.closest('.input-icon'));
                },

                submitHandler: function (form) {
                    form.submit();
                    //window.location.href = "index.html";
                }
            });


            $('.forget-form').validate({
                errorElement: 'label', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    mobile: {
                        required: true,
                        digits: true
                    }
                },

                messages: {
                    mobile: {
                        required: "手机号为必填."
                    }
                },

                invalidHandler: function (event, validator) { //display error alert on form submit   

                },

                highlight: function (element) { // hightlight error inputs
                    $(element)
                        .closest('.control-group').addClass('error'); // set error class to the control group
                },

                success: function (label) {
                    label.closest('.control-group').removeClass('error');
                    label.remove();
                },

                errorPlacement: function (error, element) {
                    error.addClass('help-small no-left-padding').insertAfter(element.closest('.input-icon'));
                },

                submitHandler: function (form) {
                    $.post('/login/forget', $(form).serialize(), function(ret) {
                        if (ret && ret.code == 0) {
                            alert(ret.msg);
                        } else {
                            alert(ret ? ret.msg : '系统错误');
                        }
                    }, 'json');
                    //window.location.href = "index.html";
                }
            });


            jQuery('#forget-password').click(function () {
                jQuery('.login-form').hide();
                jQuery('.forget-form').show();
            });

        jQuery('#back-btn').click(function () {
                jQuery('.login-form').show();
                jQuery('.forget-form').hide();
            });
        }

    };

}();
