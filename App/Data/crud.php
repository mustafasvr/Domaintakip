<?php 
ob_start();
session_start();

require_once __DIR__."/config.php";
require_once __DIR__."/whois.php";

class crud
{

    private PDO $db;
    private string $dbhost = DBHOST;
    private string $dbuser = DBUSER;
    private string $dbpass = DBPASS;
    private string $dbname = DBNAME;


    function __construct()
    {

        try {

            $this->db = new PDO('mysql:host=' . $this->dbhost . ';dbname=' . $this->dbname . ';charset=utf8', $this->dbuser, $this->dbpass);

        } catch (Exception $e) {

            die("Baglanti Başarisiz" . $e->getMessage());

        }

    }



    public function userlogin($email,$password) {

     $password = encrypt($password);
     $dp = $this->db->prepare("SELECT * FROM dp_user WHERE email=? AND password=?");
     $dp->execute([$email,$password]);
     $sonuc = $dp->fetch(PDO::FETCH_ASSOC);

        if($sonuc)
        {

            if($sonuc['user_status']==1) {

                $last_ip = $_SERVER['REMOTE_ADDR'];
                $stmt = $this->db->prepare("UPDATE dp_user SET last_ip=?,last_activity=? where email='".$sonuc['email']."'");
                $stmt->execute([$last_ip, time()]);
    
                $_SESSION['userlogin'] = [
                    "user_id" => $sonuc['user_id'],
                    "email" => $sonuc['email'],
                    "name" => $sonuc['name'],
                    "is_admin" => $sonuc['is_admin'],
                ];
    
                return json_encode([
                    "status" => true,
                    "message" => "Giriş başarılı. Yönlendiriliyorsunuz.",
                ]);
    
            } else {

                return  json_encode([
                    "status" => false,
                    "message" => "Hesabınız yönetici tarafından yasaklanmış.",
                ]);


            }

            

        } else {
           return  json_encode([
                "status" => false,
                "message" => "Bilgileriniz hatalı",
            ]);
            exit;
        }

    }

    public function register($email,$password,$name) {

        $dp = $this->db->prepare("SELECT * FROM dp_user WHERE email=?");
        $dp->execute([$email]);
        $sonuc = $dp->fetch(PDO::FETCH_ASSOC);

        if (!isset($sonuc['email']) == $email) {

            $password = encrypt($password);
            $last_ip = $_SERVER['REMOTE_ADDR'];
            $stmt = $this->db->prepare("INSERT INTO dp_user SET email=?,password=?,name=?,last_ip=?,register_date=?");
            $sonuc = $stmt->execute([$email,$password,$name,$last_ip,time()]);

            if($sonuc) {
                $login = $this->userlogin($email,$password);
                if($login) {
                    return  json_encode([
                        "status" => true,
                        "message" => "Kayıt işlemi başarılı. Yönlendiriliyorsunuz.",
                    ]);
                }
            }

        } else {
            return  json_encode([
                "status" => false,
                "message" => "Bu mail adresi daha önce kayit edilmiş",
            ]);
        }
                   
    }

    public function domainekle($domain) {

        domainekle:
        $user_id = $_SESSION['userlogin']['user_id'];

        $domain = strtolower($domain);
        $parse = parse_url($domain);

        if(isset($parse['host'])) {
            $domain = $parse['host'];
        }


        $domain = strip_tags($domain);
        $domain = str_replace(array('www.', 'http://', 'https://'), array('', ''), $domain);


        $dps = $this->db->prepare("SELECT * FROM dp_domain WHERE domain_name=?");
        $dps->execute([$domain]);
        $sonuc = $dps->fetch(PDO::FETCH_ASSOC);

        if($sonuc) {

            $dps = $this->db->prepare("SELECT * FROM dp_domain_user WHERE user_id=? and domain_id=?");
            $dps->execute([$user_id,$sonuc['domain_id']]);
            $asonuc = $dps->fetch(PDO::FETCH_ASSOC);

            if($asonuc) {

                return json_encode([
                    "status" => false,
                    "message" => "Bu domain daha önce kayıt etmişsiniz.",
                ]);


            } else {

                $dp = $this->db->prepare("INSERT INTO dp_domain_user SET user_id=?,domain_id=?");
                $dp->execute([$user_id,$sonuc['domain_id']]);

                return json_encode([
                    "status" => true,
                    "message" => "Domain başarılı bir şekilde eklendi.",
                ]);
            
            }

        } else {

            $a=new whois();

            $whois = $a->whois_server($domain);

            if(isset($whois['error'])) {

                return json_encode([
                    "status" => false,
                    "message" => $whois['error'],
                ]);

            } else {

            $dp = $this->db->prepare("INSERT INTO dp_domain SET domain_name=?,domain_hosting=?,domain_nameserver=?,domain_register=?,domain_update=?,domain_expiry=?");
            $dp->execute([$whois[0],$whois[1],$whois[2],$whois[3],$whois[4],$whois[5]]);

            $domain = $whois[0];
            
            goto domainekle;
        }

        }




    }

    public function userdomain($user_id) {
        $userd = $this->db->prepare("SELECT * FROM dp_domain INNER JOIN dp_domain_user ON dp_domain.domain_id=dp_domain_user.domain_id WHERE dp_domain_user.user_id=? ORDER BY dp_domain_user.id DESC");
        $userd->execute([$user_id]);
        $sonuc = $userd->fetchAll(PDO::FETCH_ASSOC);
        return $sonuc;

    }

    public function userdatedomain($user_id,$date) {

        $userd = $this->db->prepare("SELECT * FROM dp_domain INNER JOIN dp_domain_user ON dp_domain.domain_id=dp_domain_user.domain_id WHERE dp_domain_user.user_id=? and $date");
        $userd->execute([$user_id]);
        $sonuc = $userd->rowCount();
        return $sonuc;

    }

    public function datedomain($date) {

        $userd = $this->db->prepare("SELECT * FROM dp_domain INNER JOIN dp_domain_user ON dp_domain.domain_id=dp_domain_user.domain_id WHERE $date");
        $userd->execute();
        $sonuc = $userd->rowCount();
        return $sonuc;

    }

    function datetime($time) {
        return date('d.m.Y',$time);
    } 

    function dateago($time) {

        date('d-m-Y');
        $tarih1= new DateTime('now');

        $c = $this->datetime($time);

        $tarih2= new DateTime($c);
        $interval= $tarih1->diff($tarih2);
        return $interval->format('%a');

    }

    function adsunucu($adsunucu) {

        $json = json_decode($adsunucu);
        if (json_last_error() === JSON_ERROR_NONE) {
            $dizisayi = count($json);
            $i=0;
            while($i<=$dizisayi-1):
    
                echo $json[$i]."<br>";
    
                $i++;
            endwhile; 
        } else {
            echo $adsunucu;
        }

       

    }

    function usersdomain($domainid) {

        $dpu = $this->db->prepare("SELECT * FROM dp_domain_user INNER JOIN dp_domain ON dp_domain_user.domain_id=dp_domain.domain_id INNER JOIN dp_user ON dp_domain_user.user_id=dp_user.user_id WHERE dp_domain_user.domain_id=?");
        $dpu->execute([$domainid]);
        $sonuc = $dpu->fetchAll(PDO::FETCH_ASSOC);

        foreach($sonuc as $key) {
            echo $key['name'].",";
        }
       
    }

    public function adminmode($user_id) {


        if(isset($_SESSION['adminonline'])) {
             unset($_SESSION['adminonline']);
        } else  {

        $user = $this->db->prepare("SELECT * FROM dp_user WHERE user_id=?");
        $user->execute([$user_id]);
        $asonuc = $user->fetch(PDO::FETCH_ASSOC);




        if($asonuc['is_admin']==1) {
            
            $session = $_SESSION['adminonline'] = [
                "adminmode" => 1,
            ];

        } else {
            exit;
        }
         }
        return $asonuc;
    }

    public function domainsil($user_id,$domainid) {

        $domainid = decrypt($domainid);
        $userd = $this->db->prepare("SELECT * FROM dp_domain_user  WHERE domain_id=?");
        $userd->execute([$domainid]);
        $sonuc = $userd->rowCount();
        if($sonuc==1) {
            $userd = $this->db->prepare("DELETE FROM dp_domain_user  WHERE user_id=? and domain_id=?");
            $userd->execute([$user_id,$domainid]);

            $userd = $this->db->prepare("DELETE FROM dp_domain WHERE  domain_id=?");
            $userd->execute([$domainid]);

            return json_encode([
                "status" => true,
                "message" => "Domain silme işlemi başarılı",
            ]);

        } else {

            $userd = $this->db->prepare("DELETE FROM dp_domain_user WHERE user_id=? and domain_id=?");
            $userd->execute([$user_id,$domainid]);

            return json_encode([
                "status" => true,
                "message" => "Domain silme işlemi başarılı",
            ]);

        }


    }

    public function domainguncelle($user_id) {

        $userd = $this->db->prepare("SELECT * FROM dp_domain_user INNER JOIN dp_domain ON dp_domain_user.domain_id=dp_domain.domain_id WHERE user_id=?");
        $userd->execute([$user_id]);
        $dpu = $userd->fetchAll(PDO::FETCH_ASSOC);
        $dizi = [];

        foreach($dpu as $a) {
            $b = $a['domain_id'];
            array_push($dizi,$b);
        }



        $dizisayi = count($dizi);
        $a=new whois();
        $i=0;
        while($i<=$dizisayi-1) {
            $dpcs = $this->nSql("SELECT * FROM dp_domain WHERE domain_id='".$dizi[$i]."'")->fetch(PDO::FETCH_ASSOC);
            $whois = $a->whois_server($dpcs['domain_name']);
            $dpg = $this->nSql("UPDATE dp_domain SET domain_hosting='".$whois[1]."',domain_nameserver='".$whois[2]."',domain_register='".$whois[3]."',domain_update='".$whois[4]."',domain_expiry='".$whois[5]."' WHERE domain_id='".$dizi[$i]."'");
            $i++;
        }

        if($dpg) {
            return json_encode([
                "status" => true,
                "message" => "Tüm domainleriniz güncellendi.",
            ]);
        }

    }

    public function userban($user_id) {
    $user_id = decrypt($user_id);

    $user = $this->db->prepare("SELECT * FROM dp_user  WHERE user_id=?");
    $user->execute([$user_id]);
    $sonuc = $user->fetch(PDO::FETCH_ASSOC);
    $ban=[];
    if($sonuc['user_status']==1) {
        $status = 0;
        $ban['message'] = "Kullanıcı yasaklandı"; 
    } else {
        $status = 1;
        $ban['message'] = "Kullanıcı'nın yasağı kaldırıldı"; 
    }
    
    $dpu = $this->db->prepare("UPDATE dp_user SET user_status=? WHERE user_id='".$user_id."'");
    $wsonuc = $dpu->execute([$status]);
    if($wsonuc) {
        return $ban;
    }


    }


    public function userduzenle($user_id,$name,$email,$password,$perm) {

        $user_id = decrypt($user_id);

        if(empty($password)) {
            $dpu = $this->db->prepare("UPDATE dp_user SET name=?,email=?,is_admin=? WHERE user_id='".$user_id."'");
            $dps = $dpu->execute([$name,$email,$perm]);
        } else {
            $password = encrypt($password);
            $dpu = $this->db->prepare("UPDATE dp_user SET name=?,email=?,password=?,is_admin=? WHERE user_id='".$user_id."'");
            $dps = $dpu->execute([$name,$email,$password,$perm]);
        }

    }

    public function whoisduzenle($whois_id,$whois_exp,$whois_srv) {

            $whois_id = decrypt($whois_id);
            $dpu = $this->db->prepare("UPDATE dp_whois SET whois_extension=?,whois_server=? WHERE whois_id='".$whois_id."'");
            $dps = $dpu->execute([$whois_exp,$whois_srv]);

    }


    public function whoisstatus($whois_id) {
        $whois_id = decrypt($whois_id);
    
        $user = $this->db->prepare("SELECT * FROM dp_whois  WHERE whois_id=?");
        $user->execute([$whois_id]);
        $sonuc = $user->fetch(PDO::FETCH_ASSOC);
        $whois=[];
        if($sonuc['whois_status']==1) {
            $status = 0;
            $whois['message'] = "Durum pasif hale getirildi"; 
        } else {
            $status = 1;
            $whois['message'] = "Durum aktif hale getirildi"; 
        }
        
        $dpu = $this->db->prepare("UPDATE dp_whois SET whois_status=? WHERE whois_id='".$whois_id."'");
        $wsonuc = $dpu->execute([$status]);
        if($wsonuc) {
            return $whois;
        }
    
    
    }

    public function whoissil($whoisid) {

        $whoisid = decrypt($whoisid);

        $userd = $this->db->prepare("DELETE FROM dp_whois WHERE whois_id=?");
        $userd->execute([$whoisid]);

            return json_encode([
                "status" => true,
                "message" => "Uzantı silme işlemi başarılı",
            ]);




    }

    public function whoiskaydet($exp,$server) {

        $userd = $this->db->prepare("SELECT * FROM dp_whois WHERE whois_extension=?");
        $userd->execute([$exp]);
        $sonuc = $userd->rowCount();
        if($sonuc==1) {
            return json_encode([
                "status" => false,
                "message" => "Bu uzantıyı daha önce eklemişsiniz.",
            ]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO dp_whois SET whois_extension=?,whois_server=?");
            $sonuc = $stmt->execute([$exp,$server]);

            return json_encode([
                "status" => true,
                "message" => "Whois kaydetme işlemi başarılı",
            ]);

        }


    }


    public function nSql($sql, $options = []) {

        try {

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt;

        } catch (Exception $e) {

            return ['status' => FALSE, 'error' => $e->getMessage()];

        }
    }

    


}

function encrypt($data)
{
return openssl_encrypt($data,CIPHER,KEY);
}


function decrypt($data) {
return openssl_decrypt($data,CIPHER,KEY);
}