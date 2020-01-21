<?php
class SSH2 {
    var $ssh;
	var $stream;
	private $host;
	private $port;
	private $username;
	private $password;
    function __construct() {
		$this->host=SSH_HOST;
		$this->port=SSH_PORT;
		$this->username=SSH_USERNAME;
		$this->password=SSH_PASSWORD;
        if (!$this->ssh = ssh2_connect($this->host, $this->port)) {
            return false;
        }
    }
    function auth($user = null, $auth = null, $private = null, $secret = null) {
		$ssh_user = ($user == null ? $this->username : $user);
		$ssh_pswd = ($auth == null ? $this->password : $auth);
        if(is_file($auth) && is_readable($auth) && isset($private)) {
            if(!ssh2_auth_pubkey_file($this->ssh, $ssh_user, $ssh_pswd, $private, $secret)) {
                return false;
            }
        } else {
            if(!ssh2_auth_password($this->ssh, $ssh_user, $ssh_pswd)) {
                return false;
            }  
        }  
        return true;
    }
    function send($local, $remote, $perm) {
        if(!ssh2_scp_send($this->ssh, $local, $remote, $perm)) {
            return false;
        }      
        return true;
    }
    function get($remote, $local) {
        if(ssh2_scp_recv($this->ssh, $remote, $local)) {
            return false;
        }
        return true;
    }
    function cmd($cmd, $blocking = true) {
        $this->stream = ssh2_exec($this->ssh, $cmd);
        stream_set_blocking($this->stream, $blocking);
    }
    function exec($cmd, $blocking = true) {
        $this->cmd($cmd, $blocking = true);
    }
    function output() {
        return stream_get_contents($this->stream);
    }
}