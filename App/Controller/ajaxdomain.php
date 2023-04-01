<?php 
require_once __DIR__."/../Data/crud.php";
require_once __DIR__."/../Data/whois.php";
$data=new crud();
$whois=new whois();

if(isset($_GET["tema"])) {
    $veri = $_GET["tema"];
    $user_id = $_SESSION['userlogin']['user_id'];

    switch ($veri) : 

        
       
            case "home": 
            
            if(isset($_SESSION['userlogin']) AND isset($_SESSION['adminonline'])) : ?>


<section id="dashboard">
    
<div class="dash-wrapper">

    <div class="dash-box ayarlar">
        <div class="dash-icon"><i class="fas fa-cog"></i></div>
        <div class="dash-body">
            <div class="dash-body-header">Ayarlar</div>
            <div class="dash-body-content">Sitenin tüm ayarları burdan yapılmaktadır</div>
        </div>
    </div>
    <div class="dash-box kullanicilar">
        <div class="dash-icon"><i class="fas fa-users"></i></div>
        <div class="dash-body">
            <div class="dash-body-header">Kullanıcılar</div>
            <div class="dash-body-content">Sitenin tüm ayarları burdan yapılmaktadır</div>
        </div>
    </div>
    <div class="dash-box domainler">
        <div class="dash-icon"><i class="fas fa-tags"></i></div>
        <div class="dash-body">
            <div class="dash-body-header">Domainler</div>
            <div class="dash-body-content">Sitenin tüm ayarları burdan yapılmaktadır</div>
        </div>
    </div>
    <div class="dash-box whois">
        <div class="dash-icon"><i class="fas fa-search"></i></div>
        <div class="dash-body">
            <div class="dash-body-header">Whois Ayarları</div>
            <div class="dash-body-content">Sitenin tüm ayarları burdan yapılmaktadır</div>
        </div>
    </div>
</div>


</section>


                
            <?php

            elseif(isset($_SESSION['userlogin'])) :



            ?>

<section id="Home">
    <section id="info-box">
        <div class="box success">
            <div class="box-counter">
                <?php 
                $date = "domain_expiry>".strtotime("+100 day");
                $sonuc = $data->userdatedomain($user_id,$date);
                echo $sonuc;
                ?>
            </div>
            <span><i class="fas fa-infinity"></i> < 100</span>
        </div>
        <div class="box warring">
            <div class="box-counter">
                <?php 
            $date = "domain_expiry>".strtotime("+61 day")." and domain_expiry<".strtotime("+101 day");
            $sonuc = $data->userdatedomain($user_id,$date);
            echo $sonuc;
                ?>
            </div>
            <span>100 < 60</span>
        </div>
        <div class="box danger">
            <div class="box-counter">
                <?php 
            $date = "domain_expiry>".strtotime("now")." and domain_expiry<".strtotime("+61 day");
            $sonuc = $data->userdatedomain($user_id,$date);
            echo $sonuc;
                ?>
            </div>
            <span>60 < 0</span>

        </div>
        <div class="box">
            <div class="box-counter">0</div>
            <span>0 < <i class="fas fa-infinity"></i></span>
        </div>
    </section>

    <section id="domain-box">

        <?php 

                $sonuc = $data->userdomain($user_id);

                if(empty($sonuc)) : ?>

                <section id="welcome">
                <div class="welcome-body">
                   <div> Merhaba,<b> <?php echo $_SESSION['userlogin']['name'] ?></b></div>
                    <p>Bu alana <b class="domain-ekle">Domain ekle</b> kısmından domainler ekleyip domainleri yakından takip edebilir, domainler hakkında çeşitli bilgilere ulaşabilirsin.</p>
                    <p><b>Ulaşabileceğin bilgiler</b> <ul>
                        <li>Domain kayıt yeri (Hosting) adresi.</li>
                        <li>Domain (Ad Sunucuları) bağlı olduğu nameserver adresleri.</li>
                        <li>Domain kayıt tarihi.</li>
                        <li>Domain güncellenme tarihi.</li>
                        <li>Domain bitiş tarihi.</li>
                        <li>Domain kalan günü.</li>
                    </ul></p>
                </div>
                </section>
              
                    

               <?php else:
       
                foreach($sonuc as $key) :
                    $kalanzaman = $data->dateago($key['domain_expiry']);
                    if($kalanzaman > 100) {
                        $box = "success";
                    } elseif ($kalanzaman > 60) {
                        $box = "warring";
                    } elseif ($kalanzaman >= 0) {
                        $box = "danger";
                    } else {
                        $box = '';
                    }
                
                ?>

        <div class="box <?php echo $box; ?>"  data-id="<?php echo encrypt($key['id']) ?>">
            <div class="box-header">
                <div><?php echo $key['domain_name'] ?></div>
                <span><?php echo $kalanzaman; ?> <i class="fas fa-caret-left"></i></span>
            </div>
            <div class="box-info" style="display:none">
            <div class="box-info-body">
                <div>
                    <dl>
                        <dt>Kayıt Yeri</dt>
                        <dd><?php echo $key['domain_hosting'] ?></dd>
                    </dl>
                    <dl>
                        <dt>Ad Sunucular</dt>
                        <dd><?php echo $data->adsunucu($key['domain_nameserver']) ?></dd>
                    </dl>
                </div>
                <div>
                    <dl>
                        <dt>Kayıt Tarihi</dt>
                        <dd><?php echo $data->datetime($key['domain_register']) ?></dd>
                    </dl>
                    <dl>

                        <dt>Güncellenme Tarihi</dt>
                        <dd><?php echo $data->datetime($key['domain_update']) ?></dd>

                    </dl>
                    <dl>
                        <dt>Bitiş Tarihi</dt>
                        <dd><?php echo $data->datetime($key['domain_expiry']) ?></dd>
                    </dl>
                    <dl>
                        <dt>Kalan Gün</dt>
                        <dd><?php echo $kalanzaman; ?> <i class="far fa-calendar"></i></dd>
                    </dl>
                </div>
                </div>
                <div class="box-footer">
                    <div class="box-footer-icon domain-sil" data-id="<?php echo encrypt($key['domain_id']) ?>"><i class="fas fa-trash-can"></i></div>
                </div>
            </div>
        </div>

        <?php endforeach; endif;  ?>
    </section>
</section>

<?php

            endif;
            break;
            case "domain-ekle": ?>



<section id="domain-ekle">
    <div class="form-box">
        <form id="domain-kaydet">
            <label for="domain_name">Lütfen domain adresinizi giriniz.</label>
            <input type="text" name="domain_name" placeholder="örnek : domain.com , domain.net , domain.org" />
            <button type="submit" class="domain-kaydet">Kaydet</button>
        </form>
    </div>
</section>

<?php 
            break;

           
        endswitch;
}

if(isset($_GET["domain"])) {
    $veri = $_GET["domain"];

    $user_id = $_SESSION['userlogin']['user_id'];

    switch ($veri) : 
       
            case "kaydet":

                if(!empty($_POST['domain_name'])) {
                    $domain=$_POST['domain_name'];
                    $sorgu = $data->domainekle($domain);
                 
                    echo $sorgu;
                    exit;
                } else{
                    echo json_encode([
                        "status" => false,
                        "message" => "Domain adresi boş bırakılamaz.",
                    ]);
                    exit;
                }
                
            break;


            case "sil":

                    $domain_id = $_POST['domain_id'];
                    $sonuc = $data->domainsil($user_id,$domain_id);
                    
                    echo $sonuc;
                    exit;
                
            break;

            case "guncelle":

                $sonuc = $data->domainguncelle($user_id);
                echo $sonuc;
                exit;
            
        break;


    endswitch;
}