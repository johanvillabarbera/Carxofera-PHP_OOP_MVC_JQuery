function load_clicks(){
    $('.btn-register').on('click', function(e) {
        $('.container-login').css({ 'display' : 'none'});
        $('.container-register').css({ 'display' : 'block'});
    });

    $('.btn-login').on('click', function(e) {
        $('.container-login').css({ 'display' : 'block'});
        $('.container-register').css({ 'display' : 'none'});
    });

    $('.btn-signup').on('click', function(e) {
        e.preventDefault();
        register();
    });

    $(".btn-signup").keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            e.preventDefault();
            register();
        }//end_if
    });

    $('.btn-signin').on('click', function(e) {
        e.preventDefault();
        login();
    });

    $(".btn-signin").keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            e.preventDefault();
            login();
        }//end_if
    });
}//end_load_clicks

function register() {
    if (validate_register() != 0) {
        var result = $('.register-form').serialize();

        ajaxPromise('module/login/controller/controller_login.php?op=register', 'POST', 'JSON', result)
            .then(function(data) {
                if(data == "error_email"){
                    $('#email_reg').after('<p class="error_email_reg" style="color: red">*El email ya esta en uso</p>');
                }else if(data == "error_username"){
                    $('#username_reg').after('<p class="error_username_reg" style="color: red">*El usuario ya esta en uso</p>');
                }else{
                    toastr.success("Registrado con éxito");
                    setTimeout(' window.location.href = "index.php?page=controller_login&op=list"; ', 1000);
                }//end_else
            }).catch(function(textStatus) {
                if (console && console.log) {
                    console.log("La solicitud ha fallado: " + textStatus);
                }//end_if
            });
    }//end_if
}//end_register

function validate_register() {
    var username_exp = /^[a-zA-Z0-9]{5,}$/g;
    var email_exp = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
    var password_exp = /^(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9!@#$%^&*]{8,}$/;
    var error = false;

    //Comprobación de username
    $('.error_username_reg').empty();
    if(!$('#username_reg').val()){
        $('#username_reg').after('<p class="error_username_reg" style="color: red">*Tienes que escribir el usuario</p>');
        error = true;
    }else if($('#username_reg').val().length < 5){
        $('#username_reg').after('<p class="error_username_reg" style="color: red">*El usuario tiene que tener 5 caracteres como minimo</p>');
        error = true;
    }else if(!username_exp.test($('#username_reg').val())){
        $('#username_reg').after('<p class="error_username_reg" style="color: red">*No se pueden poner caracteres especiales</p>');
        error = true;
    }//end_if

    //Comprobación de email
    $('.error_email_reg').empty();
    if(!$('#email_reg').val()){
        $('#email_reg').after('<p class="error_email_reg" style="color: red">*Tienes que escribir el correo</p>');
        error = true;
    }else if(!email_exp.test($('#email_reg').val())){
        $('#email_reg').after('<p class="error_email_reg" style="color: red">*El formato del mail es invalido</p>');
        error = true;
    }//end_if

    //Comprobación de password1
    $('.error_password_reg1').empty();
    if(!$('#password_reg1').val()){
        $('#password_reg1').after('<p class="error_password_reg1" style="color: red">*Tienes que escribir la contraseña</p>');
        error = true;
    }else if($('#password_reg1').val().length < 8){
        $('#password_reg1').after('<p class="error_password_reg1" style="color: red">*La contraseña tiene que tener 8 caracteres como minimo</p>');
        error = true;
    }else if(!password_exp.test($('#password_reg1').val())){
        $('#password_reg1').after('<p class="error_password_reg1" style="color: red">*Debe de contener mayusculas, minusculas y simbolos especiales</p>');
        error = true;
    }//end_if

    //Comprobación de password2
    $('.error_password_reg2').empty();
    if(!$('#password_reg2').val()){
        $('#password_reg2').after('<p class="error_password_reg2" style="color: red">*Tienes que repetir la contraseña</p>');
        error = true;
    }else if($('#password_reg2').val().length < 8){
        $('#password_reg2').after('<p class="error_password_reg2" style="color: red">*La contraseña tiene que tener 8 caracteres como minimo</p>');
        error = true;
    }else if($('#password_reg2').val() != $('#password_reg1').val()){
        $('#password_reg2').after('<p class="error_password_reg2" style="color: red">*Las contraseñas no coinciden</p>');
        error = true;
    }//end_if

    if (error == true) {
        return 0;
    }//end_if
}//end_validate_register

function login() {
    if (validate_login() != 0) {
        var result = $('.login-form').serialize();
        ajaxPromise('module/login/controller/controller_login.php?op=login', 'POST', 'JSON', result)
            .then(function(data) {
                console.log(data);
                if (data == "error_user") {
                    $('#username_log').after('<p class="error_username_log" style="color: red">*El usario no existe</p>');
                } else if (data == "error_password") {
                    $('#password_log').after('<p class="error_password_log" style="color: red">*La contraseña es incorrecta</p>');
                } else {
                    localStorage.setItem("token", data[0]);
                    localStorage.setItem("token_refresh", data[1]);
                    toastr.success("Ha iniciado sesión con éxito");

                    if (localStorage.getItem('redirect_like')) {
                        setTimeout(' window.location.href = "index.php?page=controller_shop&op=list"; ', 1000);
                    } else {
                        setTimeout(' window.location.href = "index.php?page=controller_home&op=list"; ', 1000);
                    }
                }//end_else
            }).catch(function(textStatus) {
                if (console && console.log) {
                    console.log("La solicitud ha fallado: " + textStatus);
                }
            });
    }
}//end_login

function validate_login() {
    var error = false;

    //Comprobación de username
    $('.error_username_log').empty();
    if(!$('#username_log').val()){
        $('#username_log').after('<p class="error_username_log" style="color: red">*Tienes que escribir el usuario</p>');
        error = true;
    }else if($('#username_log').val().length < 5){
        $('#username_log').after('<p class="error_username_log" style="color: red">*El usuario tiene que tener 5 caracteres como minimo</p>');
        error = true;
    }//end_if

    //Comprobación de password
    $('.error_password_log').empty();
    if(!$('#password_log').val()){
        $('#password_log').after('<p class="error_password_log" style="color: red">*Tienes que escribir la contraseña</p>');
        error = true;
    }else if($('#password_log').val().length < 8){
        $('#password_log').after('<p class="error_password_log" style="color: red">*La contraseña tiene que tener 8 caracteres como minimo</p>');
        error = true;
    }//end_if

    if (error == true) {
        return 0;
    }//end_if
}//end_validate_login

$(document).ready(function() {
    load_clicks();
});