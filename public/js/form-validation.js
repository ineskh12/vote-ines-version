var FormValidation = function () {

    // basic validation
    var admin_create = function() {
        // for more info visit the official plugin documentation: 
            // http://docs.jquery.com/Plugins/Validation
            var form1 = $('#admin_create');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);

            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: true, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    avatar: {
                      extension: "png|jpg|jpeg"
                    },
                    nom: {
                        minlength: 2,
                        required: true
                    },
                    prenom: {
                        minlength: 2,
                        required: true
                    },
                    email: {
                        required: true,
                        email: true,
                        remote: "check_email"
                    },
                    password: {
                        minlength: 6,
                        required: true
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#password"
                    },
                },
                messages: { // custom messages for radio buttons and checkboxes
                    email:{
                            remote: jQuery.validator.format('Ce Email est déjà utilisé.')
                    }
                },
                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success1.hide();
                    error1.show();
                    $(".alertadd").hide();
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var cont = $(element).parent('.input-group');
                    if (cont.length > 0) {
                        cont.after(error);
                    } else {
                        element.after(error);
                    }
                    if (element.is('select')) {
                        error.insertAfter(element.closest('.form-group').find('.select2'));
                    }
                    if (element.attr('type') ==  "file") {
                        error.appendTo(element.closest('.form-group'));
                    } 
                },

                highlight: function (element) { // hightlight error inputs

                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
                    success1.show();
                    error1.hide();
                    $(".alertadd").hide();
                    form.submit();
                }
            });

             
            $('input[name="avatar"]').on('change', function(){ 
                $(this).valid();
            });

    }


    var admin_edit = function() {
        // for more info visit the official plugin documentation: 
            // http://docs.jquery.com/Plugins/Validation
            var form1 = $('#admin_edit');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);

            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: true, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    avatar: {
                      extension: "png|jpg|jpeg"
                    },
                    nom: {
                        minlength: 2,
                        required: true
                    },
                    prenom: {
                        minlength: 2,
                        required: true
                    },
                    email: {
                        required: true,
                        email: true,
                        remote: { 
                            url:"../check_email", 
                            data: { "id_admin": form1.data('id')}
                        }
                    }
                },
                messages: { // custom messages for radio buttons and checkboxes
                    email:{
                            remote: jQuery.validator.format('Ce Email est déjà utilisé.')
                    }
                },
                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success1.hide();
                    error1.show();
                    $(".alertadd").hide();
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var cont = $(element).parent('.input-group');
                    if (cont.length > 0) {
                        cont.after(error);
                    } else {
                        element.after(error);
                    }
                    if (element.is('select')) {
                        error.insertAfter(element.closest('.form-group').find('.select2'));
                    }
                    if (element.attr('type') ==  "file") {
                        error.appendTo(element.closest('.form-group'));
                    } 
                },

                highlight: function (element) { // hightlight error inputs

                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
                    success1.show();
                    error1.hide();
                    $(".alertadd").hide();
                    form.submit();
                }
            });

        $('input[name="avatar"]').on('change', function(){ 
            $(this).valid();
        });
    }


    var admin_account = function() {
        // for more info visit the official plugin documentation: 
            // http://docs.jquery.com/Plugins/Validation
            var form1 = $('#admin_account');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);

            form1.validate({
                errorElement: 'div', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: true, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    avatar: {
                      extension: "png|jpg|jpeg"
                    },
                    nom: {
                        required: true,
                        minlength: 2
                    },
                    prenom: {
                        required: true,
                        minlength: 2
                        
                    },
                    email: {
                        required: true,
                        email: true,
                        remote: { 
                            url:"admins/check_email", 
                            data: { "id_admin": form1.data('id')}
                        }
                    }
                },
                messages: { // custom messages for radio buttons and checkboxes
                    email:{
                            remote: jQuery.validator.format('Ce Email est déjà utilisé.')
                    }
                },
                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success1.hide();
                    error1.show();
                    $(".alertadd").hide();
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var cont = $(element).parent('.input-group');
                    if (cont.length > 0) {
                        cont.after(error);
                    } else {
                        element.after(error);
                    }
                    if (element.is('select')) {
                        error.insertAfter(element.closest('.form-group').find('.select2'));
                    }

                    if (element.attr('type') ==  "file") {
                        error.appendTo(element.closest('.form-group'));
                    } 
                },

                highlight: function (element) { // hightlight error inputs

                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
                    success1.show();
                    error1.hide();
                    $(".alertadd").hide();
                    form.submit();
                }
            });

        $('input[name="avatar"]').on('change', function(){ 
            $(this).valid();
        });
    }

    var login = function() {
        // for more info visit the official plugin documentation: 
            // http://docs.jquery.com/Plugins/Validation
            var form1 = $('#login');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);

            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: true, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    email: {
                        required: true,
                        email: true,
                    },
                    password: {
                        minlength: 6,
                        required: true
                    }
                },
                messages: { // custom messages for radio buttons and checkboxes
                    email:{
                            remote: jQuery.validator.format('Ce Email est déjà utilisé.')
                    }
                },
                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success1.hide();
                    error1.show();
                    $(".alertadd").hide();
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var cont = $(element).parent('.input-group');
                    if (cont.length > 0) {
                        cont.after(error);
                    } else {
                        element.after(error);
                    }
                    if (element.is('select')) {
                        error.insertAfter(element.closest('.form-group').find('.select2'));
                    }
                },

                highlight: function (element) { // hightlight error inputs

                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
                    success1.show();
                    error1.hide();
                    $(".alertadd").hide();
                    form.submit();
                }
            });
    }

    var reset = function() {
        // for more info visit the official plugin documentation: 
            // http://docs.jquery.com/Plugins/Validation
            var form1 = $('#reset');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);

            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: true, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    email: {
                        required: true,
                        email: true,
                    }
                },
                messages: { // custom messages for radio buttons and checkboxes
                    email:{
                            remote: jQuery.validator.format('Ce Email est déjà utilisé.')
                    }
                },
                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success1.hide();
                    error1.show();
                    $(".alertadd").hide();
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var cont = $(element).parent('.input-group');
                    if (cont.length > 0) {
                        cont.after(error);
                    } else {
                        element.after(error);
                    }
                    if (element.is('select')) {
                        error.insertAfter(element.closest('.form-group').find('.select2'));
                    }
                },

                highlight: function (element) { // hightlight error inputs

                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
                    success1.show();
                    error1.hide();
                    $(".alertadd").hide();
                    form.submit();
                }
            });
    }


    var reset_password = function() {
        // for more info visit the official plugin documentation: 
            // http://docs.jquery.com/Plugins/Validation
        var form1 = $('#reset_password');
        var error1 = $('.alert-danger', form1);
        var success1 = $('.alert-success', form1);

        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: true, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    minlength: 6,
                    required: true
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password"
                },
            },
            messages: { // custom messages for radio buttons and checkboxes
                email:{
                        remote: jQuery.validator.format('Ce Email est déjà utilisé.')
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit              
                success1.hide();
                error1.show();
                $(".alertadd").hide();
            },

            errorPlacement: function (error, element) { // render error placement for each input type
                var cont = $(element).parent('.input-group');
                if (cont.length > 0) {
                    cont.after(error);
                } else {
                    element.after(error);
                }
                if (element.is('select')) {
                    error.insertAfter(element.closest('.form-group').find('.select2'));
                }
            },

            highlight: function (element) { // hightlight error inputs

                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            unhighlight: function (element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },

            success: function (label) {
                label
                    .closest('.form-group').removeClass('has-error'); // set success class to the control group
            },

            submitHandler: function (form) {
                success1.show();
                error1.hide();
                $(".alertadd").hide();
                form.submit();
            }
        });
    }

    var project_edit = function() {
        // for more info visit the official plugin documentation: 
            // http://docs.jquery.com/Plugins/Validation
            var form1 = $('#project_edit');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);

            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: true, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    logo: {
                      extension: "png|jpg|jpeg"
                    },
                    titre_fr: {
                        minlength: 2,
                        required: ($("#event_id").find(':selected').attr('data-lang') == 'fr')
                    },
                    color: {
                        required: true
                    },
                    description_fr: {
                        minlength: 2
                    },
                    titre_ar: {
                        minlength: 2,
                        required: ($("#event_id").find(':selected').attr('data-lang') == 'ar')
                    },
                    description_ar: {
                        minlength: 2
                    },
                    titre_en: {
                        minlength: 2,
                        required: ($("#event_id").find(':selected').attr('data-lang') == 'en')
                    },
                    description_en: {
                        minlength: 2
                    }
                },
                messages: { // custom messages for radio buttons and checkboxes

                },
                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success1.hide();
                    error1.show();
                    $(".alertadd").hide();
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var cont = $(element).parent('.input-group');
                    if (cont.length > 0) {
                        cont.after(error);
                    } else {
                        element.after(error);
                    }
                    if (element.is('select')) {
                        error.insertAfter(element.closest('.form-group').find('.select2'));
                    }
                    
                    if (element.attr('type') ==  "file") {
                        error.appendTo(element.closest('.form-group'));
                    } 
                },

                highlight: function (element) { // hightlight error inputs

                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
                    success1.show();
                    error1.hide();
                    $(".alertadd").hide();
                    form.submit();
                }
            });

        $('input[name="avatar"]').on('change', function(){ 
            $(this).valid();
        });
    }


    var project_create = function() {
        // for more info visit the official plugin documentation: 
            // http://docs.jquery.com/Plugins/Validation
            var form1 = $('#project_create');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);

            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: true, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    logo: {
                      extension: "png|jpg|jpeg"
                    },
                    titre_fr: {
                        minlength: 2,
                        required: $("#event_id").find(':selected').attr('data-lang') == 'fr'
                    },
                    color: {
                        required: true
                    },
                    description_fr: {
                        minlength: 2
                    },
                    titre_ar: {
                        minlength: 2,
                        required: $("#event_id").find(':selected').attr('data-lang') == 'ar'
                    },
                    description_ar: {
                        minlength: 2
                    },
                    titre_en: {
                        minlength: 2,
                        required: $("#event_id").find(':selected').attr('data-lang') == 'en'
                    },
                    description_en: {
                        minlength: 2
                    }
                },
                messages: { // custom messages for radio buttons and checkboxes

                },
                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success1.hide();
                    error1.show();
                    $(".alertadd").hide();
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var cont = $(element).parent('.input-group');
                    if (cont.length > 0) {
                        cont.after(error);
                    } else {
                        element.after(error);
                    }
                    if (element.is('select')) {
                        error.insertAfter(element.closest('.form-group').find('.select2'));
                    }

                    if (element.attr('type') ==  "file") {
                        error.appendTo(element.closest('.form-group'));
                    }       
                },

                highlight: function (element) { // hightlight error inputs

                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
                    success1.show();
                    error1.hide();
                    $(".alertadd").hide();
                    form.submit();
                }
            });

        $('input[name="avatar"]').on('change', function(){ 
            $(this).valid();
        });
    }


    var criteria_create = function() {
        // for more info visit the official plugin documentation: 
            // http://docs.jquery.com/Plugins/Validation
            var form1 = $('#criteria_create');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);

            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: true, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    titre_fr: {
                        minlength: 2,
                        required: false
                    },
                    titre_ar: {
                        minlength: 2,
                        required: false
                    },
                    percentage_id:{
                        required: true
                    },
                    coefficient: {
                        customnumeric:true,
                        required: true
                    }
                },
                messages: { // custom messages for radio buttons and checkboxes

                },
                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success1.hide();
                    error1.show();
                    $(".alertadd").hide();
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var cont = $(element).parent('.input-group');
                    if (cont.length > 0) {
                        cont.after(error);
                    } else {
                        element.after(error);
                    }
                    if (element.is('select')) {
                        error.insertAfter(element.closest('.form-group').find('.select2-container'));
                    }
                    
                },

                highlight: function (element) { // hightlight error inputs

                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
                    success1.show();
                    error1.hide();
                    $(".alertadd").hide();
                    form.submit();
                }
            });



            $('.select2', form1).change(function () {
                form1.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
            });


            function isFloat(val) {
                var floatRegex = /^-?\d+(?:[.,]\d*?)?$/;
                if (!floatRegex.test(val))
                    return false;

                val = parseFloat(val);
                if (isNaN(val) || val < 0 )
                    return false;
                return true;
            }

             $.validator.addMethod("customnumeric", function(value, element) { 
                return isFloat(value)
            }, "Veuillez saisir un float.(ex.: 12.1, 0.5, 3.75)"); 
    }


    var criteria_edit = function() {
        // for more info visit the official plugin documentation: 
            // http://docs.jquery.com/Plugins/Validation
            var form1 = $('#criteria_edit');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);

            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: true, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    titre_fr: {
                        minlength: 2,
                        required: true
                    },
                    titre_ar: {
                        minlength: 2,
                        required: true
                    },
                    percentage_id:{
                        required: true
                    },
                    coefficient: {
                        customnumeric:true,
                        required: true
                    }
                },
                messages: { // custom messages for radio buttons and checkboxes

                },
                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success1.hide();
                    error1.show();
                    $(".alertadd").hide();
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var cont = $(element).parent('.input-group');
                    if (cont.length > 0) {
                        cont.after(error);
                    } else {
                        element.after(error);
                    }
                    if (element.is('select')) {
                        error.insertAfter(element.closest('.form-group').find('.select2-container'));
                    }
                    
                },

                highlight: function (element) { // hightlight error inputs

                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
                    success1.show();
                    error1.hide();
                    $(".alertadd").hide();
                    form.submit();
                }
            });


            $('.select2', form1).change(function () {
                form1.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
            });
            
            function isFloat(val) {
                var floatRegex = /^-?\d+(?:[.,]\d*?)?$/;
                if (!floatRegex.test(val))
                    return false;

                val = parseFloat(val);
                if (isNaN(val) || val < 0 )
                    return false;
                return true;
            }

             $.validator.addMethod("customnumeric", function(value, element) { 
                return isFloat(value)
            }, "Veuillez saisir un float.(ex.: 12.1, 0.5, 3.75)"); 
    }


    var note_settings = function() {
        // for more info visit the official plugin documentation: 
            // http://docs.jquery.com/Plugins/Validation
            var form1 = $('#note_settings');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);

            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: true, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    somme_mobile: {
                        customint:true,
                        required: true
                    },
                    somme_backoffice: {
                        customint:true,
                        required: true
                    }
                },
                messages: { // custom messages for radio buttons and checkboxes

                },
                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success1.hide();
                    error1.show();
                    $(".alertadd").hide();
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var cont = $(element).parent('.input-group');
                    if (cont.length > 0) {
                        cont.after(error);
                    } else {
                        element.after(error);
                    }
                    if (element.is('select')) {
                        error.insertAfter(element.closest('.form-group').find('.select2-container'));
                    }
                    
                },

                highlight: function (element) { // hightlight error inputs

                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
                    success1.show();
                    error1.hide();
                    $(".alertadd").hide();
                    form.submit();
                }
            });


            $('.select2', form1).change(function () {
                form1.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
            });
            
            function isInt(val) {
                var intRegex = /^\d+$/;
                if (!intRegex.test(val))
                    return false;

                val = parseInt(val);
                if (isNaN(val) || val <= 0 )
                    return false;
                return true;
            }

             $.validator.addMethod("customint", function(value, element) { 
                return isInt(value)
            }, "Veuillez saisir un entier.(ex.: 5, 10, 3)"); 
    }


    var percentage_create = function() {
        // for more info visit the official plugin documentation: 
            // http://docs.jquery.com/Plugins/Validation
            var form1 = $('#percentage_create');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);
            var message = "";
            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: true, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    titre_fr: {
                        minlength: 2,
                        required: false
                    },
                    titre_ar: {
                        minlength: 2,
                        required: false
                    },
                    type: {
                        required: true
                    },
                    percentage: {
                        customnumeric:true,
                        required: true,
                        remote: { 
                            url:"check_somme",
                            dataFilter: function(response) {
                                response = $.parseJSON(response);
                                if(response != true){
                                    message = response;
                                    return false;
                                }else{
                                    return true;
                                }
                            }
                        }
                    }
                },
                messages: { // custom messages for radio buttons and checkboxes
                    percentage:{
                           remote: function(){ return message; }
                    }
                },
                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success1.hide();
                    error1.show();
                    $(".alertadd").hide();
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var cont = $(element).parent('.input-group');
                    if (cont.length > 0) {
                        cont.after(error);
                    } else {
                        element.after(error);
                    }
                    if (element.is('select')) {
                        error.insertAfter(element.closest('.form-group').find('.select2'));
                    }

                    if (element.is('input:radio')) {
                        error.insertAfter(element.closest('.demo-radio-button'));
                    }
                    
                },

                highlight: function (element) { // hightlight error inputs

                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
                    success1.show();
                    error1.hide();
                    $(".alertadd").hide();
                    form.submit();
                }
            });


            function isFloat(val) {
                var floatRegex = /^-?\d+(?:[.,]\d*?)?$/;
                if (!floatRegex.test(val))
                    return false;

                val = parseFloat(val);
                if (isNaN(val) || val < 0 )
                    return false;
                return true;
            }

             $.validator.addMethod("customnumeric", function(value, element) { 
                return isFloat(value)
            }, "Veuillez saisir un float.(ex.: 12.1, 0.5, 3.75)"); 
    }


    var percentage_edit = function() {
        // for more info visit the official plugin documentation: 
            // http://docs.jquery.com/Plugins/Validation
            var form1 = $('#percentage_edit');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);
            var message = "";
            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: true, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    titre_fr: {
                        minlength: 2,
                        required: true
                    },
                    titre_ar: {
                        minlength: 2,
                        required: true
                    },
                    type: {
                        required: true
                    },
                    percentage: {
                        customnumeric:true,
                        required: true,
                        remote: { 
                            url:"../check_somme",
                            data: { "id_percentage": form1.data('id')},
                            dataFilter: function(response) {
                                response = $.parseJSON(response);
                                if(response != true){
                                    message = response;
                                    return false;
                                }else{
                                    return true;
                                }
                            }
                        }
                    }
                },
                messages: { // custom messages for radio buttons and checkboxes
                    percentage:{
                           remote: function(){ return message; }
                    }
                },
                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success1.hide();
                    error1.show();
                    $(".alertadd").hide();
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var cont = $(element).parent('.input-group');
                    if (cont.length > 0) {
                        cont.after(error);
                    } else {
                        element.after(error);
                    }
                    if (element.is('select')) {
                        error.insertAfter(element.closest('.form-group').find('.select2'));
                    }

                    if (element.is('input:radio')) {
                        error.insertAfter(element.closest('.demo-radio-button'));
                    }
                    
                },

                highlight: function (element) { // hightlight error inputs

                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
                    success1.show();
                    error1.hide();
                    $(".alertadd").hide();
                    form.submit();
                }
            });


            function isFloat(val) {
                var floatRegex = /^-?\d+(?:[.,]\d*?)?$/;
                if (!floatRegex.test(val))
                    return false;

                val = parseFloat(val);
                if (isNaN(val) || val < 0 )
                    return false;
                return true;
            }

             $.validator.addMethod("customnumeric", function(value, element) { 
                return isFloat(value)
            }, "Veuillez saisir un float.(ex.: 12.1, 0.5, 3.75)"); 
    }


    var note_create = function() {
        // for more info visit the official plugin documentation: 
            // http://docs.jquery.com/Plugins/Validation
            var form1 = $('#note_create');
            var error1 = $('.alert-danger', form1);
            var success1 = $('.alert-success', form1);

            form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: true, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    project_id: {
                        required: true
                    },
                    note: {
                        customnumeric:true,
                        remote: "check_somme",  
                        required: true
                    }
                },
                messages: { // custom messages for radio buttons and checkboxes
                    note:{
                            remote: jQuery.validator.format('La note dépasse la somme des votes.')
                    }
                },
                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success1.hide();
                    error1.show();
                    $(".alertadd").hide();
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    var cont = $(element).parent('.input-group');
                    if (cont.length > 0) {
                        cont.after(error);
                    } else {
                        element.after(error);
                    }
                    if (element.is('select')) {
                        error.insertAfter(element.closest('.form-group').find('.select2-container'));
                    }
                    
                },

                highlight: function (element) { // hightlight error inputs

                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
                    success1.show();
                    error1.hide();
                    $(".alertadd").hide();
                    form.submit();
                }
            });



            $('.select2', form1).change(function () {
                form1.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
            });


            function isFloat(val) {
                var floatRegex = /^-?\d+(?:[.,]\d*?)?$/;
                if (!floatRegex.test(val))
                    return false;

                val = parseFloat(val);
                if (isNaN(val) || val < 0 )
                    return false;
                return true;
            }

             $.validator.addMethod("customnumeric", function(value, element) { 
                return isFloat(value)
            }, "Veuillez saisir un float.(ex.: 12.1, 0.5, 3.75)"); 
    }


    return {
        //main function to initiate the module
        init: function () {

            //handleWysihtml5();
            admin_create();
            admin_edit();
            admin_account();
            project_edit();
            project_create();
            criteria_create();
            criteria_edit();
            percentage_create();
            percentage_edit();
            note_create();
            note_settings();
            login();
            reset();
            reset_password();
        }

    };

}();

jQuery(document).ready(function() {
    FormValidation.init();
});