<?php 

require_once __DIR__."/config.php";

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



    public function userregister($email,$username,$passwordone,$passwordtwo) {

    $dp = $this->db->prepare("SELECT * FROM dp_user WHERE user_mail=?");
    $sonuc = $dp->execute($email);
    print_r($sonuc);

    }

    public function userlogin($email,$password) {


    }





}