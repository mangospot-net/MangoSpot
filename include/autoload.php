<?php
require_once '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
spl_autoload_register(function ($class) {
    include strtolower($class).'.php';
});
class Mail extends PHPMailer {
    public function __construct($exceptions=true){
        $this->isHTML(true);
        $this->SMTPAuth = true;
        $this->SMTPDebug = 0;
        $this->isSMTP();
        parent::__construct($exceptions);
    }
    public function sendMail($config, $data){
        $this->Host = $config['host'];
        $this->Port = $config['port'];
        $this->Username = $config['email'];
        $this->Password = $config['pswd'];
        $this->SMTPSecure = $config['smtp'];
        $this->SetFrom($config['email'], $config['name']);
        $this->addAddress($data['email']);
        $this->Subject = $data['subject'];
        $this->msgHTML($data['message']);
        if(!empty($data['attachment'])){
            $this->addAttachment($data['attachment']);
        }
        return ($this->send() ? true : false);
    }
}
?>