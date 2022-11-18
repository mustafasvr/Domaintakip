<?php 

include __DIR__."/../Lang/tr.php";

if(isset($_GET["user"])) {
    $veri = $_GET["user"];

    switch ($veri) : 
        case "login" :

                print_r($_POST);

                if($_POST['passwordone'] != $_POST['passwordtwo']) {
                   $hata = $dil[1];
                   return $hata;
                   exit; 
                }
          

            break;
        endswitch;




}