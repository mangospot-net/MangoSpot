<?php
class Cipher {
    private $algo;
    private $mode;
    private $source;
    private $iv = null;
    private $key = null;
    public function __construct($algo = MCRYPT_3DES, $mode = MCRYPT_MODE_CBC, $source = MCRYPT_RAND) {
        $this->algo = $algo;
        $this->mode = $mode;
        $this->source = $source;
 
        if (is_null($this->algo) || (strlen($this->algo) == 0)) {
            $this->algo = MCRYPT_3DES;
        }
        if (is_null($this->mode) || (strlen($this->mode) == 0)) {
            $this->mode = MCRYPT_MODE_CBC;
        }
    }
    public function encrypt($data, $key = null, $iv = null) {
        $key = (strlen($key) == 0) ? $key = null : $key;
 
        $this->setKey($key);
        $this->setIV($iv);
 
        $out = mcrypt_encrypt($this->algo, $this->key, $data, $this->mode, $this->iv);
        return base64_encode($out);
    }
    public function decrypt($data, $key = null, $iv = null) {
        $key = (strlen($key) == 0) ? $key = null : $key;
 
        $this->setKey($key);
        $this->setIV($iv);
 
        $data = base64_decode($data);
        $out = mcrypt_decrypt($this->algo, $this->key, $data, $this->mode, $this->iv);
        return trim($out);
    }
    public function getIV() {
        return base64_encode($this->iv);
    }
    private function setIV($iv) {
        if (!is_null($iv)) {
            $this->iv = base64_decode($iv);
        }
        if (is_null($this->iv)) {
            $iv_size = mcrypt_get_iv_size($this->algo, $this->mode);
            $this->iv = mcrypt_create_iv($iv_size, $this->source);
        }
    }
    private function setKey($key) {
        if (!is_null($key)) {
            $key_size = mcrypt_get_key_size($this->algo, $this->mode);
            $this->key = hash("sha256", $key, true);
            $this->key = substr($this->key, 0, $key_size);
        }
        if (is_null($this->key)) {
            trigger_error("You must specify a key at least once in either Cipher::encrpyt() or Cipher::decrypt().", E_USER_ERROR);
        }
    }
}
?>