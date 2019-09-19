<?php
require_once "../include/config.php";
require_once '../include/cipher.php';
require_once '../include/mikrotik.php';
require_once '../email/PHPMailerAutoload.php';
header("Access-Control-Allow-Origin: *");
$Bsk    = new Basuki();
$Router = new RouterosAPI();
$Auth   = new Cipher(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
$Header = getallheaders();
$Host   = (isset($Header['Token']) ? Rahmad($Header['Token']) : (isset($Header['token']) ? Rahmad($Header['Token']) : md5($_SERVER['SERVER_NAME'])));
$Api    = (isset($Header['Api'])? $Auth->decrypt($Header['Api'], $Host) : (isset($Header['api']) ? $Auth->decrypt($Header['api'], $Host) : false));
$Key    = (isset($Header['Key'])? $Auth->decrypt($Header['Key'], $Host) : (isset($Header['key']) ? $Auth->decrypt($Header['key'], $Host) : false));
$Page   = (isset($_GET['pages'])? Rahmad($_GET['pages']) : false);
$Http   = (isset($_SERVER['HTTPS'])? 'https://' : 'http://').$_SERVER['SERVER_NAME'];
$Identity = $Bsk->Tampil("identity", "*", "status = 'true'");
$Config = $Bsk->Tampil("config", "*", "id = '$Identity[id]' ");
$Users  = $Bsk->Tampil("users", "*", "id = '$Api' and md5(pswd) = '$Key' and status = 'true'");
$Menu   = $Bsk->Tampil(
    "users a left join level b on a.level = b.id",
    "a.id, a.identity, a.level, b.value",
    "a.id = '$Api' and md5(a.pswd) = '$Key' and a.identity = '$Identity[id]' and a.status = 'true' and b.status = 'true'"
);
$Access = $Bsk->Tampil(
    "menu", 
    "value", 
    "id in ($Menu[value]) and value = '$Page' and slug != 0 and status = 'true'"
);
$Default = array("login", "data", "profile");
$Include = (in_array($Page, $Default)? $Page : $Access['value']);
$Bsk->Ubah("users", array('online' => date("YmdHis")+180), "id = '$Users[id]' ");
include ($Include? $Include : "home").".php";
