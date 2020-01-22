<?php
namespace CWG\OneSignal;
use CWG\OOneSignal\Exception\OneSignalException;
use CWG\OneSignal\Notification;
class Device {
    const IOS = 0;
    const ANDROID = 1;
    const AMAZON = 2;
    const WINDOWSPHONE = 3;
    const CHROMEAPP = 4;
    const CHROMEWEB = 5;
    const SAFARI = 7;
    const FIREFOX = 8;  
    const MACOS = 9;
    private $curl;
    private $appID;
    private $campos = [ ];
    public function __construct($appID) {
        $this->curl = CURL::getInstance();
        $this->appID = $appID;
    }
    public function setIdentifier($id) {
        $this->campos['identifier'] = $id;
        return $this;
    }
    public function setLanguage($lang) {
        $this->campos['language'] = $lang;
        return $this;
    }
    public function setDevice($type) {
        $this->campos['device_type'] = $type;
        return $this;
    }
    public function addTag($name, $value) {
        $this->campos['tags'][$name] = $value;
        return $this;
    }
    public function create($campos = null) {
        if ($campos != null) $this->campos = $campos;
        $this->campos['app_id'] = $this->appID;
        if (empty($this->campos['identifier'])) throw new OneSignalException;
        if (empty($this->campos['language'])) $campos['language'] = 'pt';
        if (empty($this->campos['device_type'])) $campos['device_type'] = SELF::ANDROID;
        return $this->curl->post('players', $this->campos);
    }
    public function update($deviceID, $campos = null) {
        if ($campos != null) $this->campos = $campos;
        $this->campos['app_id'] = $this->appID;
        return $this->curl->put('players/' . $deviceID, $this->campos);
    }
    public function getDevice($deviceID) {
        return $this->curl->put('players/' . $deviceID . '?app_id='. $this->appID);
    }
    private function setAppID($appID) {
        $this->appID = $appID;
    }
}