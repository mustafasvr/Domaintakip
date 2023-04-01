$(document).ready(function(e) {


Home();

$("body").on("click", ".box-header", (function() {
    $(this).find('.fa-caret-left').toggleClass("fa-caret-down").fadeTo("slow");
    var box = $(this).parents('.box')
    $(box).find('.box-info').toggle();
}));


$("body").on("click", ".domain-ekle", (function() {
    $("#Home").hide();
    Domainekle();
}));



$("body").on("click", ".domainler", (function() {
    $("#dashboard").hide();
    Domainler();
}));

$("body").on("click", ".kullanicilar", (function() {
    $("#dashboard").hide();
    Kullanicilar();
}));


$("body").on("click", ".ayarlar", (function() {
    $("#dashboard").hide();
    Ayarlar();
}));


$("body").on("click", ".whois", (function() {
    $("#dashboard").hide();
    Whois();
}));

$("body").on("click", ".whois-ekle", (function() {
    $("#Home").hide();
    Whoisekle();
}));


$("body").on("click", ".domain-kaydet", (function(e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "/App/Controller/ajaxdomain.php?domain=kaydet",
        data: $("#domain-kaydet").serialize(),
        datatype: "json",
        success: function(cevap) {
            var response = jQuery.parseJSON(cevap);
            if(response.status) {
                $("#alert").show();
                $("#alert").removeClass("alert-box alert-box-danger");
                $("#alert").addClass("alert-box alert-box-success");
                $("#alert").html(response.message);
                setTimeout(function () {
                    $("#alert").fadeOut();
                    Home();
                },1000);
                
            } else {
                $("#alert").show();
                Domainekle();
                $("#alert").removeClass("alert-box alert-box-success");
                $("#alert").addClass("alert-box alert-box-danger");
                $("#alert").html(response.message);
                setTimeout(function () {
                    $("#alert").fadeOut();
                },3000);
            } 
        }
    });
}));




$("body").on("click", ".guncelle", (function(e) {
        $.ajax({
            type: "POST",
            url: "/App/Controller/ajaxdomain.php?domain=guncelle",
                datatype: "json",
                success: function(cevap) {
                var response = jQuery.parseJSON(cevap);
                if(response.status) {
                    $("#alert").show();
                    $("#alert").removeClass("alert-box alert-box-danger");
                    $("#alert").addClass("alert-box alert-box-success");
                    $("#alert").html(response.message);
                    setTimeout(function () {
                        $("#alert").fadeOut();
                        Home();
                    },3000);
                    
                } else {
                    Home()
                    $("#alert").show();
                    $("#alert").removeClass("alert-box alert-box-success");
                    $("#alert").addClass("alert-box alert-box-danger");
                    $("#alert").html(response.message);
                    setTimeout(function () {
                        $("#alert").fadeOut();
                    },3000);
                } 
            }
        });
    }));


$("body").on("click", ".domain-sil", (function(e) {
    var id=$(this).data("id");
    if(confirm('Domain silmek istediğinizden emin misiniz?')) {
        $.ajax({
            type: "POST",
            url: "/App/Controller/ajaxdomain.php?domain=sil",
            data:{
                domain_id: id,
                },
                datatype: "json",
                success: function(cevap) {
                var response = jQuery.parseJSON(cevap);
                if(response.status) {
                    $("#alert").show();
                    $("#alert").removeClass("alert-box alert-box-danger");
                    $("#alert").addClass("alert-box alert-box-success");
                    $("#alert").html(response.message);
                    setTimeout(function () {
                        $("#alert").fadeOut();
                        Home();
                    },1000);
                    
                } else {
                    Home()
                    $("#alert").show();
                    $("#alert").removeClass("alert-box alert-box-success");
                    $("#alert").addClass("alert-box alert-box-danger");
                    $("#alert").html(response.message);
                    setTimeout(function () {
                        $("#alert").fadeOut();
                    },1000);
                } 
            }
        });
    }
    }));


// FUCTİON


function Home() {
        $.ajax({
        url: "/App/Controller/ajaxdomain.php?tema=home",
        type: "POST",
        success: function(result) {
            $("main").fadeIn( "slow", function() {
                $("main").html(result);
              });
        }
    });
}


function Domainekle() {
        $.ajax({
        url: "/App/Controller/ajaxdomain.php?tema=domain-ekle",
        type: "POST",
        success: function(result) {
            $("main").html(result);
        }
    });
}


function Domainler() {
    $.ajax({
    url: "/App/Controller/ajaxadmin.php?admin=domainler",
    type: "POST",
    success: function(result) {
        $("main").html(result);
    }
});
}

function Kullanicilar() {
    $.ajax({
    url: "/App/Controller/ajaxadmin.php?admin=kullanicilar",
    type: "POST",
    success: function(result) {
        $("main").html(result);
    }
});
}


function Whois() {
    $.ajax({
    url: "/App/Controller/ajaxadmin.php?admin=whois",
    type: "POST",
    success: function(result) {
        $("main").html(result);
    }
});
}

function Whoisekle() {
    $.ajax({
    url: "/App/Controller/ajaxadmin.php?admin=whois-ekle",
    type: "POST",
    success: function(result) {
        $("main").html(result);
    }
});
}

// ADMİN

function Ayarlar() {
    $.ajax({
    url: "/App/Controller/ajaxadmin.php?admin=ayarlar",
    type: "POST",
    success: function(result) {
        $("main").html(result);
    }
});
}

$("body").on("click", ".duzenle", (function(e) {
    var id=$(this).data("id");
    $.ajax({
        type: "POST",
        url: "/App/Controller/ajaxadmin.php?admin=ayarlar",
        data:{
            ayar_id: id,
            },
            datatype: 'json',
            success: function(cevap) {
            var response = jQuery.parseJSON(cevap);
            $("#ayarduzenleform").html(response.html);
        }
    });
}));

$("body").on("click", ".ayarduzenle", (function(e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "/App/Controller/ajaxadmin.php?admin=ayarduzenle",
        data: $("#ayarduzenle").serialize(),
        datatype: 'json',
        success: function(cevap) {
            var response = jQuery.parseJSON(cevap);
            $("#alert").show();
            if(response.status) {
                $("#alert").removeClass("alert-box alert-box-danger");
                $("#alert").addClass("alert-box alert-box-success");
                $("#alert").html(response.message);
                setTimeout(function () {
                    $("#alert").fadeOut();
                    Ayarlar();
                },2000);
            } else {
                $("#alert").removeClass("alert-box alert-box-success");
                $("#alert").addClass("alert-box alert-box-danger");
                $("#alert").html(response.message);
                setTimeout(function () {
                    $("#alert").fadeOut();
                },2000);
            }
        }
    });

}));




$("body").on("click", ".user-ban", (function(e) {
    var id=$(this).data("id");
        $.ajax({
            type: "POST",
            url: "/App/Controller/ajaxadmin.php?admin=user-ban",
            data:{
                user_id: id,
                },
                success: function(cevap) {
                    var response = jQuery.parseJSON(cevap);
                    Kullanicilar();
                    $("#alert").show();
                    if(response.status) {
                        $("#alert").removeClass("alert-box alert-box-danger");
                        $("#alert").addClass("alert-box alert-box-success");
                        $("#alert").html(response.message);
                        setTimeout(function () {
                            $("#alert").fadeOut();
                        },2000);
                    } else {
                        $("#alert").removeClass("alert-box alert-box-success");
                        $("#alert").addClass("alert-box alert-box-danger");
                        $("#alert").html(response.message);
                        setTimeout(function () {
                            $("#alert").fadeOut();
                        },2000);
                    }
            }
        });
    }));







$("body").on("click", ".user-duzenle", (function(e) {
    var id=$(this).data("id");
    $.ajax({
        type: "POST",
        url: "/App/Controller/ajaxadmin.php?admin=kullanicilar",
        data:{
            user_id: id,
            },
            datatype: 'json',
            success: function(cevap) {
            var response = jQuery.parseJSON(cevap);
            $("#userduzenleform").html(response.html);
        }
    });
}));



$("body").on("click", ".userayarduzenle", (function(e) {
    e.preventDefault();

    $.ajax({
        type: "POST",
        url: "/App/Controller/ajaxadmin.php?admin=userduzenle",
        data: $("#userduzenle").serialize(),
        success: function(cevap) {
            var response = jQuery.parseJSON(cevap);
            $("#alert").show();
            if(response.status) {
                $("#alert").removeClass("alert-box alert-box-danger");
                $("#alert").addClass("alert-box alert-box-success");
                $("#alert").html(response.message);
                setTimeout(function () {
                    $("#alert").fadeOut();
                    Kullanicilar();
                },2000);
            } else {
                $("#alert").removeClass("alert-box alert-box-success");
                $("#alert").addClass("alert-box alert-box-danger");
                $("#alert").html(response.message);
                setTimeout(function () {
                    $("#alert").fadeOut();
                },2000);
            }
        }
    });

}));



$("body").on("click", ".whois-duzenle", (function(e) {
    var id=$(this).data("id");
    $.ajax({
        type: "POST",
        url: "/App/Controller/ajaxadmin.php?admin=whois",
        data:{
            whois_id: id,
            },
            datatype: 'json',
            success: function(cevap) {
            var response = jQuery.parseJSON(cevap);
            $("#whoisduzenleform").html(response.html);
        }
    });
}));


$("body").on("click", ".whoisayarduzenle", (function(e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "/App/Controller/ajaxadmin.php?admin=whoisduzenle",
        data: $("#whoisduzenle").serialize(),
        success: function(cevap) {
            var response = jQuery.parseJSON(cevap);
            $("#alert").show();
            if(response.status) {
                $("#alert").removeClass("alert-box alert-box-danger");
                $("#alert").addClass("alert-box alert-box-success");
                $("#alert").html(response.message);
                setTimeout(function () {
                    $("#alert").fadeOut();
                    Whois();
                },2000);
            } else {
                $("#alert").removeClass("alert-box alert-box-success");
                $("#alert").addClass("alert-box alert-box-danger");
                $("#alert").html(response.message);
                setTimeout(function () {
                    $("#alert").fadeOut();
                },2000);
            }
        }
    });
}));

$("body").on("click", ".whois-status", (function(e) {
    var id=$(this).data("id");
        $.ajax({
            type: "POST",
            url: "/App/Controller/ajaxadmin.php?admin=whois-status",
            data:{
                whois_id: id,
                },
                success: function(cevap) {
                    var response = jQuery.parseJSON(cevap);
                    Whois();
                    $("#alert").show();
                    if(response.status) {
                        $("#alert").removeClass("alert-box alert-box-danger");
                        $("#alert").addClass("alert-box alert-box-success");
                        $("#alert").html(response.message);
                        setTimeout(function () {
                            $("#alert").fadeOut();
                        },2000);
                    } else {
                        $("#alert").removeClass("alert-box alert-box-success");
                        $("#alert").addClass("alert-box alert-box-danger");
                        $("#alert").html(response.message);
                        setTimeout(function () {
                            $("#alert").fadeOut();
                        },2000);
                    }
            }
        });
    }));


    $("body").on("click", ".whois-sil", (function(e) {
        var id=$(this).data("id");
        if(confirm('Uzantıyı silmek istediğinizden emin misiniz?')) {
            $.ajax({
                type: "POST",
                url: "/App/Controller/ajaxadmin.php?admin=whois-sil",
                data:{
                    whois_id: id,
                    },
                    datatype: "json",
                    success: function(cevap) {
                    var response = jQuery.parseJSON(cevap);
                    if(response.status) {
                        $("#alert").show();
                        $("#alert").removeClass("alert-box alert-box-danger");
                        $("#alert").addClass("alert-box alert-box-success");
                        $("#alert").html(response.message);
                        setTimeout(function () {
                            $("#alert").fadeOut();
                            Whois();
                        },1000);
                        
                    } else {
                        Whois()
                        $("#alert").show();
                        $("#alert").removeClass("alert-box alert-box-success");
                        $("#alert").addClass("alert-box alert-box-danger");
                        $("#alert").html(response.message);
                        setTimeout(function () {
                            $("#alert").fadeOut();
                        },1000);
                    } 
                }
            });
        }
        }));


        $("body").on("click", ".whois-kaydet", (function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "/App/Controller/ajaxadmin.php?admin=whois-kaydet",
                data: $("#whois-kaydet").serialize(),
                datatype: "json",
                success: function(cevap) {
                    var response = jQuery.parseJSON(cevap);
                    if(response.status) {
                        $("#alert").show();
                        $("#alert").removeClass("alert-box alert-box-danger");
                        $("#alert").addClass("alert-box alert-box-success");
                        $("#alert").html(response.message);
                        setTimeout(function () {
                            $("#alert").fadeOut();
                            Whois();
                        },1000);
                        
                    } else {
                        $("#alert").show();
                        Whois();
                        $("#alert").removeClass("alert-box alert-box-success");
                        $("#alert").addClass("alert-box alert-box-danger");
                        $("#alert").html(response.message);
                        setTimeout(function () {
                            $("#alert").fadeOut();
                        },3000);
                    } 
                }
            });
        }));



$("body").on("click", "#adminmode", (function(e) {

    var id=$(this).data("id");
    $.ajax({
        type: "POST",
        url: "/App/Controller/ajax.php?user=adminmode",
        data:{
            user_id: id,
            },
        success: function(cevap) {
            document.location.href = '/';
        }
    });


}));

});