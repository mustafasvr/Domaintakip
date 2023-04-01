<?php 
require_once __DIR__.'/setconfig.php';
if(isset($_SESSION['userlogin']['email'])) {
    header("Location:/");
    exit();
 }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="<?php echo $ayar['description'] ?>">
    <meta name="Author" content="<?php echo $ayar['author'] ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="/assets/img/favicon.ico">
    <script src="assets/js/jquery.js"></script>
    <title><?php echo $ayar['site_title'] ?></title>
    <style>
      body {
        background-color: #FAFAFA;
      }
    </style>
</head>

<body>

    <main class="container">

    <div id="login-box"> 
        <h1><a href="/"><?php echo $ayar['site_name'] ?></a></h1>
        <div id="alert"></div>
        <div class="login"></div>
    </div>

    </main>



    <script>
    $(document).ready(function() {
      Login();


        $("body").on("click", ".gonder", (function(e) {
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: "/App/Controller/ajax.php?user=login",
            data: $("#login-form").serialize(),
            datatype: "json",
            success: function(cevap) {
                console.log(cevap);
                var response = jQuery.parseJSON(cevap);
                if(response.status) {
                    $("#alert").removeClass("alert-box alert-box-danger");
                    $("#alert").addClass("alert-box alert-box-success");
                    $("#alert").html(response.message);
                    setTimeout(function () {
                        document.location.href = '/';
                    },1000);
                } else {
                    $("#alert").removeClass("alert-box alert-box-success");
                    $("#alert").addClass("alert-box alert-box-danger");
                    $("#alert").html(response.message);
                }
                    
            }
        });

    }));

        function Login() {
            $.ajax({
            url: "/App/Controller/ajax.php?user=login-thema",
            type: "POST",
            success: function(result) {
                $(".login").html(result);
            }
        });
    }

    function Register() {
            $.ajax({
            url: "/App/Controller/ajax.php?user=register-thema",
            type: "POST",
            success: function(result) {
                $(".login").html(result);
            }
        });
    }


    $("body").on("click", ".kayitol", (function(e) {
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: "/App/Controller/ajax.php?user=register",
            data: $("#register-form").serialize(),
            datatype: "json",
            success: function(cevap) {
                var response = jQuery.parseJSON(cevap);
                if(response.status) {
                    $("#alert").removeClass("alert-box alert-box-danger");
                    $("#alert").addClass("alert-box alert-box-success");
                    $("#alert").html(response.message);
                    setTimeout(function () {
                        document.location.href = '/';
                    },1000);
                } else {
                    $("#alert").removeClass("alert-box alert-box-success");
                    $("#alert").addClass("alert-box alert-box-danger");
                    $("#alert").html(response.message);
                }
                    
            }
        });

    }));


    $("body").on("click", ".kayit", (function() {
    $("#login-page").hide();
    Register();
}));



    });
    </script>




</body>

</html>