$(document).ready(function(){
    $("#register-btn").click(function(){
        $("#register-box").show();
        $("#login-box").hide();
    });

    $("#login-btn").click(function(){
        $("#register-box").hide();
        $("#login-box").show();
    });

    $("#forgot-btn").click(function(){
        $("#login-box").hide();
        $("#forgot-box").show();
    });

    $("#back-btn").click(function(){
        $("#forgot-box").hide();
        $("#login-box").show();
    });

    $("#login-frm").validate();

    $("#register-frm").validate({
        rules:{
            pass:{
                EqualTo:"#cpass",
            }
        }
    });

    $("#forgot-frm").validate();

    // submit form without page refresh
    $("#register").click(function(e){
        if (document.getElementById('register-frm').checkVisibility()) {
            e.preventDefault();
            $("#loader").show();
            $.ajax({
                url: '/api/account/register',
                method: 'post',
                data: $("#register-frm").serialize() + '&action=register',
                success:function(response) {
                    $("#alert").show();
                    $("#result").html(response);
                    $("#loader").hide();
                }
            });
        }
        return true;
    });

    $("#login").click(function(e){
        if (document.getElementById('login-frm').checkVisibility()) {
            e.preventDefault();
            $("#loader").show();
            $.ajax({
                url: '/api/account/login',
                method: 'post',
                data: $("#login-frm").serialize() + '&action=login',
                success: function(response) {
                    if (response == "ok") {
                        window.location = 'profile.php';
                    } else {
                        $("#alert").show();
                        $("#result").html(response);
                        $("#loader").hide();
                    }
                }
            });
        }
        return true;
    });

    $("#forgot").click(function(e){
        if (document.getElementById('forgot-frm').checkVisibility()) {
            e.preventDefault();
            $("#loader").show();
            $.ajax({
                url: '/api/account/forgot',
                method: 'post',
                data: $("#forgot-frm").serialize() + '&action=forgot',
                success:function(response) {
                    $("#alert").show();
                    $("#result").html(response);
                    $("#loader").hide();
                }
            });
        }
        return true;
    });
});