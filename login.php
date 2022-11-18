<section class="form-box-member">

<div class="form-box">
    <div class="form-header">
        <div class="form-logo">
        <h1>Domain Takip</h1>
    </div>
    <div class="form-description">
        <h4>Login Page</h4>
    </div>
    </div>
    
    <form id="login-forum">
    <div class="form-floating mb-3">
                    <input type="email" name="email"  class="form-control" id="floatingInput" placeholder="name@example.com">
                    <label for="floatingInput">Mail adresiniz.</label>
                </div>
                <div class="form-floating">
                    <input type="text" name="username"  class="form-control" id="floatingUsername" placeholder="Username">
                    <label for="floatingUsername">Kullanıcı adınız.</label>
                </div>
                <div class="form-floating">
                    <input type="password" name="passwordone" class="form-control" id="floatingPasswordone" placeholder="Şifreniz">
                    <label for="floatingPasswordone">Şifreniz</label>
                </div>
                <div class="form-floating">
                    <input type="password" name="passwordtwo" class="form-control" id="floatingPasswordtwo" placeholder="Şifre tekrarı">
                    <label for="floatingPasswordtwo">Şifre tekrarı</label>
                </div>
            </div>
            <div class="buton-group">
            <button class="btn btn-primary">Yeni hesap oluştur</button>
                <div class="gonder float-end btn btn-success">Giriş Yap</div>
            </div>
    </form>
</div>


</section>


<script>
$(document).ready(function() {
    $('#login-forum').keypress(function(e) {
        if (e.which == 13) return false;
    });

    $("body").on("click",".gonder",(function () {
        $.ajax({
            type: "POST",
            url: "/App/Controller/ajax.php?user=login",
            data: $("#login-forum").serialize(),
            success: function(cevap) {
                console.log(cevap);
            }
        });
    }));

});

</script>