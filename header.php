<?php 
require_once __DIR__."/setconfig.php";

if(!isset($_SESSION['userlogin']['email'])) {
    header("Location:login.php");
    exit();
 }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $ayar['description'] ?>">
    <meta name="Author" content="<?php echo $ayar['author'] ?>">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="/assets/img/favicon.ico">
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/dp.js"></script>

    <title><?php echo $ayar['site_title'] ?></title>
</head>

<body>
    <header>
        <div class="container">
            <div class="p-header">
                <div class="p-logo"><a href="/">
                        <h1><?php echo $ayar['site_name'] ?></h1>
                    </a></div>
                <div class="p-user">
                    <span>Hoşgeldin, <?php echo $_SESSION['userlogin']['name'] ?></span>
                    <div>
                        <span class="logout"><span>Çıkış yap:</span><a href="/logout.php"><i class="fas fa-power-off"></i></a></span>
                    </div>
                </div>
            </div>
            <nav>
                <div>
                    <ul class="m-menu">
                    <li><a href=""><i class="fas fa-home"></i> </a></li>
                        <li class="guncelle"><i class="fas fa-arrows-rotate"></i></li>
                        <li class="domain-ekle"><i class="fas fa-plus"></i></li>
                    </ul>
                    <ul>
                    <?php if(isset($_SESSION['userlogin']) AND isset($_SESSION['adminonline'])) : ?>
                        <li><a href="">Dashboard</a></li>
                        <?php elseif(isset($_SESSION['userlogin'])) : ?>
                            <li><a href="">Anasayfa</a></li>
                        <li class="guncelle">Güncelle</li>
                        <li class="domain-ekle">Domain Ekle</li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div>
                <?php if($_SESSION['userlogin']['is_admin']==="1") { ?>
                <div id="adminmode" title="Admin Panel" data-id="<?php echo $_SESSION['userlogin']['user_id'] ?>"><i class="fas fa-gauge"></i></div>
                <?php } ?>
                </div>
            </nav>
        </div>
    </header>

