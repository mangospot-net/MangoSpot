<?php
require_once "../include/config.php";
require_once ('../include/autoload.php');
header("Access-Control-Allow-Origin: *");

use CWG\OneSignal\OneSignal;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$SMTP       = new SMTP;
$Mail       = new Mail();
$Bsk        = new Connect();
$Router     = new MikroTik();
$Download   = new \FS\DownloadFile;
$Images     = new \claviska\SimpleImage();
$Auth       = new Cipher(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
$Header     = getallheaders();
$Page       = (isset($_GET['pages'])? Rahmad($_GET['pages']) : false);
$Http       = (isset($_SERVER['HTTPS'])? 'https://' : 'http://').$_SERVER['SERVER_NAME'];
$Host       = (isset($Header['Token']) ? Rahmad($Header['Token']) : (isset($Header['token']) ? Rahmad($Header['Token']) : md5($_SERVER['SERVER_NAME'])));
$Api        = (isset($Header['Api'])? $Auth->decrypt($Header['Api'], $Host) : (isset($Header['api']) ? $Auth->decrypt($Header['api'], $Host) : false));
$Key        = (isset($Header['Key'])? $Auth->decrypt($Header['Key'], $Host) : (isset($Header['key']) ? $Auth->decrypt($Header['key'], $Host) : false));

$Identity   = $Bsk->Show("identity",  "*", "status = 'true'");
$Config     = $Bsk->Show("config",    "*", "id = '$Identity[id]' ");
$Users      = $Bsk->Show("users",     "*", "id = '$Api' and md5(pswd) = '$Key' and status = 'true'");
$Menu       = $Bsk->Show("access",    "*", "id = '$Api' and md5(pswd) = '$Key' and identity = '$Identity[id]'");
$Online     = $Bsk->Update("users",       array('online' => date("YmdHis")+180), "id = '$Api' and md5(pswd) = '$Key'");
$Signal     = new OneSignal($Config['on_api'], $Config['on_key']);

$Default    = array("login", "forgot", "data", "profile");
$inMenu     = ($Menu ? explode(',', $Menu['value']) : array());
$Access     = $Bsk->Show("menu", "*", "value = '$Page' and slug != 0 and status = 'true'");
$Include    = (in_array($Page, $Default) ? $Page : (in_array($Access['id'], $inMenu) ? $Access['value'] : false));

require_once ($Include ? $Include : "home").".php";
