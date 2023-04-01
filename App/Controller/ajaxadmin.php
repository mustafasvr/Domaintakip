<?php 

require_once __DIR__."/../Data/crud.php";
require_once __DIR__."/../Data/whois_server.php";

$whois_server=new whois_server();
$data=new crud();



if(isset($_GET["admin"])) {
    $veri = $_GET["admin"];
    $response = [];
    switch ($veri) : 
        case "ayarlar" : ?>

<?php
                if(isset($_POST['ayar_id'])) : 
                    $sql=$data->nSql("SELECT * FROM dp_ayar WHERE id='".$_POST['ayar_id']."'");
                    $row=$sql->fetch(PDO::FETCH_ASSOC);  
                    ob_start();
                    ?>
<div class="duzenform">
    <form id="ayarduzenle" enctype="multipart/form-data">
        <label for="<?php echo $row['name'] ?>"><?php echo $row['name'] ?></label>
        <div>
            <input type="text" name="value" required="" value="<?php echo $row['value'] ?>" class="form-control"
                id="exampleInputEmail">
                <input type="hidden" name="id" required="" value="<?php echo $row['id'] ?>">

        </div>
        <button type="submit" class="ayarduzenle">düzenle</button>
    </form>
</div>
<?php 
                    $response['html'] = ob_get_clean();

                    echo json_encode($response);

                    exit;
                     endif;?>




<section id="ayarlar">

    <section id="ayarduzenleform">

    </section>

    <div class="admin-box">
        <form id="ayar-kaydet" enctype="multipart/form-data">
            <?php
                   $sonuc =  $data->nSql("SELECT * FROM dp_ayar")->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach($sonuc as $key):   
                    ?>

            <div class="input-group">
                <label for="<?php echo $key['name'] ?>"><?php echo $key['name'] ?></label>

                <?php if($key['type']=="text") { ?>

                <input type="<?php echo $key['type'] ?>" readonly disabled name="<?php echo $key['name'] ?>"
                    value="<?php echo $key['value'] ?>" />

                <?php } else if ($key['type']=="file") { ?>

                <img src="resim/web/<?php echo $key['value'] ?>" width="300">

                <?php }  ?>

                <div class="duzenle" data-id="<?php echo $key['id'] ?>"><i class="fas fa-pencil"></i></div>
            </div>
            <?php endforeach; ?>
        </form>
    </div>
</section>

<?php

    

            break;

            case "ayarduzenle" :

                $id = $_POST['id'];

                $data->nSql("UPDATE dp_ayar SET  value='".$_POST['value']."' WHERE id='".$id."'");

                echo json_encode([
                    "status" => true,
                    "message" => "Düzenleme işlemi başarılı.",
                ]);



            break;



            case "domainler" :

               $dpd = $data->nSql("SELECT * FROM dp_domain ORDER BY domain_id DESC")->fetchAll(PDO::FETCH_ASSOC);
                ?>

<section id="domain-box">

    <?php 

                foreach($dpd as $key):

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

    <div class="box <?php echo $box; ?>" id="4">
        <div class="box-header">
            <div><?php echo $key['domain_name'] ?></div>
            <span> <i class="fas fa-caret-left"></i></span>
        </div>
        <div class="box-info" style="display:none">
            <div class="box-info-body">
                <div>
                    <dl>
                        <dt>Takip Eden Kullanıcılar</dt>
                        <dd><?php echo $data->usersdomain($key['domain_id']) ?></dd>
                    </dl>
                </div>

            </div>
        </div>
    </div>

    <?php endforeach;  ?>
</section>

<?php



            break;

            
            case "kullanicilar" : ?>


<?php
                if(isset($_POST['user_id'])) : 
                    $user_id = decrypt($_POST['user_id']);
                    $sql=$data->nSql("SELECT * FROM dp_user WHERE user_id='".$user_id."'");
                    $row=$sql->fetch(PDO::FETCH_ASSOC);  
                    ob_start();
                    ?>
<div class="duzenform">
    <h3><?php echo $row['name'] ?> adlı kullanıcıyı düzenle</h3>
    <form id="userduzenle" enctype="multipart/form-data">
        <input type="text" name="name" value="<?php echo $row['name'] ?>">
        <input type="email" name="email" value="<?php echo $row['email'] ?>">
        <input type="text" name="password" placeholder="Şifre değiştirmek istersen şifre gir.">
        <select name="is_admin">
            <option <?php echo $row['is_admin']==1 ? 'selected' : '' ?> value="1">Admin</option>
            <option <?php echo $row['is_admin']==0 ? 'selected' : '' ?> value="0">Kullanıcı</option>
        </select>
        <input type="hidden" name="user_id" value="<?php echo encrypt($row['user_id']) ?>">
        <button type="submit" class="userayarduzenle">düzenle</button>
    </form>
</div>
<?php 
                    $response['html'] = ob_get_clean();

                    echo json_encode($response);

                    exit;
                     endif;?>

<?php $dpd = $data->nSql("SELECT * FROM dp_user")->fetchAll(PDO::FETCH_ASSOC);?>

<section id="user-box">

    <section id="userduzenleform"></section>

    <div class="user-box-body">
        <table>
            <tbody>
                <?php foreach($dpd as $key): ?>
                <tr>
                    <td class="td-body">
                        <div class="td-name"><?php echo $key['name'] ?></div>
                        <div class="td-mail"><?php echo $key['email'] ?></div>
                    </td>

                    <td class="td-box user-duzenle" title="Kullanıcıyı düzenle"
                        data-id="<?php echo encrypt($key['user_id']) ?>"><i class="fas fa-pencil"></i></td>



                    <td class="td-box user-ban"
                        title="<?=($key['user_status']==1) ? 'Kullanıcıyı yasakla' : 'Yasağı kaldır'?>"
                        data-id="<?php echo encrypt($key['user_id']) ?>"><i
                            class="fas <?=($key['user_status']==1) ? 'fa-ban' : 'fa-heart'?>"></i></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


</section>

<?php
 
 
 
             break;

             


             case "userduzenle":

                $sonuc = $data->userduzenle($_POST['user_id'],$_POST['name'],$_POST['email'],$_POST['password'],$_POST['is_admin']);
               
                echo json_encode([
                    "status" => true,
                    "message" => "Düzenleme işlemi başarılı.",
                ]);



            break;




             case "user-ban" :

                $user_id = $_POST['user_id'];
                $ban = $data->userban($user_id);

                echo json_encode([
                    "status" => true,
                    "message" => $ban['message'],
                ]);



             break;


             case "whois" : ?>

<?php
                if(isset($_POST['whois_id'])) : 
                    $whois_id = decrypt($_POST['whois_id']);
                    $sql=$data->nSql("SELECT * FROM dp_whois WHERE whois_id='".$whois_id."'");
                    $row=$sql->fetch(PDO::FETCH_ASSOC);  
                    ob_start();
                    ?>
<div class="duzenform">
    <h3><?php echo $row['whois_extension'] ?> uzantısını düzenle</h3>
    <form id="whoisduzenle" enctype="multipart/form-data">
        <input type="text" name="whois_extension" value="<?php echo $row['whois_extension'] ?>">
        <input type="text" name="whois_server" value="<?php echo $row['whois_server'] ?>">
        <input type="hidden" name="whois_id" value="<?php echo encrypt($row['whois_id']) ?>">
        <button type="submit" class="whoisayarduzenle">düzenle</button>
    </form>
</div>
<?php 
                    $response['html'] = ob_get_clean();

                    echo json_encode($response);

                    exit;
                     endif;?>

<?php $dpd = $data->nSql("SELECT * FROM dp_whois")->fetchAll(PDO::FETCH_ASSOC);?>

<section id="user-box">

    <div class="user-box-header">
        <button class="whois-ekle">Yeni Ekle</button>
    </div>

    <section id="whoisduzenleform"></section>

    <div class="user-box-body">
        <table>
            <tbody>
                <?php foreach($dpd as $key): ?>
                <tr>
                    <td class="td-body">
                        <div class="td-name"><?php echo $key['whois_extension'] ?></div>
                        <div class="td-mail"><?php echo $key['whois_server'] ?></div>
                    </td>

                    <td class="td-box whois-duzenle" title="Uzantıyı düzenle"
                        data-id="<?php echo encrypt($key['whois_id']) ?>"><i class="fas fa-pencil"></i></td>
                    <td class="td-box whois-status"
                        title="<?=($key['whois_status']==1) ? 'Uzantıyı pasif hale getir' : 'Uzantıyı aktif hale getir'?>"
                        data-id="<?php echo encrypt($key['whois_id']) ?>"><i
                            class="fas <?=($key['whois_status']==1) ? 'fa-ban' : 'fa-heart'?>"></i></td>
                    <td class="td-box whois-sil" title="Uzantıyı sil"
                        data-id="<?php echo encrypt($key['whois_id']) ?>"><i class="fas fa-trash"></i></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php 
             break;

             
             case "whoisduzenle":

                $sonuc = $data->whoisduzenle($_POST['whois_id'],$_POST['whois_extension'],$_POST['whois_server']);
               
                echo json_encode([
                    "status" => true,
                    "message" => "Düzenleme işlemi başarılı.",
                ]);



            break;

            case "whois-status" :

                $whois_id = $_POST['whois_id'];
                $whois = $data->whoisstatus($whois_id);

                echo json_encode([
                    "status" => true,
                    "message" => $whois['message'],
                ]);

             break;

             case "whois-sil":

                $whois_id = $_POST['whois_id'];
                $sonuc = $data->whoissil($whois_id);
                
                echo $sonuc;
                exit;
            
            break;

            case "whois-ekle": ?>


                <div class="duzenform">
                <h3>Yeni uzantı ekle</h3>
                <form id="whois-kaydet" enctype="multipart/form-data">
                <input type="text" name="whois_extension" placeholder="Domain uzantısını giriniz. örnk: com,net,org,info,com.tr,net.tr">
                <input type="text" name="whois_server" placeholder="Lütfen whois server adresini giriniz.">
                <button type="submit" class="whois-kaydet">kaydet</button>
                </form>
                </div>
        
                <?php echo "<pre>";

                print_r($whois_server->whoisServers);

                echo "</pre>" ?>

            <?php
            break;

            case "whois-kaydet": 

                    if(empty($_POST['whois_extension'])) {

                        echo json_encode([
                            "status" => false,
                            "message" => "Uzantı alanını boş bırakmayınız.",
                        ]);
                        
                    } elseif(empty($_POST['whois_server'])) {

                        echo json_encode([
                            "status" => false,
                            "message" => "Whois server alanını boş bırakmayınız.",
                        ]);
                       
                    } else{
                        $whois_extension=$_POST['whois_extension'];
                        $whois_server=$_POST['whois_server'];

                        $sonuc = $data->whoiskaydet($whois_extension,$whois_server);

                        echo $sonuc;


                        exit;
                    }
                    
            break;

        

        
        
        endswitch;

}