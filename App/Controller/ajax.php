<?php 

require_once __DIR__."/../Data/crud.php";
$data=new crud();

if(isset($_GET["user"])) {
    $veri = $_GET["user"];

    switch ($veri) : 
        case "login-thema" :?>
                <div id="login-page">
                <div class="data-form">
                <form id="login-form">
                 <input type="email" name="email" placeholder="email adresiniz giriniz" />
                 <input type="password" name="password" placeholder="şifrenizi giriniz." />
                 <button type="submit" class="gonder">Giriş yap</button>
                </form>
                </div>

                 <div class="register">
                <div>Hesabınız yok mu?</div>
                <span class="kayit">Hesap Oluştur</span>
                </div>
                </div>
                <?php


            break;

            case "login" :

                if(empty($_POST['email']))
                {     
                    echo json_encode([
                        "status" => false,
                        "message" => "Mail adresi boş olamaz",
                    ]);
                    
                }  elseif(empty($_POST['password'])) { 
                    
                    echo json_encode([
                        "status" => false,
                        "message" => "Şifre alanı boş olamaz",
                    ]);

                } else {
                    $sonuc = $data->userlogin($_POST['email'],$_POST['password']);
                    echo $sonuc;
                }


            break;


            case "register-thema" :?>
                <div class="data-form">
                <form id="register-form">
                 <input type="text" name="username" placeholder="Lütfen adınızı giriniz." />
                 <input type="email" name="email" placeholder="Lütfen email adresiniz giriniz" />
                 <input type="password" name="passwordone" placeholder="Lütfen şifrenizi giriniz." />
                 <input type="password" name="passwordtwo" placeholder="Lütfen şifrenizi tekrar giriniz." />
                 <button type="submit" class="kayitol">Kayıt Ol</button>
                </form>
                </div>
                </div>
                <?php

            break;


            case "register" :

                $hata = [];

                if(empty($_POST['username']))
                {     
                    echo json_encode([
                        "status" => false,
                        "message" => "Adınız boş olamaz",
                    ]);
                    
                } elseif(empty($_POST['email']))
                {     
                    echo json_encode([
                        "status" => false,
                        "message" => "Mail adresi boş olamaz",
                    ]);
                    
                }  elseif(empty($_POST['passwordone'])) { 
                    
                    echo json_encode([
                        "status" => false,
                        "message" => "Şifre alanı boş olamaz",
                    ]);

                } elseif(empty($_POST['passwordtwo'])) { 
                
                    echo json_encode([
                        "status" => false,
                        "message" => "Şifre tekrar alanı boş olamaz",
                    ]);

                }  else {
                    
                    if($_POST['passwordone']!=$_POST['passwordtwo']) {
                        echo json_encode([
                            "status" => false,
                            "message" => "Şifreler uyuşmuyor",
                        ]);

                    } else {

                        $sorgu = $data->register($_POST['email'],$_POST['passwordone'],$_POST['username']);

                        echo $sorgu;


                    }
                }


            break;



            case "adminmode": 

                $user_id = $_POST['user_id'];
                $zsonuc = $data->adminmode($user_id);    

                header("Location:/");
                exit;
               

                break;

        
        endswitch;

}