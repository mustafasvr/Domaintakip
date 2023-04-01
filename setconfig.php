<?php 

require_once __DIR__.'/App/Data/crud.php';
$db=new crud();


$sql=$db->nSql("SELECT * FROM dp_ayar");
$row=$sql->fetchAll(PDO::FETCH_ASSOC);


foreach ($row as $key) {
    $ayar[$key['name']]=$key['value'];
}
